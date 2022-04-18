<?php
require_once 'base.php';
require_once '../lib/guid.php';
class User
{
  public static $current_user = null;

  public static function create($attrs){
    $base = new Base();
    $encrypted_password = password_hash($attrs['password'], PASSWORD_DEFAULT);
    $sql = $base->conn->prepare(<<<SQL
      INSERT INTO `users` (`email`, `username`, `password`, `role`) VALUES (?, ?, ?, 'user');
    SQL);
    $sql->bind_param('sss', $attrs['email'], $attrs['username'], $encrypted_password);
    return $sql->execute();
  }

  public static function update($attrs){
    if (!empty($attrs['image']['tmp_name'])) {
      $image_url = self::save_image($attrs['image']);
    }elseif ($attrs['image_url']) {
      $image_url = self::save_image_from_url($attrs['image_url']);
    }else{
      $image_url = self::$current_user['image_url'];
    }
    if (empty($image_url)) {
      return false;
    }
    $base = new Base();
    $sql = $base->conn->prepare(<<<SQL
      UPDATE `users` SET `username` = ?,`image_url`=? WHERE `id`=?;
    SQL);
    $sql->bind_param('ssi', $attrs['username'], $image_url, self::$current_user['id']);
    return $sql->execute();
  }

  public static function is_signed(){
    if (empty(self::$current_user)) {
      return false;
    }
    return true;
  }

  public static function login($email, $password){
    $base = new Base();
    $sql = $base->conn->prepare(<<<SQL
      SELECT * FROM `users` WHERE `email` = ?;
    SQL);
    $sql->bind_param('s', $email);
    $sql->execute();
    $result = $sql->get_result();
    $row = $result->fetch_assoc();
    if(password_verify($password, $row['password'])){
      $_SESSION['user_id'] = $row['id'];
      return true;
    }else{
      return false;
    }
  }

  public static function authenticate_user(){
    $base = new Base();
    $sql = $base->conn->prepare(<<<SQL
      SELECT * FROM `users` WHERE `id` = ?;
    SQL);
    $sql->bind_param('i', $_SESSION['user_id']);
    $sql->execute();
    $result = $sql->get_result();
    $row = $result->fetch_assoc();
    self::$current_user = $row;
  }

  private static function save_image($file){
    preg_match('/(.*)(\..*)$/', $file['name'], $match);
    $file_name = UUID::guid() . htmlspecialchars($match[2]);
    $target_file = "/var/public/{$file_name}";
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
      return "/public/{$file_name}";
    }
    return false;
  }


  private static function save_image_from_url($image_url){
    preg_match('/(.*)(\..*)$/', $image_url, $match);
    $file_name = UUID::guid() . htmlspecialchars($match[2]);
    $target_file = "/var/public/{$file_name}";
    if (file_put_contents($target_file, file_get_contents($image_url))) {
      return "/public/{$file_name}";
    }
    return false;
  }
}
?>

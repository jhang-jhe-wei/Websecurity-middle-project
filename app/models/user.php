<?php
require_once 'base.php';
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

}
?>

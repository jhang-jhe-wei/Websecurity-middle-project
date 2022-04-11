<?php
require_once 'base.php';
class User
{
  public static function create($attrs){
    $base = new Base();
    $encrypted_password = password_hash($attrs['password'], PASSWORD_DEFAULT);
    $sql = $base->conn->prepare(<<<SQL
      INSERT INTO `users` (`email`, `username`, `password`, `role`) VALUES (?, ?, ?, 'user');
    SQL);
    $sql->bind_param('sss', $attrs['email'], $attrs['username'], $encrypted_password);
    return $sql->execute();
  }

}
?>

<?php
require_once 'base.php';
class Config
{
  public static $site_title;

  public static function update_title($title){
    $base = new Base();
    $sql = $base->conn->prepare(<<<SQL
      UPDATE `configs` SET `value`=? WHERE `key`='title';
    SQL);
    $sql->bind_param('s', $title);
    return $sql->execute();
  }

  public static function setup(){
    $base = new Base();
    $sql = $base->conn->prepare(<<<SQL
      SELECT * FROM `configs` WHERE `key` = 'title';
    SQL);
    $sql->execute();
    self::$site_title = $sql->get_result()->fetch_assoc()['value'];
  }
}

?>

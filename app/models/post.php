<?php
require_once 'base.php';
require_once '../lib/guid.php';
class Post
{
  public static function all(){
    $conn = new Base();
    $sql = <<<SQL
      SELECT posts.*, users.`username`, users.`image_url` FROM posts LEFT JOIN users ON posts.user_id = users.id ORDER BY posts.id DESC;
    SQL;
    return $conn->execute_sql($sql);
  }

}

?>

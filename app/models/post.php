<?php
require_once 'base.php';
require_once '../lib/guid.php';
class Post
{
  public static function create($attrs){
    $title = htmlspecialchars($attrs['title']);
    $content = self::content_escape($attrs['content']);
    if(!empty($attrs['file']['tmp_name'])){
     $file_url = self::save_file($attrs['file']);
      if (!$file_url) {
        return false;
      }
    }
    $base = new Base();
    $sql = $base->conn->prepare(<<<SQL
      INSERT INTO `posts` (`user_id`, `title`, `content`, `file_url`) VALUES (?, ?, ?, ?);
    SQL);
    $sql->bind_param('isss', $attrs['user_id'], $title, $content, $file_url);
    return $sql->execute();
  }

  public static function all(){
    $conn = new Base();
    $sql = <<<SQL
      SELECT posts.*, users.`username`, users.`image_url` FROM posts LEFT JOIN users ON posts.user_id = users.id ORDER BY posts.id DESC;
    SQL;
    return $conn->execute_sql($sql);
  }

  public static function find_by_id($id){
    $base = new Base();
    $sql = $base->conn->prepare(<<<SQL
      SELECT * FROM `posts` WHERE `id` = ?;
    SQL);
    $sql->bind_param('i', $id);
    $sql->execute();
    $result = $sql->get_result();
    return $result->fetch_assoc();
  }

  public static function delete($attrs){
    $base = new Base();
    $sql = $base->conn->prepare(<<<SQL
      DELETE FROM `posts` WHERE `id` = ?;
    SQL);
    $sql->bind_param('i', $attrs['id']);
    return $sql->execute();
  }

  private static function save_file($file){
    preg_match('/.*(\..*)$/', $file['name'], $match);
    $file_name = UUID::guid() . htmlspecialchars($match[1]);
    $target_file = "/var/public/{$file_name}";
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
      return "/public/{$file_name}";
    } else {
      return false;
    }
  }

  private static function content_escape($content){
    $content = htmlspecialchars($content);
    $search = array (
      '/(\[b\])(.*?)(\[\/b\])/',
      '/(\[i\])(.*?)(\[\/i\])/',
      '/(\[u\])(.*?)(\[\/u\])/',
      '/(\[img\])(.*?)(\[\/img\])/',
      '/(\[color=(.{1,10}).*\])(.*?)(\[\/color\])/',
    );

    $replace = array (
      '<b>$2</b>',
      '<i>$2</i>',
      '<u>$2</u>',
      '<img src="$2" >',
      '<span style="color: $2;">$3</span>'
    );
    return preg_replace($search, $replace, $content);
  }
}

?>

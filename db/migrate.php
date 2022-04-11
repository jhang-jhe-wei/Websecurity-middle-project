<?php
define('DB_SERVER', getenv('DB_SERVER'));
define('DB_USERNAME', getenv('DB_USERNAME'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_NAME', getenv('DB_NAME'));
$mysqli = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if (!check_table_exist($mysqli, 'users')) {
  $mysqli->query(<<<SQL
    CREATE TABLE `users` (
      `id` int(11) NOT NULL auto_increment,
      `email` varchar(64) NOT NULL,
      `password` varchar(64) NOT NULL,
      `username` varchar(32),
      `role` varchar(8),
      `image_url` varchar(128),
      PRIMARY KEY (id),
      UNIQUE(email)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
  SQL);
  $encrypted_password = password_hash('12345678', PASSWORD_DEFAULT);

  $mysqli->query(<<<SQL
    INSERT INTO `users` (`email`, `username`, `password`, `role`) VALUES ('admin@gmail.com', 'admin','{$encrypted_password}', 'admin');
  SQL);
}

if (!check_table_exist($mysqli, 'posts')) {
  $mysqli->query(<<<SQL
    CREATE TABLE `posts` (
      `id` int(11) NOT NULL auto_increment,
      `user_id` int(11) NOT NULL,
      `title` varchar(32),
      `content` text NOT NULL,
      `file_url` varchar(64),
      PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
  SQL);
}

if (!check_table_exist($mysqli, 'configs')) {
  $mysqli->query(<<<SQL
    CREATE TABLE `configs` (
      `id` int(11) NOT NULL auto_increment,
      `key` varchar(32),
      `value` varchar(32),
      PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
  SQL);
}

$mysqli->close();

function check_table_exist($mysqli, $table):bool{
  if ($result = $mysqli->query("SHOW TABLES LIKE '".$table."'")) {
      if($result->num_rows == 1) {
        return true;
      }
  }
  return false;
}
?>

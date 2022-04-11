<?php
define('DB_SERVER', getenv('DB_SERVER'));
define('DB_USERNAME', getenv('DB_USERNAME'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_NAME', getenv('DB_NAME'));

class Base
{
  public $conn;
  function __construct()
  {
    $this->conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($this->conn->connect_error) {
      die("Connection failed: " . $this->conn->connect_error);
    }
  }

  function __destruct()
  {
    mysqli_close($this->conn);
  }

  public function execute_sql(string $sql){
    return mysqli_query($this->conn, $sql);
  }
}
?>

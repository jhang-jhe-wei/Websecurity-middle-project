<?php
class View
{
  public static function render($render_data){
    extract($render_data);
    ob_start();
    include 'layouts/application.php';
    ob_end_flush();
  }
}

?>

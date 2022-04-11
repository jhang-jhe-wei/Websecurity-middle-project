<?php
  require_once 'router.php';
  require_once 'models/config.php';
  Config::setup();
  $router = new Router('/posts');

  $router->run();

?>

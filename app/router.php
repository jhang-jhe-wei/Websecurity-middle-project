<?php
class Router {
  const GET = 'GET';
  const POST = 'POST';
  const DELETE = 'DELETE';
  const PATCH = 'PATCH';

  public function __construct($root) {
    if ($_SERVER['REQUEST_METHOD']===self::POST&&isset($_REQUEST['_method'])) {
      if ($_REQUEST['_method']===self::DELETE) {
        $this->method = self::DELETE;
      }elseif ($_REQUEST['_method']===self::PATCH) {
        $this->method = self::PATCH;
      }
    }else{
      $this->method = $_SERVER['REQUEST_METHOD'];
    }
    $this->url = $_SERVER['REQUEST_URI'];
    $this->root = $root;
  }

  public function on($method, $route, $callback) {
    $this->rulers[$method][$route] = $callback;
  }

  public function run() {
    $this->is_url_root();
    $is_match = false;
    foreach ($this->rulers[$this->method] as $route => $callback) {
      if(preg_match("#^{$route}$#", $this->url, $match)){
        $callback($match);
        $is_match = true;
        break;
      }
    }
    if (!$is_match) {
      http_response_code(404);
      die();
    }
  }

  private function is_url_root(){
    if ($this->url === '/') {
      $this->rulers[$this->method][$this->root]();
      die();
    }
  }
}

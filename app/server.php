<?php
  require_once 'router.php';
  require_once 'models/user.php';
  require_once 'models/post.php';
  require_once 'models/config.php';
  require_once 'views/view.php';
  session_start();
  Config::setup();
  User::authenticate_user();

  $router = new Router('/posts');

  $router->on(Router::GET, '/users/new', function() {
    View::render(array(
      'template'=>'views/users/new.php'
    ));
  });

  $router->on(Router::GET, '/user/edit', function() {
    need_signed();
    View::render(array(
      'template'=>'views/users/edit.php'
    ));
  });

  $router->on(Router::POST, '/users', function() {
    verify_authenticity_token();
    $email = $_REQUEST['email'];
    $username = htmlspecialchars($_REQUEST['username']);
    $password = $_REQUEST['password'];
    $password_confirmation = $_REQUEST['password_confirmation'];

    if(!$email || !$username || !$password || !$password_confirmation){
      $_SESSION['alert'] = "輸入欄位不得為空";
      header('Location: /users/new', true, 301);
      die();
    }

    if($password != $password_confirmation){
      $_SESSION['alert'] = "密碼確認輸入不同";
      header('Location: /users/new', true, 301);
      die();
    }

    $result = User::create(array(
      'email' => $email,
      'username' => $username,
      'password' => $password
    ));
    if($result){
      $_SESSION['notice'] = "註冊成功";
      header('Location: /sessions/new', true, 301);
      die();
    }
    else{
      $_SESSION['alert'] = "註冊失敗";
      header('Location: /users/new', true, 301);
      die();
    }
  });

  $router->on(Router::PATCH, '/user', function() {
    need_signed();
    if(empty($_REQUEST['username'])){
      $username = User::$current_user['username'];
    }else{
      $username = htmlspecialchars($_REQUEST['username']);
    }
    $result = User::update(array(
      'username' => $username,
      'image' => $_FILES['image'],
      'image_url' => $_REQUEST['image_url']
    ));
    if ($result) {
      $_SESSION['notice'] = "已成功修改";
      header('Location: /', true, 301);
      die();
    }else{
      header('Location: /user/edit', true, 301);
      die();
    }
  });

  $router->on(Router::GET, '/sessions/new', function() {
    View::render(array(
      'template'=>'views/sessions/new.php'
    ));
  });

  $router->on(Router::POST, '/sessions', function(){
    if (User::login($_REQUEST['email'], $_REQUEST['password'])) {
      $_SESSION['notice'] = "登入成功";
      header('Location: /', true, 301);
      die();
    }
    else{
      $_SESSION['alert'] = "登入失敗";
      header('Location: /sessions/new', true, 301);
      die();
    }
  });

  $router->on(Router::DELETE, '/sessions', function(){
    $_SESSION['notice'] = "已登出";
    unset($_SESSION['user_id']);
    header('Location: /sessions/new', true, 301);
    die();
  });

  $router->on(Router::GET, '/posts', function() {
    $posts = POST::all();
      View::render(array(
      'template'=>'views/posts/index.php',
      'posts' => $posts
    ));
  });

  $router->on(Router::GET, '/posts/new', function() {
    need_signed();
    View::render(array(
      'template'=>'views/posts/new.php'
    ));
  });

  $router->on(Router::POST, '/posts', function() {
    need_signed();
    $result = Post::create(array(
      'user_id' => User::$current_user['id'],
      'title' => $_REQUEST['title'],
      'content' => $_REQUEST['content'],
      'file' => $_FILES['file']
    ));
    if($result){
      $_SESSION['notice'] = "建立成功";
      header('Location: /', true, 301);
      die();
    }
    else{
      $_SESSION['alert'] = "建立失敗";
      header('Location: /posts/new', true, 301);
      die();
    }
  });

  $router->on(Router::GET, '/posts/(?P<id>[0-9]+)', function($params) {
    View::render(array(
      'template' => 'views/posts/show.php',
      'post' => Post::find_by_id($params['id'])
    ));
  });

  $router->on(Router::GET, '/admin', function() {
    if (User::$current_user && User::$current_user['role'] === 'admin') {
      View::render(array(
        'template'=>'views/admin/index.php'
      ));
    }else{
      $_SESSION['alert'] = "您沒有權限";
      header('Location: /', true, 301);
      die();
    }
  });

  $router->on(Router::PATCH, '/admin', function() {
    verify_authenticity_token();
    if (User::$current_user && User::$current_user['role'] === 'admin') {
      $title = strip_tags($_REQUEST['title']);
      Config::update_title($title);
      $_SESSION['notice'] = "已成功修改";
      header('Location: /admin', true, 301);
      die();
    }else{
      $_SESSION['alert'] = "您沒有權限";
      header('Location: /', true, 301);
      die();
    }
  });

  $router->run();

  function need_signed(){
    if (!User::$current_user){
      $_SESSION['alert'] = "請先登入";
      header('Location: /sessions/new', true, 301);
      die();
    }
  }

?>

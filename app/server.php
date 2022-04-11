<?php
  require_once 'router.php';
  require_once 'models/user.php';
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

  $router->run();

?>

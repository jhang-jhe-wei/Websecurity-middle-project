<?php
#
#                       _oo0oo_
#                      o8888888o
#                      88" . "88
#                      (| -_- |)
#                      0\  =  /0
#                    ___/`---'\___
#                  .' \\|     |# '.
#                 / \\|||  :  |||# \
#                / _||||| -:- |||||- \
#               |   | \\\  -  #/ |   |
#               | \_|  ''\---/''  |_/ |
#               \  .-\__  '-'  ___/-. /
#             ___'. .'  /--.--\  `. .'___
#          ."" '<  `.___\_<|>_/___.' >' "".
#         | | :  `- \`.;`\ _ /`;.`/ - ` : | |
#         \  \ `_.   \_ __\ /__ _/   .-` /  /
#     =====`-.____`.___ \_____/___.-`___.-'=====
#                       `=---='
#
#
#     ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
#
#               佛祖保佑         永無Bug
#
#
#
  require_once 'router.php';
  require_once 'models/user.php';
  require_once 'models/post.php';
  require_once 'models/config.php';
  require_once 'views/view.php';
  session_start();
  Config::setup();
  User::authenticate_user();

  if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
  }

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
    $email = htmlspecialchars($_REQUEST['email']);
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
    verify_authenticity_token();

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
      $_SESSION['alert'] = "修改失敗";
      die();
    }
  });

  $router->on(Router::GET, '/sessions/new', function() {
    View::render(array(
      'template'=>'views/sessions/new.php'
    ));
  });

  $router->on(Router::POST, '/sessions', function(){
    verify_authenticity_token();
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
    verify_authenticity_token();
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
    verify_authenticity_token();
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
    $post = Post::find_by_id($params['id']);

    if (empty($post)) {
      $_SESSION['alert'] = "不要壞壞偷猜文章編號";
      header('Location: /posts', true, 301);
      die();
    }

    View::render(array(
      'template' => 'views/posts/show.php',
      'post' => $post
    ));
  });

  $router->on(Router::DELETE, '/posts/(?P<id>[0-9]+)', function($params) {
    need_signed();
    verify_authenticity_token();
    $result = null;
    $post = Post::find_by_id($params['id']);

    if (empty($post)) {
      $_SESSION['alert'] = "不要壞壞亂刪文章";
      header('Location: /posts', true, 301);
      die();
    }

    if($post['user_id'] == User::$current_user['id']){
      $result = Post::delete(array(
        'id' => $params['id']
      ));
    }
    else {
      $_SESSION['alert'] = "不要壞壞亂刪文章";
      header('Location: /', true, 301);
      die();
    }

    if($result){
      $_SESSION['notice'] = "刪除成功";
      header('Location: /', true, 301);
      die();
    }
    else{
      $_SESSION['alert'] = "刪除失敗";
      header('Location: /', true, 301);
      die();
    }
  });

  $router->on(Router::GET, '/admin', function() {
    need_signed();
    need_admin();
    View::render(array(
      'template'=>'views/admin/index.php'
    ));
  });

  $router->on(Router::PATCH, '/admin', function() {
    verify_authenticity_token();
    need_signed();
    need_admin();

    $title = htmlspecialchars($_REQUEST['title']);
    $site_title = Config::update_title($title);
    if(empty($site_title)){
      $_SESSION['alert'] = "修改失敗";
      header('Location: /admin', true, 301);
      die();
    }
    $_SESSION['notice'] = "已成功修改";
    header('Location: /admin', true, 301);
    die();
  });

  $router->run();

  function need_admin(){
    if (User::$current_user['role'] != 'admin'){
      $_SESSION['alert'] = "您沒有權限壞壞";
      header('Location: /', true, 301);
      die();
    }
  }

  function need_signed(){
    if (!User::$current_user){
      $_SESSION['alert'] = "請先登入";
      header('Location: /sessions/new', true, 301);
      die();
    }
  }

  function verify_authenticity_token(){
    if (hash_equals($_SESSION['token'], $_POST['token'])) {
      return true;
    }else{
      http_response_code(403);
      die();
    }
  }
?>

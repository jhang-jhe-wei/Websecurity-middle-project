<h1 class="my-4">登入</h1>
<form method="POST" action="/sessions">
  <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?>" />
  <div class="mb-3">
    <label for="email" class="form-label">電子信箱</label>
    <input id="email" class="form-control" required autofocus="" type="email" name="email">
  </div>
  <div class="mb-3">
    <label for="password" class="form-label"> 密碼 </label>
    <input id="password" class="form-control" required type="password" name="password">
  </div>
  <button type="submit" class="btn btn-primary">登入</button>
</form>

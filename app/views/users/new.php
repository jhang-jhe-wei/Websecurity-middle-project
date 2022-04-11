<h1 class="my-4">註冊</h1>
<form method="POST" action="/users">
  <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?>" />
  <div class="mb-3">
    <label for="email" class="form-label">電子信箱</label>
    <input id="email" class="form-control" required autofocus="" type="email" name="email">
  </div>
  <div class="mb-3">
    <label for="username" class="form-label">用戶名稱</label>
    <input id="username" class="form-control" required type="text" name="username">
  </div>
  <div class="mb-3">
    <label for="password" class="form-label"> 密碼 </label>
    <input id="password" class="form-control" required type="password" name="password">
  </div>
  <div class="mb-3">
    <label for="password_confirmation" class="form-label"> 密碼確認 </label>
    <input id="password_confirmation" class="form-control" required type="password" name="password_confirmation">
  </div>
  <button type="submit" class="btn btn-primary">註冊</button>
</form>

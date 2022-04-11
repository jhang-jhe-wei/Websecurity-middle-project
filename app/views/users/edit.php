<h1 class="my-4">修改個人資料</h1>
<form method="POST" action="/user" enctype="multipart/form-data">
  <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?>" >
  <input type="hidden" name="_method" value="PATCH" >
  <div class="mb-3">
    <label for="username" class="form-label">用戶名稱</label>
    <input id="username" class="form-control" type="text" name="username">
  </div>
  <div class="mb-3">
    <label for="image" class="form-label">大頭貼檔案</label>
    <input id="image" class="form-control" type="file" name="image">
  </div>
  <div class="mb-3">
    <label for="image_url" class="form-label">大頭貼連結</label>
    <input id="image_url" class="form-control" type="text" name="image_url">
  </div>
  <button type="submit" class="btn btn-primary">更新</button>
</form>

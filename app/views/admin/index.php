<h1 class="my-4">管理者介面</h1>
<form method="POST" action="/admin">
  <input type="hidden" name="_method" value="PATCH">
  <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?>" />
  <div class="mb-3">
    <label for="title" class="form-label">全站標題</label>
    <input class="form-control" id="title" name="title" placeholder="全站標題"></input>
  </div>
  <button type="submit" class="btn btn-primary">更新</button>
</form>

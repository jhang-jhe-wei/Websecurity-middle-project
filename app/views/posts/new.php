<h1 class="my-4">新增文章</h1>
<form method="POST" action="/posts" enctype="multipart/form-data">
  <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?>" />
  <div class="mb-3">
    <label for="title" class="form-label"> 文章標題 </label>
    <input id="title" placeholder="文章標題" required autofocus="" class="form-control" type="text" name="title">
  </div>

  <div class="mb-3">
    <label for="content" class="form-label"> 文章內文 </label>
    <textarea id="content" name="content" class="form-control" rows="5" cols="33" placeholder="文章內文"></textarea>
  </div>

  <div class="mb-3">
    <label for="file" class="form-label"> 附加檔案 </label>
    <input id="file" class="form-control" type="file" name="file">
  </div>
  <button type="submit" class="btn btn-primary">新增文章</button>
</form>

<style>
.add-posts-btn {
  width: 70px;
  height: 70px;
  border-radius: 35px;
  font-size: 50px;
  text-align: center;
  color: #FBFFFE;
  line-height: 60px;
  position: fixed;
  right: 50px;
  bottom: 50px;
  background-color: #FAA916;
  text-decoration: none;
  opacity: 0.8;
  z-index: 999;
}

.add-posts-btn:hover {
  opacity: 1;
  color: white;
}

.post-card {
  width: 100%;
  height: 200px;
  overflow-y: hidden;
  background-color: white;
  padding: 0px 20px;
  margin-top: 28px;
  border-radius: 5px;
  position: relative;
}

.post-card-title {
  white-space: nowrap;
  max-width: 80%;
  overflow-x: hidden;
  font-size: 20px;
  font-weight: bold;
  color: #0A253E;
  text-decoration: none;
}

.post-card-content {
  font-size: 16px;
  overflow-y: hidden;
  max-height: 100px;
}

.post-card-info {
  position: absolute;
  bottom: 20px;
}

.close {
  position: absolute;
  right: 20px;
  top: 20px;
  width: 10px;
  height: 10px;
  opacity: 0.3;
}

.close:hover {
  opacity: 1;
}

.close:before, .close:after {
  position: absolute;
  content: ' ';
  height: 15px;
  width: 3px;
  background-color: #0A253E;
}

.close:before {
  transform: rotate(45deg);
}

.close:after {
  transform: rotate(-45deg);
}
</style>

<?php if(User::$current_user): ?>
  <a href="/posts/new" class="add-posts-btn">+</a>
<?php endif; ?>

<div class="pb-5">
<?php while ($post = mysqli_fetch_assoc($posts)): ?>
  <div class="post-card">
    <?php if (User::$current_user['id'] == $post['user_id']): ?>
      <form class="d-inline" method="POST" action="/posts/<?php echo $post['id'] ?>">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?>" >
        <a href="#" class="close" onclick="confirm('你確定要刪掉文章嗎？') && this.closest('form').submit();return false;"></a>
      </form>
    <?php endif; ?>
    <h2>
      <a class="post-card-title" href="/posts/<?php echo $post['id'] ?>">
        <?php echo $post['title'] ?>
      </a>
    </h2>
    <p class="post-card-content"><?php echo $post['content'] ?></p>
    <div class="post-card-info">
      <img class="img-profile rounded-circle" width="20" height="20" src="<?php echo htmlspecialchars($post['image_url']) ?>">
      <span class="align-middle me-2"><?php echo htmlspecialchars($post['username']) ?></span>
              <?php if($post['file_url']): ?>
          <a href="<?php echo $post['file_url']; ?>" download>
            <img width="20" src="https://cdn.iconscout.com/icon/free/png-256/download-file-1930149-1634722.png" alt="Download img">
          </a>
        <?php endif; ?>

    </div>
  </div>
<?php endwhile; ?>
</div>

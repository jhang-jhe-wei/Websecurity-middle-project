<nav class="sticky-top navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="w-full container d-flex justify-content-between">
    <h1 class="py-2 mb-0"><a class="navbar-brand fs-1" href="/"><?php echo Config::$site_title ?></a></h1>
    <?php if(User::$current_user): ?>
      <div class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="align-middle me-2 text-white fs-5"><?php echo htmlspecialchars(User::$current_user['username']) ?></span>
          <img class="img-profile rounded-circle" width="30" height="30" src="<?php echo htmlspecialchars(User::$current_user['image_url']) ?>">
        </a>
        <!-- Dropdown - User Information -->
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
            <?php if(User::$current_user['role'] === 'admin'): ?>
              <a class="dropdown-item" href="/admin"> <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> 進入後台</a>
            <?php endif; ?>
            <a class="dropdown-item" href="/user/edit">
                <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                編輯個人資料
            </a>
            <div class="dropdown-divider"></div>
            <form method="POST" action="/sessions">
              <input type="hidden" name="_method" value="DELETE">
              <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?>" />
              <a class="dropdown-item text-danger" onclick="this.closest('form').submit();return false;">登出</a>
            </form>
        </div>
      </div>
    <?php else: ?>
      <div class="navbar-nav">
        <a class="nav-link" href="/users/new">註冊</a>
        <a class="nav-link" href="/sessions/new">登入</a>
      </div>
    <?php endif; ?>
  </div>
</nav>


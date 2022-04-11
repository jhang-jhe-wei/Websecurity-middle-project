<?php
  if (isset($_SESSION['notice'])) {
    $notice = $_SESSION['notice'];
    unset($_SESSION['notice']);
    echo <<<HTML
      <div class="text-center alert alert-success" role="alert">
        {$notice}
      </div>
    HTML;
  }
?>

<?php
  if (isset($_SESSION['alert'])) {
    $alert = $_SESSION['alert'];
    unset($_SESSION['alert']);
    echo <<<HTML
      <div class="text-center alert alert-danger" role="alert">
        {$alert}
      </div>
    HTML;
  }
?>

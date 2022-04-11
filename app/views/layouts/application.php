<!DOCTYPE html>
<html>
  <head>
    <title>Web Security</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <style>
      html, body {
        background-color: #EDEDED;
        color: #0A253E;
        min-height: 100vh;
      }

      .container-small {
        max-width: 800px;
        margin: 0 auto;
      }
    </style>
  </head>

  <body class="bg-light">
    <?php
      include('_nav.php');
      include('_notice.php');
      include('_alert.php');
    ?>
    <div class="container-small">
      <?php include($_SERVER['DOCUMENT_ROOT'].'/'.$template); ?>
    </div>
  </body>
</html>

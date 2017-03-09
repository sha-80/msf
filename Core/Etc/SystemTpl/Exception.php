<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title><?= $title ?></title>
  </head>
  <body>
    <p align="center">
      <?= $error ?>
      <?php
      if ( strpos( $_SERVER['HTTP_USER_AGENT'], 'DeAle' ) !== false )
      {
        echo '<pre>';
        print_r( debug_backtrace(  ) );
        echo '</pre>';
      }
      ?>
    </p>
  </body>
</html>
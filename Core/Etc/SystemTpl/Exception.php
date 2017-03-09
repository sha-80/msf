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
      $ua = isset( $_SERVER['HTTP_USER_AGENT'] ) and is_string( $_SERVER['HTTP_USER_AGENT'] )
        ? $_SERVER['HTTP_USER_AGENT']
        : '';

      if ( $ua == DEVEL_UA )
      {
        echo '<pre>';
        print_r( debug_backtrace(  ) );
        echo '</pre>';
      }
      ?>
    </p>
  </body>
</html>
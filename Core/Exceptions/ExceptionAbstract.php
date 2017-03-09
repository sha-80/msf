<?php
namespace Exceptions;

abstract class ExceptionAbstract extends \Exception
{
  public function __construct ( $message )
  {
    self::coreStop( $message );
  }

  public static function coreStop ( $error )
  {
    $title = 'Oops';

    if ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) and $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )
      \Components\XHR::returnError( $error );

    exit( include ( MSF_CORE . '/Etc/SystemTpl/Exception.php' ) );
  }

  public static function log ( $code_type, $desc, $line = 0, $file = '', $mail = FALSE )
  {
    return true;
  }

}
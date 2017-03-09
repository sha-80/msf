<?php
namespace Exceptions;

/**
 * Обрабатывает системные исключения и прерывает работу системы.
 *
 * @author De Ale <4deale@gmail.com>
 */
class CoreException extends ExceptionAbstract
{
  public static function coreExceptionHandler ( $e )
  {
    $debug = debug_backtrace();

    echo '<pre>';
    print_r( $debug );
    echo '</pre>';

    self::coreStop( $e -> getMessage() );
  }

  public static function coreErrorHandler ( $errno, $errstr, $errfile, $errline )
  {
    $debug = debug_backtrace();

    echo '<pre>';
    print_r( $debug );
    echo '</pre>';
    
    self::coreStop( "{$errstr}_{$errfile}:{$errline}" );
  }
}
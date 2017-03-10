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
    self::coreStop( $e -> getMessage() );
  }

  public static function coreErrorHandler ( $errno, $errstr, $errfile, $errline )
  {
    self::coreStop( "{$errstr}_{$errfile}:{$errline}" );
  }
}
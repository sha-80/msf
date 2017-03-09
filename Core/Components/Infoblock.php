<?php
namespace Components;

class Infoblock
{
  /**
   * Массив инфосообщений
   * @var array
   */
  private static $_info = [];

  public static function setInfo( $info, $block = 'general' )
  {
    self::setBlock( $block, 'info', $info );
    return true;
  }

  public static function setSuccess( $success, $block = 'general' )
  {
    self::setBlock( $block, 'success', $success );
    return true;
  }

  public static function setWarning( $warning, $block = 'general' )
  {
    self::setBlock( $block, 'warning', $warning );
    return true;
  }

  public static function setError( $error, $block = 'general' )
  {
    self::setBlock( $block, 'error', $error );
    return true;
  }

  protected static function setBlock( $block, $type, $info )
  {
    self::$_info[$block][$type] = $info;
  }
}
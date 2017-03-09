<?php
namespace Components;

use Database\DB;

class Session
{
  /**
   * Стартовать сессию при запуске
   * @var bool
   */
//  protected static $_autostart = false;

  /**
   * Признак старта сессии для возможных блокировок повторных стартов и прочего
   * @var bool
   */
  protected static $_run = false;

  /**
   * Контейнер для хранения настроек для ini_set при запуске сессии
   * @var array
   */
  protected static $_set = [];

//  public static function autoStart( $start )
//  {
//    self::$_autostart = (bool)$start;
//  }

  public static function setSettingsArray( array $array )
  {
    foreach ( $array as $name => $value )
      self::setSettings ( $name, $value );
  }

  public static function setSettings( $name, $value )
  {
    self::$_set[$name] = $value;
  }

  public static function run(  )
  {
    if ( self::$_run )
      return true;

    self::$_run = true;

    foreach ( self::$_set as $name => $value )
      ini_set( "session.{$name}", $value );

    session_start(  );
  }

  public static function setSession( $name, $value )
  {
    self::run(  );
    $_SESSION[$name] = $value;
    return $value;
  }

  public static function issetSession( $name )
  {
    self::run(  );
    return isset( $_SESSION[$name] );
  }

  public static function unsetSession( $name )
  {
    self::run(  );
    unset( $_SESSION[$name] );
  }

  public static function getSession( $name, $default = null )
  {
    self::run(  );
    return ( self::issetSession( $name ) ) ? $_SESSION[$name] : $default;
  }

  public static function getArraySession(  )
  {
    self::run(  );
    return $_SESSION;
  }

  public static function getSessionId(  )
  {
    self::run(  );
    return session_id(  );
  }

  public static function destroy(  )
  {
    self::run(  );
    return session_destroy(  );
  }

  public static function unsetAllSession(  )
  {
    self::run(  );
    return session_unset(  );
  }
}

<?php
namespace Core\Controller;

class Router
{
  protected static $_rules = [];
  
  public static function setRule( $uri, array $settings )
  {
    self::$_rules[$uri] = $settings;
  }
  
  public static function getRule( $uri )
  {
    return ( isset( self::$_rules[$uri] ) ) ? self::$_rules[$uri] : null;
  }
  
  public static function removeRule( $uri )
  {
    unset( self::$_rules[$uri] );
  }
  
  public static function findRule( $uri )
  {
    return ( isset( self::$_rules[$uri] ) ) ? self::$_rules[$uri] : false;
  }
}
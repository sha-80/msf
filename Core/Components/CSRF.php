<?php
namespace Components;

class CSRF
{
  public static function check(  )
  {
    $token = ( isset( $_POST['token'] ) and is_string( $_POST['token'] ) )
      ? trim( $_POST['token'] )
      : false;

    if ( $token and $token === Session::getSessionId() )
      return true ;
    
    return false;
  }

  public static function generate(  )
  {
    return Session::getSessionId() ;
  }
}

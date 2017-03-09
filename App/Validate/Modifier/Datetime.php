<?php
namespace App\Validate\Modifier;

class Datetime extends \Core\Property\ModifierAbstract
{
  public static function toBD( $data )
  {
    return ( $data === true ) ? date( 'Y-m-d H:i:s' ) : ( ( is_int( $data ) ? date( 'Y-m-d H:i:s', time(  ) + $data ) : $data ) ) ;
  }
}
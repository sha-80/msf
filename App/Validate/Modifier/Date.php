<?php
namespace App\Validate\Modifier;

class Date extends \Core\Property\ModifierAbstract
{
  public static function toBD( $data )
  {
    return ( $data === true ) ? false : ( ( is_int(  $data ) ? date( 'Y-m-d', time(  ) + $data ) : $data ) ) ;
  }
}
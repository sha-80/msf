<?php
namespace App\Validate\Modifier;

class Password extends \Core\Property\ModifierAbstract
{
  public static function toBD( $data )
  {
    return hash( 'sha512', md5( $data ) );
  }

  public static function toPrint( $data, $model = null )
  {
    return '';
  }
}
<?php
namespace App\Validate;

use Core\Property\ValidateException as Exc;

class Id extends \Core\Property\ValidateAbstract
{
  public $attr = [
    'required' => true,
    'autocomplete' => 'off'
  ];

  public function check ( $data )
  {
    if ( $this -> attr['required'] and empty( $data ) )
      throw new Exc( 'Данные не переданы' );

    if ( ! $this -> attr['required'] and empty( $data ) )
      return true;

    if ( ! preg_match( '#^\d{1,10}$#', $data ) )
      throw new Exc( 'Идентификатор не число' );

    if ( $data > 4294967295 )
      throw new Exc( 'Число большое' );

    return true;
  }
}
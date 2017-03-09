<?php
namespace App\Validate;

use Core\Property\ValidateException as Exc;

class Text extends \Core\Property\ValidateAbstract
{
  public $attr = [
    'required' => true,
    'maxlength' => 255
  ];

  public function check ( $data )
  {
    if ( $this -> attr['required'] and empty( $data ) )
      throw new Exc( 'Данные не переданы' );

    if ( ! $this -> attr['required'] and empty( $data ) )
      return true;

    if ( mb_strlen( $data ) > $this -> attr['maxlength'] )
      throw new Exc( 'Строка длинная' );

    return true;
  }
}
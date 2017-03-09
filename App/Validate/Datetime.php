<?php
namespace App\Validate;

use Core\Property\ValidateException as Exc;

class Datetime extends \Core\Property\ValidateAbstract
{
  public $attr = [
    'required' => true,
  ];

  public function check( $data )
  {
    if ( ! $data and $this -> attr['required'] )
      throw new Exc( 'Дата не передана' );

    else if ( ! $data and ! $this -> attr['required'] )
      return true;

    if ( ! preg_match( '#^\d{1,4}\-\d{2}\-\d{2}\s\d{2}\:\d{2}\:\d{2}$#ui', $data ) )
      throw new Exc( 'Дата имеет не правильный формат' );

    list( $y, $m, $d ) = explode( '-', $data );

    if ( strtotime( $data ) === false )
      throw new Exc( 'Даты не существует' );

    return true;
  }
}
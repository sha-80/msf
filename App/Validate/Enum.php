<?php
namespace App\Validate;

use Core\Property\ValidateException as Exc;

class Enum extends \Core\Property\ValidateAbstract
{
  public $attr = [
    'required' => true,
    'values'    => []
  ];

  public function check( $data )
  {
    if ( ! $data and $this -> attr['required'] )
      throw new Exc( 'Данные не переданы' );

    else if ( ! $data and ! $this -> attr['required'] )
      return true;

    if ( ! $this -> attr['values'] )
      throw new Exc( 'Список значений пуст' );

    if ( is_array( each( $this -> attr['values'] )[1] ) )
    {
      $st = false;
      array_walk( $this -> attr['values'], function( $value, $key ) use ( $data, &$st ) {
        if ( in_array( (string)$data, $value ) )
          $st = true;
      } );

      return $st ;
    }

    if ( ! in_array( (string)$data, $this -> attr['values'] ) )
      throw new Exc( 'Выберите один из предложенных вариантов' );

    return true;
  }
}
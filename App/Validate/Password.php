<?php
namespace App\Validate;

use Core\Property\ValidateException as Exc;

class Password extends \Core\Property\ValidateAbstract
{
  public $attr = [
    'required' => true,
  ];

  public function check( $data )
  {
    if ( ! $data and $this -> attr['required'] )
      throw new Exc( 'Пароль не передан' );

    else if ( ! $data and ! $this -> attr['required'] )
      return true;

    $len = strlen( $data );

    if ( $len < 4 )
      throw new Exc( 'Пароль не может быть меньше 4 символов' );

    if ( $len > 255 )
      throw new Exc( 'Пароль не может быть больше 255 символов' );

    if ( is_numeric( $data ) )
      throw new Exc( 'Пароль не может быть числом' );

    if ( preg_match( '#^\d{2,4}\.?\d{2,4}\.?\d{2,4}$#ui', $data ) )
      throw new Exc( 'Пароль не может быть похожим на дату' );

    return true;
  }
}
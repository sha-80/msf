<?php
namespace App\Validate;

use Core\Property\ValidateException as Exc;

class Email extends \Core\Property\ValidateAbstract
{
  public $attr = [
    'required' => true,
  ];

  public function check( $data )
  {
    if ( ! $data and $this -> attr['required'] )
      throw new Exc( 'Email не передан' );

    else if ( ! $data and ! $this -> attr['required'] )
      return true;

    if ( ! filter_var( $data, FILTER_VALIDATE_EMAIL ) )
      throw new Exc( 'E-mail не корректен' );

    return true;
  }
}
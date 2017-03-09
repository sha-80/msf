<?php
namespace Core\Property;

abstract class ValidateAbstract
{
  const VALIDATE_TYPE_STRING = 1;
  const VALIDATE_TYPE_FILE   = 2;
  const VALIDATE_TYPE_ARRAY  = 3;

  protected $_type = self::VALIDATE_TYPE_STRING;

  public $attr = [
    'required' => true,
  ];

  public function check( $data )
  {
    return true;
  }

  public function getType(  )
  {
    return $this -> _type;
  }
}

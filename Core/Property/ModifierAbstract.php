<?php
namespace Core\Property;

abstract class ModifierAbstract
{
  public static function toBD( $data )
  {
    return $data;
  }

  public static function toPrint( $data, $model = null )
  {
    return $data;
  }
}
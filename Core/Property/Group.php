<?php
namespace Core\Property;

class Group
{
  protected $_error  = false;
  protected $_status = [];
  protected $_property = [];
  protected $_model = null;

  public function __construct ( $model = null )
  {
    $this -> _model = $model;
  }

  public function useFields ( array $fields )
  {
    foreach ( $fields as $row )
      $this -> _property[$row] = $this -> _model -> getFields( $row );

    return $this;
  }

  public function setProperty ( array $param )
  {
    $this -> _property[$param[0]] = new Init( $param );
    return $this ;
  }

  public function getProperty ( $name )
  {
    return $this -> _property[$name];
  }

  public function getStatus (  )
  {
    return $this -> _status;
  }

  public function getError (  )
  {
    return $this -> _error;
  }

  public function unsetProperty ( $name )
  {
    unset( $this -> _property[$name] );
    return $this;
  }

  public function setPropertys ( array $params )
  {
    foreach ( $params as $row )
      $this -> setProperty ( $row );

    return $this ;
  }

  public function isValid(  )
  {
    $a_status = [];

    foreach ( $this -> _property as $name => $row )
    {
      if ( $row -> validate(  ) )
        $a_status[$name] = true;
      else
      {
        $this -> _error = true;
        $a_status[$name] = $row -> getInfo(  );
      }
    }

    $this -> _status = $a_status;
    return ! $this -> _error;
  }
}
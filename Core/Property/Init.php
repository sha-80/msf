<?php
namespace Core\Property;

class Init
{
  /**
   * Массив опций свойства
   * @var array
   */
  protected $_attr = [];

  /**
   * Имя свойства
   * @var string
   */
  protected $_name = 'property';

  /**
   * Маркер или название свойства, что видно пользователю
   * @var string
   */
  protected $_marker = '_UNDEFINED_PROPERTY';

  /**
   * Объект класса валидатора этого свойства/ Используется вдальнейшем для
   * валидации этого свойства
   * @var App\Property
   */
  protected $_validate;

  /**
   * Объект модификатора данных при работе с БД и выводом
   * @var Property\Modifier
   */
  protected $_modifier;

  protected $_info = null;
  protected $_error = null;

  /**
   * Значение свойства после валидации. Устанавливается по ссылке с модели.
   * @var mixed
   */
  public $value;

  public function __construct( array $init = null )
  {
    $this -> preparePropertyArray( $init );
    $this -> value = &$this -> _attr['value'];
  }

  protected function preparePropertyArray( array $init )
  {
    if ( ! $init )
      return true;

    if ( count( $init ) < 2 )
      throw new \Exceptions\DevelException( '_PROPERTY_INIT_ARRAY_INCORRECT' );

    $this -> setName( $init[0] );
    $this -> initValidate( $init[1] );
    $this -> setMarker( $init[2] );
    $this -> setAttr( isset( $init[3] ) ? $init[3] : [] );
  }

  /**
   * @param string $method
   * @return boolean
   */
  public function validate( $method )
  {
    $this ->_method = $method;
    $this -> getData(  );

    try
    {
      $this -> getValidate() -> check( $this -> getValue() );
    }
    catch ( ValidateException $e )
    {
      $this -> _error = true;

      $this -> setError( $e -> getMessage(  ) );
      return false;
    }

    return true;
  }

  protected function getData(  )
  {
    $name = $this -> getName(  );

    switch ( $this -> _validate -> getType(  ) )
    {
      case ValidateAbstract::VALIDATE_TYPE_STRING:
        $data = ( isset( $_REQUEST[$this -> _name] ) and is_string( $_REQUEST[$this -> _name] ) )
          ? trim( (string)$_REQUEST[$this -> _name] )
          : '';
        break;

      case ValidateAbstract::VALIDATE_TYPE_FILE:
        exit( 'Потом допишем' );
        break;

      case ValidateAbstract::VALIDATE_TYPE_ARRAY:
        $data = ( isset( $_REQUEST[$this -> _name] ) and is_array( $_REQUEST[$this -> _name] ) )
          ? (array)$_REQUEST[$this -> _name]
          : [];
        break;

      default:
        exit('ааааа нет типа!!!');
    }
    /*
    if ( $this -> getValidate() -> isArray(  ) )
    {
      if ( $this ->_method  == 'post' )
        $data = ( isset( $_POST[$name] ) ) ? (array)$_POST[$name] : [] ;
      else
        $data = ( isset( $_REQUEST[$name] ) )  ? (array)$_REQUEST[$name]  : [] ;
    }

    else if ( $this -> getValidate() -> isFile(  ) )
      $data = ( isset( $_FILES[$name] ) ) ? $_FILES[$name] : [] ;

    else
    {
      if ( $this ->_method  == 'post' )
        $data = ( isset( $_POST[$name] ) ) ? trim( (string)$_POST[$name] ) : '' ;
      else if ( $this ->_method  == 'cookie' )
        $data = ( isset( $_COOKIE[$name] ) ) ? trim( (string)$_COOKIE[$name] ) : '' ;
      else
        $data = ( isset( $_REQUEST[$name] ) )  ? trim( (string)$_REQUEST[$name] )  : '' ;
    }*/

    $this -> setValue( $data );
  }

  public function setAttr( $attr )
  {
    if ( ! is_array( $attr ) )
      throw new \Exceptions\DevelException( '_PROPERTY_INIT_OPTION_NO_ARRAY' );

    $attr += ['name' => $this -> getName()];
    $attr += $this -> getValidate() -> attr;
    $this -> getValidate() -> attr = $attr;
    $this -> _attr = &$this -> getValidate() -> attr;
    return $this;
  }

  public function setName( $name )
  {
    if ( ! preg_match( '#^[a-z]{1}[a-z_0-9]{0,254}$#i', $name ) )
      throw new \Exceptions\DevelException( '_PROPERTY_INIT_NAME_INCORRECT__' . $name );

    $this -> _name = $name;
  }

  public function initValidate( $type )
  {
    if ( ! preg_match( '#^[a-z]{1}[a-z_0-9]{0,24}$#i', $type ) )
      throw new \Exceptions\DevelException( '_PROPERTY_INIT_VALIDATE_INCORRECT' );

    $this -> _validateName = ucfirst( $type );
    $validate_name = ucfirst( $type );
    $namespace = 'App\\Validate\\' . $validate_name;

    if ( ! \Autoload::nameSpaceExists( $namespace ) )
      throw new \Exceptions\DevelException( '_VALIDATOR_TYPES_NF__' . $validate_name );

    $this -> _validate = new $namespace(  );

    $modifier = "App\\Validate\\Modifier\\" . $validate_name;

    if ( \Autoload::nameSpaceExists( $modifier ) )
      $this -> _modifier = new $modifier( );
    else
      $this -> _modifier = new \Core\Property\Modifier( );

    return $this;
  }

  public function setMarker( $marker )
  {
    $this -> _marker = $marker;
  }

  public function getError(  )
  {
    return $this -> _error;
  }

  public function getInfo(  )
  {
    return $this -> _info;
  }

  public function getValidateName(  )
  {
    return $this -> _validateName;
  }

  public function getMarker(  )
  {
    return $this -> _marker;
  }

  public function getName(  )
  {
    return $this -> _name;
  }

  /**
   * Выборка значения свойства по имени
   * @param string $name
   * @return mixed
   */
  public function getAttrName( $name )
  {
    return ( isset( $this -> _attr[$name] ) ) ? $this -> _attr[$name] : null;
  }

  public function getAllAttr(  )
  {
    return $this -> _attr;
  }

  public function getValidate(  )
  {
    if ( ! $this -> _validate )
      throw new \Exceptions\DevelException( '_PROPERTY_INIT_VALIDATE_NF' );

    return $this -> _validate;
  }

  public function setValue( $value )
  {
    $this -> addAttr( 'value', $value );
    return $this;
  }

  public function &getValue(  )
  {
    $var = $this -> getAttrName( 'value' );
    return $var;
  }

  public function addAttr( $name, $value )
  {
    $this -> _attr[$name] = $value;
    $this -> _validate -> attr[$name] = $value;
    return $this;
  }

  public function setError( $error )
  {
    $this -> _info = $error;
    return $this;
  }

  public function toBD(  )
  {
    return $this -> _modifier -> toBD( $this -> getValue(  ) );
  }

  public function toPrint( $data )
  {
    return $this -> _modifier -> toPrint( $data, $this );
  }
}

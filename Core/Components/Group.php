<?php
namespace Components;

class Group
{
  /**
   * Объект сущности с которого будет происходить импорт данных полей
   * @var object \Model\ModelBundle
   */
  private $_model = null;

  /**
   * Массив объектов полей, используемых в форме
   * @var object \Property\Init
   */
  private $_fields = [ ];

  /**
   * Статуси валидации полей при прохождении валидации.
   * Содержит в себе имена полей и их успешность валидации в bool типе.
   * @var array
   */
  private $_fieldValid = null;

  /**
   * Метод формы
   * @var string
   */
  private $_method = 'post';

  /**
   * Признак валидации группы
   * @var bool
   */
  protected $_valid;

  /**
   * Необходимость проверить токен CSRF
   * @var bool
   */
  private $_checkToken = true;

  /**
   * Установка инфоблока по умолчанию, если обнаружена ошибка
   * @var bool
   */
  private static $_autoError = true;

  /**
   * Текст ошибки при валидации по умолчанию
   * @var bool
   */
  private static $_textErrorValid = 'В переданных данных найдены ошибки';

  /**
   * Имя инфоблока, куда будут сбрасываться ошибки при работе с группой
   * @var bool
   */
  private static $_nameInfoBlock = 'general';

  /**
   * Текст ошибки при валидации
   * @var bool
   */
  protected $_generalError;

  /**
   * Признак построения формы с группы. Необходимо для валидации и проверки CSRF
   * @var bool
   */
//  protected $_form_exists = false;

  function __construct ( \Core\Model\ModelAbstract $model = null )
  {
    if ( $model )
      $this -> _model = $model;
  }

  public static function setAutoErrorInfoBlock( $bool )
  {
    self::$_autoError = (bool)$bool;
  }

  public static function setTextValidError( $text )
  {
    self::$_textErrorValid = $text;
  }

  public static function setNameInfoBlock( $name )
  {
    self::$_nameInfoBlock = $name;
  }

  public function getName ()
  {
    return $this -> _name;
  }

  public function getFieldValid(  )
  {
    return $this -> _fieldValid;
  }

  /**
   * Перечень полей модели, которые надо использовать в билдере
   * @param array $_fields
   * @return \Group\Builder
   */
  public function useFields ( array $_fields )
  {
    foreach ( $_fields as $name )
      $this -> addField( $name );

    return $this;
  }

  public function addField( $name )
  {
    $this -> _fields[$name] = $this -> _model -> getFields( $name );
    return $this ;
  }

  public function checkToken ( $check = true )
  {
    $this -> _checkToken = $check;
    return $this;
  }

  public function issetFields ( $field  )
  {
    return isset( $this -> _fields[$field] );
  }

  /**
   *
   * @param string $field
   * @return object Property\Init
   */
  public function getFields ( $field = null )
  {
    if ( $field )
    {
      if ( isset( $this -> _fields[$field] ) )
        return $this -> _fields[$field];
      else
        exit( $field . 'Stoped: <b>' . mf_get_spath( 1 ) . '</b>' );
    }

    return $this -> _fields;
  }

  public function getValue ( $field  )//getFieldsValue
  {
    if ( isset( $this -> _fields[$field] ) )
      return $this -> _fields[$field] -> getValue(  );
    else
      exit( $field . 'Stoped: <b>' . mf_get_spath( 1 ) . '</b>' );
  }

  public function getValuesAllField (  )
  {
    $a_ret = [ ];

    foreach ( $this -> _fields as $name => $field )
    {
      $a_ret[$field -> getName()] = $field -> getValue();
    }

    return $a_ret;
  }

  public function isValid ( $method = null )
  {
    if ( $method )
      $this -> _method = $method;

    $a_valid = [  ];
    $this -> _valid  = true;

    foreach ( $this -> _fields as $name => $field )
    {
      $valid          = $field -> validate( $this -> _method );
      $a_valid[$name] = (bool)$valid;

      if ( ! $valid )
        $this -> _valid = false;
    }

    $this -> _fieldValid = $a_valid;

    if ( ! $this -> _valid )
    {
      $this -> _generalError = self::$_textErrorValid;

      if ( self::$_autoError and $this -> _checkToken )
        \Components\Infoblock::setInfo( self::$_textErrorValid );
    }

    if ( $this -> _valid and $this -> _method == 'post' and $this -> _checkToken )
    {
      if ( ! \Components\CSRF::check(  ) )
      {
        $this -> _generalError = '!Токен безопастности устарел. Обновите страницу и повторите попытку.';

        if ( self::$_autoError )
          \Components\Infoblock::setError( '!Токен безопастности устарел. Обновите страницу и повторите попытку.' );

        $this -> _valid = false;
      }
    }

    return $this -> _valid;
  }

  public function setField ( array $options = [  ] )
  {
    $obj = new \Property\Init( $options );

    $this -> _fields[$options[0]] = $obj;
    return $this;
  }

  public function getAllError ()
  {
    $a_err = [ ];

    foreach ( $this -> _fields as $name => $field )
    {
      if ( $field -> getError(  ) )
        $a_err[$field -> getName()] = $field -> getInfo();
    }

    return $a_err;
  }

  public function setFieldError ( $field, $error )
  {
    if ( ! isset( $this -> _fields[$field] ) )
      throw new \Exceptions\DevelException( '_BUILDER_FIELD_NF__' . $field );

    $this -> _error = true;
    $this -> _fields[$field] -> setError( $error );

    $this -> _generalError = self::$_textErrorValid;

    if ( self::$_autoError )
      \Components\Infoblock::setInfo( self::$_textErrorValid );

    return true;
  }
}

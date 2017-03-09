<?php
/**
 * Автолоадер для неймспесов.
 *
 * Выполняет загрузку классов учитывая его пространство имен
 * @author De Ale <4deale@gmail.com>
 */
class Autoload
{
  // Контенер неймспейсов
  protected static $reset = [];

  // Контенер неймспейсов
  protected static $namespaces = [];

  // Контейнер расширений файлов
  protected static $exts = [];

  /**
   * Установка namespaces
   * @param array $nss
   * return null
   */
  public static function setNameSpaces ( array $nss )
  {
    foreach ( $nss as $ns_name => $dir )
      self::addNameSpace( $ns_name, $dir );
  }

  /**
   * Добавление namespace
   * @param string $namespace
   * @param string $dir
   * @return null
   * @example \System\Common\Timer Первым параметром указывается имя пространства,
   *          вторым - путь в файлу с классом с обратным слешем относительно
   *          системной директории.
   * @throws DevelException
   */
  public static function addNameSpace ( $namespace, $dir )
  {
    $valid = self::validateNameSpace( $namespace, $dir );

    if ( $valid !== TRUE )
      throw new \Exceptions\DevelException( $valid );

    self::$namespaces[trim( $namespace, '\\' )] = $dir;
  }

  /**
   * Удаление namespace
   * @param string $namespace
   * @return null
   */
  public static function deleteNameSpace ( $namespace )
  {
    unset( self::$namespaces[$namespace] );
  }

  /**
   * Перезаписывание namespace
   * @param string $namespace
   * @param string $dir
   * @return null
   */
  public static function replaceNameSpace ( $namespace, $dir )
  {
    self::deleteNameSpace( $namespace );
    self::addNameSpace( $namespace, $dir );
  }

  /**
   * Проверка существования namespace
   * @param string $namespace
   * @return bool
   */
  public static function isNameSpace ( $namespace )
  {
    return isset( self::$namespaces[$namespace] );
  }

  /**
   * Проверка namespace
   * @param string $namespace
   * @return bool
   */
  public static function nameSpaceExists ( $space )
  {
    return (bool)self::findFile( $space );
  }

  /**
   * Выборка namespaces
   * @param string $namespace (Default: NULL)
   * @return array
   */
  public static function getNameSpaces ( $namespace = NULL )
  {
    if ( $namespace )
      return isset( self::$namespaces[$namespace] ) ? self::$namespaces[$namespace] : FALSE;

    return self::$namespaces;
  }

  /**
   * Установка файлом расширений
   * @param array $exts
   */
  public static function setFileExts ( array $exts )
  {
    foreach ( $exts as $ext )
      self::addFileExt( $ext );
  }

  /**
   * Добавление файла расширений
   * @param string $ext
   */
  public static function addFileExt ( $ext )
  {
    if ( array_search( $ext, self::$exts ) === FALSE )
      self::$exts[] = $ext;
  }

  /**
   * Выборка всех расширений
   * @return array
   */
  public static function getFileExts ()
  {
    return self::$exts;
  }

  /**
   * Валидация namespace
   * @param string $ns_name
   * @param string $dir
   */
  protected static function validateNameSpace ( $ns_name, $dir )
  {
    if ( ! preg_match( '/^([\\a-z_]+)$/i', $ns_name ) )
      return 'Название неймспейса не поддерживается__' . $ns_name;

    foreach ( self::getFileExts() as $ext )
    {

      if ( is_file( "{$dir}.{$ext}" ) or is_dir( $dir ) )
        return true;
    }

    return "Путь к неймспейсу не корректен__(dir:<b>{$dir}</b>; ns:<b>{$ns_name}</b>)";
  }

  protected static function prepareNs( $namespaces, $namespace )
  {
    $ns = '';

    foreach ( $namespaces as $ns_name => $ns_dir )
    {
      if ( strpos( $namespace, $ns_name ) === 0 )
      {
        if ( mb_strlen( $ns_name ) > mb_strlen( $ns ) )
          $ns = $ns_name;
      }
    }

    if ( ! $ns or ! isset( $namespaces[$ns] ) )
      return FALSE;

    $file = $namespaces[$ns];
    $file .= str_replace( '\\', '/', substr( $namespace, strlen( $ns ) ) );
    return $file;
  }

  /**
   * Поиск файла
   * @param string $namespace
   * @return bool
   */
  protected static function findFile ( $namespace )
  {
    // Выбираем все декларированные NameSpace
    $namespaces = self::getNameSpaces();

    if ( ! $file = self::prepareNs( $namespaces, $namespace ) )
      return false;

    return self::getNamespacePath( $namespace, $file );
  }

  protected static function getNamespacePath( $namespace, $file )
  {
    foreach ( self::getFileExts() as $ext )
    {
      if ( is_file( "{$file}.{$ext}" ) )
        return "{$file}.{$ext}";
      else if ( is_file( "{$file}/{$namespace}.{$ext}" ) )
        return "{$file}/{$namespace}.{$ext}";
    }

    return false;
  }

  /**
   * Load class
   */
  public static function loadClass ( $class )
  {
    if ( isset( self::$reset[$class] ) )
      return false;

    if ( $file = self::findFile( $class ) )
      require_once $file;
    else
      throw new \Exceptions\DevelException( "Класс не найден__autoload({$class})" );
  }

  public static function resetNamespace ( $namespace, $path )
  {
    $valid = self::validateNameSpace( $namespace, $path );

    if ( $valid !== TRUE )
      throw new \Exceptions\DevelException( $valid );

    self::$reset[trim( $namespace, '\\' )] = $path;
    require_once "{$path}.php";
  }
}

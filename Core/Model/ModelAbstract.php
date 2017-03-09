<?php
namespace Core\Model;

abstract class ModelAbstract
{
  protected static $db;
  protected $_table, $_pk, $_rules, $_lastError;

  protected $_fields;

  public function __construct (  )
  {
    if ( ! $this -> _table )
      throw new \Exception( 'Не заполнена таблица' );

    if ( ! $this -> _pk )
      throw new \Exception( 'Не заполнено PK' );

    if ( ! $this -> _rules )
      throw new \Exception( 'Не заполнено опись полей' );
  }

  public function getLastError(  )
  {
    return $this -> _lastError;
  }

  public function getFields( $name )
  {
    if ( isset( $this -> _fields[$name] ) )
      return $this -> _fields[$name];

    $obj = new \Core\Property\Init( $this -> _rules[$name] );
    $this -> _fields[$name] = $obj;
    $this -> $name = &$obj -> value;


    return $this -> _fields[$name] ;
  }

  public function __set( $name, $value )
  {
    if ( ! isset( $this -> _rules[$name] ) )
      throw new \Exception( 'Нет филда:' . $name );

    $this -> $name = $value;
    $this -> _fields[$name] = new \Core\Property\Init( $this -> _rules[$name] );
    $this -> _fields[$name] -> value =& $this -> $name;
    $this -> _fields[$name] -> addAttr( 'value', $this -> $name );
  }

  public function save(  )
  {
    if ( isset( $this -> id ) )
      return $this -> update(  );
    else
      return $this -> insert(  );
  }

  public function findPk( $id )
  {
    $sql = "SELECT * FROM `{$this -> _table}` WHERE `{$this -> _rules[$this -> _pk][0]}` = ? LIMIT 1";

    $prep = $this -> getDB(  ) -> prepare( $sql );
    $prep -> execute( [$id] );
    return $prep -> fetch();
  }

  public function deletePk( $id )
  {
    if ( method_exists( $this, 'beforeDelete' ) )
    {
      try
      {
        $this -> beforeDelete(  );
      }
      catch( \Exception $e )
      {
        $this -> _lastError = $e -> getMessage(  );
        return false;
      }
    }

    $sql = "DELETE FROM `{$this -> _table}` WHERE `{$this -> _rules[$this -> _pk][0]}` = ? LIMIT 1";

    $prep = $this -> getDB(  ) -> prepare( $sql );
    //afterDelete
    return $prep -> execute( [$id] );
  }

  public function findByAttributes( array $find )
  {
    $sql = "SELECT * FROM `{$this -> _table}` WHERE  ";
    $ph = [];

    foreach ( $find as $name => $value )
    {
      $sql .= "`{$this -> _rules[$name][0]}` = :{$name} AND ";
      $ph[$name] = $value;
    }

    $prep = $this -> getDB(  ) -> prepare( rtrim( $sql, ' AND ' ) );
    $prep -> execute( $ph );
    return $prep;
  }

  public function findAll(  )
  {
    $sql = "SELECT * FROM `{$this -> _table}` ";
    return $this -> getDB(  ) -> query( $sql );
  }

  public function update(  )
  {
    if ( method_exists( $this, 'beforeUpdate' ) )
    {
      try
      {
        $this -> beforeUpdate(  );
      }
      catch( \Exception $e )
      {
        $this -> _lastError = $e -> getMessage(  );
        return false;
      }
    }

    $sql = "UPDATE `{$this -> _table}` SET ";
    $ph  = [  ];

    foreach ( $this -> _fields as $name => $property )
    {
      $ph[$name] = $this -> $name;

      if ( $name == $this -> _pk )
        continue;

      $sql .= "`{$this -> _rules[$name][0]}` = :{$name}, ";
    }

    $sql = trim( $sql, ', ' );
    $sql .= " WHERE `{$this -> _rules[$this -> _pk][0]}` = :{$this -> _pk}";

    $prep = $this -> getDB(  ) -> prepare( $sql );
    $result = $prep -> execute( $ph );
    //afterUpdate
    return $prep;
  }

  public function insert(  )
  {
    if ( method_exists( $this, 'beforeInsert' ) )
    {
      try
      {
        $this -> beforeInsert(  );
      }
      catch( \Exception $e )
      {
        $this -> _lastError = $e -> getMessage(  );
        return false;
      }
    }

    $sql = "INSERT INTO `{$this -> _table}` SET ";
    $ph  = [  ];

    foreach ( $this -> _fields as $name => $property )
    {
      $sql .= "`{$this -> _rules[$name][0]}` = :{$name}, ";
      $ph[$name] = $property -> toBd(  );
    }

    $prep = $this -> getDB(  ) -> prepare( trim( $sql, ', ' ) );

    try
    {
      $result = $prep -> execute( $ph );
    }
    catch ( \PDOException $e )
    {
      $this -> _lastError = $e -> getMessage();
      echo '<pre>';
      print_r($e);
      echo '</pre>';
      exit( 'Stoped: <b>' . mf_get_spath() . '</b>' );
      return false;
    }
    //afterInsert
    return $this -> getDB(  ) -> lastInsertId(  );
  }

  public function getDB(  )
  {
    if ( isset( self::$db ) )
      return self::$db;

    try
    {
      $pdo = new \PDO(
        "mysql:host=localhost;dbname=" . DB_BASE, DB_USER, DB_PASS, [
        \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8', time_zone = '+00:00'",
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ]
      );

      self::$db = $pdo;
    }
    catch ( \PDOException $e )
    {
      exit( 'Подключение не удалось: ' . $e -> getMessage() );
    }

    return self::$db;
  }
}
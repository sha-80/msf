<?php
namespace Core\View;

class View
{
  private static $instance;
  protected $_data = [];
  protected $_tpl;

  public static function getInstance()
  {
    if ( ! (self::$instance instanceof self) )
      self::$instance = new self();

    return self::$instance;
  }

  private function __construct()
  {

  }

  private function __clone()
  {

  }

  private function __sleep()
  {

  }

  private function __wakeup()
  {

  }

  public function setData( $name, $data, $xss = true )
  {
    if ( $xss )
      $this -> _data[$name] = self::xss( $data );
    else
      $this -> _data[$name] = $data;


    return $this;
  }

  public final static function xss( $data )
  {
    if ( is_array( $data ) )
    {
      array_walk_recursive(
        $data,
        function( &$item, $key ){
          $item = htmlspecialchars( $item );
        }
      );
      return $data;
    }

    return htmlspecialchars( $data );
  }

  public function getData( $name )
  {
    return ( isset( $this -> _data[$name] ) ) ? $this -> _data[$name] : null;
  }

  public function removeData( $name )
  {
    unset( $this -> _data[$name] );
    return $this;
  }

  public function setTpl( $tpl )
  {
    $this -> _tpl = $tpl;
    return $this;
  }

  public function getTpl()
  {
    return $this -> _tp;
  }

  public function render(  )
  {
    extract( $this -> _data );

    if ( file_exists( MSF_APP . "/Views/Pages/{$this -> _tpl}.php" ) )
      include MSF_APP . "/Views/Pages/{$this -> _tpl}.php";
    else
      $this -> e404(  );

    exit;
  }

  public function e404(  )
  {
//    header( "HTTP/1.1 404 Not Found" );
    include MSF_APP . "/Views/Pages/404.php";
    exit;
  }
}
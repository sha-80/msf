<?php
namespace Core\Controller;

class FrontController
{
  protected static $_run = false;
  protected static $_uri;

  public static function run()
  {
    if ( self::$_run )
      throw new \Exceptions\DevelException( "Фронт контроллер уже загружен" );

    self::$_run = true;

    self::prepareURI(  );
    
    $furl = Router::findRule( self::$_uri );

    if ( $furl === false )
    {
      $furl = explode( '/', self::$_uri );
      unset( $furl[0] );
      $furl = array_values( $furl );

      $furl[0] = ( isset( $furl[0] ) and $furl[0] ) ? ucfirst( mb_strtolower( $furl[0] ) ) : 'Index';
      $furl[1] = ( isset( $furl[1] ) and $furl[1] ) ? ucfirst( mb_strtolower( $furl[1] ) ) : 'Index';
    }

    $cont = "App\\Controllers\\{$furl[0]}Controller";
    $view = \Core\View\View::getInstance();

    if ( \Autoload::nameSpaceExists( $cont ) )
    {
      $pc = new $cont;
      $action = "action{$furl[1]}";

      if ( method_exists( $pc, $action ) )
      {
        $view -> setTpl( "{$furl[0]}/{$furl[1]}" );

        $pc -> $action(  );

        $view -> render(  );
      }
      else
        $view -> e404();
    }
    else
      $view -> e404();
  }

  protected static function prepareURI(  )
  {
    self::$_uri = preg_replace( '#(\.{2,}|\.\/|/\.+)#ui', '', addslashes(
      str_replace( "?{$_SERVER['QUERY_STRING']}", '', $_SERVER['REQUEST_URI'] )
    ) );
  }
}
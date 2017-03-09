<?php
namespace Components;

class XHR
{
  public static function returnData ( $text, $array = [  ] )
  {
    $data = [
      'status' => 1,
      'result' => ( ( is_array( $text ) ) ? $text : ['text' => $text] )
    ];

    if ( $array )
      $data['data'] = $array;

    exit( json_encode( $data ) );
  }

  public static  function returnError ( $text, $error = [ ] )
  {
    exit( json_encode( [
      'status' => 0,
      'info'   => $text,
      'error'  => $error,
    ] ) );
  }
}

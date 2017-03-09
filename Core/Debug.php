<?php
function mf_get_spath( $tree = false )
{
  $debug = debug_backtrace(  );

  $path = substr(
    str_replace(
      array( MSF_ROOT . '/', '/' ),
      array( '', '-' ),
      $debug[0]['file']
    ),
    0, -4
  );

  $path .= "/{$debug[0]['line']}";

  if ( $tree )
  {
    $path .= '|||' . substr(
      str_replace(
        array( MSF_ROOT . '/', '/' ),
        array( '', '-' ),
        $debug[1]['file']
      ),
      0, -4
    );
    $path .= "/{$debug[1]['line']}";
  }

  $path .= "<br />" . mf_get_time() . mf_get_memory();
  return $path ;
}

function mf_get_time(  )
{
  return 'Time:<b>' . round( microtime( true ) - MSF_HEADTIME, 4 ) . '</b> sec.<br />';
}

function mf_get_memory(  )
{
  return 'Memory:<b>' . ( round( ( memory_get_usage(  ) - MSF_HEAD_MEMORY_USG ) / (1024 * 2), 3 ) ) . '</b> Kb<br />';
}
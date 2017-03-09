<?php

namespace App\Components;

class Tree
{

  public static function buildTree( $data, $parent_id, $current_id )
  {
    $result = [ ];

    if ( is_array( $data ) )
    {
      $items_count = count( $data );

      for ( $i = 0; $i < $items_count; $i ++ )
      {
        $item = $data[$i];

        if ( $item[$parent_id] == 0 )
        {
          $children      = Tree::getChild( $data, $item[$current_id], $parent_id, $current_id );
          $item['child'] = $children;
          $result[]      = $item;
        }
      }
    }
    return (isset( $result )) ? $result : false;
  }

  public static function getChild( $array, $id, $parent_id, $current_id )
  {
    $count = count( $array );

    for ( $i = 0; $i < $count; $i ++ )
    {
      $item = $array[$i];

      if ( $item[$parent_id] == $id )
      {
        $children      = Tree::getChild( $array, $item[$current_id], $parent_id, $current_id );
        $item['child'] = $children;
        $child_array[] = $item;
      }
    }
    return (isset( $child_array )) ? $child_array : false;
  }

  public static function getIds( $data )
  {
    if ( is_array( $data ) )
    {
      foreach ( $data as $val )
      {
        $id = $val['id'];

        if ( is_array( $val['child'] ) )
        {
          $child        = getChildIds( $val['child'] );
          $result[$id]  = $id . ',' . implode( ',', $child );
          $sub          = getIds( $val['child'] );
          foreach ( $sub as $key => $arr )
            $result[$key] = $arr;
          $child        = [ ];
        }
        else
          $result[$id] = $id;
      }
    }

    return (isset( $result )) ? $result : '';
  }

  public static function getChildIds( $data )
  {
    if ( is_array( $data ) )
    {
      foreach ( $data as $val )
      {
        $result[] = $val['id'];

        if ( is_array( $val['child'] ) )
        {
          $child  = getChildIds( $val['child'] );
          $result = array_merge( $result, $child );
          $child  = [ ];
        }
      }
    }
    return (isset( $result )) ? $result : false;
  }

  public static function getId( $data, $url = '' )
  {
    if ( is_array( $data ) )
    {
      $items_count = count( $data );

      for ( $i = 0; $i < $items_count; $i ++ )
      {
        $result[$data[$i]['id']] = $url . $data[$i]['url'];
        
        if ( is_array( $data[$i]['child'] ) )
        {
          $my           = $url;
          $url .= $data[$i]['url'] . "/";
          $child        = getId( $data[$i]['child'], $url );
          foreach ( $child as $key => $val )
            $result[$key] = $val;
          $child        = [ ];
          $url          = $my;
        }
      }
    }
    return (isset( $result )) ? $result : false;
  }

}

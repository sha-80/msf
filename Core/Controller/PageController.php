<?php
namespace Core\Controller;

abstract class PageController
{
  public function setVar( $name, $data )
  {
    $view = \Core\View\View::getInstance(  );
    $view -> setData( $name, $data );
  }
}
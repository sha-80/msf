<?php
namespace Core\Controller;

abstract class PageController
{
  public function setVar( $name, $data, $xss = true )
  {
    $view2 = \Core\View\View::getInstance(  );
    $view2 -> setData( $name, $data, $xss );
  }
}
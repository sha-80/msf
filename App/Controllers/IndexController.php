<?php
namespace App\Controllers;

class IndexController extends Base
{
  public function actionContact(  )
  {

  }

  public function actionAbout(  )
  {

  }

  public function actionIndex(  )
  {
//    $blog = new \App\Models\Blog;
//    $blog -> id    = 19;
//    $blog -> title = 'New titlesdfdsfsdfdfs';
//    $blog -> text  = 'New textfsdfsdfsdsdffsdfsdfsdfsdfsdfsddsf';
//    $blog -> save();

    $blog = new \App\Models\Blog;
    $this -> setVar( 'a_blog', $blog -> findByAttributes( ['title' => 1111] ) -> fetchAll() );
  }
}
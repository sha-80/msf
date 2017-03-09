<?php
namespace App\Controllers;

class IndexController extends Base
{
  public function actionIndex(  )
  {
    exit( 'Stoped: <b>' . mf_get_spath() . '</b>' );
  }
}
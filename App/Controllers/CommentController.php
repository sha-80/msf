<?php
namespace App\Controllers;

class CommentController extends Base
{
  public function actionIndex(  )
  {
//    $comment = new \App\Models\Comment;
//    $result   = $comment -> findAllComment(  );
//    $count = $comment -> getDB() -> query( 'SELECT FOUND_ROWS()' ) -> fetchColumn();
//    $this -> setVar( 'count', $count );
//    $this -> setVar( 'data', \App\Components\Tree::buildTree( $result -> fetchAll(  ), 'comment_parent_id', 'comment_id' ) );
  }

  public function actionVote(  )
  {
    if ( ! $user_id = \Components\Session::getSession( 'user_id' ) )
      \Components\XHR::returnError ( 'Для голосования сначала пройтиде авторизацию' );

    $group = new \Components\Group( $vote = new \App\Models\Vote );
    $group -> useFields( ['commentId', 'type'] );

    if ( $group -> isValid(  ) )
    {
      if ( $vote -> save(  ) )
        \Components\XHR::returnData( 'Голос учтен' );

      \Components\XHR::returnError( $vote -> getLastError(  ) );
    }

    \Components\XHR::returnError( 'В данных найдены ошибки.', $group -> getAllError(  ) );
  }

  public function actionDelete(  )
  {
    if ( ! $user_id = \Components\Session::getSession( 'user_id' ) )
      \Components\XHR::returnError ( 'Для удаления комментария сначала пройтиде авторизацию' );

    $group = new \Components\Group( $comment = new \App\Models\Comment );
    $group -> useFields( ['id'] );

    if ( $group -> isValid(  ) )
    {
      if ( $comment -> deletePk( $group -> getValue( 'id' ) ) )
        \Components\XHR::returnData( 'Комментарий удален' );

      \Components\XHR::returnError( $comment -> getLastError(  ) );
    }

    \Components\XHR::returnError( 'В данных найдены ошибки.', $group -> getAllError(  ) );
  }

  public function actionAdd(  )
  {
    $group = new \Components\Group( $comment = new \App\Models\Comment );
    $group -> useFields( ['text', 'parentId'] );

    if ( ! $user_id = \Components\Session::getSession( 'user_id' ) )
      $group -> useFields( ['userName', 'userEmail'] );

    if ( $group -> isValid(  ) )
    {
      if ( $id = $comment -> save(  ) )
        \Components\XHR::returnData( 'Комментарий добавлен', $comment -> getSingleRow( $id ) );

      \Components\XHR::returnError( $comment -> getLastError(  ) );
    }

    \Components\XHR::returnError( 'В данных найдены ошибки.', $group -> getAllError(  ) );
  }

  public function actionRegister(  )
  {
    $group = new \Components\Group( $user = new \App\Models\User );
    $group -> useFields( ['name', 'email', 'password'] );

    if ( $group -> isValid(  ) )
    {
      if ( $id = $user -> save(  ) )
      {
        \Components\Session::setSession( 'user_id', $id );
        \Components\XHR::returnData( 'Регистрация прошла успешно', $id );
      }

      \Components\XHR::returnError( $user -> getLastError(  ) );
    }

    \Components\XHR::returnError( 'В данных найдены ошибки.', $group -> getAllError(  ) );
  }

  public function actionInit(  )
  {
    $comment = new \App\Models\Comment;
    $result   = $comment -> findAllComment(  );
    $count = $comment -> getDB() -> query( 'SELECT FOUND_ROWS()' ) -> fetchColumn();

    \Components\XHR::returnData( [
      'count' => $count,
      'data'  => \App\Components\Tree::buildTree( $result -> fetchAll(  ), 'comment_parent_id', 'comment_id' )
    ] );
  }
}
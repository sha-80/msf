<?php
namespace App\Models;

class Vote extends \Core\Model\ModelAbstract
{
  protected $_table  = 'sg_vote';
  protected $_pk     = 'id';
  protected $_rules  = [
    'id'        => [ 'vote_id',         'id', 'ID комментария' ],
    'commentId' => [ 'vote_comment_id', 'id', 'ID комментария' ],
    'userId'    => [ 'vote_user_id',    'id', 'ID пользователя' ],
    'type'      => [ 'vote_type',       'enum', 'Тип голоса', [
      'values' => ['up', 'down']
    ] ],
  ];

  public function beforeInsert()
  {
    $this -> userId = \Components\Session::getSession( 'user_id' );

    if ( ! isset( $this -> userId ) or ! $this -> userId )
      throw new \Exception( 'Авторизируйтесь для голосования' );

    if ( isset( $this -> commentId ) )
    {
      $check = new Comment;
      $data  = $check -> findByAttributes( ['id' => $this -> commentId ] ) -> fetch(  );

      if ( ! $data or $data['comment_user_id'] == \Components\Session::getSession( 'user_id' ) )
        throw new \Exception( 'Вы не можете голосовать за свой комментарий' );
    }
    else
      throw new \Exception( 'Комметария не существует' );

    $check = new Vote;
    $data  = $check -> findByAttributes( [
      'userId'    => $this -> userId,
      'commentId' => $this -> commentId,
    ] ) -> fetch(  );

    if ( $data )
      throw new \Exception( 'Вы уже голосовали за этот комментарий' );
  }
}
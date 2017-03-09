<?php
namespace App\Models;

class Comment extends \Core\Model\ModelAbstract
{
  protected $_table  = 'sg_comment';
  protected $_pk     = 'id';
  protected $_rules  = [
    'id'       => [ 'comment_id',        'id',       'ID комментария' ],
    'parentId' => [ 'comment_parent_id', 'id',       'ID родителя', ['required' => false] ],
    'userId'   => [ 'comment_user_id',   'id',       'ID пользователя' ],
    'text'     => [ 'comment_text',      'text',     'Текст комментария' ],
    //'rating'   => [ 'comment_rating',    'rating',   'Рейтинг' ],
    'create'   => [ 'comment_create',    'datetime', 'Дата создания' ],

    'userEmail' => [ 'comment_user_email', 'email', 'E-mail' ],
    'userName'  => [ 'comment_user_name',  'text',  'Имя', ['maxlenght' => 50] ],
  ];

  public function getSingleRow( $id )
  {
    $sql = "SELECT
        SQL_CALC_FOUND_ROWS
        `t`.*,
        IF ( `u`.`user_name` IS NOT NULL, `user_name`, `t`.`comment_user_name` ) AS `user_name`,
        (
            (SELECT COUNT(*) FROM `sg_vote` WHERE `vote_type` = 'up'   AND `vote_comment_id` = `t`.`comment_id` )
          - (SELECT COUNT(*) FROM `sg_vote` WHERE `vote_type` = 'down' AND `vote_comment_id` = `t`.`comment_id` )
        ) AS `rating`
      FROM `{$this -> _table}` AS `t`
      LEFT JOIN `sg_user` AS `u` ON
        `u`.`user_id` = `t`.`comment_user_id`
      WHERE `t`.`comment_id` = ? ";

    $prep = $this -> getDB(  ) -> prepare( $sql );
    $prep -> execute( [$id] );

    return $prep -> fetch(  );
  }
  public function findAllComment(  )
  {
    $sql = "SELECT
        SQL_CALC_FOUND_ROWS
        `t`.*,
        IF ( `u`.`user_name` IS NOT NULL, `user_name`, `t`.`comment_user_name` ) AS `user_name`,
        (
            (SELECT COUNT(*) FROM `sg_vote` WHERE `vote_type` = 'up'   AND `vote_comment_id` = `t`.`comment_id` )
          - (SELECT COUNT(*) FROM `sg_vote` WHERE `vote_type` = 'down' AND `vote_comment_id` = `t`.`comment_id` )
        ) AS `rating`
      FROM `{$this -> _table}` AS `t`
      LEFT JOIN `sg_user` AS `u` ON
        `u`.`user_id` = `t`.`comment_user_id` ";

    return $this -> getDB(  ) -> query( $sql );
  }

  public function beforeDelete(  )
  {
    $check = new Comment;
    $data  = $check -> findByAttributes( ['id' => $this -> id ] ) -> fetch(  );

    if ( ! $data or $data['comment_user_id'] != \Components\Session::getSession( 'user_id' ) )
      throw new \Exception( 'Вы можете удалить только свой комментарий' );
  }

  public function beforeInsert()
  {
    $this -> create = true;

    if ( \Components\Session::getSession( 'user_id' ) )
      $this -> userId = \Components\Session::getSession( 'user_id' );

    if ( ! $this -> parentId )
    {
      unset( $this -> parentId );
      unset( $this -> _fields['parentId'] );
      //$this -> getDB() -> exec( "SET FOREIGN_KEY_CHECKS = 0" );
    }
  }

}
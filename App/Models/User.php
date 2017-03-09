<?php
namespace App\Models;

class User extends \Core\Model\ModelAbstract
{
  protected $_table        = 'sg_user';
  protected $_pk           = 'id';
  protected $_rules        = [
    'id'       => [ 'user_id',       'id',       'ID пользователя' ],
    'email'    => [ 'user_email',    'email',    'E-mail' ],
    'password' => [ 'user_password', 'password', 'Пароль' ],
    'name'     => [ 'user_name',     'text',     'Имя', ['maxlenght' => 50] ],
  ];

  public function beforeInsert()
  {
    $check = new User;
    $data  = $check -> findByAttributes( ['email' => $this -> email ] ) -> fetch(  );

    if ( $data )
      throw new \Exception( 'Этот e-mail уже зарегистрирован' );
  }
}

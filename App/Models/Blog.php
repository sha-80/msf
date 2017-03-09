<?php
namespace App\Models;

class Blog extends \Core\Model\ModelAbstract
{
  protected $_table  = 'blog';
  protected $_pk     = 'id';
  protected $_rules  = [
    'id'     => [ 'id',       'id',       'ID записи', ['maxlength' => 50] ],
    'title'  => [ 'title',    'text',     'Заголовок', ['maxlength' => 50] ],
    'text'   => [ 'text',     'textarea', 'Текст сообщения' ],
    'create' => [ 'datetime', 'datetime', 'Дата публикации' ],
  ];
}
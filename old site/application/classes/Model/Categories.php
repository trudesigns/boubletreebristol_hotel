<?php defined('SYSPATH') or die('No direct script access.');

class Model_Categories extends ORM
{
    protected $_table_name = 'categories';
    protected $_has_many = [
		'news'=> [
                                        'model' => 'News',
                                        'through'  => 'news_categories',
                                        'far_key'=>'news_id',
                                        'foreign_key'=>'categories_id'
                         ]
                               
            ];
  
 
}


<?php defined('SYSPATH') or die('No direct script access.');

class Model_News extends ORM
{
    protected $_db;
    protected $_table_name = 'news';
    protected $_has_many = [
		'categories'=> [
                                        'model' => 'Categories',
                                        'through'  => 'news_categories',
                                        'far_key'=>'categories_id',
                                        'foreign_key'=>'news_id'
                                ]
                               
                        ];
  
   
 
}


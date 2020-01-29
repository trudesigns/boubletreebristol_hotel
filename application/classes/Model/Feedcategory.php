<?php defined('SYSPATH') or die('No direct script access.');

class Model_Feedcategory extends ORM {
	
	protected $_table_name = 'feed_categories';
	
	protected $_belongs_to = array(
    	'feed' => array(
        			'model'       => 'feed',
        			'foreign_key' => 'feed_id',
    				),
	);
	
	protected $_has_many = array(
    	'articles' => array(
	        'model'   => 'feedarticle',
	        'foreign_key' => 'article_id',
	        'through' => 'feed_category_articles',
    	),
	);
	
}

?>
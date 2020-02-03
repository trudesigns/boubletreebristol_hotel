<?php defined('SYSPATH') or die('No direct script access.');

class Model_Feedarticle extends ORM {
	
	protected $_table_name = 'feed_articles';
	
	protected $_belongs_to = array(
                        'page' => array(
        			'model'       => 'page',
        			'foreign_key' => 'page_id',
                        ),
	);
	
	protected $_has_many = array(
		'categories' => array(
					'model' => 'feedcategory',
					'foreign_key' => 'article_id',
					'through' => 'feed_category_articles',
					),
	);
	
}

?>
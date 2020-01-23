<?php defined('SYSPATH') or die('No direct script access.');

class Model_Feedlookup extends ORM {
	
	protected $_table_name = 'feed_category_articles';
	
	protected $_belongs_to = array(
    	'feedcategory' => array(
        			'model'       => 'feedcategory',
        			'foreign_key' => 'category_id',
    				),
	);
	
	protected $_has_many = array(
		'articles' => array(),
	);
	
}

?>
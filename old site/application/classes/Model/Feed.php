<?php defined('SYSPATH') or die('No direct script access.');

class Model_Feed extends ORM {
	
//	protected $_table_name = 'feeds';
	
	protected $_has_many = array(
		'feedcategory' => array(),
	);
	
}

?>
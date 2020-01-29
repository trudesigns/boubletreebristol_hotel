<?php defined('SYSPATH') or die('No direct script access.');

class Model_Formfield extends ORM {
	
	protected $_table_name = 'form_fields';
	
	protected $_belongs_to = array(
		'form' => array(),
	);
	
}
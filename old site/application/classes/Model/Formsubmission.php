<?php defined('SYSPATH') or die('No direct script access.');

class Model_Formsubmission extends ORM {
	
	protected $_table_name = 'form_submissions';
	
	protected $_belongs_to = array(
		'form' => array(),
	);
	
	protected $_has_many = array(
		'submissionfield' => array(),
	);
	
}
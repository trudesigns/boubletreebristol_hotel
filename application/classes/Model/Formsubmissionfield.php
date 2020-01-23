<?php defined('SYSPATH') or die('No direct script access.');

class Model_Formsubmissionfield extends ORM {
	
	protected $_table_name = 'form_submission_fields';
	
	protected $_belongs_to = array(
		'formsubmission' => array(),
		'formfield' => array(),
	);
	
}
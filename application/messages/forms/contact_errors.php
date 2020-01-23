<?php defined('SYSPATH') OR die('No direct access allowed.'); 

return array 
( 
 
 	'name' => array('not_empty'=>'<span class="required_red">The <strong>Name</strong> field is required.</span>'),
	'email' => array('not_empty'=>'<span class="required_red">The <strong>Email</strong> field is required.</span>',
					'email'=> '<span class="required_red">The <strong>Email</strong> you provided is invalid.</span>'),
	'phone' => array('phone'=> '<span class="required_red">The <strong>Phone</strong> number you provided is invalid.</span>'),	
	'message' => array('not_empty'=> '<span class="required_red">The <strong>Message</strong> field is required.</span>'),
	'verify' => array('not_empty'=>'<span class="required_red">The <strong>Security Code</strong> field is required.</span>', 
					  'Model_Captcha::checkCaptcha'=>'<span class="required_red">The <strong>Security Code</strong> was typed incorrectly. Please try again</span>')
 
);
?>
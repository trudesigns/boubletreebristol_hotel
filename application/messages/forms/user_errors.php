<?php defined('SYSPATH') OR die('No direct access allowed.'); 

return array 
( 
 
 	'first' => array('not_empty'=>'<span class="required_red">The <strong>First Name</strong> field is required.</span>'),
                    'lastst' => array('not_empty'=>'<span class="required_red">The <strong>Last Name</strong> field is required.</span>'),
	'email' => array('not_empty'=>'<span class="required_red">The <strong>Email</strong> field is required.</span>',
                     'email'=> '<span class="required_red">The <strong>Email</strong> you provided is invalid.</span>'),
                     'password2'=>array('matches'=>'<span class="required_red">The <strong>Confirm Password</strong> field has to match <strong>Password</strong>.</span>'),
	'password' => array('not_empty'=> '<span class="required_red">The <strong>Password</strong> field is required.</span>')
	
 
);

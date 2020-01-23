<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Captcha extends Controller_Setup {         
	
	public $auth_required = false;
	public $auto_render = false; // don't output this through the template controller (as defined in setup)

	public function action_index(){
		$captcha = new Model_Captcha();
		//Request::instance()->headers['Content-Type'] = 'image/jpeg';
		$captcha->makeCaptcha();
	}



} // end controller


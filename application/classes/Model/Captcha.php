<?php defined('SYSPATH') or die('No direct script access.');

class Model_Captcha {
	
	var $font = './assets/fonts/monofont.ttf';

	/**
	 * create the captcha code
	 *
	 * $length	int	- the number of characteres to generate
	 *
	 */
	private function generateCode($length=6) {
		/* list all possible characters, similar looking characters and vowels have been removed */
		$possible = '23456789bcdfghjkmnpqrstvwxyz';
		$code = '';
		$i = 0;
		while ($i < $length) { 
			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		return $code;
	}

	/**
	 * create the captcha image resource
	 *
	 */
	private function generateImage($code,$width='120',$height='40') {
	
		/* font size will be 85% of the image height */
		$font_size = $height * 0.85;
		$image = @imagecreate($width, $height) or die('Cannot initialize new GD image stream');
		
		/* set the colours */
		$background_color = imagecolorallocate($image, 240, 255, 255);
		$text_color = imagecolorallocate($image, 0,82,155);  			// default blue was 0, 60, 120
		$noise_color = imagecolorallocate($image, 20,0,200);			// default blue was 20, 0, 200
		$line = imagecolorallocate($image, 100, 100, 100); 
		
		/* generate random dots in background */
		for( $i=0; $i<($width*$height)/3; $i++ ) {
			imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
		}
		
		/* generate random lines in background */
		for( $i=0; $i<($width*$height)/150; $i++ ) {
			imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $line);
		}
		
		/* create textbox and add text */
		$textbox = imagettfbbox($font_size, 0, $this->font, $code) or die('Error in imagettfbbox function');
		
		// Calculate text position in the image box
		$x = ($width - $textbox[4])/2;
		$y = ($height - $textbox[5])/2-4.5;
		imagettftext($image, $font_size, 0, $x, $y, $text_color, $this->font , $code) or die('Error in imagettftext function');

		return $image;
	}
	
	
	public function makeCaptcha(){
		$code = $this->generateCode();
		
		//set session with hashed value of code
		$prepCode = md5($code);		
		Kohana_Session::instance()->set('captcha', $prepCode);	
	
		//return image		
		return imagejpeg($this->generateImage($code),NULL,80 );
	}
	

	/**
	  *  compare posted value to cookie value
	  *  returns true or false
	  *
	  */
	 public static function checkCaptcha($post_value){
	 	return ( md5($post_value) != Kohana_Session::instance()->get('captcha') ) ? false : true;
	 }

}
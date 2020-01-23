<?php defined('SYSPATH') or die('No direct script access.');

class Model_Contentversion extends ORM {
	
	protected $_table_name = 'content_versions';
	
	protected $_has_many = array(
		'content' => array(),
	);
	
	/**
	 * return an array of Version IDs that are available based on current user instance
	 *
	 * note that content versions for any given block can only be tied to 1 selector type. 
	 * eg. All content versions must be URL based -OR- role based -OR- Session based
	 * and you can not create content that changes based on both the URL value and a user's role or session. 
	 *
	 */
	static function getAvailableVersions(){
		$all_versions = ORM::factory('contentversion')->find_all();
		$versions = array();
		foreach($all_versions as $version){

			switch($version->selector ){
				case 'url':
					//check current url. if its starts with this select key then this is an available version type
					if($_SERVER['REQUEST_URI'] != "" && $version->selector_key != "" && strpos($_SERVER['REQUEST_URI'],$version->selector_key) ){
						$versions[] = $version->id;
					}
				break;	
				
				case 'session':
					if($_SESSION['content_selector'] == $version->selector_key ){
						$versions[] = $version->id;
					}
				break;
				
				case 'role': // if user has the given role, this version type is available
					if(Auth::instance()->logged_in($version->selector_key )){
						$versions[] = $version->id;
					}
				break;					
			}
		}
		
		return $versions;
	}
}
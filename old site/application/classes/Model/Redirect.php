<?php defined('SYSPATH') or die('No direct script access.');

class Model_Redirect extends ORM {

	public function lookup($alias,$update_count=false){
		if(trim($alias == "")) { return false; }
		
		$redirect = ORM::factory('Redirect')->where('alias','=',$alias)->find();	
		if($redirect->loaded() )
		{
			if($update_count)
			{
				$redirect->hits = $redirect->hits +1;
				$redirect->last_hit = date("Y-m-d H:i:s");
				$redirect->save();
			}	
			
			return $redirect;		
		}
		else
		{
			return false;
		}
		
		
	}
	
}

<?php defined('SYSPATH') or die('No direct script access.');

class Model_Menus extends ORM {

	protected $_table_name = 'menus';
	
	/**
	 * Get the file path of where a given menu's JSON array is cached
	 */
	private static function menuPath($menu_id)
	{
		return DOCROOT."assets/menus/menubuilder_menu".$menu_id.".html";	
	}
	
		
	/**
	 * Save a menu's data as a JSON array in a flat file for cacheing 
	 */
	public static function publishMenu($menu_id)
	{
		if(!isset($menu_id) || !is_numeric($menu_id))
		{
			return false;
		}
	
		$pageObj = new Model_Page;
		$pages = $pageObj->getPages( array('menu_id'=>$menu_id));
		
		$filename = Model_Menus::menuPath($menu_id);
		return file_put_contents($filename,json_encode($pages));	
	}
	
	
	/**
	 * Output the menu to the browser as an unordered list (UL > LI)
	 * 
	 * $menu_id INT		Required
	 * $params	ARRAY	optional parameters to pass to the Model_Pages->drawMenu_UL() method.
	 * 					see drawMenu_UL() for accepted parameter.
	 * 					NOTE:	setting 'static_menu' => TRUE as a parameter will keep this function
	 * 							from setting the `thisID` parameter and thus keeping the drawMenu_UL() function
	 * 							from wasting time adding all the breadcrumb classes to the output
	 * 
	 */ 					
	public static function drawMenu($menu_id,$params=array())
	{
		if(!isset($menu_id) || !is_numeric($menu_id))
		{
			return false;
		}
		
		$menu = ORM::factory('Menus',$menu_id);
		$menu_uri = Model_Menus::menuPath($menu_id);
		$pages = @file_get_contents($menu_uri);
			
		$li_format = '<a href=\"$newSlug\" target=\"$item->target\" $item->link_attributes $drawClass>$item->label</a>';
		$pageObj = new Model_Page;
		
		$default_params = array('ul_html'=>$menu->ul_html,
								'li_format'=>$li_format
								);	
		
		if(!array_key_exists('static_menu',$params) || $params['static_menu'] === false)
		{	
			$current_page_uri_parts = explode("?",$_SERVER['REQUEST_URI']);
			$current_page_uri = trim($current_page_uri_parts[0],"/");
			$current_page = $pageObj->getPagefromURL($current_page_uri);	
			if($current_page)
			{
				$current_menu_page = ORM::factory('Menupage')
										->where('menu_id','=',$menu_id)
										->where('link_value','=',$current_page->id)
										->find();
				$default_params['thisID'] = $current_menu_page->id;
			}
		}
		else // this is a static menu, no need to send $thisID
		{
			unset($params['thisID']);
			unset($params['static_menu']);
		}
		
		return $pageObj->drawMenu_UL(json_decode($pages), array_merge($params,$default_params));
	}
	

	//alias function
	public static function loadMenu($menu_id,$params=array())
	{
		return Model_Menus::drawMenu($menu_id,$params);
	}
	
}
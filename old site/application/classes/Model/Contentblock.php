<?php defined('SYSPATH') or die('No direct script access.');

class Model_Contentblock extends ORM {
	
	protected $_table_name = 'content_blocks';
	
	protected $_has_many = array(
		'content' => array(),
	);
	
	/**
	 * return HTML code wrapping content for a given block 
	 *
	 * $params	ARRAY => 'template' (object) of the calling pages template
	 *					 'block' (object) of the containing block
	 *					 'content' (string) of the actual content
	 *
	 */
	static function wrapBlock($params){
		
		$block_objectkey = $params['block']->objectkey;
		
		if($params['block']->output_type == "content")
		{
			$html = "\n";
			$html.= "<div id=\"content_block_".$block_objectkey."\"";
		
			if( Auth::instance()->logged_in() ){ // if use is logged in
			
				//what roles can update this template's block of content
				$template_block_lookup = ORM::factory('Templatecontentblock',array('template_id'=>$params['template']->id,'content_block_id'=>$params['block']->id));
				$role = ORM::factory('Role',$template_block_lookup->required_edit_role);
				if(Auth::instance()->logged_in($role->name)){ // if this user can update this content, update div
					$html.= ' class="CMS_EDIT" data-link="'.$block_objectkey.'" ';
				}
			}
			$html.=">\n";
			$html.= $params['content']['content'];
			$html.= "\n</div>\n";
		}
		
		else{	
			$html = $params['content']['content'];
		}

		return $html;
	}
	
}
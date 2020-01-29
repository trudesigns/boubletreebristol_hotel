<?php defined('SYSPATH') or die('No direct script access.');

/**
 * IMPORTANT NOTE:
 * When creating custom controllers to be used by a CMS controlled page
 * the function should always return a view
 * 
 * The default controller that calls a custom action from this class will pass an instance of itself ($this) as a parameter to the given action
 *
 */

class Controller_Custom {	
	
	public function action_search($parent_this)
	{
		$view = new View('public/pages/search');
		if (isset($_GET['csrf']) && Security::check(base64_decode($_GET['csrf']))) {
			if(isset($_GET['q']) )
			{
				$contentObj = new Model_Content;
				$params["q"] = htmlspecialchars($_GET['q']);
				$params["exactMatch"] = (isset($_GET['match']) && $_GET['match'] == "phrase") ? true : false;
				$params["limit"] = (isset($_GET['limit']) && is_numeric($_GET['limit'])) ? $_GET['limit'] : 10;
				$params["offset"] = (isset($_GET['offset']) && is_numeric($_GET['offset'])) ? $_GET['offset'] : 0;

				// add common words related to specific site or client which too frequently in the content
				$params["additionalStopWords"] = array("");

				$view->search_results = $contentObj->search($params);
			}
			else
			{
				$view->search_results = array();	
			}
		} else {
			$view->search_results = array();
		}

		return $view;		
	}
	
	public function action_sitemapXML($parent_this)
	{
		$view = new View('public/pages/sitemap');	
		$view->xml = true;
		header("Content-type: text/xml");
		$this->auto_render = false;
		exit($view);
	}
        
	public function action_sitemap()
	{
		$view = new View('public/pages/sitemap');	
		$view->xml = false;
		return $view;
	}
	
	/****													****/
	
	/**** Example Contact Us Form w/ Captcha and Validation ****/
	
	/****													****/
	
	public function action_contact() {
		
                //echo "SHERERERE";exit;
            
		$view = new View('public/pages/contact');
		
		if ($_POST) {
                    
                     // check for hidden input field - honeytrap bot catcher
                    if (isset($_POST['favorite']) && $_POST['favorite'] != '') {
                        // Bad robot, just die
                        die();
                    }
                    
                    if (isset($_POST['csrf']) && Security::check($_POST['csrf'])) {
		
			$post = Validation::factory($_POST)
					// Trim all fields using a custom function (new since Kohana 3.2+)
					->rule(TRUE, 'Model_Tools::trimValue')
					->rule('name', 'not_empty')
					->rule('email', 'not_empty')
					->rule('email', 'email')
					->rule('phone', 'phone')
                                        //->rule('company','not_empty')
					->rule('message', 'not_empty')
					//->rule('verify', 'not_empty')
					//->rule('verify', 'Model_Captcha::checkCaptcha')	
					;
			
			//process post data (if applicable)
			if ($post->check()){
				
				
				//*******************************************************************************************************// 
				// FORM VALIDATION NOTES & HOW-TO
				//
				// Validated $_POST/$post variables are now READ-ONLY as of Kohana 3.2+
				// You must map ALL form field values INDIVIDUALLY to a new variable called "$posted" in these examples
				//
				// Ex: 
				// $posted['name'] = $post['name'];
				//
				//*******************************************************************************************************//
				
				// First, map all fields which require validation to $posted var
				// Tip: Don't bother mapping non-required fields and textarea fields which will be dealt with later
				
				$posted['name'] = $post['name'];
				$posted['email'] = $post['email'];
				
				
				// Next, handle non-required fields (if applicable)
				// If a field is not required and has partial or no validation, you must test the variable's existance
				//		then check if it's blank,
				//		then map the field using $posted to it's $post eqivalent, or set it to [Not Provided] so that users know it wasn't programatically lost/omitted
				//
				// Ex:
				// isset($_POST['company']) && $_POST['company'] != '' ? $posted['company'] = $_POST['company'] : $posted['company'] = '[Not Provided]';
				
				isset($post['company']) && $post['company'] != '' ? $posted['company'] = $post['company'] : $posted['company'] = '[Not Provided]';
				isset($post['phone']) && $post['phone'] != '' ? $posted['phone'] = $post['phone'] : $posted['phone'] = '[Not Provided]';
				
				
				// Next, handle any textarea input fields (if applicable)
				// Such as processing the input using nl2br()
				//
				// Ex:
				// $posted['message'] = nl2br($post['message']);
				
				$posted['message'] = nl2br($post['message']);
				
				
				// Next, set up e-mail details for sending
				// Tip: multiple addresses in any given $to field should be comma-separated 
				
				$to['to'] = Kohana::$config->load('siteconfig.contact.email');
				//$to['cc'] = "";
				//$to['bcc'] = "";
				
				//$from = $posted['email'];
				
				$subject = "Doubletreebristol.com: Contact Us notification";
				$message = "A user has filled out the Contact Us form. Their information is below.";
				
				// Next, match $posted key names as "Label => Key"
				
				$fields = array("Name:" => "name",
					"Company:" => "company",
					"Email:" => "email",
					"Phone: " => "phone",
					"Message:<br />" => "message"
					);
					$stor = [
						"Name"=>$posted['name'],
						"Phone"=>$posted['phone'],
						"Email"=>$posted['email'],
						"Message"=>$posted['message'],
						"Company"=>$posted['company']
					];
				
				// Finally, pass "$posted" array into the Send E-mail function
				$com = new Model_Communications;
				$com->type="Contact Us";
				$com->content =json_encode($stor);
				$com->timestamp = date("Y-m-d H:i:s");
				$com->save();
                                
                                
				$tools = new Model_Tools;
                                
				
				if($tools->sendEmail($to,false,$subject,$message,$posted,$fields)){
					
					// Sets a flag for the front-end view to hide the form and display the thank you message.
					$view->success = true;
					
					
					// If you need to send the user somewhere else, suggest using one of the following methods:
					
					// A) Get URL of a page based on an ID number
					// $goto = Model_Page::getPageURL(1);
					
					// B) You can simply set your own path
					// $goto = '/'
					
					// Uncomment both lines below to enable redirection
					//Request::initial()->redirect($goto);		
					//exit();
					
				}else{
					$view->success = false;
					exit("error sending e-mail. contact <a href=\"mailto:support@thepitagroup.com\">support@thepitagroup.com</a> for assistance");
				}
				
			} else {
				// $post->check() Validation failed
				
				// Collect post values so they can be returned to the form
				$view->post = $_POST;
				
				//collect the errors, run them through /application/messages/[file] for pretty output
				$view->errors = $post->errors('forms/contact_errors');	
			
			} // end if($post->check())
                    }   
		} // end if($_POST) 
		
		return $view;
		
	} // end action_formContactUs()
        
        public function action_rfp() {
		
                //echo "SHERERERE";exit;
            
		$view = new View('public/pages/rfp');
		
		if ($_POST) {
                    
                    
                         
                     
                    
                    // check for hidden input field - honeytrap bot catcher
                    if (isset($_POST['favorite']) && $_POST['favorite'] != '') {

                        // Bad robot, just die
                        die();
                    }
                    
                    if (isset($_POST['csrf']) && Security::check($_POST['csrf'])) {
		
			$post = Validation::factory($_POST)
					// Trim all fields using a custom function (new since Kohana 3.2+)
					->rule(TRUE, 'Model_Tools::trimValue')
					->rule('name', 'not_empty')
					->rule('email', 'not_empty')
					->rule('email', 'email')
					->rule('phone', 'phone')
                                        //->rule('company','not_empty')
					->rule('message', 'not_empty')
					//->rule('verify', 'not_empty')
					//->rule('verify', 'Model_Captcha::checkCaptcha')	
					;
			
			//process post data (if applicable)
			if ($post->check()){
				
				
				//*******************************************************************************************************// 
				// FORM VALIDATION NOTES & HOW-TO
				//
				// Validated $_POST/$post variables are now READ-ONLY as of Kohana 3.2+
				// You must map ALL form field values INDIVIDUALLY to a new variable called "$posted" in these examples
				//
				// Ex: 
				// $posted['name'] = $post['name'];
				//
				//*******************************************************************************************************//
				
				// First, map all fields which require validation to $posted var
				// Tip: Don't bother mapping non-required fields and textarea fields which will be dealt with later
				
				$posted['name'] = $post['name'];
				$posted['email'] = $post['email'];
				
				
				// Next, handle non-required fields (if applicable)
				// If a field is not required and has partial or no validation, you must test the variable's existance
				//		then check if it's blank,
				//		then map the field using $posted to it's $post eqivalent, or set it to [Not Provided] so that users know it wasn't programatically lost/omitted
				//
				// Ex:
				// isset($_POST['company']) && $_POST['company'] != '' ? $posted['company'] = $_POST['company'] : $posted['company'] = '[Not Provided]';
				
				isset($post['company']) && $post['company'] != '' ? $posted['company'] = $post['company'] : $posted['company'] = '[Not Provided]';
				isset($post['phone']) && $post['phone'] != '' ? $posted['phone'] = $post['phone'] : $posted['phone'] = '[Not Provided]';
				
				
				// Next, handle any textarea input fields (if applicable)
				// Such as processing the input using nl2br()
				//
				// Ex:
				// $posted['message'] = nl2br($post['message']);
				
				$posted['message'] = nl2br($post['message']);
				
				
				// Next, set up e-mail details for sending
				// Tip: multiple addresses in any given $to field should be comma-separated 
				
				$to['to'] = Kohana::$config->load('siteconfig.rfp.email');
				//$to['cc'] = "";
				//$to['bcc'] = "";
				
				//$from = $posted['email'];
				
				$subject = "Doubletreebristol.com: Request for Proposal notification";
				$message = "A user has filled out the Contact Us form. Their information is below.";
				
				// Next, match $posted key names as "Label => Key"
				
				$fields = array("Name:" => "name",
					"Company:" => "company",
					"Email:" => "email",
					"Phone: " => "phone",
					"Request:<br />" => "message"
					);
					$stor = [
						"Name"=>$posted['name'],
						"Phone"=>$posted['phone'],
						"Email"=>$posted['email'],
						"Request"=>$posted['message'],
						"Company"=>$posted['company']
					];
				
				// Finally, pass "$posted" array into the Send E-mail function
				$com = new Model_Communications;
				$com->type="RFP";
				$com->content =json_encode($stor);
				$com->timestamp = date("Y-m-d H:i:s");
				$com->save();
                                
                                
				$tools = new Model_Tools;
                                
				
				if($tools->sendEmail($to,false,$subject,$message,$posted,$fields)){
					
					// Sets a flag for the front-end view to hide the form and display the thank you message.
					$view->success = true;
					
					
					// If you need to send the user somewhere else, suggest using one of the following methods:
					
					// A) Get URL of a page based on an ID number
					// $goto = Model_Page::getPageURL(1);
					
					// B) You can simply set your own path
					// $goto = '/'
					
					// Uncomment both lines below to enable redirection
					//Request::initial()->redirect($goto);		
					//exit();
					
				}else{
					$view->success = false;
					exit("error sending e-mail. contact <a href=\"mailto:support@thepitagroup.com\">support@thepitagroup.com</a> for assistance");
				}
				
			} else {
				// $post->check() Validation failed
				
				// Collect post values so they can be returned to the form
				$view->post = $_POST;
				
				//collect the errors, run them through /application/messages/[file] for pretty output
				$view->errors = $post->errors('forms/contact_errors');	
			
			} // end if($post->check())
                    } 
	
		} // end if($_POST) 
		
		return $view;
		
	} // end action_formContactUs()
	
	 
	
}
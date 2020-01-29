<?php defined('SYSPATH') or die('No direct script access.');

class Model_Form extends ORM {
	
	protected $_has_many = array(
		'formfields' => array(),
		'formsubmissions' => array(),
	);
	
	// recursive function to clean and trim posted data array
	private static function cleandata($post,$keephtml=false)
	{
		foreach($post as $key => $val)
		{
			if(!is_array($val))
			{
				$return_array[$key] = ($keephtml) ? trim($val) : trim(htmlspecialchars($val));
			}
			else
			{
				$return_array[$key] = Model_Form::cleandata($val,$keephtml);	
			}
		}
		return $return_array;
	}

	public static function fieldname($field)
	{
		$clean = substr( strtolower( preg_replace("/\W|_/", "", $field->label) ),0, 16);
		return $clean ."_".$field->id;
	}
	
	public static function doPostBack($post,$form,$fields=false)
	{
		if(!is_array($post))
		{
			exit("Critical Error: no post data array");
		}
		
		// check if form and fields objects were included in the parameters
		if(!is_object($form) && !is_object($formfields))
		{
			// if not, but the $form var is a number, assume its the form's ID and get the data now
			if(is_numeric($form))
			{
				$form = ORM::factory('Form',$form_id);
				$fields = ORM::factory('Formfield')->where('form_id','=',$form_id)->order_by('field_order')->find_all();
			}
			else
			{
				exit("Critical Error: invalid form paramaters");
			}
		}
	
		//trim input data and strip HTML 
		$post = Model_Form::cleandata($post);
		
		// check for required fields and validate data if neccessary
		foreach($fields as $field)
		{
			$fieldname = Model_Form::fieldname($field);
			if(array_key_exists($fieldname,$post) && $post[$fieldname] != "")
			{
				if($field->validation != "")
				{
					// do validation here
				}
			}
			elseif($field->required == 1)
			{
				$errors[$fieldname] = array("message"=>"Missing Required Field: ".$field->label);
			}
		}
		
		// check for captcha
		if($form->captcha == 1)
		{
			if(!Model_Captcha::checkCaptcha($post['captcha']))
			{
				$errors['captcha'] = array("message"=>"Incorrect or missing Security Code");
			}
		}
		
		// if there were error, return to the form with the array of errors
		if(isset($errors) && count($errors > 0)){
			return $errors;
		}
		
		// no errror: save the data to the database, then send e-mail (if applicable) and send user confirmation
		
		// save submission data
		$submission = ORM::factory('Formsubmission',false);
		$submission->form_id = $form->id;
		$submission->datestamp = date("Y-m-d H:i:s");
		$submission->ip_address = $_SERVER['REMOTE_ADDR'];
		$submission->user_agent = $_SERVER['HTTP_USER_AGENT'];
		$submission->save();
		
		// save each field's data
		foreach($fields as $field)
		{
			$fieldname = Model_Form::fieldname($field);
			if(array_key_exists($fieldname,$post) && $post[$fieldname] != "")
			{
				$submit = ORM::factory('Formsubmissionfield',false);
				$submit->submission_id = $submission->id;
				$submit->field_id = $field->id;
				$submit->value = (is_array($post[$fieldname])) ? implode(",",$post[$fieldname]) : $post[$fieldname];
				$submit->save();
				
				$emailFields[$field->label.": "] = $fieldname; // used to send more readable e-mail
			}		
		}
		
		// send e-mail
		if($form->email_to != "")
		{
			$to = $form->email_to;
			$from = "no-reply@".$_SERVER['HTTP_HOST'];
			$subject = "Form submission: ". $form->name;
			$message = "";
			$tools = new Model_Tools;
			$tools->sendEmail($to,$from,$subject,$message,$post,$emailFields);
		}
		
		// confirmation to user
		if($form->success_action == "message")
		{
			return $form->success_value;
		}
		elseif($form->success_action == "forward")
		{
			header("Location: ".$form->success_value);
			exit();
		}
		
	}

}
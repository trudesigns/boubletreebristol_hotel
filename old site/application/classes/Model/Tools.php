<?php defined('SYSPATH') or die('No direct script access.');

class Model_Tools {
    private $mailgun;

	/**
	 * SEND E-MAIL
	 *
	 * $to		string OR array.  Array formated as "to" => "list,of,addresses; "cc" => "you,get,the,idea" //OUTDATED! MUST USE $to['to'] = ["something@something.com", somethingelse@something.com"]
	 * $from	string OR array.  Array ("from"=>"micah@thepitagroup.com","fromName"=>"Micah Murray")
	 * 							  DEFAULT (regardless of what string you set) is thisDomain@thisDomain.com
	 * $subject	string
	 * $message	string	OPTIONAL	text message to display before form fields
	 * $fields	array	OPTIONAL	friendly/custom labels for each post field "field label" => post_key. 
	 *								(leaving this blank but sending $_POST data will email ALL posted fields
	 * $post	array	original $_POST data
	 *
	 */
	public function sendEmail($to,$from=false,$subject,$message='',$post=false,$fields=false){
            
            
            /* 
             * Prepare details of the email request so that it can be logged in /application/logs/ folder as a fallback in case something in this function fails.
             */
            $mail_details = '';
            $mail_data ="";
            if($post !== false){
                $mail_data = implode(', ', array_map(function ($v, $k) { return sprintf("%s='%s'", $k, $v); }, $post, array_keys($post)));
            }
//          echo"TO<pre>"; 
//print_r($to);exit;
                if(isset($to['to']) && is_array($to['to'])){
                    foreach($to['to'] as $t) {

                        $mail_details .= ' Email request TO: '.$t.
                                ' SUBJECT: '.$subject.
                                ' MESSAGE: '.$message.
                                ' DATA: '.$mail_data;
                    }
                } else {
                    $mail_details .= 'Email request TO: '.$to.
                                ' SUBJECT: '.$subject.
                                ' MESSAGE: '.$message.
                                ' DATA: '.$mail_data;
                }
            
                /* add email request details for each item in $to array as a single line item to log file with INFO as level */
            
                Log::instance()->add(Log::INFO, $mail_details);
            //echo "HERE";exit;
                
                /* 
                 * then, proceed as normal with sending the message
                 */
                
                
                // if $from is a string, ignore user setting and force no-reply address
                $host = $_SERVER['HTTP_HOST'];
                $host_parts = explode("www.",$host);
                $domain = $host;
                if(count($host_parts)>1){
                    $domain = $host_parts[1];
                }
                
                
                $mail_newline = "\n";
                $html_linebreak = "<br />\n";
                
                $plain_text = $message;
                $htmlEmail = false;
                if(isset($post) && is_array($post)){
                    if( $message != "")
                    {
                        $message .= $html_linebreak;
                        $message .= "<hr>".$html_linebreak;
                    
                        $plain_text.= $mail_newline;
                        $plain_text.= "------------------------------------------------------------ ".$mail_newline;
                    }
                    
                    if(isset($fields) && is_array($fields)){
                        foreach($fields as $label => $key){
                            $value = (is_array($post[$key])) ? implode(",",$post[$key]) : $post[$key];
                            $message .= "<strong>".stripslashes($label)."</strong> ".stripslashes($value) .$html_linebreak;
                            $plain_text.= stripslashes($label)." ".stripslashes($value) .$mail_newline;
                        }
                    }else{
                        foreach($post as $field_name => $value){
                            $message .= "<strong>".stripslashes($field_name)."</strong> ".stripslashes($value) .$html_linebreak;
                            $plain_text.= stripslashes($field_name)." ".stripslashes($value) .$mail_newline;
                        }
                    }
                                $htmlEmail = true;
                }
		
                if(!is_object($this->mailgun)){
                    $this->mailgun = (object) Kohana::$config->load('siteconfig.mailgun');
                }
                //var_dump($this->mailgun);
                if($this->mailgun->type === "api"){
                    
                    if($from === false){
                       $from = $domain." website <no-reply@".$domain.">";
                    } 
                    
                    if(is_array($to)){
                        foreach($to as $t){
                            $this->fire([
                                "to"=>$t,
                                "from"=>$from,
                                "subject"=>$subject,
                                "text"=>$plain_text,
                                "html"=>$message
                            ]);
                        }
                        return true;
                    } else {
                        $this->fire([
                            "to"=>$to,
                            "from"=>$from,
                            "subject"=>$subject,
                            "text"=>$plain_text,
                            "html"=>$message
                        ]);
                        return true;
                    }
                     return false;
                    
                    
                } else {
                   
                    require_once Kohana::find_file( 'vendor/phpmailer', 'class.phpmailer' );
                    $mail = new PHPMailer;	
                    //$mail->SMTPDebug =true;
    //var_dump($to);exit;
                    if(is_array($to)){
                                                    foreach($to['to'] as $t)$mail->addAddress($t);

                            if(isset($to['cc']) && $to['cc'] != "")
                            {
                                     foreach($to['cc'] as $t)$mail->addCC($t);
                            }
                            if(isset($to['bcc']) && $to['bcc'] != "")
                            {
                                     foreach($to['bcc'] as $t)$mail->addBCC($t);
                            }
                    }
                    else // $to is just a string
                    {
                            $mail->addAddress($to);
                    }

                    

                    $mail->From = $from;
                    if($from === false){
                        $mail->From = "no-reply@".$domain;
                    }
                    $mail->FromName = $domain." website";

                   // var_dump($htmlEmail);exit;

                    $mail->isHTML($htmlEmail);
    //echo "BODY:";var_dump($message);
                    $mail->Subject = $subject;
                    $mail->Body    = $message;
                    if($htmlEmail){
                        $mail->AltBody = $plain_text;
                    }
                    $smtp = Kohana::$config->load('smtp.default');
                    //var_dump($smtp);exit;
                    if($smtp['active'])
                    {
                            $mail->IsSMTP();
                            $mail->SMTPAuth = true; 
                            $mail->Host = $smtp['host'];
                            $mail->Port = $smtp['port'];
                            $mail->Username = $smtp['username'];
                            $mail->Password = $smtp['password'];   
                    }
                    else
                    {			
                            // don't use SMTP. Just send the mail from this server.
                    }
                    $sent = $mail->Send();
                    //var_dump($sent);exit;
                    return $sent;
                }
	}
        
	
	
	public static function trimValue($val)
	{
		if(!is_array($val))
		{
			return trim($val);
		}
		else
		{
			foreach($val as $key => $value)
			{
				$return_array[$key] = Model_Tools::trimValue($value);	
			}
			return $return_array;
		}	
	}
        
        
         /**
        * This funstion will connect to MailGun and send an email 
        * @param array $data 
        */
       public function fire($data)
       {
            $this->mailgun = (object) Kohana::$config->load('siteconfig.mailgun');
          
          //print_r($data);exit;
           return $this->createCurl($this->mailgun->api_domain."/messages",  http_build_query($data));
           //echo "EMAIL";
           //var_dump($email);
       }


       
       private function createCurl($url,$postdata = null)
       {
          //var_dump($url);
           //var_dump($postdata);
           $ch = curl_init();
           curl_setopt($ch, CURLOPT_URL,$this->mailgun->api_baseurl.$url);
           curl_setopt($ch, CURLOPT_USERPWD, "api:".$this->mailgun->api_key);
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
           curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
           curl_setopt($ch, CURLOPT_TIMEOUT, 5);
           if(!is_null($postdata)){
               //echo "POST DATA";
               curl_setopt($ch, CURLOPT_POST, 1);
               curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
           }
          // var_dump($ch);
           $data = curl_exec($ch);
          // var_dump($data);
           $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
         // var_dump($httpcode);
           curl_close($ch);
           return ($httpcode>=200 && $httpcode<300) ? $data : false;
       }
        
	
}
<?php defined('SYSPATH') or die('No direct script access.');

return array(

	'default' => array(
		/**
		 * SMTP connection information to send transactional e-mails
		 */
		'active'=>true,
		//'host'=>'smtp.mandrillapp.com',
//                'username'=>'support@thepitagroup.com',
//		'password'=>'m_FNE9Z65OjpftlLOxVmzQ', 
                'host' => 'smtp.mailgun.org',
                'username' => "postmaster@mg.thepitagroup.com",
                'password' => "6M<K{rO8=+Mx",
		'port'=>587,
	),

);

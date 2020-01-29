<?php defined('SYSPATH') or die('No direct script access.');

return array(

	'sandbox' => array(
		/**
		 * Accept Payments via Stripe in TEST MODE
		 */
		
		"secret_key"      => "sk_test_p3GNj3NS75LEk9hJMBWoDxRe",
  		"publishable_key" => "pk_test_gyBcERP6hXEomXHWmKkxE5MD"
	),
	
	'live' => array(
		/**
		 * LIVE KEYS for accepting payments from Stripe
		 */
		"secret_key"	=> "",
		"publishable_key" => ""
	),

);

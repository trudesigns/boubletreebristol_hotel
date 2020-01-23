<?php
/**
 * Stripe process
 * 
 * 1) user fills out form
 * 2) hitting submit stays client side, taking the appropriate information and sending it via XHR to stripe.com for pre-processing
 * 3) stripe.com returns a token which is entered into a hidden form field via javascript
 * 4) the form is submitted via POST.  
 *    NOTE: the creditcard number, expiration date and CVV code fields DO NOT have names so that
 *          they are NOT INCLUDED in the $_POST array and therefore NEVER touch our server
 * 5) PHP receives the POST data and Attempts to Charge Card
 * 6) If charge is successfull:
 * 		the posted data (that is not card specific) is saved to the database and a confirmation e-mail is sent
 *      the user is forwarded to the confirmation/"thank you" page
 *    If transaction fails, error message is shown and page reloads to be filled out again
 */
	
	$confirmation_page = 'donate/thank-you'; // where to forward the user upon success

	// initiate the Stripe library
	require_once Kohana::find_file( 'vendor/stripe', 'stripe_setup' );  
	
	// grab the authentication strings from the config file 
	$stripe = Kohana::$config->load('stripe.sandbox');  // toggle this to "stripe.live" to go live. "stripe.sandbox" to test.
	
	Stripe::setApiKey($stripe['secret_key']);

	if($_POST)
	{
		foreach($_POST as $key => $val)
		{
			$post[$key] = htmlspecialchars($val);
		}
		
		$err = array();
		if($post['stripeToken'] == "")
		{
			$err[] = "Error pre-processing your card.  Make sure you have JavaScript turned on in your browser";
		}
		if($post['name'] == "")
		{
			$err[] = "Please enter your full name";
		}
		if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL) || !preg_match('/@.+\./', $post['email']) )
		{
			$err[] = "Missing or invalid e-mail address";	
		}
		if($post['amount'] == "")
		{
			$err[] = "Please enter an amount for your payment.";
		}

		// process post
		if(count($err) === 0)
		{
			
			try {
			
				// make the charge
				$charge = Stripe_Charge::create(array(
					'card' => $post['stripeToken'], // note, alternatively you could first create a strip customer with the token, then create this charge to the customer instead of the card
					'amount'   => $post['amount'] * 100, // amount is sent in cents.  so $5 = 500
					'currency' => 'usd',
					
					//optional parameters
					'description' => 'Donation to '. $post['product'].' fund',
					
					// note, the metadata is pretty worthless in manage.stripe.com (you can't even currently search for it) but
					// its worth storing so that the administrative user can parse through the payments easier when matching them 
					// up to the data store locally
					'metadata' => array('email'=> $post['email'],
										'fund' => $post['product']
									   ),
					'statement_description' => 'Donation'
				));			
				
				// save charge to database
				$checkout = ORM::factory('Checkout',false);
				$checkout->amount = $post['amount'];
				$checkout->card_description = $post['card_type'] ."***".$post['card_last4'];
				$checkout->timestamp = date("Y-m-d H:i:s");
				$checkout->vendor_transaction_id = $charge->id;
				$checkout->ip_address = $_SERVER['REMOTE_ADDR'];
				$checkout->name = $post['name'];
				$checkout->email = $post['email'];
				$checkout->product = $post['product'];
				$checkout->save();
					
				// send confirmation email
				$tools = new Model_Tools;
				$subject = "Thank you for your donation!";
				$message = "Thank you for your recent donation. <br><br>\n\n";
				$message.= "Name: ".$post['name'];
				$message.= "Amount: $". $post['amount'] ."<br />\n";
				$message.= "Fund: ". $post['product'] ."<br />\n";
				$message.= "Card: ". $checkout->card_description ."<br />\n";
				$message.= "Transaction ID: ". Model_Checkout::generateTransactionID($checkout->timestamp,$checkout->id);
				$message.= "<br><br>\n\n";
				$message.= "Please contact us with any questions or concerns.  Thanks.";	
				$tools->sendEmail(array("to"=>$post['email'],"bcc"=>"support@thepitagroup.com"),'no-reply',$subject,$message);
				
				// forward to "thank you" confirmation page
				Request::initial()->redirect(PATH_BASE . $confirmation_page);
				exit();
				
			} catch (Stripe_CardError $e) {
				$body = $e->getJsonBody();
				// print_r($body);	
				$err[] = $body['error']['message'];
			}	
		}
	}
?>
<h1>Checkout Page</h1>

<noscript><h1>For your security, this form requires that JavaScript be turned on in your browswer.</h1></noscript>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
Stripe.setPublishableKey('<?php echo $stripe['publishable_key']; ?>');

//this is the callback function from "createToken()"
var stripeResponseHandler = function(status, response) {
  var form = $('#payment-form');

  if (response.error) {
    // Show the errors on the form
    form.find('.payment-errors').text(response.error.message);
    form.find('button').prop('disabled', false);
  } else {
    // token contains id, last4, and card type
    var token = response.id;
    // Insert the token into the form so it gets submitted to the server
    form.append($('<input type="hidden" name="stripeToken">').val(token));
    // and submit
    form.get(0).submit();
  }
};

function checkCardValue(cardnumber)
{
	if( $.trim(cardnumber) === "")
  	{
  		$("#card_last4").val('');
  		$("#card_type").val('');
  		$("#card_type_display").val('');
  		return false;
  	}
  	if(!Stripe.card.validateCardNumber(cardnumber))
  	{
  		$("#card_type_display").html("This card does not appear valid").fadeIn("fast");
  		$("#card_last4").val("");
  		$("#card_type").val("");
  		return false;
  	}
  	else
  	{
  		var cardtype = Stripe.card.cardType(cardnumber);
  		if(cardtype !== "Unknown")
  		{
  			$("#card_type").val(cardtype);
  			$("#card_last4").val( cardnumber.substr(cardnumber.length - 4));
  			$("#card_type_display").html( cardtype ).fadeIn("fast");
  			return true;
  		}
  		else
  		{
	  		$("#card_last4").val("");
	  		$("#card_type").val("");
	  		$("#card_type_display").val("Card type unknown or not accepted");
	  		return false;
  		}
  	}
        
}

$(document).ready(function(){
  
	$("#card_number").blur(function(){
		checkCardValue($(this).val());
	})
	.on('keyup', function(){
		if( $(this).val().length < 13 )
		{
			$("#card_last4").val('');
			$("#card_type").val('');
			$("#card_type_display").val('');
		}	
	});
	
	$("#exp_year").blur(function(){
		var year = parseInt($(this).val());
		if($.trim(year) !== "" && year < 100)
		{
			year = 2000 + year;
			$(this).val( year);
		}
		if( isNaN(year) || year < <?= date('Y'); ?> || year > 2099)
		{
			alert('Invalid expiration year'); // show this a better way
			return false;
		}
	});
	
	$("#cvc").blur(function(){
		if(!Stripe.card.validateCVC($(this).val()))
		{
			alert('Invalid CVC code'); // show this a better way then a dumb alert
			return false;
		}
	});
	
	$('#payment-form').submit(function(event) {
		var form = $(this);
		
		// Disable the submit button to prevent repeated clicks
		form.find('button').prop('disabled', true);
		
		var form = $(this);
		Stripe.card.createToken(form, stripeResponseHandler);
		
		// Prevent the form from submitting with the default action
		return false;
	});

});

</script>

<?php
if(isset($err) && count($err) > 0)
{
	echo "<ul>\n";
	foreach($err as $err_msg)
	{
		echo " <li>".$err_msg."</li>\n";
	}
	echo "</ul>\n";
} 
?>

<form action="" method="POST" id="payment-form">
  <span class="payment-errors"></span>

  <div class="form-row">
    <label>php echo
      <span>Full Name</span>
      <input name="name" type="text" size="20" value="<?=(isset($post['name']))? $post['name'] : '' ?>" data-stripe="name"/>
    </label>
  </div>
  
  <div class="form-row">
    <label>
      <span>E-Mail</span>
      <input name="email" type="email" size="20" value="<?= (isset($post['email']))? $post['email'] : '' ?>" data-stripe="email"/>
    </label>
  </div>  
  
  <div class="form-row">
    <label>
      <span>Fund</span>
      <select name="product">
      	<option value="General Fund">General Fund</option>
      	<option value="Some Other Fund">Some Other Fund</option>
      </select>
    </label>
  </div>  

  <div class="form-row">
    <label>
      <span>Donation Amount</span>
      <input name="amount" type="number" min="1" max="10000" step=".01" size="8" data-stripe="amount"/>
    </label>
  </div>  

  <div class="form-row">
    <label>
      <span>Card Number</span>
      <input type="text" size="20" id="card_number" data-stripe="number"/>
      <span id="card_type_display" style="display:none"></span>
      <input type="hidden" id="card_type" name="card_type">
      <input type="hidden" id="card_last4" name="card_last4">
    </label>
  </div>

  <div class="form-row">
    <label>
      <span>Expiration (MM/YYYY)</span>
      <input type="text" size="2" data-stripe="exp-month"/>
    </label>
    <span> / </span>
    <input type="text" id="exp_year" size="4" data-stripe="exp-year"/>
    <span id="expiration_error" style="display:none;">Month/Year must be in the future</span>
  </div>

	<div class="form-row">
	<label>
	  <span>CVC</span>
	  <input type="text" id="cvc" size="4" data-stripe="cvc"/>
	</label>
	</div>

	<div class="form-row">
	<label>
	  <span>Billing Zipcode</span>
	  <input type="text" size="4" data-stripe="address_zip"/>
	</label>
	</div>


  <button type="submit">Submit Payment</button>
</form>
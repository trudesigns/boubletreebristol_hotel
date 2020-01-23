<div class="container">
<h2>User Registration</h2>

<?php if (!empty($errors)){ ?>
<div class="form_error_message">
 <ul>
 <?php foreach($errors as $err){ 
		if(is_array($err)){
			foreach($err as $field => $message){
				echo "<li>".$message."</li>\n";
			}
		}else{
			echo "<li>".$err."</li>\n";
		}
	} 
?>
 </ul>
</div>
<?php } ?>

<form id="register" class="" action="<?=PATH_BASE."user/register"; echo (isset($_GET['goto'])) ? '/?goto='.$_GET['goto'] : '/'; ?>" method="post">
<p>All fields are required</p>

<div class="form_row"> 
 <div class="form_label"><label for="first">First Name</label></div>
 <div class="form_field"><input type="text" id="first" name="first" value="<?=(isset($post['first'])) ? $post['first'] : ''; ?>" /></div>
</div>

<div class="form_row"> 
 <div class="form_label"><label for="last">Last Name</label></div>
 <div class="form_field"><input type="text" id="last" name="last" value="<?=(isset($post['last'])) ? $post['last'] : ''; ?>" /></div>
</div>

<div class="form_row"> 
 <div class="form_label"><label for="email">E-Mail Address</label></div>
 <div class="form_field"><input type="text" id="email" name="email" value="<?=(isset($post['email'])) ? $post['email'] : ''; ?>" /></div>
</div>



<div class="form_row"> 
 <div class="form_label"><label for="password">Password</label></div>
 <div class="form_field"><input type="password" id="password" name="password" value="<?=(isset($post['password'])) ? $post['password'] : ''; ?>" /></div>
</div>

<div class="form_row"> 
 <div class="form_label"><label for="password_confirm">Confirm Password</label></div>
 <div class="form_field"><input type="password" id="password_confirm" name="password_confirm" value="<?=(isset($post['password_confirm'])) ? $post['password_confirm'] : ''; ?>" /></div>
</div>
<input type="text" name="favorite" id="favorite" style="display:none;">
<div class="form_row"> 
 <div class="form_label">&nbsp;</div>
 <div class="form_field"><button type="submit" class="yb-button first">Register</button> &nbsp; &nbsp; <span id="validating_message"></span></div>
</div>

</form>

<p class="form_error_message" style="margin-top: 20px"><strong>Note:</strong> Registering for access creates a user record but does not automatically grant access to the CMS.  
Once registered, administrator permission roles must be assigned manually by an authorized user.</p>

</div>
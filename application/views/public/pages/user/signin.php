<div id="login">
    
    <h2>Sign In</h2>
    
    <?php if (!empty($errors)){ ?>
    <p class="form_error_message"><?= $errors; /* Invalid email/password combination. Please try again. */ ?></p>
    <?php } else {
		echo '<br>';	
	}?>
    
    <form action="<?= PATH_BASE."user/signin"; echo (isset($_GET['goto'])) ? '/?goto='.$_GET['goto'] : '/'; ?>" method="post">
    
    <div class="form_row"> 
        <div class="form_label"><label for="email">Username</label></div>
        <div class="form_field"><input type="text" id="email" name="email" value="<?=(isset($post['email'])) ? $post['email'] : ''; ?>" /></div>
    </div>
    
    <div class="form_row">
    	<div class="form_label"><label for="password">Password</label></div>
        <div class="form_field"><input type="password" id="password" name="password" value="<?=(isset($post['password'])) ? $post['password'] : ''; ?>" /></div>
    </div>
    
    <div class="form_row">
        <div class="form_field">
            <input type="checkbox" id="remember" name="remember" class="align-left"> &nbsp;
            <label for="remember" id="remember-label">Remember me</label>
            <button type="submit" name="submit" value="Sign In" class="yb-button align-right">Log In</button>
        </div>
    </div>
    	
    <br><br>
    
    <div class="form_row">
    	 <div class="form_label"><a href="/" class="align-left">&larr; Homepage</a></div>
   		 <div class="form_field">
                     <a href="/user/register<?= isset($_GET['goto']) ? '/?goto='.$_GET['goto'] : '' ?>">Register</a>
                    <a href="#" data-target-id="resetPassword" id="reset_password" class="align-right">Forgot Password?</a>
                 </div>
    </div>
    
    </form>
   
    
    <div id="resetPassword" style="display: none">
        <h2>Reset Password</h2>
        <span id="reset-msg"></span>
             <div id="resetPasswordForm">
             <p>To have your password reset, enter the e-mail address associated with your account and click "Reset Password." A new password will be generated and e-mailed to you.</p>
                 
                 <div class="form_row">
                        <div class="form_label"><label for="resetEmail">Email Address</label></div>
                        <div class="form_field">
                        	<input type="text" id="resetEmail" name="resetEmail" value="" />
                        	<button type="submit" name="submit" id="resetPasswordSubmit" value="Reset Password" class="yb-button">Reset Password</button>
                        </div>
                  </div>
                  
                    
             
        </div>
    </div>
</div><!-- end "login" -->
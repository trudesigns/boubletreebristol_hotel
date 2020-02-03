<div id="user-profile">
<h2>My Account</h2>
<br>
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

<?php if(!empty($success)): ?>
	<p class="form_success_message"><?= $success; ?></p>
<?php endif; ?>

        
        
<form id="profile" class="" action="<?= PATH_BASE."user/profile"; echo (isset($_GET['goto'])) ? '/?goto='.$_GET['goto'] : '/'; ?>" method="post">
<input type="hidden" id="userid" name="userid" value="<?= $user->id ?>" />
<input type="hidden" id="current_email" name="current_email" value="<?= $user->email ?>" />
<?php 
$ref = "/admin";
if(isset($_SERVER['HTTP_REFERER']) && Cookie::get("ref") !== ""){
    $ref = $_SERVER['HTTP_REFERER']; 
    Cookie::set("ref", $ref);
}
?>
<input type="hidden" id="referer" name="referer" value="<?= $ref; ?>" />
<div class="form_row"> 
 <div class="form_label"><label for="first">First Name</label></div>
 <div class="form_field"><input type="text" id="first" name="first" value="<?= (isset($post['first'])) ? $post['first'] : $user->first; ?>" /></div>
</div>

<div class="form_row"> 
 <div class="form_label"><label for="last">Last Name</label></div>
 <div class="form_field"><input type="text" id="last" name="last" value="<?= (isset($post['last'])) ? $post['last'] : $user->last; ?>" /></div>
</div>

<div class="form_row"> 
 <div class="form_label"><label for="email">E-Mail Address</label></div>
 <div class="form_field"><input type="text" id="email" name="email" value="<?= (isset($post['email'])) ? $post['email'] : $user->email; ?>" /></div>
</div>
<?php /*
<div class="form_row"> 
 <div class="form_label"><label for="username">Username</label></div>
 <div class="form_field"><input type="text" id="username" name="username" value="<?= (isset($post['username'])) ? $post['username'] : $user->username; ?>" /></div>
</div>


<div class="form_row"> 
 <div class="form_label"><label for="city">City</label></div>
 <div class="form_field"><input type="text" id="city" name="city" value="<?= (isset($post['city'])) ? $post['city'] : $user->city; ?>" /></div>
</div>

*/?>
<br>
<div class="form_row"> 
 <div class="form_label">&nbsp;</div>
 <div class="form_field"><strong>Optional</strong> <em>Leave blank to keep existing password</em></div>
</div>

<div class="form_row"> 
 <div class="form_label"><label for="password">Password</label></div>
 <div class="form_field"><input type="password" id="password" name="password" value="<?= (isset($post['password'])) ? $post['password'] : '' ?>" /></div>
</div>

<div class="form_row"> 
 <div class="form_label"><label for="password_confirm">Confirm Password</label></div>
 <div class="form_field"><input type="password" id="password_confirm" name="password_confirm" value="<?= (isset($post['password_confirm'])) ? $post['password_confirm'] : '' ?>" /></div>
</div>
<br>
<div class="form_row"> 
 <div class="form_label"><a href="<?= Cookie::get("ref"); ?>" class="yb-button first">&larr; Go back</a></div>
 <div class="form_field"><button type="submit" value="Update User Profile" class="yb-button first">Update User Profile</button></div>
</div>


</form>
</div>

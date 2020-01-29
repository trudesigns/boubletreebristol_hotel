<input type="hidden" name="serialze_fields" value="featured_user,favorite_color">

<div class="form_row">
	<div class="form_label">User</div>
    <div class="form_field">
    
    	<select name="featured_user">
         <option value="">Select a User...</option>
        <?php	$users = ORM::factory('User')->find_all();
		 	foreach($users as $user)
			{
				$name = $user->first.' '.$user->last;
		?>
         <option value="<?=$name ?>" <?=( isset($content_array['featured_user']) && $content_array['featured_user'] == $name) ? "SELECTED" : "" ?> ><?=$name ?></option>
        <?php	} ?>
        </select>
    </div>
</div>

<div class="form_row">
	<div class="form_label">Favorite Color</div>
    <div class="form_field"><input name="favorite_color" value="<?=( isset($content_array['favorite_color'])) ? $content_array['favorite_color'] : '' ?>" /></div>
</div>


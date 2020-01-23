<?php 
	// system roles required for CMS to function properly.  Should not be changed.
	$hidden_roles = explode(",","login,admin,developer,content,content-limited,users,pages,publish,designer");
	
?>	
<div id="app-wrapper">
<h1>User Roles</h1>
<br>
<?php 
function value($field,$crud_selected,$htmlentities=false){
	if($crud_selected && isset($crud_selected->$field))
	{ 
	return ($htmlentities) ? htmlentities($crud_selected->$field) : $crud_selected->$field;
	}
	else
	{
		return '';
	}
} 
?>
<a href="/admin/roles/" class="yb-button"><span class="ui-icon ui-icon-plusthick"></span>Add New&nbsp;</a>
<select class="yb-select" onchange="window.location = '/admin/roles/'+$(this).val()">
	<option value="">EDIT A ROLE ></option>
<?php	foreach($crud_all as $crud_row){
	  $selected = (is_object($crud_selected) && $crud_row->id == $crud_selected->id) ? " SELECTED" : "";
	  if( in_array($crud_row->name,$hidden_roles) && !Auth::instance()->logged_in('developer') )
	  {
		  continue;
	  }
	  
	  echo "  <option value=\"".$crud_row->id."\"".$selected.">".$crud_row->name."</option>\n"; 
	   
   }
?>
</select>
<br><br>
<h2><?=(is_object($crud_selected)) ? "Edit" : "Add New" ?> User Role</h2>
<br>
<?php if(isset($errors)){ ?>
<ul class="form_errors">
<?php	foreach($errors as $field => $error){ 
?>
  <li><?= $error; ?></li>    
<?php  }  ?>
</ul>
<?php }  ?>

<form action="" method="post">

    <div class="form_row">
        <div class="form_label">Name</div>
        <div class="form_field"><input name="name" value="<?=value('name',$crud_selected) ?>" /></div>
    </div>
    
    <div class="form_row">
        <div class="form_label">Description</div>
        <div class="form_field"><input name="description" value="<?=value('description',$crud_selected) ?>" /></div>
    </div>
    
    <div class="form_row">
        <div class="form_label">Role Type</div>
        <div class="form_field"><input type="checkbox" name="role_type" id="role_type" value="access" <?=(value('role_type',$crud_selected) == "access") ? " CHECKED" : "" ?>>
            <label for="role_type">Access Role *</label>
        </div>
    </div>
    
    <div class="form_row">
        <div class="form_label">&nbsp;</div>
        <div class="form_field">
            <button type="submit" name="submit" class="yb-button" value="Submit" >Save</button>
            <span id="delete_message" style="display:none; color:#900">This item will be deleted upon submission</span>
        </div>
    </div>
    
    <div class="form_row" style="display: <?=(is_object($crud_selected)) ? " " : "none" ?>">
    	<div class="form_label">Delete</div>
        <div class="form_field"><input type="checkbox" name="delete" id="delete" value="delete"> <label for="delete">Delete This Item</label>
    </div>

</form>

<br><br><br>*Access Roles are roles like "Login" or "Paying Member" that grant access to areas of the site.  Non-Access roles grant permisions to do certain actions, like "users" lets someone manage other users.  Access Roles appear in the page manager when making pages require a log in.
</div><!-- end "app-wrapper" -->

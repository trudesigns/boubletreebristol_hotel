<div id="app-wrapper">
<h1>Menus Setup</h1>
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
<a href="/admin/menussetup/" class="yb-button"><span class="ui-icon ui-icon-plusthick"></span>Add New&nbsp;</a>
<select class="yb-select" id="thisMenu" onchange="window.location = '/admin/menussetup/'+$(this).val()">
	<option value="">EDIT A MENU ></option>
<?php	foreach($crud_all as $crud_row){
	  $selected = (is_object($crud_selected) && $crud_row->id == $crud_selected->id) ? " SELECTED" : "";
	  echo "  <option value=\"".$crud_row->id."\"".$selected.">".$crud_row->name."</option>\n"; 
	   
   }
?>
</select>
<br><br>
<h2><?=(is_object($crud_selected)) ? "Edit" : "Add New" ?> Menu</h2>
<br>
<?php if(isset($errors)){ ?>
<ul class="form_errors">
<?php foreach($errors as $field => $error){  
?>
  <li><?= $error; ?></li>    
<?php  }  ?>
</ul>
<?php }  ?>

<form action="" method="post">

<div class="form_row">
	<div class="form_label">Menu Name</div>
    <div class="form_field"><input style="width: 350px;" name="name" value="<?=value('name',$crud_selected) ?>"></div>
</div>

<div class="form_row">
	<div class="form_label">UL HTML</div>
    <div class="form_field">
    	<input style="width: 350px;" name="ul_html" value="<?=htmlspecialchars(value('ul_html',$crud_selected)) ?>" placeholder="example: id=&quot;my-menu&quot; class=&quot;sf-menu&quot;">
    	<span class="tooltip" title="Arbitrary HTML to be included in the top level UL element.">[?]</span>
    </div>
</div>

<div class="form_row">
	<div class="form_label">Available</div>
	<div class="form_field"><input type="checkbox" name="active" id="active" value="1"<?=(value('active',$crud_selected) == 1 || !isset($crud_selected->id) ) ? " CHECKED" : "" ?> >
	     <label for="active">This menu is available for editing</label>
	     <span class="tooltip" title="Unchecking this box hides this item from the list of available menus for non-developers.">[?]</span>
	</div>
</div>

<div class="form_row">
	<div class="form_label">&nbsp;</div>
    <div class="form_field">
        <button name="submit" type="submit" value="Save" class="yb-button first">Save</button>
        <span id="delete_message" style="display:none; color:#900">This item will be deleted upon submission</span>        
    </div>
</div>

<?php if(is_object($crud_selected)): ?>
<div class="form_row" style="border: 1px #336699 solid; padding-top: 5px;">
	<div class="form_label">Example Usage </div>
    <div class="form_field">
		<code>
		&lt;?php
		 &nbsp; &nbsp; echo Model_Menus::drawMenu(&lt;?=$crud_selected->id ?&gt;);
		?&gt;</code>
		<br>
		<p>Note: this "drawMenu" function accepts an array of additional parameters as a second variable.<br>
			 See "Model_Pages->drawMenu_UL()" for options.</p>
    </div>
</div>
<?php endif; ?>

<div class="form_row" style="display: <?=(is_object($crud_selected)) ? " " : "none" ?>">
    <div class="form_label">Delete</div>
    <div class="form_field"><input type="checkbox" name="delete" id="delete" value="delete"> <label for="delete">Delete This Item</label>
</div>

</form>
</div> <!-- end "app-wrapper" --> 

<div id="menu-setup-popup"></div>

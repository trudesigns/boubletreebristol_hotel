<div id="app-wrapper">
<h1>Content Blocks</h1>
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
<a href="/admin/blocks/" class="yb-button"><span class="ui-icon ui-icon-plusthick"></span>Add New&nbsp;</a>
<select class="yb-select" onchange="window.location = '/admin/blocks/'+$(this).val()">
	<option value="">EDIT ></option>
<?php	foreach($crud_all as $crud_row){
	  $selected = (is_object($crud_selected) && $crud_row->id == $crud_selected->id) ? " SELECTED" : "";
	  echo "  <option value=\"".$crud_row->id."\"".$selected.">".$crud_row->name."</option>\n"; 
	   
   }
?>
</select>
<br><br>
<h2><?=(is_object($crud_selected)) ? "Edit" : "Add New" ?> Content Block</h2>
<br><br>
<?php if(isset($errors)){ ?>
<ul class="form_errors">
<?php	foreach($errors as $field => $error){ 
?>
  <li><?=$error; ?></li>    
<?php  }  ?>
</ul>
<?php }  ?>

<form action="" method="post">

<div class="form_row">
	<div class="form_label">Name</div>
    <div class="form_field"><input name="name" value="<?=value('name',$crud_selected) ?>" /></div>
</div>

<div class="form_row">
	<div class="form_label">Object Key</div>
    <div class="form_field"><input name="objectkey" value="<?=value('objectkey',$crud_selected) ?>" /></div>
</div>

<div class="form_row">
	<div class="form_label">Description</div>
    <div class="form_field"><input name="description" value="<?=value('description',$crud_selected) ?>" /></div>
</div>

<div class="form_row">
	<div class="form_label">Output Type</div>
    <div class="form_field">
    	<select name="output_type" id="output_type" >
          <option value="content" <?=(value('output_type',$crud_selected) == "content") ? "SELECTED" : "" ?>>Visible Content</option>
          <option value="meta" <?=(value('output_type',$crud_selected) == "meta") ? "SELECTED" : "" ?>>Hidden/Meta Data</option>
        </select>
    </div>
</div>

<div class="form_row">
	<div class="form_label">Input Type</div>
        <?php 
        /*
        echo "<prE>";print_r($crud_selected);exit;
         
         */
        ?>
    <div class="form_field">
    	<select name="input_type" id="input_type" >
          <option value="">Select...</option>
          <option value="wysiwyg" <?=(value('input_type',$crud_selected) == "wysiwyg") ? "SELECTED" : "" ?>>WYSIWYG Editor</option>
          <option value="textfield" <?=(value('input_type',$crud_selected) == "textfield") ? "SELECTED" : "" ?>>Single line text field</option>
          <option value="filemanager" <?=(value('input_type',$crud_selected) == "filemanager") ? "SELECTED" : "" ?>>Local Filepath</option>
          <option value="textarea" <?=(value('input_type',$crud_selected) == "textarea") ? "SELECTED" : "" ?>>Block text area</option>
          <option value="multifield" <?=(value('input_type',$crud_selected) == "multifield") ? "SELECTED" : "" ?>>Multiple fields (JSON array)</option>
          <option value="customform" <?=(value('input_type',$crud_selected) == "customform") ? "SELECTED" : "" ?>>Custom PHP Form (include path)</option>
          <option value="module" <?=(value('input_type',$crud_selected) == "module") ? "SELECTED" : "" ?>>PHP Module</option>
        </select>
    </div>
</div>

<div class="form_row">
	<div class="form_label">Input Parameters</div>
    <div class="form_field">
    <textarea id="input_parameters" name="input_parameters" rows="15" style="width: 650px;"><?=value('input_parameters',$crud_selected,true) ?></textarea>
    </div>
</div>

<div class="form_row">
	<div class="form_label">&nbsp;</div>
    <div class="form_field">
        <button name="submit" type="submit" value="Save" class="yb-button first">Save</button>
        <span id="delete_message" style="display:none; color:#900">This item will be deleted upon submission</span>        
    </div>
</div>

<div class="form_row" style="display: <?=(is_object($crud_selected)) ? " " : "none" ?>">
    <div class="form_label">Delete</div>
    <div class="form_field"><input type="checkbox" name="delete" id="delete" value="delete"> <label for="delete">Delete This Item</label>
</div>

</form>
</div> <!-- end "app-wrapper" --> 
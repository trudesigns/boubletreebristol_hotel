<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>



<div id="app-wrapper">

<h1>Forms</h1>
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
<select class="yb-select" id="thisMenu" onchange="window.location = '/admin/form/'+$(this).val()">
	<option value="">EDIT A FORM ></option>
<?php	foreach($crud_all as $crud_row){
	  $selected = (is_object($crud_selected) && $crud_row->id == $crud_selected->id) ? " SELECTED" : "";
	  echo "  <option value=\"".$crud_row->id."\"".$selected.">".$crud_row->name."</option>\n"; 
   }
?>
</select>

<?php
if (!$crud_selected)
{ 
	return; 
} 
?>

<br><br>
<a href="javascript:;" class="yb-button align-left first" onclick="createField(); return false;">Add New Field</a>
<br>


<br clear="all">

<?php 

$form = $crud_selected;
$fields = ORM::factory('Formfield')->where('form_id','=',$form->id)->order_by('field_order')->find_all();
?>

<input type="hidden" id="selected_form" value="<?=$crud_selected->id ?>">
<div id="form" class="fields">Loading...</div>


<div id="page_toolbar">
	<div class="page_toolbar_button dd-no-move tooltip ui-icon ui-icon-wrench ui-state-default ui-corner-all ui-state-hover" title="Edit field properties">Edit Field</div>
	<div class="page_toolbar_button dd-no-move tooltip ui-icon ui-icon-arrowthick-2-n-s ui-state-default ui-corner-all ui-state-hover" title="Move this field">Reorder</div>
	<div class="page_toolbar_button dd-no-move tooltip ui-icon ui-icon-trash ui-state-default ui-corner-all ui-state-hover" title="Delete this field">Delete</div>
</div>

<div id="editFieldDialog" class="dialog" title="Edit Field Properties">
    <div class="form_row">
        <div class="form_label">Field Label: </div>
        <div class="form_field">
            <input type="text" id="label" name="label" size="45" value="">
            <input type="hidden" id="field_id" value=""> 
        </div>
    </div>
    <div class="form_row">
        <div class="form_label">Field Type: </div>
        <div class="form_field">
        	<select id="fieldtype" onchange="drawFieldOptions();">
        <?php 
            $types = array("none"=>"No Input Field", "text"=>"Text - Single Line","textarea"=>"Text - Paragraph","checkbox"=>"Checkboxes","radio"=>"Radio Buttons","select"=>"Select List","multiple"=>"Select Multiple");
			foreach ($types as $value => $label)
			{
		?>
			<option value="<?=$value ?>"> <?=$label ?> </option>
		<?php	} ?>
		</select>
        </div>
    </div>
    
    <div id="defaultvalue" style="display: none">
	    <div class="form_row">
	        <div class="form_label">Initial Value: </div>
	        <div class="form_field"><input type="text" id="value" size="45"></div>
	    </div>
	    
	    <div class="form_row">
	        <div class="form_label"><label for="max_length">Max Length</label></div>
	        <div class="form_field">
	            <input type="text" id="max_length" value="" maxlength="3" style="width: 40px" class="tooltip" title="The maximum number of characters a user can enter into the field"> (up to 255 characters)<br>
	            <em>Leave blank for no maximum entry length</em>
	        </div>
	    </div>
    
    </div>
    
    
    <div class="form_row" id="options" style="display: none">
        <div class="form_label">Options: </div>
        <div class="form_field">
        <table id="field_options">
    	<thead>
    		<th>Label</th>
    		<th>Value</th>
    		<th>Selected</th>
    	</thead>
    	<tbody>
    		
    	</tbody>
    	</table>
    	<a href="#" onClick="drawFieldOption(); return false">+ Add Options</a>
    	</div>
    </div>
    
    <div class="form_row">
        <div class="form_label">&nbsp;</div>
        <div class="form_field">
            <input type="checkbox" id="required" value="1">&nbsp;
            <label for="required">Answer Required</label> 
        </div>
    </div>
   
   <div class="form_row">
        <div class="form_label">&nbsp;</div>
        <div class="form_field">
            <input type="checkbox" id="active" value="1">&nbsp;
            <label for="active">Include field in form</label><br>
            <em>uncheck this box to remove the field from the live form but keep it accesable for future use.</em> 
        </div>
    </div>
   
</div><!-- end "editPageDialog" page properties pop-up -->


<div id="save-order"><span class="tooltip" title="Cancel page order changes."><a href="javascript:;" onClick="	loadFormFields(true);  return false;">Cancel</a></span><span class="tooltip" title="Changes have been made to the form's fields order and must be published to take effect."><a href="javascript:;" onClick="saveorder(); return false;">Publish New Order</a></span></div>

</div><!-- end "app-wrapper" -->

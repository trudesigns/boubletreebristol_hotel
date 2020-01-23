<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<div id="app-wrapper">
<h1>Templates</h1>
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
<a href="/admin/templates/" class="yb-button"><span class="ui-icon ui-icon-plusthick"></span>Add New&nbsp;</a>
<select class="yb-select" onchange="window.location = '/admin/templates/'+$(this).val()">
	<option value="">EDIT A TEMPLATE ></option>
<?php	
	foreach($crud_all as $crud_row){
	  $selected = (is_object($crud_selected) && $crud_row->id == $crud_selected->id) ? " SELECTED" : "";
	  echo "  <option value=\"".$crud_row->id."\"".$selected.">(". $crud_row->type.") ".$crud_row->name."</option>\n";    
	}
?>
</select>

<br>
<div id="editTemplate">
<h2><?=(is_object($crud_selected)) ? "Edit" : "Add New" ?> Template</h2>
<br>

<?php if(isset($errors)){ ?>
<ul class="form_errors">
<?php	foreach($errors as $field => $error){ 
?>
  <li><?= $error; ?></li>    
<?php  }  ?>
</ul>
<?php }  


$type = value('type',$crud_selected);
$parameters = json_decode( value('parameters',$crud_selected));
	
	$params_shell = (isset($parameters->shell)) ? $parameters->shell : '';
	$params_layout = (isset($parameters->layout)) ? $parameters->layout : '';
	$params_page = (isset($parameters->page)) ? $parameters->page : '';
	$params_controller = (isset($parameters->controller)) ? $parameters->controller : '';
	$params_controllerAction = (isset($parameters->controller_action)) ? $parameters->controller_action : '';
	$params_dynamicURI = (isset($parameters->dynamic_uri)) ? $parameters->dynamic_uri : '';
	$params_available = (isset($parameters->available)) ? $parameters->available : '';	

?>

<form action="" method="post">

<div class="form_row">
	<div class="form_label">Name</div>
    <div class="form_field"><input name="name" value="<?=value('name',$crud_selected) ?>" ></div>
</div>

<div class="form_row">
	<div class="form_label">Description</div>
    <div class="form_field"><textarea name="description" style="width: 400px; height: 100px"><?=value('description',$crud_selected) ?></textarea></div>
</div>

<script type="text/javascript">
	
</script>

<div class="form_row">
	<div class="form_label">Type</div>
	<div class="form_field">
		<select name="type" onchange="layoutTypeChange(this.value)">
			<option value="page" <?=( value('type',$crud_selected) == "page") ? "SELECTED" : "" ?>>Page Template</option>
			<option value="layout" <?=( value('type',$crud_selected) == "layout") ? "SELECTED" : "" ?>>Layout Template (inner)</option>	
			<option value="shell" <?=( value('type',$crud_selected) == "shell") ? "SELECTED" : "" ?>>Shell Template (outer)</option>		
		</select>
	</div>
</div>

<div id="typeIsNotPage" class="form_row" style="display: <?=($type == "shell" || $type == "layout") ? "block" : "none"; ?>">
	<div class="form_label">application/views/</div>
    <div class="form_field"><input style="width: 350px;" name="parameters" value="<?=($type == "shell" || $type == "layout") ? value('parameters',$crud_selected) : '' ?>" placeholder="public/templates/layout_or_shell_filename">
    <span class="tooltip" title="Path to the 'view' file.<br>Do no include the leading slash ('/') or the '.php' extension.">[?]</span>
    </div>
</div>

<div id="typeIsPage"  style="display: <?=(!is_object($crud_selected) || $type == "page") ? "block" : "none"; ?>">
	<div class="form_row">
		<div class="form_label">Shell</div>
	    <div class="form_field">
	    	<select name="parameters_shell">
	    		<option value="">Use Default Shell</option>
				<?php
				foreach($crud_all as $crud_row)
				{
					if($crud_row->type != "shell")
					{
						continue;
					}
					$selected = ($params_shell == $crud_row->id) ? " SELECTED" : "";
					echo "  <option value=\"".$crud_row->id."\"".$selected.">".$crud_row->name."</option>\n";    
				}
				?>
	    	</select>
	    </div>
	</div>
	<div class="form_row">
		<div class="form_label">Layout</div>
	    <div class="form_field">
	    	<select name="parameters_layout">
	    		<option value="">Use Default Layout</option>
	    		<?php
				foreach($crud_all as $crud_row)
				{
					if($crud_row->type != "layout")
					{
						continue;
					}
					$selected = ($params_layout == $crud_row->id) ? " SELECTED" : "";
					echo "  <option value=\"".$crud_row->id."\"".$selected.">".$crud_row->name."</option>\n";    
				}
				?>
	    	</select>
	    </div>
	</div>
	
	<div class="form_row">
		<div class="form_label">Available</div>
	    <div class="form_field">
	    	<input type="checkbox" name="parameters_available" id="param_available" value="1" <?=($params_available == "1" || !is_object($crud_selected)) ? "CHECKED" : "" ?>>
	    	<label for="param_available">This template is available for new pages.</label>
	    	<span class="tooltip" title="Unchecking this box allows only developers to use this template.<br><br>
	    	Once a page is assigned this template, non-developers can no longer select a different template for that page.">[?]</span>
	    </div>
	</div>
	
	<div class="form_row" id="pageViewField" style="display:<?=(!is_object($crud_selected) || $params_controller == "") ? 'block' : 'none' ?>">
		<div class="form_label">application/views/</div>
	    <div class="form_field"><input style="width: 350px;" name="parameters_page" value="<?=$params_page ?>" placeholder="public/pages/myPageName">
	    <span class="tooltip" title="Leave blank to use default page view">[?]</span>
	    </div>
	</div>

	<div class="form_row">
		<div class="form_label">Controller</div>
	    <div class="form_field"><input id="controller" name="parameters_controller" value="<?=$params_controller ?>" placeholder="custom">
	    <span class="tooltip" title="Leave blank to use the default controller">[?]</span>
	    </div>
	</div>

	<div id="hasController" style="display: <?=($params_controller != "") ? "block" : "none" ?> ">
		<div class="form_row">
			<div class="form_label">Controller Action</div>
		    <div class="form_field"><input name="parameters_controller_action" value="<?=$params_controllerAction ?>" placeholder="action_index">
		    <span class="tooltip" title="The function name within the custom controller.<br><br>Required for custom controllers">[?]</span>
		    </div>
		</div>
	
		<div class="form_row">
			<div class="form_label">Dynamic URI</div>
		    <div class="form_field">
		    	<input type="checkbox" name="parameters_dynamic_uri" value="1" id="param_dynamicURI" <?=($params_dynamicURI == "1" ) ? "CHECKED" : "" ?>>
		    	<label for="param_dynamicURI">URL may include child pages</label>
		    	<span class="tooltip" title="When checked, the setup controller will not automatically return 404 pages if the URL is invalid.  
		    	<br><br>This allows the custom controller to create pseudo pages based on the given URI">[?]</span>
		    </div>
		</div>
	</div>

</div>	

    <div class="form_row">
        <div class="form_label">&nbsp;</div>
        <div class="form_field">
            <button type="submit" name="submit" class="yb-button first" value="Submit">Save</button>
            <span id="delete_message" style="display:none; color:#900">This item will be deleted upon submission</span>
        </div>
    </div>

    <div class="form_row" style="display: <?=(is_object($crud_selected)) ? " " : "none" ?>">
            <div class="form_label">Delete</div>
            <div class="form_field"><input type="checkbox" name="delete" id="delete" value="delete"> <label for="delete">Delete This Item</label></div>
    </div>


</form>
</div> <!-- end of "editTemplate" -->


<div id="addBlocks">

<?php if(is_object($crud_selected) && $type != "page")
{
	echo '</div>';
	echo '<br style="clear: both">&nbsp;';
	return; // leave this page
}
?>

<h2>Included Content Blocks</h2>
<br>



<?php if(!is_object($crud_selected))
{
	echo "Save this new template before adding content blocks to it.";
	//return;
}

    if(is_object($crud_selected))
    {
        $getTemplate_blocks = ORM::factory('Templatecontentblock')->where('template_id','=',$crud_selected->id)->find_all();
        $template_blocks = array();
        foreach ($getTemplate_blocks as $getTemplate_block)
        {
                $template_blocks[] = $getTemplate_block->content_block_id;
        }

        $blocks = ORM::factory('Contentblock')->order_by('name')->find_all();   
        foreach($blocks as $block)
        {
            ?>
            <input type="checkbox" data-template-id="<?=$crud_selected->id;?>" class="blocks_checkbox" value="<?=$block->id ?>" id="block_<?=$block->id ?>"<?=( in_array($block->id,$template_blocks) ) ? " checked" : "" ?>>
            <label for="block_<?=$block->id ?>"><?=$block->name ?></label><br>
            <?php 
        }
    
    }
?>

</div><!-- end of "addBlocks" -->
<br style="clear: both">


</div><!-- end "app-wrapper" -->

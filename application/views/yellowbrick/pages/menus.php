<article id="menus" class="list">

<h1>Menus</h1>
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
<?php 
/*
 * <select class="yb-select" id="thisMenu" onchange="window.location = '/admin/menus/'+$(this).val()">
 */
?>
<div class="col-xs-12" id="menu_block">
<span class="col-xs-12 row" >
<select class="yb-select" id="thisMenu">
	<option value="">EDIT A MENU ></option>
<?php	
                $menus = "";
                foreach($crud_all as $crud_row){
	  $selected = (is_object($crud_selected) && $crud_row->id == $crud_selected->id) ? " SELECTED" : "";
	  $menus .= "  <option value=\"".$crud_row->id."\"".$selected.">".$crud_row->name."</option>\n"; 
   }
   echo $menus;
?>
</select>

<a href="#" id="save_item" class="yb-tooltip btn btn-info hide" title="Save to the DB the changes.">Save</a>
<a href="#" id="cancel_item" class="yb-tooltip btn btn-info hide" title="Cancel changes made.">Cancel</a>
<a href="#" id="publish_item" class="yb-tooltip btn btn-danger hide" title="Update the cached menu and make changes live.">Publish</a>
</span>

<aside id="menu_tools" class="col-xs-12 hide-on-load row">
    <div class="row">
        <div class="col-xs-8">
<!--            <ul class="yb-tree"></ul>-->
        <select class="yb-tree  chosen-single" id="add_to_menu" name="add_to_menu">
            <option value="">-- Select Page on Site --</option>
        </select>
        </div>
    </div>
    <div class="row0">
        <span>
            <a href="#" class="btn btn-default add_tree_page" data-children="false">Add Selected Page</a>
        </span>
        <span>
            <a href="#" class="btn btn-default add_tree_page" data-children="true">Add Page &amp; Subsections</a>
        </span>
         <span>
            <select name="copy_menu" id="copy_menu" class="chosen-single">
                <option value="">Copy Menu To</option>
                <?=$menus;?>
            </select>
        </span>
        <span >
            <a href="#" class="btn btn-default add_custom" data-toggle="modal" data-target="#custom_url">Add Custom URL</a>
        </span>
        <span class="pull-right">
            <a href="#" class="btn btn-default add_sitemap">Add Entire Sitemap</a>
        </span>
    </div>
    
    
</aside>
</div>
<section id="menus_list" class="col-xs-12 hide-on-load clearfix">
    <div id="nestable" class="nestable ">Loading...</div>
   
     <div class="modal fade" id="custom_url">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Custom URL Page</h4>
               </div>
                <?=Form::open(NULL,array("id"=>"custom_url_form","novalidate"));?>
               <div class="modal-body">
                    <?=Form::hidden("link_id",NULL,array("id"=>"link_id"));?>
                   <?=Form::hidden("link_type",NULL,array("id"=>"link_type"));?>
                    <?=Form::hidden("menu_id",NULL,array("id"=>"menu_id"));?>
                   <?=Form::hidden("parent_id",NULL,array("id"=>"parent_id"));?>
                   <?=Form::hidden("display_order",NULL,array("id"=>"display_order"));?>
                     <div >
                                <?=Form::label("link_value","Page URL"); ?>
                               
                                <?=Form::input("link_value", NULL, array("class"=>"form-control","id"=>"link_value"));?>     
                    </div>
                    <div >
                                <?=Form::label("label","Label"); ?>
                                <?=Form::textarea("label",NULL,array("class"=>"form-control","id"=>"label")); ?>
                    </div>
                    <div>
                                <?=Form::label("target","Target"); ?>
                                <?=Form::select("target",array("_self"=>"Same Window","_blank"=>"New Window"),'_self',array("class"=>"form-control","id"=>"target")); ?>
                    </div>
                    <div>
                                <?=Form::label("attributes","HTML Attributes"); ?>
                                <?=Form::input("attributes", NULL, array("class"=>"form-control ignore","placeholder"=>"ex: class='tooltip' title='some extra tip'","title"=>"optional HTML parameters added to the menu item","id"=>"attributes"));?>     
                      </div>
                          
  
                   
               </div>
               <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-default"  id="submit-changes">Save Changes</button>
                </div>
                <?=Form::close();?>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->
       
</section>
<div class="hide" id="menus_toolbar">
    <span id="menu-edit">
            <a href="#" class="glyphicon glyphicon-edit edit-item" title="Edit menu item properties" >&nbsp;</a>
    </span>
      <span id="menu-remove">
            <a href="#" class="glyphicon glyphicon-remove remove-item" title="Remove this menu item">&nbsp;</a>
      </span>
</div>
</article><!-- end "app-wrapper" -->


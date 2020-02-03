<div id="app-wrapper">
<h1>Blog Feed Manager</h1>
<br>
<?php

if(is_object($crud_selected))
{
	$crud_selected->other = json_decode($crud_selected->other);
}

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
<a href="/admin/blog" class="yb-button"><span class="ui-icon ui-icon-plusthick"></span>Add New&nbsp;</a>
<select id="selected_feed_article" class="chzn-select" onchange="window.location = '/admin/blog/'+$(this).val()">
	<option value="">EDIT ></option>
<?php	foreach($crud_all as $crud_row){
	  $selected = (is_object($crud_selected) && $crud_row->id == $crud_selected->id) ? " SELECTED" : "";
	  $crud_row->other = json_decode($crud_row->other);
	  
	  $pagetitle = ($crud_row->page_id != 0) ? $crud_row->page->label : $crud_row->other->url_label;
	  echo "  <option value=\"".$crud_row->id."\"".$selected.">" .date("m/d/Y",strtotime($crud_row->display_date))." - ". $pagetitle ."</option>\n"; 
	   
   }
?>
</select>
<?php if (is_object($crud_selected)): ?>
<a href="javascript:;" id="delete-btn" name="delete-btn" class="yb-button align-right">Delete</a>

<?php if($page !== false){ ?>
&nbsp
<a href="/admin/edit/?page_id=<?=$page->id ?>&block_id=3&version_id=1" class="yb-button align-right"><span class="ui-icon ui-icon-pencil"></span>Edit Ariticle Content &nbsp;</a>
<?php } ?>

<?php endif; ?>
<br><br>

<div style="width: 475px; padding: 10px; border: 1px #336699 solid; float: left">
	
	<h2><?=(is_object($crud_selected)) ? "Edit" : "Add New" ?> Feed Article</h2>
	
	<?php if(isset($errors)){ ?>
	<ul class="form_errors">
	<?php	foreach($errors as $field => $error){ 
	?>
	  <li><? echo $error; ?></li>    
	<?php  }  ?>
	</ul>
	<?php }  ?>
	
	
	<form id="redirectsform" action="" method="post">
	
	<div class="form_row">
	 <div class="form_label">Link Type:</div>
	 <div class="form_field">
	 	<input type="radio" name="linkType" id="linkTypePage" value="page" <?=(!is_object($crud_selected) || $crud_selected->other == "") ? "CHECKED" : "" ?>>
	 	<label for="linkTypePage">A page on this website</label>
	 <br>
	 	<input type="radio" name="linkType" id="linkTypeUrl" value="url" <?=(is_object($crud_selected) && $crud_selected->other != "") ? "CHECKED" : "" ?>>
	 	<label for="linkTypeUrl">A file or external URL</label>
	 
	 </div>
	</div>
	
	
	<div class="form_row" id="selectPage" style="display:<?=(!is_object($crud_selected) || $crud_selected->other == "" ) ? "block" : "none" ?>">
	 <div class="form_label">Selected Page:</div>
	 <div class="form_field">
 
	<?php	if (!is_object($crud_selected))
		{  // if this is a NEW feed article, offer to create a new page
	?> 
	   <input type="radio" id="selected_page_new" name="selected_page_new" value="1" onclick="$('#selectable_pages_list').fadeOut('fast');" checked="checked">
	   <label for="selected_page_new">Create New Page</label>
	   <br>
	  <input type="radio" id="selected_page_exisiting" name="selected_page_new" value="0" onclick="$('#selectable_pages_list').fadeIn('fast'); listSitePages();">
	   <label for="selected_page_exisiting">Link to an exisiting page</label>
	<?php	} ?>
	
	   <div id="selectable_pages_list" style="display:<?=(!is_object($crud_selected)) ? "none" : "block" ?>">
	 		<select name="page_id" id="page_id"></select>  
	   </div>

	 </div>
	</div>
	
	<div id="selectURL" style="display: <?=(is_object($crud_selected) && $crud_selected->other != "" ) ? "block" : "none" ?>">
	<div class="form_row">
		<div class="form_label">URL Title:</div>
	    <div class="form_field"><input name="url_label" value="<?=(is_object($crud_selected) && $crud_selected->other != "" ) ? value('url_label',$crud_selected->other) : '' ?>"></div>
	</div>
	<div class="form_row">
		<div class="form_label">URL:</div>
	    <div class="form_field"><input id="url" name="url" placeholder="http://external-website.com" value="<?=(is_object($crud_selected) && $crud_selected->other != "" ) ? value('url',$crud_selected->other) : '' ?>">
	    	
	    	<button id="content_launchfilemanager">Browse...</button>
			<script type="text/javascript"> 
			$('#content_launchfilemanager').click(function() { 
					CKFinder.popup( { basePath : '/ckfinder/',  
						rememberLastFolder: false, 
						startupPath : 'Files:/',
						startupFolderExpanded: true, 
						selectActionFunction : function(fileURL){  $("#url").val(fileURL); }
			 		});
					return false;
			});
			</script>
	    	
	    </div>
	</div>

	<div class="form_row">
		<div class="form_label">URL Description:</div>
		<div class="form_field"><textarea name="url_description"><?=(is_object($crud_selected) && $crud_selected->other != "" ) ? value('url_description',$crud_selected->other) : ''  ?></textarea></div>
	</div>

	</div>
	
	<div class="form_row">
		<div class="form_label">Display Date:</div>
	    <div class="form_field"><input id="display_date" name="display_date" value="<?=value('display_date',$crud_selected) ?>">
	    <br>This vanity date effects the article's order in the feed but is unrealated to the publish dates attributed to the article
	    </div>
	</div>
	
	<div class="form_row">
		<div class="form_label">Active:</div>
	    <div class="form_field">
	    	<input type="checkbox" name="active" id="active" value="1" <?=(is_object($crud_selected) && $crud_selected->active == 0) ? "" : "checked" ?>>
	    	<label for="active">This article should appear in the feed.</label>
	    </div>
	</div>
	
	<div class="form_row">
		<div class="form_label">Select Categories:</div>
	    <div class="form_field">
	    <select id="categories" name="categories[]" multiple class="chzn-select" style="width: 300px">
	<?php	
		$categories = ORM::factory('Feedcategory')->where('feed_id','=',1)->find_all();
		$selected_categories = array();
		
		if(is_object($crud_selected))
		{
			$getSelected_categories = ORM::factory('Feedlookup')->where('article_id','=',$crud_selected->id)->find_all();
			foreach($getSelected_categories as $selected_category)
			{
				$selected_categories[] = $selected_category->category_id;
			}	
		}
		else
		{
			$selected_categories[] = 1; // default pre-selected category
		}
		
		foreach($categories as $category)
		{
	?>
	     <option value="<?=$category->id ?>"<?=(in_array($category->id,$selected_categories)) ? " selected" : "" ?>><?=$category->name ?></option>
	<?php	} ?>
	    </select>    
	    <br>The article must be assigned to at least one category to appear in a feed.
	    </div>
	</div>
	
	<div class="form_row">
		<div class="form_label">&nbsp;</div>
	    <div class="form_field">
	        <button type="submit" id="submit-form" name="submit-form" class="yb-button" value="Submit">Save Article Data</button>
	        <span id="delete_message" style="display:none; color:#900">This item will be deleted upon submission</span> 
	    </div>
	</div>
	
	<input type="hidden" id="delete" name="delete" value="">
	</form>

</div><!-- end of "edit feed article" box -->

<?php	if(is_object($page) && $page !== false)
	{ 
?>
<div style="width: 420px; border: 1px #336699 solid; float: right; padding: 10px;">
<h2>Edit Page Data</h2>

<div class="form_row">
	<div class="form_label">Page Label:</div>
	<div class="form_field"><input id="page_label" value="<?=value('label',$page) ?>" onkeyup="updateSlug(this.value)"></div>
</div>

<div class="form_row">
	<div class="form_label">Page Slug:</div>
	<div class="form_field">
		<input id="page_slug" value="<?=value('slug',$page) ?>">
		<input type="hidden" id="original_slug" value="<?=value('slug',$page) ?>">
        <span id="slug_icon" class="tooltip tooltip-help"></span>
        <span style="display: none" id="reset_slug_link" class="tooltip" title="If slug has changed, click to reset to previous value.">
        <a href="" onclick="$('#page_slug').val( $('#original_slug').val() ); return false">Reset</a></span>
	</div>
</div>

<div class="form_row">
	<div class="form_label"><label for="page_active">Active:</label></div>
	<div class="form_field"><input type="checkbox" id="page_active" value="1" <?=(value('active',$page)==1) ? "CHECKED" : "" ?>>
			Unchecking this box will cause a "404 - Page Not Found" error.
	</div>
</div>

<div class="form_row">
	<div class="form_label">Start Date:</div>
	<div class="form_field"><input id="page_start_date" value="<?=(value('start_date',$page) == "0000-00-00 00:00:00") ? "" : value('start_date',$page); ?>">
	<br><em>blank date fields have no begining or end date</em>
	</div>
</div>

<div class="form_row">
	<div class="form_label">End Date:</div>
	<div class="form_field"><input id="page_end_date" value="<?=(value('end_date',$page) == "0000-00-00 00:00:00") ? "" : value('end_date',$page); ?>"></div>
</div>

<div class="form_row">
	<div class="form_label">&nbsp;</div>
	<div class="form_field"><button  id="submit_pagedata" class="yb-button">SAVE PAGE DATA</button>
	<br>
	<br>
	<a href="/admin/pages#<?=$page->id ?>" style="font-size: 11px;">advanced page properties</a>
	</div>
</div>
</div> <!-- end of "edit page data" box -->


<?php	} ?>


</div> <!-- end "app-wrapper" -->

<script type="text/javascript">
function listSitePages()
{
	$.getJSON( XHR_PATH+'allPages',function(data){	
		
		var html="", pages=data, mydelimiter = "x", f = function myfunction(mydelimiter)
		{
			if( mydelimiter === 0) { mydelimiter = ""; }
			
			$.each(pages,function(index,pagedata) 
			{			
				var selected = ( pagedata.thisID == <?=($crud_selected && $crud_selected->page_id != 0 )?$crud_selected->page->id : '-1'; ?>) ? " SELECTED" : "";
				html+= '\n\t<option value="'+ pagedata.thisID +'"'+selected+'>'+ mydelimiter +' '+ pagedata.label +'</option>';		
				
				if( pagedata.children > 0)
				{
					pages = pagedata.child_pages;
	
					html = myfunction( mydelimiter +" -"); // run this again, recursively 
				}				
			});
			return html;		
		};	
		
		$('#page_id').html( f ).chosen();
	});
}

function updateSlug(page_name){
	$.get(XHR_PATH+'generateSlug/',{string:page_name},function(newSlug){
	
		$('#page_slug').val(newSlug);
		if(newSlug !== $('#original_slug').val() && $('#original_slug').val !== ""){
			$("#reset_slug_link").fadeIn('fast');
		}else{
			$("#reset_slug_link").fadeOut('fast');	
		}
	//	checkSlug();
		
	});
}

$(document).ready(function(){
	
	$(".chzn-select").chosen();
	
	$("input[name=linkType]").on("change",function(){
			if($(this).val() == "url")
			{
				$("#selectPage").fadeOut(0);
				$("#selectURL").fadeIn('fast');
			}
			else
			{
				$("#selectURL").fadeOut(0);
				$("#selectPage").fadeIn('fast');
				listSitePages();
			}
	});
	
	$("#display_date").datepicker({ dateFormat: "yy-mm-dd 00:00:00" });
	
<?php if(is_object($crud_selected) && $crud_selected->page_id != 0)
{
?>
	listSitePages();
	
	$("#page_start_date").datepicker({ dateFormat: "yy-mm-dd 00:00:00" });
	$("#page_end_date").datepicker({ dateFormat: "yy-mm-dd 00:00:00" });
	
	$("#submit_pagedata").on("click",function(){
		var original_button_value = $(this).val();
		$(this).val("Saving...");
		var sendData = {
			page_id: <?=$crud_selected->page_id ?>,
			label: $("#page_label").val(),
			slug: $("#page_slug").val(),
			active: ( $("#page_active").is(':checked')) ? 1 : 0,
			start_date: ($("#page_start_date").val() !== "") ? $("#page_start_date").val() : '0000-00-00 00:00:00',
			end_date: ($("#page_end_date").val() !== "") ? $("#page_end_date").val() : '0000-00-00 00:00:00',
			searchable: <?=value('searchable',$page) ?>,
			display_in_sitemap: <?=value('display_in_sitemap',$page) ?>
		}
		
		$.post(XHR_PATH+'savePageProperties/',sendData, function(data){
			data = $.trim(data);
			if(data !== "done")
			{ 
				uiAlert(data);
			}
			else
			{
				uiAlert("Success!<br>Page data changes saved.");		
			}
			$(this).val(original_button_value);
		});
		
	});
	
		
		
<?php	
} //end options for "edit page data" block 
?>
	
});
</script>
<?php if(!$block->id){ die( "Invalid Content ID"); } 

/**
 * fix string that from accidently interpreting dollar signs ($) as a regex backreference
 *
 */
function preg_replace_quote($str) {
   	return preg_replace('/(\$|\\\\)(?=\d)/', '\\\\\1', $str);
}


if (isset($msg)) {
	$message = '
    		<div class="ui-widget">
			<div class="ui-state-highlight ui-corner-all yb-inline-msg" style="margin-top: 20px;"> <br />
				<p><span class="ui-icon ui-icon-info align-left"></span>
				<strong>&nbsp;'.$msg.'</strong></p>
			</div>
		</div>
		';
		echo $message;
		echo '<br />';
}

//$nav = new Model_Page;
//$breadcrumbs = $page->getBreadcrumbs($page->id);
$page = new Model_Page;
$breadcrumbs = $page->getBreadcrumbs($content->page->id);
$i=1;
$uri = "/";
$breadcrumbs_html = "";
foreach($breadcrumbs as $crumb){
        $breadcrumbs_html.= $crumb['label'];
        $breadcrumbs_html.= ($i != count($breadcrumbs)) ? " > " : "";

        $uri.= ($crumb['slug'] != "" && $crumb['slug'] != "/") ? $crumb['slug']."/" : "";

        $i++;
}
?>


<h2><?=$breadcrumbs_html ?>: <?=$block->name; ?></h2>
<?php 

   if($revisions && $content->revision_date != $revisions[0]->revision_date){   
   	$old_revision = true; 
?>
	<div class="ui-state-error ui-corner-all yb-inline-msg"> 
		<span class="ui-icon ui-icon-alert align-left"></span>
        <strong>&nbsp;Note:</strong> A more recently saved revision of this page's content exists.
	</div>
<?php } ?>
    

<?php if( $content->page->required_role != ""){?>
<div class="ui-state-highlight ui-corner-all yb-inline-msg">
	<span class="ui-icon ui-icon-locked align-left"></span>
    <strong>&nbsp;Note:</strong> This page requires users to be logged in with the "<?=$content->page->required_role ?>" role to view its content.
</div>
<?php } ?>

<div id="edit-control-wrap">

<?php if(Auth::instance()->logged_in('publish') || Auth::instance()->logged_in('developer') ){?>
<button onClick="validate(true)" class="yb-button align-right last">Publish</button>
<?php } ?>
<button id="save" class="yb-button align-right">Save Draft</button>

<a href="<?=$uri ?>?preview_block_objectkey=<?=$block->objectkey ?>&content_id=<?=$content->id ?>" onClick="preview( $(this).attr('href')); return false;" class="yb-button align-right tooltip" title="Preview this draft">Preview</a>

<a href="<?=$uri ?>"  target="_blank" class="yb-button align-right tooltip" title="View live page in new window">View Page</a>



<?php if(count($other_blocks) > 0) { ?>
	
    <div id="edit-block-wrap">
    <select id="block-select" class="chzn-select" onChange="/* window.location = $(this).val() */">
     <?php foreach($other_blocks as $other_block){ ?>
      <option value="<?=$other_block->id ?>"<?=($other_block->id == $block->id) ? " SELECTED" : "" ?>><?=$other_block->name ?></option>
     <?php } ?>
    </select>&nbsp;&nbsp;
    </div>
<?php } ?>


<?php if(count($contentversions) > 1) { ?>

	<div id="edit-versions-wrap">Versions:
    <select id="version-select" class="chzn-select" onChange="/* window.location = $(this).val() */">
      
     <?php foreach($contentversions as $version){ ?>
      <option value="<?=$content->version_id ?>"<?=($version->id == $content->version_id) ? " SELECTED" : "" ?>/><?=$version->name ?></option>
     <?php } ?>
    </select>
    </div>
<?php } ?>

</div><!-- end edit-control-wrap -->
<br clear="all">

<script type="text/javascript" charset="utf-8">


$(document).ready(function() {
	
	//alert('page-side document.ready(): '+start_slide);			
	
	$('.flexslider').flexslider({
		animation: "slide",              //String: Select your animation type, "fade" or "slide"
		direction: "horizontal",        //String: Select the sliding direction, "horizontal" or "vertical"
		reverse: false,                 //{NEW} Boolean: Reverse the animation direction
		animationLoop: true,             //Boolean: Should the animation loop? If false, directionNav will received "disable" classes at either end
		smoothHeight: false,            //{NEW} Boolean: Allow height of the slider to animate smoothly in horizontal mode 
		startAt: <?= $block->id - 1; ?>,            //Integer: The slide that the slider should start on. Array notation (0 = first slide)
		slideshow: false,                //Boolean: Animate slider automatically
		slideshowSpeed: 7000,           //Integer: Set the speed of the slideshow cycling, in milliseconds
		animationSpeed: 600,            //Integer: Set the speed of animations, in milliseconds
		initDelay: 0,                   //{NEW} Integer: Set an initialization delay, in milliseconds
		randomize: false,               //Boolean: Randomize slide order
		 
		// Usabi#336699lity features
		pauseOnAction: true,            //Boolean: Pause the slideshow when interacting with control elements, highly recommended.
		pauseOnHover: false,            //Boolean: Pause the slideshow when hovering over slider, then resume when no longer hovering
		useCSS: true,                   //{NEW} Boolean: Slider will use CSS3 transitions if available
		touch: true,                    //{NEW} Boolean: Allow touch swipe navigation of the slider on touch-enabled devices
		 
		// Primary Controls
		controlNav: false,               //Boolean: Create navigation for paging control of each clide? Note: Leave true for manualControls usage
		directionNav: true,             //Boolean: Create navigation for previous/next navigation? (true/false)
		prevText: "Previous",           //String: Set the text for the "previous" directionNav item
		nextText: "Next",               //String: Set the text for the "next" directionNav item
		 
		// Secondary Navigation
		keyboard: true,                 //Boolean: Allow slider navigating via keyboard left/right keys
		multipleKeyboard: false,        //{NEW} Boolean: Allow keyboard navigation to affect multiple sliders. Default behavior cuts out keyboard navigation with more than one slider present.
		mousewheel: false,              //{UPDATED} Boolean: Requires jquery.mousewheel.js (https://github.com/brandonaaron/jquery-mousewheel) - Allows slider navigating via mousewheel
		pausePlay: false,               //Boolean: Create pause/play dynamic element
		pauseText: 'Pause',             //String: Set the text for the "pause" pausePlay item
		playText: 'Play',               //String: Set the text for the "play" pausePlay item
		 
		// Carousel Options
		itemWidth: 100,                   //{NEW} Integer: Box-model width of individual carousel items, including horizontal borders and padding.
		itemMargin: 0,                  //{NEW} Integer: Margin between carousel items.
		minItems: 3,                    //{NEW} Integer: Minimum number of carousel items that should be visible. Items will resize fluidly when below this.
		maxItems: 3,                    //{NEW} Integer: Maxmimum number of carousel items that should be visible. Items will resize fluidly when above this limit.
		move: 1                        //{NEW} Integer: Number of carousel items that should move on animation. If 0, slider will move all visible items.
		
	});
	 
	
		
   
 });

 
</script>

<?php if(count($other_blocks) > 0) { ?>
<div id="block-slider" class="flexslider">
  <ul class="slides">
 <li>&nbsp;<!-- active slide position hack --></li>
   <?php 
   foreach($other_blocks as $other_block){ ?>
   
    
    <li><a href="javascript:;" onClick="editBlock(<?=$other_block->id ?>);" <?=($other_block->id == $block->id) ? ' class="current-slide"' : '' ?> title="<?=$other_block->description ?>"><?=$other_block->name ?></a></li>
    
	<?php } ?>
    <li><!-- bug fix --></li>
  </ul>
</div>
<?php } ?>


<?php if( (isset($_SESSION['ckfinder_baseURL']) && $_SESSION['ckfinder_baseURL'] != "") || $content->page->required_role != "" ){?>
<span title="Images and document uploaded into the file manager in 'secure mode' are stored in a secured folder and can only be viewed by users logged in with access to this page." class="tooltip">
<input onChange="set_ckfinder_baseURL()"  type="checkbox" id="securefolder" value="<?=$content->page->required_role ?>" <?=(isset($_SESSION['ckfinder_baseURL']) && $_SESSION['ckfinder_baseURL'] != "") ?"CHECKED" : "" ?>> <label for="securefolder">Launch File Manager in secure mode</label>
</span>
<?php } ?>

<form action="<?=PATH_BASE ?>admin/edit/<?=$content->id ?>" id="savecontent" name="savecontent" method="post">
<textarea id="original_content" style="display: none"><?=$content->content ?></textarea>
<input type="hidden" name="blocktype" id="blocktype" value="<?=$block->input_type ?>" />
<input type="hidden" id="edit_time" value="<?=date("Y-m-d H:i:s") ?>" /> 
<input type="hidden" id="page_id" value="<?=$content->page_id ?>" /> 
<input type="hidden" id="block_id" value="<?=$content->block_id ?>" /> 
<input type="hidden" id="version_id" value="<?=$content->version_id ?>" /> 
<input type="hidden" name="live" id="live" value="<?=$content->live ?>" /> 
<input type="hidden" name="publish" id="publish" value="0">

<?php
if ($block->input_type != 'wysiwyg') { echo '<div id="edit-zone-wrap">'; }


if( $block->input_type == "multifield")
{
	
	$content_array = json_decode( $content->content, true ); //convert to Array for use by included file
	$input = json_decode( $block->input_parameters, true ); // convert to Array
	
	foreach ($input as $row)
	{
		$key_index = key($row);
	
		if( count($content_array) > 0 )
		{
			$input = preg_replace('@{content}@',preg_replace_quote($content_array[$key_index]),$row[$key_index]['input']);
		}else{
			$input = $row[$key_index]['input'];
		}
//	echo '</div>';
            ?>	
            <div class="form_row">
                    <div class="form_label"><?=$row[$key_index]['label'] ?></div>
                <div class="form_field"><?=$input ?></div>
            </div>
  <?php } ?>
	<input type="hidden" id="content" name="content" value="" />
<?php } elseif( $block->input_type == "customform"){
	
	$content_array = json_decode( $content->content, true ); //convert to Array for use by included file
	include_once $_SERVER['DOCUMENT_ROOT'].'/application/views/'. ltrim($block->input_parameters,"/");

?>
	<input type="hidden" id="content" name="content" value="" />
<?php } else {
	echo  preg_replace('@{content}@',preg_replace_quote($content->content),$block->input_parameters);
}

if ($block->input_type != 'wysiwyg') { echo '</div> <!-- end #edit-zone-wrap -->'; }

?>

</form>



<div id="edit-revision-info-wrap">

<?php
	// this is the new line 194
	
	if(is_numeric($content->updated_by ) && $content->updated_by != 0){
		$updated_by_user = ORM::factory('User',$content->updated_by);
		$updated_by_name = $updated_by_user->first." ".$updated_by_user->last;
	}else{
		$updated_by_name = "Unknown User";
	}
        
	if($content->published_by != 0){
		$published_by_user = ORM::factory('User',$content->updated_by);
		$published_by_name = $published_by_user->first." ".$published_by_user->last;
	}else{
		$published_by_name = "Unknown User";
	}
?>

<div class="align-left">
    	<strong>Saved:</strong> <?=date("F j, Y h:ia",strtotime($content->revision_date)) ?> &nbsp; <strong>By:</strong> <?=$updated_by_name; ?>
     <br />
		<?php if(!isset($old_revision)){ ?>
           
        <a href="" onclick="$(this).css({display:'none'}); $('#revision_list').fadeIn('fast'); return false">Show Save History</a>
		<?php } ?>
    
	<span id="revision_list" <?php if(!isset($old_revision)) { echo 'style="display: none;"'; } ?>>
   		<strong>History:</strong>&nbsp;&nbsp;
   		<select name="revisionList" id="revisionList" onchange="window.location = '/admin/edit/'+this.options[this.selectedIndex].value;">
<?php foreach($revisions as $revision){ ?>			
                    <option value="<?=$revision->id ?>"<?php if($content->id == $revision->id){ echo " SELECTED"; } ?>><?=date("M d, Y g:ia",strtotime($revision->revision_date)); 	    
				if($revision->live == 1 ){ echo " - LIVE";  }
				elseif($revision->publish_date != "0000-00-00 00:00:00"){ echo " *"; } ?></option>
<?php } ?>
   		</select> 
	</span>
</div>

 
<div class="align-right">
<?php if($content->publish_date == "0000-00-00 00:00:00"){ ?>
	<span class="tooltip revision-status orange" title="This content has not been published.">Unpublished Draft</span> <!--This content and meta data has not been published.-->
<?php	}
	else
	{
		if($content->live == 1)
		{
			$title_text = "This content is currently being shown on the website.";
			$text = "Live Revision";
			$revision_class = "green";
		}
		else
		{
			$title_text = "This revision was previously live on the website.";
			$text = "Previously Published";
			$revision_class = "red";
		}
		
		$title_text.= "<br><br>Published ".date("m/d/Y h:ia",strtotime($content->publish_date)) ." by ". $published_by_name;
?>	

	<span class="tooltip revision-status <?=$revision_class?>" title="<?=$title_text ?>"><?=$text ?></span>
<?php } ?>

</div> 

 </div>

'<!-- end of revision-info-wrap div -->
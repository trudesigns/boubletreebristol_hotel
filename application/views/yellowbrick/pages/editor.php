<?php if(!$block->id){ die("Invalid Content ID"); } 


//
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
			<div class="ui-state-highlight ui-corner-all yb-inline-msg" style="margin-top: 20px;"> <br >
				<p><span class="ui-icon ui-icon-info align-left"></span>
				<strong>&nbsp;'.$msg.'</strong></p>
			</div>
		</div>
		';
		echo $message;
		echo '<br >';
}


///echo "SBXBSBS: ".$content_id;
//$nav = new Model_Page;
//$breadcrumbs = $page->getBreadcrumbs($page->id);
$page = new Model_Page;
$breadcrumbs = $page->getBreadcrumbs($content->page->id);
  		$i=1;
		$uri = "/";
		$breadcrumbs_html = "";
		foreach($breadcrumbs as $crumb){
		//	foreach($key_index as $label => $slug){
	    		 $breadcrumbs_html.= $crumb['label'];
		 		$breadcrumbs_html.= ($i != count($breadcrumbs)) ? " > " : "";
	    
		 		$uri.= ($crumb['slug'] != "" && $crumb['slug'] != "/") ? $crumb['slug']."/" : "";
		 
		 		$i++;
		//	}
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
  


<?php if(!in_array("config",$accessroles)  && !in_array("publish",$accessroles) && !in_array("edit",$accessroles) && !in_array("create",$accessroles)){  ?>

<div class="ui-state-highlight ui-corner-all yb-inline-msg">
	<span class="ui-icon ui-icon-locked align-left"></span>
    <strong>&nbsp;Note:</strong> This page requires users to be logged in with the "<?=$content->page->required_role ?>" role to view its content.
</div>
<?php } ?>



<section id="edit-control-wrap">
    <aside id="admin-control">
<?php 
//print_r($accessroles);exit;
// "Publish" button only works for those with "publish" role or config
if(in_array("config",$accessroles)  || in_array("publish",$accessroles)){  ?>
<?php /*if(Auth::instance()->logged_in('publish') || Auth::instance()->logged_in('developer') ){ */?>
<button id="publishBTN" class="yb-button publish-block align-right last">Publish Block</button>
<?php } ?>
<?php 
// "Publish" button only works for those with "publish" role or config
if(in_array("config",$accessroles)  || in_array("publish",$accessroles)){  ?>
<button id="save" class="yb-button align-right save-block">Save Draft</button>
<?php } ?>
<a id="content-preview" data-page-id="<?=$content->page->id;?>" data-content-id="<?=$content->id;?>" data-block-key="<?=$block->objectkey ?>" data-page-name="<?=$breadcrumbs_html;?>"href="#"   class="yb-button align-right yb-tooltip" title="Preview this draft">Preview</a>
<?php 
/*
 <a href="<?=$uri ?>?preview_block_objectkey=<?=$block->objectkey ?>&content_id=<?=$content->id ?>" onClick="preview( $(this).attr('href')); return false;" class="yb-button align-right yb-tooltip" title="Preview this draft">Preview</a>
 */
?>



<a href="<?=$uri ?>"  target="_blank" class="yb-button align-right yb-tooltip" title="View live page in new window">View Page</a>

<div class="modal fade col-xs-12" id="preview-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Preview Page: <span></span></h4>
               </div>
                <div class="modal-body">
                    <iframe id="preview-frame" style="width:100%;height:500px;"></iframe>
               </div>
               <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->

    </aside>
<aside id="edit-block-wrap">
<?php
// leave the DIV outside of the count check so that it acts as a placeholder and helps maintains design specs
//echo "COUNT: ".count($other_blocks);
if(count($other_blocks) > 0) { ?>

    <select id="block-select" class="yb-select">
     <?php foreach($other_blocks as $other_block){ ?>
      <option value="<?=$other_block->id ?>"<?=($other_block->id == $block->id) ? " SELECTED" : "" ?>><?=$other_block->name ?></option>
     <?php } ?>
    </select>&nbsp;&nbsp;
    
<?php } ?>
</aside>

 
    
    
<?php if(count($contentversions) > 1) { ?>

	<div id="edit-versions-wrap">Versions:
    <select id="version-select" class="yb-select">
      
     <?php foreach($contentversions as $version){ ?>
      <option value="<?=$content->version_id ?>"<?=($version->id == $content->version_id) ? " SELECTED" : "" ?>><?=$version->name ?></option>
     <?php } ?>
    </select>
    </div>
<?php } ?>

    
    
    
</section><!-- end edit-control-wrap -->

 <?php 
    echo Form::open('admin/edit/'.$content->id,array("id"=>'savecontent','name'=>'savecontent')); 
    echo Form::hidden('csrf', Security::token());

 ?>
<aside id="access-control">
   

<?php if( (isset($_COOKIE['ckfinder_baseURL']) && $_COOKIE['ckfinder_baseURL'] != "") || $content->page->required_role != "" ){?>
    <span title="Images and document uploaded into the file manager in 'secure mode' are stored in a secured folder and can only be viewed by users logged in with access to this page." class="yb-tooltip">
    <input onChange="set_ckfinder_baseURL()"  type="checkbox" id="securefolder" value="<?=$content->page->required_role ?>"> <label for="securefolder">Launch File Manager in secure mode</label>
    </span>
<?php } ?>
</aside>

<?php 

echo Form::textarea("original_content",$content->content,array("style"=>"display:none;","id"=>"original_content"));
 echo Form::hidden("blocktype",$block->input_type,array("id"=>"blocktype"));
 echo Form::hidden("edit_time",date("Y-m-d H:i:s"),array('id'=>'edit_time'));
 echo Form::hidden("page_id",$content->page_id,array("id"=>"page_id"));
 echo Form::hidden("block_id",$content->block_id,array("id"=>"block_id"));
 echo Form::hidden("version_id",$content->version_id,array("id"=>"version_id"));
 echo Form::hidden("live",$content->live);
 echo Form::hidden("publish",0,array("id"=>"publish"));

 
 switch($block->input_type){
     case "multifield":
            $content_array = json_decode( $content->content, true ); //convert to Array for use by included file
            $input = json_decode( $block->input_parameters, true ); // convert to Array
            foreach ($input as $row)
            {
		$key_index = key($row);
		$fieldlist[] = $key_index;
		if( count($content_array) > 0 )
		{
			$input = preg_replace('@{content}@',preg_replace_quote($content_array[$key_index]),$row[$key_index]['input']);
		}else{
			$input = preg_replace('@{content}@','',$row[$key_index]['input']);
		}
                	
                echo "<div class='form-group'>";
                echo "<label>".$row[$key_index]['label']."</label>";
                echo $input;
                echo "</div>";
            }  //end foreach
            echo Form::hidden("serialze_fields",implode(",",$fieldlist));
            echo Form::hidden("content","");
         break;
     case "customform":
            $content_array = json_decode( $content->content, true ); //convert to Array for use by included file
            include_once $_SERVER['DOCUMENT_ROOT'].'/application/views/'. ltrim($block->input_parameters,"/");
            echo Form::hidden("content","");
         break;
     case "module":
         echo $block->input_parameters;
         $input = $block->input_parameters;
       //  print_r(Kohana::modules());exit;
         //echo "INPUT: ".var_dump($input);exit;
         $mod= new $input();
        // $mod->load();
         
         break;
     default:
         //echo "BLOCK: ".$block->input_parameters;
            echo preg_replace('@{content}@',preg_replace_quote($content->content),$block->input_parameters);
         break;
 }
 echo Form::close();
?>



<section id="edit-revision-info-wrap">

<?php
	// this is the new line 194
	$user_name = "Unknown User";
	if(is_numeric($content->updated_by ) && $content->updated_by != 0)
	{
		$updated_by_user = ORM::factory('User',$content->updated_by);
		$user_name = $updated_by_user->first." ".$updated_by_user->last;
	}
	
                //$published_by_name = "Unknown User";
	if(!is_null($content->published_by) && $content->published_by != 0){
           // echo "UPDATE BY: ".$content->updated_by;
		$published_by_user = ORM::factory('User',$content->published_by);
		$user_name = $published_by_user->first." ".$published_by_user->last;
	}
	
?>

    <aside class="align-left">
    	<strong>Status:</strong>
            <?php if(is_null($content->publish_date) || $content->publish_date == "0000-00-00 00:00:00"){ 
                $title_text = "This content has not been published.";
                $text = "Unpublished Draft";
                $revision_class = "orange";
            } else {
                switch($content->live){
                    case 1:
                        $title_text = "This content is currently being shown on the website.";
                        $text = "Live Revision";
                        $revision_class = "green";
                    break;
                    case 0:
                        $title_text = "This revision was previously live on the website.";
                        $text = "Previously Published";
                        $revision_class = "red";
                    break;

                }
            } ?>
        <span class="yb-tooltip-html yb-revision-status <?=$revision_class?>" title="<?=$title_text ?>"><?=$text ?></span>
        &nbsp; <strong>Saved:</strong> <?=date("F j, Y h:ia",strtotime($content->revision_date)) ?> &nbsp; <strong>By:</strong> <?=$user_name; ?>
        <br >
	<?php if(!isset($old_revision)){ ?>
           <a href="" onclick="$(this).css({display:'none'}); $('#revision_list').fadeIn('fast'); return false">Show Save History</a>
	<?php } ?>
    
	<span id="revision_list" <?php if(!isset($old_revision)) { echo 'style="display: none;"'; } ?>>
   	<strong>History:</strong>&nbsp;&nbsp;
   	<select name="revisionList" id="revisionList" onchange="window.location = '/admin/edit/'+this.options[this.selectedIndex].value;">
        <?php foreach($revisions as $revision){ ?>
            <?php 
         //  var_dump($revision->publish_date); 
            ?>
            <option value="<?=$revision->id ?>"<?php if($content->id == $revision->id){ echo " SELECTED"; } ?>>
                <?php echo date("M d, Y g:ia",strtotime($revision->revision_date)); 
               // echo "PUB :".var_dump($revision->publish_date);
				if((int)$revision->live === 1 ){ echo " - LIVE";  }
				elseif($revision->publish_date !== "0000-00-00 00:00:00" && !is_null($revision->publish_date)  && (int)$revision->live === 0 ){ echo " *"; } ?></option>
<?php } ?>
   		</select> 
	</span>
</aside>

 
<aside class="align-right">

</aside> 

 </section>


<?php $page = ORM::factory("Content",$content->id)->page;?>

<input type="hidden" name="serialze_fields" value="page_id,title,caption,link,image">
<script type="text/javascript">
$('#caption').ckeditor(function() 
 { CKFinder.setupCKEditor(this,'/ckfinder/') }, 
	{ allowedContent: true, 
	  resize_enabled : true, 
	  height : '350px', 
	  toolbar : 'CMSbasic' //these are set in CKeditor/config.js 
	} 
); 
$(function(){
    
    $('#publishBTN,#save').removeAttr('id');//REMOVE THE ID OF THE PUBLISH/SAVE BUTTON TO PREVENT SUBMISSION OF VALIDATION ERROR
    
    
    $('article#editor').on('click','.publish-block,.save-block',function(event){
//alert("SHSHHS");

           // var totalItems = $('#sortable').find('.link_field').not('.clone_field').length;
            var errors  ="";
            var title = $('#title').val();
            if(title === ""){
                var msg ="The Title field is required.";
                errors += msg+"<br />";
                $('#title').closest('.form-group').addClass('has-error').find('.message').text("* "+msg);
            } else {
                $('#title').closest('.form-group').addClass('has-success').find('.message').text("* ");
            }
            var cap = $('#caption').val();
            if(cap === ""){
                var msg= "The Caption field is required.";
                errors += msg+"<br />";
                $('#caption').closest('.form-group').addClass('has-error').find('.message').text("* "+msg);
            } else{
                $('#caption').closest('.form-group').addClass('has-success').find('.message').text("* ");
            }
            var link = $('#link').val();
            if(link === ""){
                var msg = "The Link field is required.";
                errors += msg+"<br />";
                $('#link').closest('.form-group').addClass('has-error').find('.message').text("* "+msg);
                
            } else {
                 $('#link').closest('.form-group').addClass('has-success').find('.message').text("* ");
            }
             var fileURL  =$('#preview-image-field').val();
             if(fileURL === ""){
                var msg = "The Image field is required.";
                errors += msg+"<br />";
                $('#preview-image-field').closest('.form-group').addClass('has-error').find('.message').text("* "+msg);
            } else {
                $('#preview-image-field').closest('.form-group').addClass('has-success').find('.message').text("* ");
            }
             
             if(errors !== ""){
                   uiAlert(errors);
                   return false;
              }else {
                  // console.log($(this).attr('class'));
                  var pub = 0;
                  if($(this).hasClass('publish-block')){
                      pub = 1;
                     
                  } 
                 // alert(pub);
                 
                    $('#publish').val(pub);
                   ybeditor.ckeditor();
                   ybeditor.submit();
                   //return true;
              }
            

        });
    
});
        


</script>
<div class="row" id="form-content">
   <?php
  // print_r($content_array);
   ?>
    <div class="col-xs-12">
        <h4>Specials</h4>
            <div id="special-form">
             <?=Form::hidden("page_id",$page->id);?>
                <div class='form-group'>
                <?=Form::label('title',"Title <span class='message'>*</span>");?>
                <?=Form::input("title",$content_array['title'],["id"=>"title",'class'=>'form-control','required']);?>
                </div>
                 <div class='form-group'>
                    <?=Form::label('image',"Image <span class='message'>*</span>");?>
                    
                    <div class="clearfix">
                        <a href="#" id="addImage" class="btn btn-default image_rotator btn btn-default" data-startup-path="Images:/homepage/specials/">&nbsp; + &nbsp; Add an Image... &nbsp;</a>
                        <input name="image" type="hidden" value="<?=$content_array['image'];?>" id="preview-image-field"  />
                        <img src="<?=$content_array['image'];?>" alt="<?=$content_array['title'];?>"   id="preview-image"/>
                    </div>
                 
                </div>
                <div class='form-group link-selector'>
                    <?=Form::label('link',"URL");?>
                    <div class="input-group">
                    <?=Form::input("link",$content_array['link'],['class'=>'link form-control col-xs-7',"id"=>'link']);?>
                        <span class="input-group-btn">
                            <a href="#" id="clear-link"class="btn btn-default">&times;</a>
                        </span>    
                    </div>
                    <?=Form::hidden('destination_f',NULL,['class'=>'destination']);?>
                    <?=Form::select("select-destination", NULL,NULL , array("class"=>'hide form-control select-destination'));?>
                    <a  href="#" class="page_select btn btn-default">Select Page</a>
                    <span class="hide destination_control">
                       <a href="#" class="page_cancel btn btn-default">Cancel</a>
                       <a href="#" class="page_accept btn btn-default">Accept</a>
                    </span>
                </div>
                <div class='form-group'>
                <?=Form::label('caption',"Caption <span class='message'>*</span>");?>
                <?=Form::textarea("caption",$content_array['caption'],["id"=>"caption",'class'=>'form-control ckeditor_basic','required',"maxlength"=>"300"]);?>
                </div>

            </div>
    </div>
</div><!-- /.row -->

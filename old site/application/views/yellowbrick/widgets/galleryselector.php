

<?php $page = ORM::factory("Content",$content->id)->page;?>

<input type="hidden" name="serialze_fields" value="page_id,gallery">
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
    
     $(".chosen-single").chosen({
                allow_single_deselect: true,
                width: "95%"
            });
    
//    
//    $('#publishBTN,#save').removeAttr('id');//REMOVE THE ID OF THE PUBLISH/SAVE BUTTON TO PREVENT SUBMISSION OF VALIDATION ERROR
//    
//    
//    $('article#editor').on('click','.publish-block,.save-block',function(event){
////alert("SHSHHS");
//
//           // var totalItems = $('#sortable').find('.link_field').not('.clone_field').length;
//            var errors  ="";
//            var title = $('#title').val();
//            if(title === ""){
//                var msg ="The Title field is required.";
//                errors += msg+"<br />";
//                $('#title').closest('.form-group').addClass('has-error').find('.message').text("* "+msg);
//            } else {
//                $('#title').closest('.form-group').addClass('has-success').find('.message').text("* ");
//            }
//            var cap = $('#caption').val();
//            if(cap === ""){
//                var msg= "The Caption field is required.";
//                errors += msg+"<br />";
//                $('#caption').closest('.form-group').addClass('has-error').find('.message').text("* "+msg);
//            } else{
//                $('#caption').closest('.form-group').addClass('has-success').find('.message').text("* ");
//            }
//            var link = $('#link').val();
//            if(link === ""){
//                var msg = "The Link field is required.";
//                errors += msg+"<br />";
//                $('#link').closest('.form-group').addClass('has-error').find('.message').text("* "+msg);
//                
//            } else {
//                 $('#link').closest('.form-group').addClass('has-success').find('.message').text("* ");
//            }
//             var fileURL  =$('#preview-image-field').val();
//             if(fileURL === ""){
//                var msg = "The Image field is required.";
//                errors += msg+"<br />";
//                $('#preview-image-field').closest('.form-group').addClass('has-error').find('.message').text("* "+msg);
//            } else {
//                $('#preview-image-field').closest('.form-group').addClass('has-success').find('.message').text("* ");
//            }
//             
//             if(errors !== ""){
//                   uiAlert(errors);
//                   return false;
//              }else {
//                  // console.log($(this).attr('class'));
//                  var pub = 0;
//                  if($(this).hasClass('publish-block')){
//                      pub = 1;
//                     
//                  } 
//                 
//                
//                   ybeditor.ckeditor();
//                   ybeditor.submit(pub);
//                   //return true;
//              }
//            
//
//        });
    
});
        


</script>
<div class="row" id="form-content">
   <?php
  // print_r($content_array);
   $gallery_list = [];
   $template = ORM::factory("Template")->where("name","=","Gallery")->find();
   $pages = ORM::factory("Page")->where("template_id","=",$template->id)->find_all();
   $gallery_list[] ="";
   foreach($pages as $p){
       $gallery_list[$p->id] = $p->label;
   }
   ?>
    <div class="col-xs-12">
        <h4>Gallery Selector</h4>
            <div id="galleryselector-form">
             <?=Form::hidden("page_id",$page->id);?>
                <div class='form-group'>
                <?=Form::label('gallery',"Gallery <span class='message'>*</span>");?>
                <?=Form::select("gallery",$gallery_list,$content_array['gallery'],["id"=>"gallery",'class'=>'form-control chosen-single','data-placeholder'=>'Select a Gallery', 'required']);?>
                </div>
              

            </div>
    </div>
</div><!-- /.row -->

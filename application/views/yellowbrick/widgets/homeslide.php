

<?php $page = ORM::factory("Content",$content->id)->page;?>

<input type="hidden" name="serialze_fields" value="page_id,image">

<div class="row" id="form-content">
    <div class="col-xs-7">
        <?php
      //  echo nccc::getSliderImagebypageID($page->id);
       // print_r($content_array);exit;
      
        echo doubletree::Slider($content_array);
//        <iframe width="854" height="480" src="https://www.youtube.com/embed/a3uN8O9bTcc" frameborder="0" allowfullscreen></iframe>
        ?>
    </div>
    <div class="col-xs-5">
        <h4>Create Slide</h4>
            <div id="slide-form">
             <?=Form::hidden("page_id[]",$page->id);?>
                 <?=Form::hidden('csrf', Security::token());?>
                 <div class='form-group'>
                    <?=Form::label('image_f',"Image <span class='message'>*</span>");?>
                    
                    <div class="clearfix">
                        <a href="#" id="addImage" class="btn btn-default image_rotator btn btn-default" data-startup-path="Images:/homepage/sliders/">&nbsp; + &nbsp; Add an Image... &nbsp;</a>
                        <input name="image_f" type="hidden" value="" id="preview-image-field"  />
                        <img src="" alt=""  id="preview-image"/>
                    </div>
                 
                </div>
                <div class="form-group col-xs-12">
                    <a href="#" id="save-slide" class="btn btn-primary">Create Slide</a>
                </div>

            </div>
    </div>
</div><!-- /.row -->

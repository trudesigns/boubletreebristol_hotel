


<?php $page = ORM::factory("Content",$content->id)->page;?>

<input type="hidden" name="serialze_fields" value="page_id,title,link,image">

<div class="row" id="form-content">
    <div class="col-xs-7">
        <?=doubletree::Squares($content_array);?>
    </div>
    <div class="col-xs-5">
        <h4>Create new Square</h4>
            <div id="square-form">
             <?=Form::hidden("page_id_f",$page->id);?>
                <div class='form-group'>
                <?=Form::label('title_f',"Title <span class='message'>*</span>");?>
                <?=Form::input("title_f",NULL,["id"=>"title",'class'=>'form-control','required']);?>
                </div>
                 <div class='form-group'>
                    <?=Form::label('image_f',"Image <span class='message'>*</span>");?>
                    
                    <div class="clearfix">
                        <a href="#" id="addImage" class="btn btn-default image_rotator btn btn-default" data-startup-path="Images:/properties/squares/">&nbsp; + &nbsp; Add an Image... &nbsp;</a>
                        <input name="image_f" type="hidden" value="" id="preview-image-field"  />
                        <img src="" alt=""  width="250" id="preview-image"/>
                    </div>
                 
                </div>
                <div class='form-group link-selector'>
                    <?=Form::label('link_f',"URL <span class='message'>*</span>");?>
                    <div class="input-group">
                    <?=Form::input("link_f",NULL,['class'=>'link form-control col-xs-7',"id"=>'link']);?>
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
                    <a href='#' id="linkTofile" class="file_browser btn btn-default" data-callback="#link">Select File</a>
                </div>
                <div class="form-group col-xs-12">
                    <a href="#" id="save-square" class="btn btn-default">Create</a>
                </div>

            </div>
    </div>
</div><!-- /.row -->

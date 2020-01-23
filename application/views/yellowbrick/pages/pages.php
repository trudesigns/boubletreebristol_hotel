<article id="pages" class="list">

<h1>Pages</h1>
<aside id="actions">
    <a href="#" id="addrootpage" title="Create a new top-level page" class="yb-button align-left"><span class="ui-icon ui-icon-plusthick"></span>New Top-Level Page</a>
    <a href="#" id="save_item" class="yb-tooltip btn btn-info hide" title="Save to the DB the changes.">Save</a>
    <a href="#" id="cancel_item" class="yb-tooltip btn btn-info hide" title="Cancel changes made.">Cancel</a>
    <a href="#" class="toggleAll yb-button align-right last" data-action="expand-all">Expand All</a>
    <a href="#" class="toggleAll yb-button align-right" data-action="collapse-all">Collapse All</a>
</aside>


<div id="nestable" class="nestable">
    
    <?=ybr::loadChildrenPage($pages,$templates,$groups);?>

</div>

<div class="modal fade page-properties-modal" id="page-prop">
                <div class="modal-dialog">
                <div class="modal-content ">
                     <?=Form::open("#",array("novalidate","id"=>"form-page-prop")); ?>
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title clearfix">Page Properties</h4>
                  </div>
                  <div class="modal-body clearfix">
                    
                        <?=Form::hidden('csrf', Security::token());?>
                         <?=Form::hidden('editid', NULL);?>
                      <?=Form::hidden('parentid', NULL);?>
    
                    <div class="form-group col-xs-12">
                        <?=Form::label('label',"Page Label <span class='message'>* </span>");?>
                        <?=Form::input("label",NULL,array('class'=>'form-control','required'));?>
                    </div>
                    <div class="form-group col-xs-12 ">
                        <?=Form::label('slug',"URL Slug <span class='message'>* </span>");?>
                        <?=Form::input("slug",NULL,array('class'=>'form-control verify-slug','required'));?>
                        <span id="slug_icon"></span>
                    </div>
                    <div class="form-group col-xs-9 ">
                        <?=Form::label('template',"Template <span class='message'>* </span>");?>
                        <?=Form::select("template", $templates,NULL ,array("class"=>'form-control chosen-single','required',"id"=>'template_id'));?>
                    </div>
                      <div class="form-group col-xs-3 ">
                        <?=Form::label('status',"Status <span class='message'>* </span>",array("class"=>"yb-tooltip", "title"=>"Active: display page on site and in navigation. Inactive: Page will return a '404 page not found' Error"));?>
                        <?=Form::select("status", array("1"=>"Active","0"=>"Inactive"),NULL, array("class"=>'form-control chosen-single','required',"id"=>"status_id"));?>
                    </div>
                      <div class="form-group col-xs-6 ">
                        <?=Form::label('startDate',"Start Date",array("class"=>"yb-tooltip", "title"=>"Optional future date for page to become available."));?>
                        <?=Form::input("startDate",NULL, array("class"=>'form-control datepicker-time'));?>
                    </div>
                      <div class="form-group col-xs-6 ">
                        <?=Form::label('endingDate',"End Date",array("class"=>"yb-tooltip", "title"=>"Leave blank to keep page live on site indefinitely."));?>
                        <?=Form::input("endingDate",NULL, array("class"=>'form-control datepicker-time' ));?>
                    </div>
                      <div class="form-group col-xs-4 ">
                        <?=Form::label('sitemap',"Sitemap",array("class"=>"yb-tooltip", "title"=>"Display this page in the site map."));?>
                        <?=Form::checkbox("sitemap",NULL, array("class"=>'form-control'));?>
                    </div>
                      <div class="form-group col-xs-4 ">
                        <?=Form::label('searchable',"Searchable",array("class"=>"yb-tooltip", "title"=>"Searchable (include this page when using site's custom search feature) Note that search engines will still index any public page regardless of this setting."));?>
                        <?=Form::checkbox("searchable",NULL, array("class"=>'form-control'));?>
                    </div>
                      <?php 
                        $disabled = "disabled";
                       // print_r($accessroles);
                        if(in_array('developer',$accessroles)){
                             $disabled = "";
                        }?>
                      <div class="form-group col-xs-4 <?=$disabled;?> ">
                        <?=Form::label('lock',"Lock Subpage",array("class"=>"yb-tooltip", "title"=>"only Developers can add child pages"));?>
                        <?=Form::checkbox("lock",NULL, array("class"=>'form-control'));?>
                    </div>
                   
                      <div class="form-group col-xs-9 ">
                        <?=Form::label('groups[]',"Log in as");?>
                        <?=Form::select("groups[]", $groups,NULL, array("class"=>'form-control chosen-multi','multiple','id'=>"groups_id"));?>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <?= Form::submit("submit","Save",array('class'=>'btn btn-primary',"id"=>"save-prop"));?>
                  </div>
                    <?=Form::close(); ?>
                </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
    
<div class="modal fade page-move-modal" id="page-move">
       <div class="modal-dialog">
           <div class="modal-content">
                 <?=Form::open("#",array("novalidate","id"=>"form-page-move")); ?>
               <?=Form::hidden('csrf', Security::token());?>
               <?=Form::hidden('editid', NULL);?>
             <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
               <h4 class="modal-title">Page Move: <span class="move-name"></span></h4>
             </div>
             <div class="modal-body clearfix">
                 <div class="col-xs-12">
                   <label for="dd-pages">New Parent:</label>
                   <select id="dd-pages" name="parent" class="form-control"></select>
                 </div>
                 <div class="col-xs-12">
                   <label for="dd-sibblings">Order Below:</label>
                   <select id="dd-siblings" name="sibling" class="form-control"></select>
                 </div>
             </div>
             <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               <button type="button" class="btn btn-primary" id="save-move">Save</button>
             </div>
               <?=Form::close(); ?>
           </div><!-- /.modal-content -->
       </div><!-- /.modal-dialog -->
   </div><!-- /.modal -->

</article><!-- end "app-wrapper" -->
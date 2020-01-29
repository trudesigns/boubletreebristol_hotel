<?php defined('SYSPATH') OR die('No direct script access.');


class doubletree extends Security{
    
    
    /**
     * Using the current page url will return the root slug;
     * @return string the root slug
     */
    public static function getRootSlugfromURL()
    {
        //echo "HSRERE";
        $qs = Request::current()->url().URL::query();
        $ex = explode("/",$qs);
        return $ex[1];
        //echo $qs;
    }
    
    
    public static function Square($call)
    {
        $out ="";

     // print_r($call);
      //exit;
        $cnt = 0;
        if(isset($call['title']) && $call['title'] != ""){
            $cnt = count($call['title']);
        }
            $title = "";
            $img = "";

            $link = "";
            $pageid  = "";
            for($i=0;$i<$cnt;$i++){
                //echo "TINES: ".$i;
                if(isset($call['title'][$i]))$title = $call['title'][$i];
                if(isset($call['link'][$i]))$link = $call['link'][$i];
  
                if(isset($call['page_id'][$i]))$pageid = $call['page_id'][$i];
                if(isset($call['image'][$i]))$img = $call['image'][$i];
                        
                $out .= "<li class='callout col-xs-12'>";
                // $out .= "<a href='#' class='btn btn-xs btn-default remove-item yb-tooltip' title='This button will delete the item from this list'><span class='glyphicon glyphicon-remove'>&nbsp;</span></a>";
                 $out .= Form::hidden('page_id[]',$pageid);    
                 $out .="<div class='col-xs-6'>";
                $out .= "<img src='".$img."'  alt='".$title."' class='preview-image img-responsive'  />";
                $out .= "<input name='image[]' type='hidden' value='".$img."' class='image_field clone_field'  />";
                $out .="</div>";
    
                $out .="<div class='callout-content col-xs-6'>";
                $out .= "<div class='row action'>";
                $out .= "<a hre='#' class='btn btn-default draggable yb-tooltip pull-right seohide' title='This button allows you to reorder the items on this slider'><span class='glyphicon glyphicon-move'>&nbsp;</span></a> ";
                $out .= "<a href='#' class='btn btn-xs btn-default remove-item yb-tooltip pull-left' title='This button will delete the item from this list'><span class='glyphicon glyphicon-remove'>&nbsp;</span></a>";
                $out .="</div>";
                //TITLE
                $out .= "<div class='row'>";
                $out .= "<div class='input-group'>";
                $out .= "<span class='input-group-addon'>";
                $out .= "Title";
                $out .="</span>";
                $out .= "<input class='form-control clone_field title_field' name='title[]' type='text' value='".$title."' maxlength='45' />";
                $out .= "</div>";
                //LINK
                $out .= "<div class='link-selector'>";
                $out .= "<div class='input-group'>";
                $out .= "<span class='input-group-addon'>";
                $out .= "Link";
                $out .="</span>";
                $out .= Form::hidden('destination',NULL,['class'=>'destination']);
                $out .= "<input class='form-control  link' name='link[]' type='text' value='".$link."' maxlength='255' placeholder='http://www.doubletreebristol.com' />";
                $out .= Form::select("select-destination", NULL,NULL , array("class"=>'hide form-control select-destination'));
                $out .= "<span class='input-group-btn'>";
      
                $out .= "<a href='#'  class='page_select btn btn-default yb-tooltip' title='This button will let you select a page from this site.'>Select Page</a>";
                $out .="</span>";
                $out .= "</div>";
                $out .="<div class='hide destination_control'>";
                $out .= "<a href='#' class='page_cancel btn btn-default'>Cancel</a>";
                $out .= "<a  href='#' class='page_accept btn btn-default'>Accept</a>";
                $out  .= "</div>";
                $out  .= "</div><!-- /.link-selector -->";  
         
                $out .= "</div><!-- /.row -->";
                $out .= "</div><!-- /.callout-content -->";
                $out .="</li>";
            }
     //  }
             // $out .= "</a>";
        return $out;
    }
    
    public static function Squares($call)
    {
        $out = "<ul id='sortable' class='callouts-content'>";
        //CLONE
         $out .= "<li class='hide clone col-xs-12'>";


        $out .= Form::hidden('page_id_clone');    
        
        $out .="<div class='col-xs-6'>";
        $out .= "<img src=''  alt='' class='preview-image img-responsive'  />";
        $out .= "<input name='image_clone[]' type='hidden' value='' class='image_field clone_field'  />";
        $out .="</div>";
        
        $out .="<div class='square-content col-xs-6'>";
        $out .= "<div class='row action'>";
        $out .= "<a hre='#' class='btn btn-default draggable yb-tooltip pull-right seohide' title='This button allows you to reorder the items on this slider'><span class='glyphicon glyphicon-move'>&nbsp;</span></a> ";
        $out .= "<a href='#' class='btn btn-xs btn-default remove-item yb-tooltip pull-left' title='This button will delete the item from this list'><span class='glyphicon glyphicon-remove'>&nbsp;</span></a>";
        $out .="</div>";
        $out .= "<div class='row'>";
        //TITLE
        $out .= "<div class='input-group'>";
        $out .= "<span class='input-group-addon'>";
        $out .= "Title";
        $out .="</span>";
        $out .= "<input class='form-control clone_field title_field' name='title_clone' type='text' value='' maxlength='45' />";
        $out .= "</div>";
        //LINK
        $out .= "<div class='link-selector'>";
        $out .= "<div class='input-group'>";
        $out .= "<span class='input-group-addon'>";
        $out .= "Link";
        $out .="</span>";
        $out .= Form::hidden('destination',NULL,['class'=>'destination']);
        $out .= "<input class='form-control clone_field link_field' name='clone_field' type='text' value='' maxlength='255' placeholder='http://www.doubletreebristol.com'  />";
        $out .= Form::select("select-destination", NULL, NULL, array("class" => 'hide form-control select-destination'));
        $out .= "<span class='input-group-btn'>";
        $out .= "<a href='#'  class='page_select btn btn-default yb-tooltip' title='This button will let you select a page from this site.'>Select Page</a>";
        $out .="</span>";
        $out .= "</div>";
         $out .="<div class='hide destination_control'>";
        $out .= "<a href='#' class='page_cancel btn btn-default'>Cancel</a>";
        $out .= "<a  href='#' class='page_accept btn btn-default'>Accept</a>";
       $out  .= "</div>";
       $out  .= "</div><!-- /.link-selector -->";     
       

        $out .= "</div><!-- /.row -->";
         $out .= "</div><!-- /.callout-content -->";
        $out .= "</li>";        
   
        $out .= self::Square($call);
        $out .= "</ul><!-- /.callouts-content -->";
        return $out;
    }

    
    public static function Callout($call)
    {
        $out ="";

     // print_r($call);
      //exit;
        $cnt = 0;
        if(isset($call['title']) && $call['title'] != ""){
            $cnt = count($call['title']);
        }
            $title = "";
            $img = "";
            $cap = "";
            $link = "";
            $pageid  = "";
            for($i=0;$i<$cnt;$i++){
                //echo "TINES: ".$i;
                if(isset($call['title'][$i]))$title = $call['title'][$i];
                if(isset($call['link'][$i]))$link = $call['link'][$i];
                if(isset($call['caption'][$i]))$cap = $call['caption'][$i];
                if(isset($call['page_id'][$i]))$pageid = $call['page_id'][$i];
                if(isset($call['image'][$i]))$img = $call['image'][$i];
                        
                $out .= "<li class='callout col-xs-12'>";
                // $out .= "<a href='#' class='btn btn-xs btn-default remove-item yb-tooltip' title='This button will delete the item from this list'><span class='glyphicon glyphicon-remove'>&nbsp;</span></a>";
                 $out .= Form::hidden('page_id[]',$pageid);    
                 $out .="<div class='col-xs-6'>";
                $out .= "<img src='".$img."'  alt='".$title."' class='preview-image img-responsive'  />";
                $out .= "<input name='image[]' type='hidden' value='".$img."' class='image_field clone_field'  />";
                $out .="</div>";
    
                $out .="<div class='callout-content col-xs-6'>";
                $out .= "<div class='row action'>";
                $out .= "<a hre='#' class='btn btn-default draggable yb-tooltip pull-right seohide' title='This button allows you to reorder the items on this slider'><span class='glyphicon glyphicon-move'>&nbsp;</span></a> ";
                $out .= "<a href='#' class='btn btn-xs btn-default remove-item yb-tooltip pull-left' title='This button will delete the item from this list'><span class='glyphicon glyphicon-remove'>&nbsp;</span></a>";
                $out .="</div>";
                //TITLE
                $out .= "<div class='row'>";
                $out .= "<div class='input-group'>";
                $out .= "<span class='input-group-addon'>";
                $out .= "Title";
                $out .="</span>";
                $out .= "<input class='form-control clone_field title_field' name='title[]' type='text' value='".$title."' maxlength='45' />";
                $out .= "</div>";
                //LINK
                $out .= "<div class='link-selector'>";
                $out .= "<div class='input-group'>";
                $out .= "<span class='input-group-addon'>";
                $out .= "Link";
                $out .="</span>";
                $out .= Form::hidden('destination',NULL,['class'=>'destination']);
                $out .= "<input class='form-control  link' name='link[]' type='text' value='".$link."' maxlength='255' placeholder='http://www.doubletreebristol.com' />";
                $out .= Form::select("select-destination", NULL,NULL , array("class"=>'hide form-control select-destination'));
                $out .= "<span class='input-group-btn'>";
      
                $out .= "<a href='#'  class='page_select btn btn-default yb-tooltip' title='This button will let you select a page from this site.'>Select Page</a>";
                $out .="</span>";
                $out .= "</div>";
                $out .="<div class='hide destination_control'>";
                $out .= "<a href='#' class='page_cancel btn btn-default'>Cancel</a>";
                $out .= "<a  href='#' class='page_accept btn btn-default'>Accept</a>";
                $out  .= "</div>";
                $out  .= "</div><!-- /.link-selector -->";  
                //CAPTION
                $out .= "<div class='input-group'>";
                $out .= "<span class='input-group-addon'>";
                $out .= "Caption";
                $out .="</span>";
                $out .= "<textarea class='form-control caption_field clone_field' name='caption[]'   maxlength='300' >".$cap."</textarea>";
                $out .= "</div>";
                $out .= "</div><!-- /.row -->";
                $out .= "</div><!-- /.callout-content -->";
                $out .="</li>";
            }
     //  }
             // $out .= "</a>";
        return $out;
    }
    
    public static function Callouts($call)
    {
        $out = "<ul id='sortable' class='callouts-content'>";
        //CLONE
         $out .= "<li class='hide clone col-xs-12'>";


        $out .= Form::hidden('page_id_clone');    
        
        $out .="<div class='col-xs-6'>";
        $out .= "<img src=''  alt='' class='preview-image img-responsive'  />";
        $out .= "<input name='image_clone[]' type='hidden' value='' class='image_field clone_field'  />";
        $out .="</div>";
        
        $out .="<div class='callout-content col-xs-6'>";
        $out .= "<div class='row action'>";
        $out .= "<a hre='#' class='btn btn-default draggable yb-tooltip pull-right seohide' title='This button allows you to reorder the items on this slider'><span class='glyphicon glyphicon-move'>&nbsp;</span></a> ";
        $out .= "<a href='#' class='btn btn-xs btn-default remove-item yb-tooltip pull-left' title='This button will delete the item from this list'><span class='glyphicon glyphicon-remove'>&nbsp;</span></a>";
        $out .="</div>";
        $out .= "<div class='row'>";
        //TITLE
        $out .= "<div class='input-group'>";
        $out .= "<span class='input-group-addon'>";
        $out .= "Title";
        $out .="</span>";
        $out .= "<input class='form-control clone_field title_field' name='title_clone' type='text' value='' maxlength='45' />";
        $out .= "</div>";
        //LINK
        $out .= "<div class='link-selector'>";
        $out .= "<div class='input-group'>";
        $out .= "<span class='input-group-addon'>";
        $out .= "Link";
        $out .="</span>";
        $out .= Form::hidden('destination',NULL,['class'=>'destination']);
        $out .= "<input class='form-control clone_field link_field' name='clone_field' type='text' value='' maxlength='255' placeholder='http://www.doubletreebristol.com'  />";
        $out .= Form::select("select-destination", NULL, NULL, array("class" => 'hide form-control select-destination'));
        $out .= "<span class='input-group-btn'>";
        $out .= "<a href='#'  class='page_select btn btn-default yb-tooltip' title='This button will let you select a page from this site.'>Select Page</a>";
        $out .="</span>";
        $out .= "</div>";
         $out .="<div class='hide destination_control'>";
        $out .= "<a href='#' class='page_cancel btn btn-default'>Cancel</a>";
        $out .= "<a  href='#' class='page_accept btn btn-default'>Accept</a>";
       $out  .= "</div>";
       $out  .= "</div><!-- /.link-selector -->";     
       
       //CAPTION
        $out .= "<div class='input-group'>";
        $out .= "<span class='input-group-addon'>";
        $out .= "Caption";
        $out .="</span>";
        $out .= "<textarea class='form-control clone_field caption_field' name='caption_clone'  maxlength='300'></textarea>";
        $out .= "</div>";
        $out .= "</div><!-- /.row -->";
         $out .= "</div><!-- /.callout-content -->";
        $out .= "</li>";        
   
        $out .= self::Callout($call);
        $out .= "</ul><!-- /.callouts-content -->";
        return $out;
    }
    
    public static function Slide($slide)
    {
        $out ="";

     //  print_r($slide);
        $cnt = 0;
        if(isset($slide['image']) && $slide['image'] != ""){
            $cnt = count($slide['image']);
        }
      //  echo "CNT: ".$cnt;
     //   $slide['image'] 
      // if($slide['image'] !=""){//no blank entry
            $image = "";
            $link = "";
            $pageid  = "";
            for($i=0;$i<$cnt;$i++){
                //echo "TINES: ".$i;
                if(isset($slide['image'][$i]))$image = $slide['image'][$i];
                if(isset($slide['page_id'][$i]))$pageid = $slide['page_id'][$i];
       
                
                $out .= "<li class='slide col-xs-12'>";
                 
                $out .= Form::hidden('page_id[]',$pageid);  
                
                
      
                
                $out .="<div class='slide-content'>";
                $out .= "<div class='row action'>";
                $out .="<div class='col-xs-12'>";
               $out .= "<a hre='#' class='btn btn-default draggable yb-tooltip pull-right seohide' title='This button allows you to reorder the items on this slider'><span class='glyphicon glyphicon-move'>&nbsp;</span></a> ";
               $out .= "<a href='#' class='btn btn-xs btn-default remove-item yb-tooltip pull-left' title='This button will delete the item from this list'><span class='glyphicon glyphicon-remove'>&nbsp;</span></a>";
               $out .="</div>";
               $out .="</div>";
               $out .= "<div class='row'>";
         $out .="<div class='col-xs-12'>";
       $out .= "<img src='".$image."'  alt='' class='preview-image img-responsive'  />";
                $out .= "<input name='image[]' type='hidden' value='".$image."' class='image_field clone_field'  />";
        $out .="</div>";
       $out .="</div>";
                
                $out .= "</div><!-- /.slide-content -->";
                $out .="</li>";
            }
     //  }
             // $out .= "</a>";
        return $out;
    }
    
    public static function Slider($slides)
    {
        $out = "<ul id='sortable' class='slider-content'>";
        //CLONE
         $out .= "<li class='hide clone col-xs-12'>";
         
        $out .= Form::hidden('page_id_clone');    



        
        $out .="<div class='slide-content'>";
         $out .= "<div class='row action'>";
         $out .="<div class='col-xs-12'>";
        $out .= "<a hre='#' class='btn btn-default draggable yb-tooltip pull-right seohide' title='This button allows you to reorder the items on this slider'><span class='glyphicon glyphicon-move'>&nbsp;</span></a> ";
        $out .= "<a href='#' class='btn btn-xs btn-default remove-item yb-tooltip pull-left' title='This button will delete the item from this list'><span class='glyphicon glyphicon-remove'>&nbsp;</span></a>";
        $out .="</div>";
        $out .="</div>";
        $out .= "<div class='row'>";
         $out .="<div class='col-xs-12'>";
        $out .= "<img src=''  alt='' class='preview-image img-responsive'  />";
        $out .= "<input name='image_clone[]' type='hidden' value='' class='image_field clone_field'  />";
        $out .="</div>";
       $out .="</div>";
        
         $out .= "</div><!-- /.slide-content -->";
        $out .= "</li>";        
   
        $out .= self::Slide($slides);
        $out .= "</ul><!-- /.slider-content -->";
        return $out;
    }
    public static function GalleryItem($slide)
    {
        $out ="";

     //  print_r($slide);
        $cnt = 0;
        if(isset($slide['image']) && $slide['image'] != ""){
            $cnt = count($slide['image']);
        }
      //  echo "CNT: ".$cnt;
     //   $slide['image'] 
      // if($slide['image'] !=""){//no blank entry
            $title = "";
            $img = "";
            $cap = "";
            $link = "";
            $pageid  = "";
            for($i=0;$i<$cnt;$i++){
                //echo "TINES: ".$i;
                if(isset($slide['title'][$i]))$title = $slide['title'][$i];
                if(isset($slide['link'][$i]))$link = $slide['link'][$i];
                if(isset($slide['image'][$i]))$img = $slide['image'][$i];
                if(isset($slide['caption'][$i]))$cap = $slide['caption'][$i];
                if(isset($slide['page_id'][$i]))$pageid = $slide['page_id'][$i];
                        
                $out .= "<li class='slide col-xs-12'>";
                 
                $out .= Form::hidden('page_id[]',$pageid);        
                $out .="<div class='col-xs-6'>";
                $out .= "<img src='".$img."'  alt='".$title."' class='preview-image img-responsive'  />";
                $out .= "<input name='image[]' type='hidden' value='".$img."' class='image_field clone_field'  />";
                $out .="</div>";
                  
                $out .="<div class='slide-content col-xs-6'>";
                $out .= "<div class='row action'>";
                $out .= "<a hre='#' class='btn btn-default draggable yb-tooltip pull-right seohide' title='This button allows you to reorder the items on this slider'><span class='glyphicon glyphicon-move'>&nbsp;</span></a> ";
                $out .= "<a href='#' class='btn btn-xs btn-default remove-item yb-tooltip pull-left' title='This button will delete the item from this list'><span class='glyphicon glyphicon-remove'>&nbsp;</span></a>";
                $out .="</div>";
                //TITLE
                $out .= "<div class='row'>";
                $out .= "<div class='input-group'>";
                $out .= "<span class='input-group-addon'>";
                $out .= "Title";
                $out .="</span>";
                $out .= "<input class='form-control clone_field title_field' name='title[]' type='text' value='".$title."' maxlength='45' />";
              
                $out .= "</div>";
                //LINK
                $out .= "<div class='link-selector'>";
                $out .= "<div class='input-group'>";
                $out .= "<span class='input-group-addon'>";
                $out .= "Link";
                $out .="</span>";
                $out .= Form::hidden('destination',NULL,['class'=>'destination']);
                $out .= "<input class='form-control  link' name='link[]' type='text' value='".$link."' maxlength='255' placeholder='http://www.doubletreebristol.com' />";
                $out .= Form::select("select-destination", NULL,NULL , array("class"=>'hide form-control select-destination'));
                $out .= "<span class='input-group-btn'>";
      
                $out .= "<a href='#'  class='page_select btn btn-default yb-tooltip' title='This button will let you select a page from this site.'>Select Page</a>";
                $out .="</span>";
                $out .= "</div>";
                $out .="<div class='hide destination_control'>";
                $out .= "<a href='#' class='page_cancel btn btn-default'>Cancel</a>";
                $out .= "<a  href='#' class='page_accept btn btn-default'>Accept</a>";
                $out  .= "</div>";
                $out  .= "</div><!-- /.link-selector -->";  
                //CAPTION
                $out .= "<div class='input-group'>";
                $out .= "<span class='input-group-addon'>";
                $out .= "Caption";
                $out .="</span>";
                $out .= "<textarea class='form-control caption_field clone_field' name='caption[]'  maxlength='300'>".$cap."</textarea>";
                $out .= "</div>";
                $out .= "</div><!-- /.row -->";
                $out .= "</div><!-- /.slide-content -->";
                $out .="</li>";
            }
     //  }
             // $out .= "</a>";
        return $out;
    }
    
    public static function Gallery($slides)
    {
        $out = "<ul id='sortable' class='slider-content' >";
        //CLONE
         $out .= "<li class='hide clone col-xs-12'>";
         
        $out .= Form::hidden('page_id_clone');    


           $out .="<div class='col-xs-6'>";
        $out .= "<img src=''  alt='' class='preview-image img-responsive'  />";
        $out .= "<input name='image_clone[]' type='hidden' value='' class='image_field clone_field'  />";
        $out .="</div>";
        
        $out .="<div class='slide-content col-xs-6'>";
        $out .= "<div class='row action'>";
        $out .= "<a hre='#' class='btn btn-default draggable yb-tooltip pull-right seohide' title='This button allows you to reorder the items on this slider'><span class='glyphicon glyphicon-move'>&nbsp;</span></a> ";
        $out .= "<a href='#' class='btn btn-xs btn-default remove-item yb-tooltip pull-left ' title='This button will delete the item from this list'><span class='glyphicon glyphicon-remove'>&nbsp;</span></a>";
        $out .="</div>";
        $out .= "<div class='row'>";
        //TITLE
        $out .= "<div class='input-group'>";
        $out .= "<span class='input-group-addon'>";
        $out .= "Title";
        $out .="</span>";
        $out .= "<input class='form-control clone_field title_field' name='title_clone' type='text' value='' maxlength='45' />";
        
        $out .= "</div>";
        //LINK
        $out .= "<div class='link-selector'>";
        $out .= "<div class='input-group'>";
        $out .= "<span class='input-group-addon'>";
        $out .= "Link";
        $out .="</span>";
        $out .= Form::hidden('destination',NULL,['class'=>'destination']);
        $out .= "<input class='form-control clone_field link_field' name='clone_field' type='text' value='' maxlength='255' placeholder='http://www.doubletreebristol.com'  />";
        $out .= Form::select("select-destination", NULL, NULL, array("class" => 'hide form-control select-destination'));
        $out .= "<span class='input-group-btn'>";
        $out .= "<a href='#'  class='page_select btn btn-default yb-tooltip' title='This button will let you select a page from this site.'>Select Page</a>";
        $out .="</span>";
        $out .= "</div>";
         $out .="<div class='hide destination_control'>";
        $out .= "<a href='#' class='page_cancel btn btn-default'>Cancel</a>";
        $out .= "<a  href='#' class='page_accept btn btn-default'>Accept</a>";
       $out  .= "</div>";
       $out  .= "</div><!-- /.link-selector -->";     
       
       //CAPTION
        $out .= "<div class='input-group'>";
        $out .= "<span class='input-group-addon'>";
        $out .= "Caption";
        $out .="</span>";
        $out .= "<textarea class='form-control clone_field caption_field' name='caption_clone' maxlength='255'></textarea>";
        $out .= "</div>";
        $out .= "</div>";
         $out .= "</div><!-- /.slide-content -->";
        $out .= "</li>";        
   
        $out .= self::GalleryItem($slides);
        $out .= "</ul><!-- /.slide-content -->";
        return $out;
    }
    
    public static function GallerySelector($galleries,$options =[])
    {
        if(count($galleries)>0){

            $css_class = "";
            if(count($options) >0){
                $css_class  = $options['css_class'];
            }
            
            
            $orderList = [];
          //  var_dump($galleries);exit;
            //GET THE CONTENT AND ORDER INTO AN ARRAY
            foreach($galleries as $g){
                $orderList[$g->page->display_order] = [
                    "content"=>json_decode($g->content)
                    ,"title" => $g->page->label
                    ];
            }

            //SORT THE ARRAY KEY FORM LOW TO HIGH
            ksort($orderList);



            //PARSE THrough ORDER ARRAY SO THAT WE CAN DISPLAY CONTENT 
            foreach($orderList as $g){
                $g = (object) $g;
                $title = $g->title;
                ?>

                <div class="photobox <?=$css_class;?>" title="<?=$title;?>">
                    <h2><?=$title;?></h2>
                    <figure>
                    <?php 
                    $class = "";
                    for( $i = 0;$i < count($g->content->image); $i++){
                        if($i >0)$class ="hide";
                        ?>
                        <a class='<?=$class;?>' href="<?=$g->content->image[$i];?>" title="<?=$g->content->title[$i];?>">
                             <img src="<?=$g->content->image[$i];?>" alt="<?=$g->content->title[$i];?>" class="gallery-image img-responsive" />

                        </a>


                    <?php } ?>


                       </figure>

                </div>

            <?php }//end foreach

        } else {//end if
            die ("Please provide a Gallery Object");
        }
    }
    
    
    public static function getStaff($pageid)
    {
        $dir = ORM::factory('Directory')->where('status',">",1)->find_all();
        $feat = [];
        foreach($dir as $d){
           // $feat =[];
            $featured = (array)json_decode($d->featured_on);
           
        //    $feat[$d->id]=$featured;
        //    print_r($feat);
            foreach($featured as $v){
         //       $feat[] =$v->page;
                if($v->page === $pageid)$feat[]= ORM::factory("Directory",$d->id);
                   // $feat[$v] = $d->id;
         
            }

        }
   //print_r($feat);
            return $feat;
        
    }
    
    public static function getNewsFeatured($sid,$id)
    {
        $nsc = ORM::factory('Newsschools')->where("schools_id","=",$sid)->where('news_id','=',$id)->find()->featured;
        return $nsc;
    }
    
    
    /**
     *  Set an item as featured
     * @param int $recid record id
     * @param int $recid record id
     * @param int $status the status either 3 for featured or 4 for unfeatured
     */
    public static function setNewsFeatured($recid,$sid,$status)
    {
        
        $prestatus = intval($status);
       if($status == 4){
            $status =0;
            $ret = 3;
        } elseif($status == 3){
            $status = 1;
            $ret = 2;
        } else {
            $ret = 3;
         
        }
        $nsc = ORM::factory('Newsschools')->where("schools_id","=",$sid)->where('news_id','=',$recid)->find();
        if(count($nsc) ==0 ){
            $nsc = new Model_Newsschools();
            $nsc->schools_id = $sid;
            $nsc->news_id = $recid;
        }
        $nsc->featured = $status;
         try{
                $nsc->save();
                $n = ORM::factory("News")->where("id","=",$recid)->find();
                //echo "PRESTATUS: ".var_dump($prestatus);
                if($prestatus === 4 || $prestatus === 3){
                    $n->status = 2;
                } else {
                    $n->status = $prestatus;
                }
                $n->save();
               // var_dump($nsc);
                return $ret;
             //   echo $nsc->last_query();
        } catch(Database_Exception $e){
            echo "DATABASE ERRORS<br /><pre>";
           var_dump($e);
           exit;
       } catch(ORM_Validation_Exception $e){
           echo "VALIDATION ERRORS<br /><pre>";
           var_dump($e->errors());
           exit;
       }

                
    }
    
    public static function buildURL($id)
    {
        //echo "ID: ".$id;exit;
        $school = ORM::factory("Schools")->where("id","=",$id)->find();
        $data = json_decode($school->data);
       // print_r(Kohana::$environment);exit;
        $sub = $data->subdomain;
        switch(Kohana::$environment){
            case 40:
                if($sub !=""){
                 $sub = $sub.".cttech.local";
                } else {
                    $sub = "cttech.local";
                }
                break;
            case 30:
                if($sub !=""){
                 $sub = $sub.".cttech.qa.pita.website";
                } else {
                    $sub = "cttech.qa.pita.website";
                }
                break;
            case 20:
                if($sub !=""){
                 $sub = $sub.".cttech.pita.website";
                } else {
                    $sub = "cttech.pita.website";
                }
                break;
            case 10:
                if($sub !=""){
                 $sub = $sub.".cttech.org";
                } else {
                    $sub = "cttech.org";
                }
                break;
        }
       
        return [
                "url"=>$sub
                ,"name"=>$data->friendly_name
        ];
    }
    
}
<?php defined('SYSPATH') or die('No direct script access.');?>


<article id="news" class="form">
     <?php 
    
    
    

        $title= "";
        $title_class ="";
        $title_msg = "<span class='message'>*</span>";
        
        $content ="";
        $content_class ="";
        $content_msg = "<span class='message'>*</span>";
        
        $short ="";
        $short_class ="";
        $short_msg = "<span class='message'>*</span>";
        
        $slug = "";
       $slug_class ="";
        $slug_msg = "<span class='message'>*</span>";
        
        $sdate ="";
        $sdate_class = "";
        $sdate_msg  = "<span class='message'></span>";
        
        $selcats =[];
        $cats_class = "";
        $cats_msg  = "<span class='message'></span>";
        
        
        $chk_news =false;
        $chk_event =false;
        
       // print_r($cats);exit;
        

        if (!empty($errors)){ 
     //   var_dump($errors);exit;
        foreach($errors as $k=>$message){ 
            switch($k){
                case "title":
                    $title_class = "has-error";
                    $title_msg = "<span class='message'>* ".$message."</span>";
                    break;
                case "content":
                    $content_class = "has-error";
                    $content_msg = "<span class='message'>* ".$message."</span>";
                    break;
                case "type":
                    $type_class = "has-error";
                    $type_msg= "<span class='message'>* ".$message."</span>";
                    break;
                case "start_date":
                    $sdate_class = "has-error";
                    $sdate_msg = "<span class='message'>* ".$message."</span>";
                    break;
                 case "categories":
                    $cats_class = "has-error";
                    $cats_msg = "<span class='message'>* ".$message."</span>";
                    break;
                case "slug":
                    $slug_class = "has-error";
                    $slug_msg = "<span class='message'>* ".$message."</span>";
                    break;
                case "short":
                    $short_class = "has-error";
                    $short_msg = "<span class='message'>* ".$message."</span>";
                    break;
                
            }
            
            
        
        } 
    }
        $id= "";
        $pageTitle ="Create News/Event";
  // var_dump((bool)$redirect->is_301);exit;
       // echo "Q: ".$new->last_query();
       // echo "TITLE: <pre>";
       // echo "TTTIT";
        //var_dump($new->id);
        //var_dump($new->start_date);
        if(is_object($new)){
            $id = $new->id;
            $title = $new->title;
            $content  = $new->content;
            $short = $new->short;
           $sdate = $new->start_date;
           $slug = $new->slug;
           
           foreach($new->categories->find_all() as $c){
               $selcats[$c->id] = $c->id;
           }
         // print_r($selcats);exit;
           //$selcats = $new->categories;
            $pageTitle = "Edit News/Event: ".$title;
        } 
        
        //echo "SSSS: ".$new->start_date;
       // print_r($post);
        if(isset($post)){
            $title= $post['title'];
            $content = $post['content'];
            $sdate = $post['sdate'];
            $slug = $post['slug'];
             $short = $post['short'];
            $selcats = $post['category'];
         
        }
        //var_dump($date);
       // echo "SDATE1: ".$sdate;
         if($sdate!= "") $sdate = date("m/d/Y",strtotime($sdate));
       // echo "<br />SADTE2: ".$sdate;
     
        
 ?> 
    
    <h1><?=$pageTitle;?></h1>
    <?php if(isset($msg)){?>
    <p><?= $msg;?></p>
    <?php  }  ?>
    <a id="form"></a>
 <?=Form::open("/admin/news/create/".$id,array("novalidate","id"=>"news_form","class"=>'row')); ?>
 <?=Form::hidden('csrf', Security::token());?>
 <?=Form::hidden('editid', $editid,array("id"=>"editid"));?>

    <div class="form-group col-xs-6 <?=$title_class;?>">
        <?=Form::label('title',"Title".$title_msg);?>
        <div class="input-group">
        <?=Form::input("title",$title,array('class'=>'form-control','required',"id"=>"title"));?>
            <span class="input-group-btn">
                <a href="#" class="btn btn-default disabled" id="update-slug">Update</a>
            </span>
        </div>
    </div>
    <div class="form-group col-xs-6 <?=$slug_class;?>">
        <?=Form::label('slug',"Slug".$slug_msg);?>
        <div class="input-group">
        <?=Form::input("slug",$slug,array('class'=>'form-control','id'=>'slug'));?>
            <span class="input-group-btn">
                <a href="#" class="btn btn-default" id="check-slug">Check</a>
            </span>
        </div>
    </div>
    <div class="form-group col-xs-6 <?=$sdate_class;?>">
        <?=Form::label('sdate',"Start Date".$sdate_msg);?>
        <?=Form::input("sdate",$sdate,array('class'=>'form-control datepicker'));?>
    </div>
    <div class="form-group col-xs-6 <?=$cats_class;?>">
        <?=Form::label('category',"Category".$cats_msg);?>
        <?=Form::select("category[]",$cats,$selcats,array('class'=>'form-control chosen-multi',"data-placeholder"=>"Select Category","multiple"=>true));?>
    </div>
     <div class="form-group col-xs-12 <?=$short_class;?>">
        <?=Form::label('short',"Short Description".$short_msg);?>
        <?=Form::textarea("short",$short,array('class'=>'form-control','required',"id"=>"short"));?>
    </div>
     <div class="form-group col-xs-12 <?=$content_class;?>">
        <?=Form::label('content',"Content".$content_msg);?>
        <?=Form::textarea("content",$content,array('class'=>'form-control ckeditor_basicLinks','required',"id"=>"content"));?>
    </div>



 <div class="form-group col-xs-12">
    <?= Form::submit("submit","Save",array('class'=>' btn btn-md btn-default'));?>
     <a href="/admin/news" class='btn btn-default'>Back</a>
    </div>
   
    <?=Form::close(); ?>
    
    
</article>
    

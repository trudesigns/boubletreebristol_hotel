<?php defined('SYSPATH') or die('No direct script access.');?>


<article id="news" class="form">
     <?php 
    
    
    

        $title= "";
        $title_class ="";
        $title_msg = "<span class='message'>*</span>";
        
       
        
       // print_r($cats);exit;

        if (!empty($errors)){ 
     //   var_dump($errors);exit;
        foreach($errors as $k=>$message){ 
            switch($k){
                case "title":
                    $title_class = "has-error";
                    $title_msg = "<span class='message'>* ".$message."</span>";
                    break;
                
            }
            
            
        
        } 
    }
        $id= "";
        $pageTitle ="Create Category";
  // var_dump((bool)$redirect->is_301);exit;
       // echo "Q: ".$new->last_query();
       // echo "TITLE: <pre>";
       // echo "TTTIT";
        //var_dump($new->id);
        //var_dump($new->start_date);
        if(is_object($cat)){
            $id = $cat->id;
            $title = $cat->name;

           
          
           //$selcats = $new->categories;
            $pageTitle = "Edit Category: ".$title;
        } 
        
        //echo "SSSS: ".$new->start_date;
       // print_r($post);
        if(isset($post)){
            $title= $post['name'];

         
        }
        //var_dump($date);
       // echo "SDATE1: ".$sdate;
        // if($sdate!= "") $sdate = date("m/d/Y",strtotime($sdate));
       // echo "<br />SADTE2: ".$sdate;
     
        
 ?> 
    
    <h1><?=$pageTitle;?></h1>
    <?php if(isset($msg)){?>
    <p><?= $msg;?></p>
    <?php  }  ?>
    <a id="form"></a>
 <?=Form::open("/admin/categories/create/".$id."#form",array("novalidate","id"=>"categories_form","class"=>'row')); ?>
 <?=Form::hidden('csrf', Security::token());?>
 <?=Form::hidden('editid', $editid,array("id"=>"editid"));?>

    <div class="form-group col-xs-6 <?=$title_class;?>">
        <?=Form::label('name',"Title".$title_msg);?>
       
        <?=Form::input("name",$title,array('class'=>'form-control','required',"id"=>"title"));?>
           

    </div>




 <div class="form-group col-xs-12">
    <?= Form::submit("submit","Save",array('class'=>' btn btn-md btn-default'));?>
     <a href="/admin/categories" class='btn btn-default'>Back</a>
    </div>
   
    <?=Form::close(); ?>
    
    
</article>
    

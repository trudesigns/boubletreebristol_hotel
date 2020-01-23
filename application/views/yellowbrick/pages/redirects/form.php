<?php defined('SYSPATH') or die('No direct script access.');?>


<article id="redirects" class="form">
     <?php 
    
    
    

        $path= "";
        $path_class ="";
        $path_msg = "<span class='message'>*</span>";
        
        $dest ="";
        $dest_class ="";
        $dest_msg = "<span class='message'>*</span>";
        
        $notes = "";
        $notes_class = "";
        $notes_msg = "";
        
        $s301 ="";
        $s301_class = "";
        $s301_msg  = "";
        
        

        if (!empty($errors)){ 
     //   var_dump($errors);exit;
        foreach($errors as $k=>$message){ 
            switch($k){
                case "first":
                    $path = "has-error";
                    $e_first = "<span class='message'>* ".$message."</span>";
                    break;
                case "last":
                    $lname = "has-error";
                    $e_last = "<span class='message'>* ".$message."</span>";
                    break;
                case "email":
                    $eemail = "has-error";
                    $e_email = "<span class='message'>* ".$message."</span>";
                    break;
                case "password":
                    $epass = "has-error";
                    $e_password = "<span class='message'>* ".$message."</span>";
                    break;
                case "password2":
                    $epass2 = "has-error";
                    $e_password2 = "<span class='message'>* ".$message."</span>";
                    break;
                
            }
            
            
        
        } 
    }
        $id= "";
        $path="";
        $pageTitle ="Create Redirect";
        $s301_check = false;
  // var_dump((bool)$redirect->is_301);exit;
        if(is_object($redirect)){
            $id = $redirect->id;
            $path = $redirect->alias;
            $dest  = $redirect->destination;
            $notes  = $redirect->notes;
            $s301  = (bool)$redirect->is_301;
            if($s301){
            $s301_check  =true;
            }
            $pageTitle = "Edit Redirect: ".$path;
        } 
       // print_r($post);
        if(isset($post)){
            $path= $post['path'];
            $dest = $post['destination'];
            $notes = $post['last'];
         
        }
        
     
        
 ?> 
    
    <h1><?=$pageTitle;?></h1>
    <?php if(isset($msg)){?>
    <p><?= $msg;?></p>
    <?php  }  ?>
    <a id="form"></a>
 <?=Form::open("/admin/redirects/create/".$id."#form",array("novalidate","id"=>"redirect_form","class"=>'row')); ?>
 <?=Form::hidden('csrf', Security::token());?>
 <?=Form::hidden('editid', $editid,array("id"=>"editid"));?>
    
    <div class="form-group col-xs-6 <?=$path_class;?>">
        <?=Form::label('path',"Path".$path_msg);?>
        <?=Form::input("path",$path,array('class'=>'form-control','required',"id"=>"path"));?>
        
       
    <br><small>Ex: "/contact/new-form.php" or "/about/history"</small>
    <br><em><small>Note: Path is absolute and must begin with "http://" (for 3rd party links) or "/" (for local links)</small></em>
    </div>
     <div class="form-group col-xs-6 <?=$dest_class;?>">
        <?=Form::label('destination',"Destination".$dest_msg);?>
        <?=Form::input("destination",$dest,array('class'=>'form-control','required',"id"=>"destination"));?>
         <?=Form::hidden('destination_id', NULL,array("id"=>"destination_id"));?>
         <?=Form::select("select-destination", NULL,NULL , array("class"=>'hide form-control',"id"=>"select-destination"));?>
         <a id="page_select" href="#" class="btn btn-default">Select Page</a>
         <span class="hide" id="destination_control">
            <a id="page_cancel" href="#" class="btn btn-default">Cancel</a>
            <a id="page_accept" href="#" class="btn btn-default">Accept</a>
         </span>
    </div>
    <div class="form-group col-xs-12 <?=$notes_class;?>">
        <?=Form::label('notes',"Notes".$notes_msg);?>
        <?=Form::textarea("notes",$notes,array('class'=>'form-control'));?>
    </div>
     <div class="form-group col-xs-12 <?=$s301_class;?>">
        <?=Form::label('s301',"301 Permanent Redirect".$s301_msg);?>
        <?=Form::checkbox("s301","301",$s301_check);?>
    </div>


 <div class="form-group col-xs-12">
    <?= Form::submit("submit","Save",array('class'=>' btn btn-md btn-default'));?>
    </div>
   
    <?=Form::close(); ?>
    
    
</article>
    

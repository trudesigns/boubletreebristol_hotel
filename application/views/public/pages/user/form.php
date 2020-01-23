<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<article id="user" class="form">
    
  
    
    <?php 
    
    
    

        $fname = "";
        $lname ="";
        $f_name = "";
        $l_name ="";
        $eemail = "";
        $d_email = "";
        $epass  = "";
        $epass2  = "";
        $d_pass   = "";//$user->comment;
        $d_pass2 = "";
        $e_first = "<span class='message'>*</span>";
        $e_last = "<span class='message'>*</span>";
        $e_email = "<span class='message'>*</span>";
        $e_password = "<span class='message'>*</span>";
        $e_password2 = "<span class='message'>*</span>";
        if (!empty($errors)){ 
        
        foreach($errors as $k=>$message){ 
            switch($k){
                case "first":
                    $fname = "has-error";
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
        
        $path="";
        $pageTitle ="Create User";
   // var_dump($member);exit;
        if(is_object($member)){
            $d_email = $member->email;
            $f_name  = $member->first;
            $l_name  = $member->last;
            
            $path = $member->id;
            $pageTitle = "Edit User: ".$l_name.", ".$f_name;
        } 
       // print_r($post);
        if(isset($post)){
            $d_email= $post['email'];
            $f_name = $post['first'];
            $l_name = $post['last'];
           // $d_com  = $post['comment'];
        }
        
     
        
 ?> 
    
    <h2><?=$pageTitle;?></h2>
    <?php if(isset($msg)){?>
    <p><?= $msg;?></p>
    <?php  }  ?>
    <a id="form"></a>
 <?=Form::open("/admin/userCreate/".$path."#form",array("novalidate","id"=>"user_form")); ?>
 <?=Form::hidden('csrf', Security::token());?>
 
    
    <div class="form-group <?=$lname;?>">
        <?=Form::label('last',"Last Name".$e_last);?>
        <?=Form::input("last",$l_name,array('class'=>'form-control','required'));?>
    </div>
     <div class="form-group <?=$fname;?>">
        <?=Form::label('first',"First Name".$e_first);?>
        <?=Form::input("first",$f_name,array('class'=>'form-control','required'));?>
    </div>
    <div class="form-group <?=$eemail;?>">
        <?=Form::label('email',"Email".$e_email);?>
        <?=Form::input("email",$d_email,array("type"=>'email','class'=>'form-control','required'));?>
    </div>
     <div class="form-group <?=$epass;?>">
        <?=Form::label('password',"Password".$e_password);?>
        <?=Form::password("password",$d_pass,array('class'=>'form-control password','required'));?>
    </div>
      <div class="form-group <?=$epass2;?>">
        <?=Form::label('password2',"Confirm Password".$e_password2);?>
        <?=Form::password("password2",$d_pass2,array('class'=>'form-control','required'));?>
    </div>

 <div class="form-group">
    <?= Form::submit("submit","Save",array('class'=>' btn btn-md btn-hplct'));?>
    </div>
   
    <?=Form::close(); ?>
    

    
</article>
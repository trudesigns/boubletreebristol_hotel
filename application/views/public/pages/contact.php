<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<?php
 $slides= json_decode($_this->pageContents->siteslider->content);
 $img_cnt = 0;
 if(isset($slides->image)){
    $img_cnt = count($slides->image);
 }
 if($img_cnt > 0){
?>
<div id="carousel" class="carousel slide carousel-fade" data-ride="carousel">
    <div class="carousel-inner">
        
        <?php
            for($i=0;$i<$img_cnt;$i++){
                $extraClass = "";
                 if($i ===0){
                    $extraClass = "active";
                }
                echo '<div class="'.$extraClass.' item"><div class="shadow-overlay hidden-xs"></div><img src="'.$slides->image[$i].'"></div>';

            }
        ?>
    </div>
</div>
 <?php } else { ?>

<section class="tierImg">
    <?php 
     if (isset($_this->pageContents->tierimage->content)) {
        $pageImg = $_this->pageContents->tierimage->content;
     } else {
            $pageImg = '/assets/uploads/images/page-image-accomodations.jpg';
        }
        
    ?>
    <div class="shadow-overlay hidden-xs"></div><img class="img-responsive" src="<?php echo $pageImg ?>">
</section>

 <?php } ?>
<section class="tier">
    



    <a id="form"></a>
  
    
    <div class="container first-tier">
          <?php if (!empty($success) && empty($errors) ): ?>
            <div class="inline-thank-you">
            <h3>Thank You!</h3>
            <p>Your information has been successfully submitted. Our representative will contact you soon.</p>
            <p><a href="<?= $_SERVER['REQUEST_URI']; ?>">Submit another form</a></p>
            </div>
            <?php else: ?>
        
        <h1><?php echo $_this->page->label; ?></h1>
            <?=$_this->pageContents->main_content->content;?>
    
    <?php
    
    
        $name= "";
        $name_class ="";
        $name_msg = "<span class='message'>*</span>";
        
        $company ="";
        $company_class ="";
        $company_msg = "<span class='message'></span>";
        
        $email ="";
        $email_class ="";
        $email_msg = "<span class='message'>*</span>";
        
        $phone = "";
        $phone_class ="";
        $phone_msg = "<span class='message'></span>";
        
        $message ="";
        $message_class = "";
        $message_msg  = "<span class='message'>*</span>";
        
        $security =[];
        $security_class = "";
        $security_msg  = "<span class='message'>*</span>";
        
    
        if (!empty($errors)){ 
     //   var_dump($errors);exit;
            foreach($errors as $k=>$message){ 
                switch($k){
                    case "name":
                        $name_class = "has-error";
                        $name_msg = "<span class='message'>* ".$message."</span>";
                        break;
                    case "company":
                        $company_class = "has-error";
                        $company_msg = "<span class='message'>* ".$message."</span>";
                        break;
                    case "email":
                        $email_class = "has-error";
                        $email_msg= "<span class='message'>* ".$message."</span>";
                        break;
                    case "phone":
                        $phone_class = "has-error";
                        $phone_msg = "<span class='message'>* ".$message."</span>";
                        break;
                     case "message":
                        $message_class = "has-error";
                        $message_msg = "<span class='message'>* ".$message."</span>";
                        break;
                    case "verify":
                        $security_class = "has-error";
                        $security_msg = "<span class='message'>* ".$message."</span>";
                        break;
                }        
            } 
        }
        
        $name    = "";
        $phone   = "";
        $email   = "";
        $company = "";
        $message = "";
        
        
        if(isset($post)){
            $name    = $post['name'];
            $company = $post['company'];
            $semail  = $post['email'];
            $phone   = $post['phone'];
            $message = $post['message'];
          
         
        }
        
        

            echo Form::open(''.$_SERVER['REQUEST_URI'].'#form',['id'=>"contact-form"]) ;

            if(isset($msg)){    ?>
                <p><?= $msg;?></p>
            <?php  } 
                    
                    ?>

            <small><span class="required_red">*</span> = required field</small>
            <br><br>
             <?=Form::hidden('csrf', Security::token());?>
            <?= Form::input('favorite', NULL, array('id' => 'favorite', 'style' => 'display:none;') ); ?>
            <div class="col-xs-12 col-sm-6">
                <div class="form-group <?=$name_class;?>">
                    <?= Form::label('name', 'Name'.$name_msg) ?><br>
                    <?= Form::input('name', $name, array('id' => 'name', 'size' => '30','class'=>'form-control') ); ?>
                </div>
<!--            </div>
             <div class="col-xs-6">-->
                <div class="form-group <?=$company_class;?>">
                    <?= Form::label('company', 'Company:'.$company_msg) ?> <br>
                    <?= Form::input('company', $company , array('id' => 'company', 'size' => '30','class'=>'form-control') ); ?>
                </div>
<!--             </div>
             <div class="col-xs-6">-->
                <div class="form-group <?=$email_class;?>">
                    <?= Form::label('email', 'Email'.$email_msg) ?> <br>
                    <?= Form::input('email',$email, array('id' => 'email', 'size' => '30','class'=>'form-control') ); ?>
                </div>
<!--            </div>
             <div class="col-xs-6">-->
                <div class="form-group <?=$phone_class;?>">
                    <?= Form::label('phone', 'Phone:'.$phone_msg) ?><br>
                    <?= Form::input('phone', $phone, array('id' => 'phone', 'size' => '30','class'=>'form-control') ); ?>
                </div>
<!--             </div>
             <div class="col-xs-6">-->
                <div class="form-group <?=$message_class;?>">
                    <?= Form::label('message', 'Message:'.$message_msg) ?><br>
                    <?= Form::textarea('message', $message, array('id' => 'message', 'rows' => '5', 'cols' => '32','class'=>'form-control') );  ?>
                </div>
<!--            </div>
             <div class="col-xs-6">-->

             </div>

            <br clear="all"><br clear="all">
            <?= Form::button(NULL, 'Submit', array('type' => 'submit', 'class' => 'submitBtn btn btn-default')) ?>

        <?= Form::close() ?>
            <?php endif; ?>
    </div>
    

</section>
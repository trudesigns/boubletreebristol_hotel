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
    <div class="container first-tier pagecontent">
        <h1><?php echo $_this->page->label; ?></h1>
        <?=$_this->pageContents->main_content->content;?>
   
        <?php
    //var_dump($_this->pageContents->galleryselector->content);
        
        //ADD THIS BLOCK TO GET THE GALLERY TO DISPLAY
        if(isset($_this->pageContents->galleryselector) && !is_null($_this->pageContents->galleryselector->content) && $_this->pageContents->galleryselector->content !== ""){
            //echo "QUERY SELECTPOR";
            $page = json_decode($_this->pageContents->galleryselector->content);
            $content = new Model_Content;
            $galleries =  $content->getContentRevisionWBlockName([
                "page_id"=>(int)$page->gallery,
                "block_name"=>"Gallery" //COULD BE DIFFERENT
                    ]);
            echo doubletree::GallerySelector($galleries,["css_class"=>"col-xs-6"]);
        }
        ?>
</div>
</section>


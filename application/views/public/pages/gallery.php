<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>


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
<section class="tier">

<div class="container first-tier">
        <?=$_this->pageContents->main_content->content;?>
    

    <div id="albums" class="stack">
    <?php

       echo doubletree::GallerySelector($galleries,["css_class"=>"col-md-3 col-xs-6"]);
    ?>
    </div>
</div>

</section>


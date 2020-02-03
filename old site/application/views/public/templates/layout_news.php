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
<section class="tier layout_news">
    <div class="container first-tier">
        <h1><?php echo $_this->page->label; ?> <a href="#" class="sortby">Filter By</a></h1>
            <?php echo $pageView ?>
   
    

         </div>
</section>

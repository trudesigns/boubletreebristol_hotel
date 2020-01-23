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
    <div class="container first-tier">
        <?=$_this->pageContents->main_content->content;?>
    </div>
   <?php 
   // check for The Willows Restarant page
   if ($_this->page->id == '217') {
   // echo View::factory('/public/widgets/opentable'); 
   } ?>
    <div id="property-view" class="container no-padding">
        <div class="row">
            <div id="tier-support" class="col-md-4 col-md-push-8">
                <div class="containerPad">
                    <?=$_this->pageContents->RightSide->content;?>
               
                </div>
            </div>
            <div id="menu-listing" class="col-md-8 col-md-pull-4">
                <!--need to loop via php and be sure to alternate image order for output-->
                 <?php 
                
                 $square = json_decode($_this->pageContents->Squares->content);
                 //$cnt = 0;
                 $out ="";
                 if (!empty($square)) {
                     for($i=0;$i<count($square->title);$i++){
                 //foreach(json_decode($_this->pageContents->Squares->content) as $square){
                     //var_dump($square);exit;
                     $out .= "<div class='row'>";
                   if($i % 2 ===0 ){//even
                        $out .= "<a href='".$square->link[$i]."' class='overlay'>&nbsp;</a><div class='col-xs-6'><img class='img-responsive' src='".$square->image[$i]."'></div><div class='col-xs-6'><div class='arrow-left arrow-left-dark'>&nbsp;</div><a href='".$square->link[$i]."' class='menus'>".$square->title[$i]."</a></div>";
                     
                   } else {//odd
                        $out .= "<a href='".$square->link[$i]."' class='overlay'>&nbsp;</a><div class='col-xs-6'><a href='".$square->link[$i]."' class='menus'>".$square->title[$i]."</a><div class='arrow-right arrow-right-green'>&nbsp;</div></div><div class='col-xs-6'><img class='img-responsive' src='".$square->image[$i]."'></div>";
                       
                   }
                    $out .="</div>";
                     
                     //$cnt++;
                 }
                 }
                 echo $out;
                    ?>
                

                <!--end output loop -->
            
            </div>
        </div>
           <div class="row">
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
        
    </div>
   
</section>
<div id="carousel" class="carousel slide carousel-fade" data-ride="carousel">
    <div class="carousel-inner">

        <?php
        $slides= json_decode($_this->pageContents->HomeSlide->content);

        for($i=0;$i<count($slides->image);$i++){

            $extraClass = "";
            if($i ===0){
                $extraClass = "active";
            }
//
            echo '<div class="'.$extraClass.' item"><div class="shadow-overlay hidden-xs"></div><img src="'.$slides->image[$i].'"></div>';

        }

        ?>

        <!--        <div class="item"><div class="shadow-overlay"></div><img src="/assets/uploads/images/banner-carousel-lobby.jpg"></div>-->
    </div>
</div>

<div class="no-padding container">
    <div class="owl-carousel">
        <?php

        $callouts= json_decode($_this->pageContents->Callouts->content);


        // print_r($callouts->image);exit;
        $out ="";
        for($i=0;$i<count($callouts->image);$i++){
            $out .= "<div>";
            $out .= "<a href='".$callouts->link[$i]."' class='overlay'>&nbsp;</a>";
            if ($i % 2 == 0){//even
                $out .= "<div class='copy'><div class='containerPad'><span class='heading'>".$callouts->title[$i]."</span><p>".$callouts->caption[$i]."</p></div></div>";
                $out .= "<div class='arrow-down'><img src='".$callouts->image[$i]."'></div>";
            } else {//odd
                $out .= "<div class='arrow-up'><img class='img-responsive' src='".$callouts->image[$i]."'></div>";
                $out .= "<div class='copy'><div class='containerPad'><span class='heading'>".$callouts->title[$i]."</span><p>".$callouts->caption[$i]."</p></div></div>";
            }
            $out .= "</div>";
        }
        echo $out;
        ?>
    </div>
</div>

<section id="home">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-lg-9">
                <div id="mainContent">
                    <div class="intro">
                        <?php echo $_this->pageContents->main_content->content; ?>
                    </div>

                    <div class="row margin-fix">
                        <div id="homeCallout" class="col-lg-6">
                            <?php

                            $specials= json_decode($_this->pageContents->Specials->content);


                            //print_r($specials);
                            ?>
                            <div class="arrow-up dark">
                                <img class="img-responsive" src="<?=$specials->image;?>">
                            </div>
                            <div class="containerPad">
                                <span class="heading"><?=$specials->title;?></span>
                                <p><?=$specials->caption;?></p>
                                <a class="moreBtn" href="<?=$specials->link;?>">More</a>
                            </div>

                        </div>
                        <div id="homeNews" class="col-lg-6">
                            <div class="containerPad">
                                <span class="heading">News &amp; Events</span>
                                <?php foreach($news as $new){?>
                                    <div class="newsItem">
                                        <span><a href="/news/<?=$new->slug;?>"><?=$new->title;?></a></span>
                                        <p><?=$new->short;?></p>
                                    </div>
                                <?php } ?>

                                <a class="moreBtn" href="/news">More</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div id="tripadvisor" class="col-sm-4 col-lg-3 hidden-xs">
                <div id="TA_selfserveprop788" class="TA_selfserveprop">
                    <ul id="IhFzYnE5Z5s" class="TA_links aFBO8R1kA">
                        <li id="52Yc2eDYWCQv" class="kJMacaTx87">
                            <a target="_blank" href="http://www.tripadvisor.com/"><img src="http://www.tripadvisor.com/img/cdsi/img2/branding/150_logo-11900-2.png" alt="TripAdvisor"/></a>
                        </li>
                    </ul>
                </div>
                <p>&nbsp;</p>
                <script src="http://www.jscache.com/wejs?wtype=selfserveprop&amp;uniq=788&amp;locationId=487316&amp;lang=en_US&amp;rating=true&amp;nreviews=5&amp;writereviewlink=true&amp;popIdx=true&amp;iswide=false&amp;border=false&amp;display_version=2"></script>
                <div id="TA_certificateOfExcellence613" class="TA_certificateOfExcellence">
                    <ul id="T9cjlcUoG" class="TA_links IGYFWir9Q5">
                        <li id="HkPFUyFmHQeY" class="VuSal6">
                            <a target="_blank" href="http://www.tripadvisor.com/Hotel_Review-g33728-d487316-Reviews-DoubleTree_by_Hilton_Hotel_Bristol_Connecticut-Bristol_Connecticut.html"><img src="http://www.tripadvisor.com/img/cdsi/img2/awards/CoE2015_WidgetAsset-14348-2.png" alt="TripAdvisor" class="widCOEImg" id="CDSWIDCOELOGO"/></a>
                        </li>
                    </ul>
                </div>
                <script src="http://www.jscache.com/wejs?wtype=certificateOfExcellence&amp;uniq=613&amp;locationId=487316&amp;lang=en_US&amp;year=2015&amp;display_version=2"></script>


            </div>
        </div>
    </div>
</section>

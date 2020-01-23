<?php defined('SYSPATH') or die('No direct script access.');?>

    


    <?php

    //foreach($article as $a){
      
        $date = DateTime::createFromFormat('Y-m-d', $article->start_date);
        $pub = "";
        if($date !== false){
            $pub = $date->format('l, F j, Y');
        }

        ?>

    <h3><?=$article->title;?></h3>

    <div class="news-article">
    <span class="pub_date"><?=$pub;?></span>
    <?=$article->content;?>
    <a href="/news" class="btn btn-default">Back</a>
    </div>
        
  
    



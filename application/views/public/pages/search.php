<section id="search-page">
    <?php
   // var_dump($_GET['q']);
     $q = "";
    if (isset($_GET['q']))$q = htmlspecialchars($_GET['q']);
   
     $match = "";
     if (isset($_GET['match']))$match = $_GET['match'];
     
    ?>
    
    
                <?=Form::open("/search#form",["novalidate","id"=>"search_form","method"=>'get']); ?>
                    <?php $token = base64_encode(Security::token());?>
    
                    <?=Form::hidden('csrf', $token);?>
                <div class="row">
                    <div class="col-xs-12 col-xs-offset-0 col-sm-8 col-sm-offset-2">
                        <div class="input-group">
                            <div class="input-group-addon left"><?=Form::label('search',"Search");?></div>
                            <?php
                            
                            $search = NULL;

                            echo Form::input("q",$q,["id"=>"search","class"=>"form-control input-xs"]);?>
                            <div class="input-group-addon right"><?=Form::button("Submit",NULL,['class'=>'fa fa-angle-right']);?></div>
                        </div>
                    </div> 
                        
                </div>
                <div class="row">
                    <div class="col-xs-12 col-xs-offset-0 col-sm-8 col-sm-offset-2">
                        <div class="form-group">
                            <p>Match
                            <?php
                                $checked_any = false;
                                $checked_ph = false;
                                if(isset($_GET['match'])){
                                    switch($_GET['match']){
                                        case "phrase":
                                            $checked_ph = true;
                                            break;
                                        default:
                                            $checked_any = true;
                                            break;
                                    }
                                }
                                echo Form::radio("match",NULL,$checked_any,["id"=>'match_any']);
                                echo Form::label('match_any',"Any Word");
                                echo Form::radio("match",NULL,$checked_any,["id"=>'match_phrase']);
                                echo Form::label('match_any',"The exact Phrase");
                            ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?=Form::close(); ?>
    
    

  <div class="row">
      <a id="form" />
        <div id="search-results-wrap" class="col-xs-12 col-xs-offset-0 col-sm-10 col-sm-offset-1">
        <?php
        if ($q != "") {

            $description_length = 200;
            $result_cnt = 0;
            if(is_object($search_results))$result_cnt =$search_results->total_pages; 
            ?>
           <p >Your search returned <strong><?=$result_cnt;?>result(s)</strong> </p>

            
            <?php if (isset($search_results->results)) {?>
                <div class='pagination-links'><?=ybr::searchResultPagination($search_results,$q,$match,$token);?></div>
                <?php   foreach ($search_results->results as $row) {?>
                   <div class="search_result">
                    <span class="search_result_title"><a href="<?=$row['path'];?>"><?=$row['page_label'];?></a></span>

                    <?php 
                    $description = "";
                    if (trim(strip_tags($row['page_description'])) != "")$description = $row['page_description'];
                        if (trim($description) != "") {?>
                            <span class="search_result_description"> 
                            <?php 
                            echo substr(strip_tags($description), 0, $description_length);
                            if(strlen(strip_tags($description)) > $description_length){?>
                                ...</span>
                            <?php } else { ?>
                                 </span>
                            <?php } 
                        } ?>

                    <br /><span class="search_result_url" title="last updated <?=date("m/d/Y h:ia", strtotime($row['page_last_updated']));?>"><?= ybr::serverProtocol() . $_SERVER['HTTP_HOST'] . $row['path'];?></span>
                    </div>
               <?php } ?>
                <div class='pagination-links'><?=ybr::searchResultPagination($search_results,$q,$match,$token);?></div>
           <?php }
        }//end $q != ""
        ?>
    </div>

        <div class="col-xs-12 col-xs-offset-0 col-sm-10 col-sm-offset-1">
        <p><small>Search this site <a href="https://www.google.com/search?q=site:<?= $_SERVER['HTTP_HOST']; ?>" target="_blank" onclick="$(this).attr('href', 'https://www.google.com/search?q=' + ($('#q').val() + ' site:<?= $_SERVER['HTTP_HOST']; ?>').replace(/ /g, '+'))">using Google</a></small></p>
        </div>
</div>
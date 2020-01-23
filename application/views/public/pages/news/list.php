<?php defined('SYSPATH') or die('No direct script access.');?>






            <div id="news" class="list">
                <div id="dt-filters" class="hide">
                    <aside id="filter-date">
                        <select class="form-control chosen-single" data-placeholder="Sort Date">
                            <option value="asc">Past</option>
                            <option value="desc" selected>Most Recent</option>
                        </select>
                    </aside>
                    <aside id="filter-category">
                        
                        <select class="form-control chosen-single" placeholder="Filter Category" data-placeholder="Filter Category">
                            <option value=""></option>
                            <?php foreach($cats as $c){?>
                             <option value="<?=$c->id;?>"><?=$c->name;?></option>
                            <?php } ?>
                        </select>
                    </aside>
                </div>

                <table data-model="News" class="datatable-news table table-condensed table-responsive" >
                  <thead>
                        <tr>
                            <th class="hide">STARTDATE</th>
                            <th class="hide">CATEGORIES</th>
                            <th class="hide" style="width:150px;">Content</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $id= "";
                        $sdate ="";
                        $title = "";
                        $content = "";  
                        $slug = "";
                        $origin ="";
                  //   var_dump($news_list);exit;
                        if(!is_null($news_list)){
                          // var_dump($news_list);exit;
                        foreach($news_list as $n){
                           // echo "sjdhdhd";
                            //var_dump($n);
                            $n = (object)$n;
                                if(isset($n->id))$id = $n->id;
                                if(isset($n->title))$title = $n->title;
                                if(isset($n->slug))$slug = $n->slug;
                                if(isset($n->short))$short = $n->short;
                                
              
                                $origin_url = "/news/".$slug;
                                //$origin_name = ORM::factory("Schools")->where("id","=",$origin)->find()->name;
                                if(isset($n->start_date)){
                                    //echo $n->date;exit;
                                    $date = DateTime::createFromFormat('Y-m-d', $n->start_date);
                                    $sdate = $date->format('l, F j, Y');

                                }

                                if(isset($n->content))$content = $n->content;



//print_r(implode(",",$n->categories->find_all()->as_array()));exit;

                            ?>
                        <tr data-recordid="<?=$id;?>" <?php if (strtotime($sdate) < strtotime('today')) { echo 'class="pastevent"'; } ?> >
                            <td data-order="<?=strtotime($sdate);?>"><span><?=$sdate;?></span></td>
                             <td><?=implode(",",$n->categories->find_all()->as_array());?></td>
                            <td>
                                
                                <h2><?=$title;?></h2>
                                <span class="pub_date"><?=$sdate;?></span>


                                <p><?=substr(strip_tags($short),0,225);?>&nbsp;<a href="/news/<?=$slug;?>" class=" moreBtn">Read more</a></p>
                            </td>
                        </tr>


                        <?php } 
                        }
                        ?>
                    </tbody>
             </table>
            </div>


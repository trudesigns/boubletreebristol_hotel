<?php defined('SYSPATH') or die('No direct script access.');?>

<article id="news" class="list">
    
    
    
    
<h1>News</h1>

<div id="dt-filters">
    <aside id="filter-status">
        <select class="chosen-single" data-placeholder="Filter by Status">
            <option value="-1"></option>
            <option value="0">Deleted</option>
            <option value="1">Inactive</option>
            <option value="2">Active</option>
             <option value="3">Featured</option>
        </select>
    </aside>      
    <aside id="filter-categories">
    <?php 
 
    echo Form::select("cats", $cats, NULL, ["class"=>"chosen-single","data-placeholder"=>'Filter by Categories']);
    ?>
</aside>
    
</div>


 <table data-model="News" class="datatable-news table table-condensed table-bordered table-responsive" >
      <thead>
            <tr>
                <th>STATUS</th>
                <th>CATEGORIES</th>
                <th style="width:150px;">Title</th>
                <th style="width:150px;">Slug</th>
                <th style="width:50px;">Start Date</th>
               <th style="width:50px;">Categories</th>
                <th style="width:30px;">Info</th>
                 <th style="width:50px;">Status</th>
                <th style="width:50px;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $id= "";
            $sdate ="";
            $title = "";
            $content = "";
            $cdate ="";
            $cby ="";
            $mdate ="";
            $mby ="";
            $slug = "";
            $origin = "";
            $status = "";
            $action = "";
           
            //print_r($news);exit;
            if(!is_null($news)){
            foreach($news as $n){
                 $cats  = [];
                 $sel = [];
                $n =(object)$n;
             //var_dump($n->origin);
               // echo "<br />TITLE:".$n['title'];
                    if(isset($n->id))$id = $n->id;
                    if(isset($n->title))$title = $n->title;
                    if(isset($n->slug))$slug = $n->slug;
                    if(isset($n->start_date)){
                        //echo $n->date;exit;
                        $sdate = date("m/d/Y",strtotime($n->start_date));
                        
                    }
                  //  if(isset($n->origin))$origin = $n->origin;
                    if(isset($n->content))$content = $n->content;
                    if(isset($n->created_by))$cby = $n->created_by;
                    if(isset($n->created_date))$cdate= $n->created_date;
                    if(isset($n->modified_by))$mby = $n->modified_by;
                    if(isset($n->modified_date))$mdate = $n->modified_date;
                    if(isset($n->status))$status = $n->status;
                   //var_dump($n->categories);
                   // exit;
                    if(isset($n->categories)){
                        foreach($n->categories->find_all() as $c ){
                          $cats[] = $c->name;
                          $sel[] = $c->id;
                        }
                        
                    }
                    //print_r($cats);//exit;
                    
                   //MORE INFO MODAL CONTENT
                    $info ="Created By: <strong>". ORM::factory("User",$cby)->email."</strong> on <strong>".date("m/d/Y g:ia",strtotime($cdate))."</strong><br />";
                    if($mby!=""){
                        $info .= "<br />Modified By: <strong>".ORM::factory("User",$mby)->email."</strong> on <strong>".date("m/d/Y g:ia",strtotime($mdate))."</strong><br />";
                    }
                    $info .="<br />Contents:<br />".$content;
                    
                    //CATEGORIES MODAL CONTENT
                    $gories = "<ul>";
                    foreach($cats as $c){
                    $gories .= "<li><span>".$c."</span></li>";
                    }
                    $gories .= "</ul>";
                    
                    
                    

//                    $allow_feature = 1;
//                    if($school_id === $origin){
//                        $allow_feature = 3;
//                    }
//                    $featured = cttech::getNewsFeatured($school_id,$id);
//                    if((bool)$featured){
//                        $status = 3;
//                    } else {
//                        if($status == 4){
//                            $status =2;
//                        }
//                    }
                ?>
            <tr data-recordid="<?=$id;?>">
                <td><?=$status;?></td>
                <td><?=implode(",",$sel);?></td>
                <td><span><?=$title;?></span></td>
                <td><span><?=$slug;?></span></td>
                <td data-order="<?=strtotime($sdate);?>"><span><?=$sdate;?></span></td>
                <td>
                     <a href='#' class='roles' data-toggle="modal" data-target="#news_cat_<?=$id;?>" data-recordid='<?=$id;?>'>Categories</span></a>
                       <div class="modal fade" id="news_cat_<?=$id;?>">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                              <h4 class="modal-title">Categories</h4>
                            </div>
                            <div class="modal-body">
                                <?=$gories;?> 
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
 
                            </div>
                          </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                      </div><!-- /.modal -->
                </td>
                <td>
                      <a href='#' class='roles' data-toggle="modal" data-target="#news_info_<?=$id;?>" data-recordid='<?=$id;?>'>Info</span></a>
                       <div class="modal fade" id="news_info_<?=$id;?>">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                              <h4 class="modal-title">Extra Info</h4>
                            </div>
                            <div class="modal-body">
                                <?=$info;?> 
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
 
                            </div>
                          </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                      </div><!-- /.modal -->
                    
                </td>
                <td  class="status" >

                      
                       <?=ybr::statusMenu($status,3); ?>
                    </td>


                    <td class='reset' data-recordid='<?= $id; ?>' data-username='<?= $user->username; ?>'>
                       
                        <div class="reset-box">
                            <a href='#' class='btn btn-default dropdown-toggle actions' data-toggle='dropdown' data-recordid='<?= $id; ?>'><span>Actions</span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a  href="/admin/news/create/<?= $id; ?>" >Edit</a></li>
                            </ul> 
                        </div>
                       
                    </td>
            </tr>
            
            
            <?php } 
            }
            ?>
        </tbody>
 </table>
 <a href="/admin/news/create" class="btn btn-primary btn-md">Create News</a>

</article> <!-- end "app-wrapper" -->
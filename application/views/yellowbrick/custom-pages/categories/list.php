<?php defined('SYSPATH') or die('No direct script access.');?>

<article id="categories" class="list">
    
    
    
    
<h1>Categories</h1>



 <table data-model="News" class="datatable-news table table-condensed table-bordered table-responsive" >
      <thead>
            <tr>
                
                <th style="width:150px;">Title</th>
                <th style="width:50px;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $id= "";
            $title = "";
           
            $action = "";
           
            //print_r($news);exit;
            if(!is_null($cats)){
            foreach($cats as $c){
                 
                $c =(object)$c;
             //var_dump($n->origin);
               // echo "<br />TITLE:".$n['title'];
                    if(isset($c->id))$id = $c->id;
                    if(isset($c->name))$title = $c->name;

                ?>
            <tr data-recordid="<?=$id;?>">
                <td><span><?=$title;?></span></td>



                    <td class='reset' data-recordid='<?= $id; ?>' data-username='<?= $user->username; ?>'>
                       
                        <div class="reset-box">
                            <a href='#' class='btn btn-default dropdown-toggle actions' data-toggle='dropdown' data-recordid='<?= $id; ?>'><span>Actions</span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a  href="/admin/categories/create/<?= $id; ?>" >Edit</a></li>
                            </ul> 
                        </div>
                       
                    </td>
            </tr>
            
            
            <?php } 
            }
            ?>
        </tbody>
 </table>
 <a href="/admin/categories/create" class="btn btn-primary btn-md">Create Category</a>

</article> <!-- end "app-wrapper" -->
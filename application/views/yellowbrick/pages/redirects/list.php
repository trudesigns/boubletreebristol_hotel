<?php defined('SYSPATH') or die('No direct script access.');?>

<article id="redirects" class="list">
    
    
    
    
<h1>URL Redirects</h1>

<div id="dt-filters">
    <aside id="filter-status">
           <select class="chosen-single" data-placeholder="Status">
            <option value="-1"></option>
            <option value="0">Deleted</option>
            <option value="1">Inactive</option>
            <option value="2">Active</option>
        </select>
    </aside>
    </div>

 <table data-model="Redirect" class="datatable table table-condensed table-bordered table-responsive" >
      <thead>
            <tr>
                <th>STATUS</th>
                <th style="width:150px;">Request Path</th>
                <th style="width:150px;">Destination</th>
                <th style="width:20px;">Hits</th>
                <th style="width:20px;">301</th>
                <th style="width:30px;">Info</th>
                 <th style="width:50px;">Status</th>
                <th style="width:50px;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $path = "";
            $dest = "";
            $notes = "";
            $s301 = "false";
            $status = "";
            $info ="";
            $hits =0;
            $last =null;
            $action = "";
            
            foreach($redirects as $r){
                    if(isset($r->alias))$path = $r->alias;
                    if(isset($r->destination))$dest = $r->destination;
                    if(isset($r->notes))$notes = $r->notes;
                    if(isset($r->is_301))$s301 = $r->is_301;
                    if(isset($r->created_by))$created = $r->created_by;
                    if(isset($r->hits))$hits = $r->hits;
                    if(isset($r->last_hit))$last = $r->last_hit;
                    if(isset($r->status))$status = $r->status;
                    
                        $info ="Created By: ". ORM::factory("User",$r->created_by)->email."<br />";
                       $info .= "<br />Modified By: ".ORM::factory("User",$r->modified_by)->email."<br />";
                        if($last != "0000-00-00 00:00:00" && !is_null($last)){
                          
                            $info .="<br />Last Hit: ".date("m/d/Y h:m:s",strtotime($last));
                        }
                        $info .="<br />Notes:<br />".$notes;
                   
                        if((bool)$s301){
                            $s301 = "True";
                        } else {
                            $s301 = "False";
                        }
                        
                ?>
            <tr data-recordid="<?=$r->id;?>">
                <td><?=$status;?></td>
                <td><div class="small-url"> <span><?=$path;?></span></div></td>
                <td><div class="small-url"><span><?=$dest;?></span></div></td>
                <td><span><?=$hits;?></span></td>
                <td><span><?=$s301;?></span></td>
                <td>
                      <a href='#' class='roles' data-toggle="modal" data-target="#redirects_info_<?=$r->id;?>" data-recordid='<?=$r->id;?>'>Info</span></a>
                       <div class="modal fade" id="redirects_info_<?=$r->id;?>">
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

                        <?= ybr::statusMenu($status); ?>



                    </td>


                    <td class='reset' data-recordid='<?= $r->id; ?>' data-username='<?= $user->username; ?>'>
                        <div class="reset-box">
                            <a href='#' class='btn btn-default dropdown-toggle actions' data-toggle='dropdown' data-recordid='<?= $r->id; ?>'><span>Actions</span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a  href="/admin/redirects/create/<?= $r->id; ?>" >Edit</a></li>
                            </ul> 
                        </div>
                    </td>
            </tr>
            
            
            <?php } ?>
        </tbody>
 </table>
 <a href="/admin/redirects/create" class="btn btn-primary btn-md">Create new Redirect</a>
</article> <!-- end "app-wrapper" -->
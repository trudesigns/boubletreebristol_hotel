<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<article id="users" class="list">
    
    
    <div id="dt-filters">
     <aside id="filter-status">
           <select class="chosen-single" data-placeholder="Filter by Status">
            <option value="-1"></option>
            <option value="0">Deleted</option>
            <option value="1">Inactive</option>
            <option value="2">Active</option>
        </select>
      </aside>
    </div>
    
    <table data-model="User" class="datatable table table-condensed table-bordered table-responsive">
      <thead>
            <tr>
                <th>STATUS</th>
                <th>Username/Email</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Roles/Groups</th>
                <th>Last Login</th>
                <th>Logins</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php  
            $username = "";
            $first = "";
            $last="";
            $logins = 0;
            $ltime = null;
            foreach ($users as $user) {
                if(isset($user->username))
                    $username = $user->username;
                if(isset($user->first))
                    $first = $user->first;
                if(isset($user->last))
                    $last = $user->last;
                if(isset($user->last_login))
                    $ltime = (int)$user->last_login;
                if(isset($user->logins))
                    $logins = (int)$user->logins;
                
                $cell_class = "";
                $status ="";
                switch((int)$user->status){
                    case 0:
                        $cell_class = "yb-deleted";
                        $status = "deleted";
                        break;
                    case 1:
                        $cell_class = "yb-inactive";
                        $status = "inactive";
                        break;
                    case 2:
                        $cell_class = "yb-active";
                        $status = "active";
                        break;
                }
                switch((int)$user->auth_scheme){
                    case 0:
                        $cell_class .= " native";
                        break;
                    case 1:
                        $cell_class .= " ldap";
                        break;
                    case 2:
                        $cell_class .= " openid";
                        break;
                }
                ?>
            <tr data-recordid="<?=$user->id;?>" class="<?= $cell_class; ?>">
                <td><?=$user->status;?></td>
                <td><span><?=$username;?></span></td>
                <td><span><?=$last;?></span></td>
                <td><span><?=$first;?></span></td>
                <td>
                    <a href='#' class='roles' data-toggle="modal" data-target="#user_groups_<?=$user->id;?>" data-userid='<?=$user->id;?>'>Groups</span></a>
                       <div class="modal fade" id="user_groups_<?=$user->id;?>">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                              <h4 class="modal-title">Roles/Groups</h4>
                            </div>
                            <div class="modal-body">
                                <?php
                                  $i = 1;
                              //  echo "CNT: ".count($all_roles);
                                  
                                  $grpr = array();
                                foreach ($all_roles as $role) { 
                                    $grps = $user->groups->find_all();
                                    foreach($grps as $g){
                                        $grpr[] = $g->id;
                                    }
                                  
                                   $chk = false;
                                    if(in_array($role->id,$grpr)){
                                        $chk = true;
                                    }
                                    echo "<div class='trees'>";
                                    echo "<div class='checkbox-inline'>";
                                    echo Form::checkbox("role_" . $role->id . "_user_" . $user->id, $role->id, $chk,array('data-userid'=>$user->id, 'class'=>'role-change'));
                                    echo  Form::label("role_" . $role->id . "_user_" . $user->id,$role->name);
                                   echo "&nbsp;&nbsp;<a href='#' class='popup' data-toggle='tooltip' data-placement='top' title='".$role->description."'><span class='glyphicon glyphicon-question-sign'>&nbsp;</span></a>";
                                    echo "</div>";
                                    echo "</div>";
                                }
                             //    var_dump($all_roles);exit;
                                ?>
                                
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
 
                            </div>
                          </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                      </div><!-- /.modal -->
                </td>
                <td><span>
                                
                                <?php
                                // var_dump($ltime);
                               //var_dump(strtotime($ltime));
                                
                                if(!is_null($ltime)){
                               //    echo "HSHSHS";
                                echo   date("m/d/Y g:ia", $ltime);
                                }?>
                    </span>
                </td>
                 <td><span><?=$logins;?></span></td>
                  <td  class="status" >
                     
                   <?= ybr::statusMenu($user->status); ?>
                       
  
                      
                 </td>
                 
                 
                 <td class='reset' data-userid='<?=$user->id;?>' data-username='<?=$user->username;?>'>
                     <div class="reset-box">
                     <a href='#' class='btn btn-default dropdown-toggle actions' data-toggle='dropdown' data-userid='<?=$user->id;?>'><span>Actions</span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="/admin/userCreate/<?=$user->id;?>" >Edit</a></li>
                        <li><a href="#" class='ajax' data-action='reset' data-toggle="modal" data-target="#reset-modal">Reset Password</a></li>
                    </ul> 
                     </div>
                 </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <a href="/admin/userCreate" class="btn btn-primary btn-md">Create new User</a>
    
    
    <div class="modal fade" id="reset-modal" >
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Reset Password</h4>
      </div>
      <div class="modal-body">
          <p class='start'>This action will reset <span class='user_name'></span> password and generate an e-mail to the address associated with that user.<br />Are you sure you want to reset this password?</p>
          <p class='msg' style='display:none;'></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="reset-pass">Reset Password</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    
    
    
</article>
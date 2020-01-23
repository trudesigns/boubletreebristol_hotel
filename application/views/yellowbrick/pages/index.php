<article id="dashboard">

<h1>Dashboard</h1>
<?php 
//echo "<pre>";
//var_dump($user->last);
//exit;
?>

<p>Welcome, <a href="/user/profile" class="yb-tooltip" title="Update my account settings. Change Username. Change Password."><?=$userdata->first." ".$userdata->last ?></a> | <small><strong>Last Login: </strong> <?=($user->last_login != "") ? date("m/d/Y g:ia",$user->last_login) : '&nbsp;' ?> | <strong>Total Logins:</strong> <?=ltrim($user->logins,'0'); ?></small>
    

<div class="yb-half-column">
    <h2>Site Stats</h2>
    
    <?php if(in_array("pages",$accessroles)){  ?>
    <div class="yb-stat-block"><a href="/admin/pages"><span class="yb-stat-block-num"><?=ORM::factory('Page')->find_all()->count() ?></span></a>Pages</div>
    <?php } ?>
    <?php if(in_array("redirects",$accessroles)){  ?>
    <div class="yb-stat-block"><a href="/admin/redirects"><span class="yb-stat-block-num"><?=ORM::factory('Redirect')->find_all()->count() ?></span></a>Redirects</div>
    <?php } ?>
    <?php if(in_array("menus",$accessroles)){  ?>
    <div class="yb-stat-block"><a href="/admin/menus"><span class="yb-stat-block-num"><?=ORM::factory('Menus')->find_all()->count() ?></span></a>Menus</div>
    <?php } ?>
    <?php if(in_array("users",$accessroles)){  ?>
    <div class="yb-stat-block"><a href="/admin/users"><span class="yb-stat-block-num"><?=ORM::factory('User')->where('last','NOT LIKE', '%[Deleted User]')->find_all()->count() ?></span></a>Users</div>
    <?php } ?>
    
    <br clear="all"><br clear="all">
    <h2>Custom Homepage Tools</h2>
        
     <a href="/admin/edit?page_id=1&block_id=7&version_id=1" class="yb-app-link">Slider</a>
    <a href="/admin/edit?page_id=1&block_id=6&version_id=1" class="yb-app-link">Callouts</a>
    <a href="/admin/edit?page_id=1&block_id=8&version_id=1" class="yb-app-link">Specials</a>
<?php if(in_array("dashboard",$accessroles)){  ?>
<h2>Custom Sitewide Tools</h2>
<a href="/admin/news" class="yb-app-link">News</a>
<a href="/admin/categories" class="yb-app-link">Categories</a>
<?php } ?>
    
 
    
 <br clear="all"><br clear="all">
 <?php if(in_array("admin",$accessroles)){  ?>
 <h2>System Info:</h2>
 
 <div class="col-xs-4">
 <h4>Yellow Brick Version</h4>
 <div class="alert alert-info"><?=Kohana::$config->load('yb')->version; ?></div>
 </div>
 
 <div class="col-xs-4">
 <h4>Kohana Version</h4>
 <div class="alert alert-info"><?=Kohana::VERSION;?></div>
 </div>
 
 
 <div class="col-xs-4">
 <h4>Apache Version</h4>
 <div class="alert alert-info"><?=apache_get_version();?></div>
 </div>
 <div class="col-xs-4">
 <h4>PHP Version</h4>
 <div class="alert alert-info"><?=phpversion();?></div>
 </div>
  <div class="col-xs-4">
 <h4>MYSQL Version</h4>
 <div class="alert alert-info"><?= DB::query(Database::SELECT, 'SELECT version() as v;')->execute()->get('v', 0);?></div>
  </div>
 <div class="col-xs-12">
     <h4>Folders Permission</h4>
     <div class="row">

         
     
         <div class="col-xs-4"  title="<?=APPPATH;?>cache/ ">  
          <?php if(is_writable(APPPATH."/cache/")){?>
            <div class="alert alert-success ">
          <?php }else {?>
             <div class='alert alert-danger'> 
          <?php  }?>
              <span>The Cache folder</span>
                 
            </div>
            </div>
         
     
                 
           <div class="col-xs-4"  title="<?=APPPATH;?>logs/ ">  
          <?php if(is_writable(APPPATH."/logs/")){?>
            <div class="alert alert-success ">
          <?php }else {?>
             <div class='alert alert-danger'> 
          <?php  }?>
              <span>The Logs folder</span>
                 
            </div>
            </div>
            <div class="col-xs-4"  title="<?=DOCROOT;?>assets/uploads/ ">  
          <?php if(is_writable(DOCROOT."assets/uploads/")){?>
            <div class="alert alert-success ">
          <?php }else {?>
             <div class='alert alert-danger'> 
          <?php  }?>
              <span>The Upload folder</span>
                 
            </div>
            </div>
            


 </div>
            </div>
 <div class="col-xs-12">
 <h4>PHP Extensions</h4>
 <div class="row">

<?php 
//print_r($extension);
foreach($extension as $ext){?>
     <div class="col-xs-4">
            <?php if (!extension_loaded($ext)) {?>
                
                <div class="alert alert-danger"><span class="txt"><?=$ext; ?></span>
                <span class="glyphicon glyphicon-ban-circle pull-right">&nbsp;</span>
             
               
           <?php } else {?>
                <div class="alert alert-success"><span class="txt"
                       <?php if( phpversion($ext) !== false){?>
                        title="V:<?=phpversion($ext);?>"
                       <?php } ?>
                    ><?=$ext;?></span>
            
               
                <span class="glyphicon glyphicon-ok-circle pull-right">&nbsp;</span>
            <?php } ?>
         </div></div>
         <?php } ?>
     

</div>
 </div>
 <?php } ?>
</div><!-- end left side "half-column" -->

<div class="yb-half-column">
	<?php
	//$user = Auth::instance()->get_user();
	
$contents = ORM::factory('Content')
			->where('updated_by','=',$user->id)
			->where('revision_date','=', DB::expr('(SELECT revision_date FROM contents WHERE page_id = content.page_id AND block_id = content.block_id AND updated_by = '.$userdata->id.' ORDER BY revision_date DESC LIMIT 1)') )
			->order_by('revision_date','desc')
			->limit(10)
			->find_all();
?>
    <h2>My Recently Edited Content</h2>
    <ul id="yb-recent-content-wrap">
    <?php	$i=0;
        foreach($contents as $content)
        {
            if($i==0)
            {
                $class="first";
            }
            elseif($i== count($contents) -1)
            {
                $class="last";
            }
            else
            {
                $class="";
            }
    ?>
        <li class="<?=$class ?>">
         
        <?php if($content->publish_date == "0000-00-00 00:00:00"): ?>
        	<div class="yb-revision-status orange yb-tooltip" title="This content has not been published."></div>
        <?php elseif($content->live == 1): ?>
        	<div class="yb-revision-status green yb-tooltip" title="This content is currently being shown on the website."></div>
        <?php else: ?>
        	<div class="yb-revision-status red yb-tooltip" title="This content was previously live on the website."></div>
        <?php endif; ?>
       <a href="/admin/edit/<?=$content->id ?>">
        <?= date("m/d/Y h:ia",strtotime($content->revision_date)) ?>

    | <strong><?=$content->page->label ?>: <?=$content->block->name ?></strong>
    
    </a></li>
    <?php 	$i++;
        } ?>
    
    
    </ul>
    <br clear="all">
    
</div><!-- end right side "half-column" -->





<div class="clear-fix"></div>


<div class="clear-fix"></div>
</article>

 
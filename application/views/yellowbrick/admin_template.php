<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<!doctype html>
<!--[if lt IE 7 ]><html class="ie ielt9 ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ielt9 ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ielt9 ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]><html class="ie ielt10 ie9" lang="en"> <![endif]-->
<!--[if (gte IE 10)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
    <head>
        <meta charset="UTF-8">

        <title>Yellow Brick CMS</title>

        <script>
            var sessiontoken = "<?= Session::instance()->get('ybr_loggedin'); ?>";
            var BASE_PATH = "/";							// root path to this site
            var XHR_PATH = BASE_PATH + "admin/request/"; 	// path to the admin "ajax" controller
            var XHR_PUBLIC_PATH = BASE_PATH + "request/";	// path to the front-facing "ajax" controller
            var ckeditor_cachebuster = "<?=Kohana::$config->load('siteconfig.ckeditor_cachebuster');?>"; // FORM SITECONFIG
        </script>

        <?php
//loop and load each style
foreach ($_this->template->tstyles as $style => $type) {
	echo HTML::style($style,array('media' => $type)), "\n";
}
foreach ($_this->template->mstyles as $style => $type) {
	echo HTML::style($style,array('media' => $type)), "\n";
}
foreach ($_this->template->bstyles as $style => $type) {
	echo HTML::style($style,array('media' => $type)), "\n";
}

//extra page-specific header code
        ?>


    </head>

    <body>

        <?php
        // echo "<pre>";
        //var_dump($accessroles);//exit;
        ?>

        <aside id="yb-status-msg-wrap">
            <div id="yb-status-msg"></div><div id="yb-status-msg-close"><a onclick="clearYBmsg();">X</a></div>
        </aside>

        <main id="yb-wrap">

            <header id="yb-header">


                <div id="yb-top-nav-wrap">
                    <div class="yb-container">
                        <div class="yb-top-nav-btn align-left first">
                            <a href="/" class="yb-tooltip" data-toggle="tooltip" data-placement="bottom"  title="Website's public-facing homepage" target="_blank">VIEW SITE</a>
                        </div>
                        <div class="yb-top-nav-btn align-right last">
                            <a href="<?= PATH_BASE ?>user/signout">LOG OUT</a>
                        </div>
                        <div class="yb-top-nav-btn inner align-right">
                            <a href="<?= PATH_BASE ?>user/profile" data-toggle="tooltip" data-placement="bottom"  class="yb-tooltip" title="Update my account settings. Change Username. Change Password.">MY ACCOUNT</a>
                        </div>
                    </div>
                </div>
                <div class="yb-container">
                    <h1><a href="/admin">Yellow Brick CMS</a></h1>

                    <div id="yb-main-nav">
                        <ul class="sf-menu">
                            <li><a href="<?= PATH_BASE; ?>admin" title="" class="gradient <?php if ($_SERVER['REQUEST_URI'] === '/admin') {
            echo 'current_section';
        } ?>">Dashboard</a></li>
                            <?php if(in_array("upload",$accessroles)){  ?>
                            <li><a href="<?= PATH_BASE; ?>admin/files" class="gradient <?php if ($_SERVER['REQUEST_URI'] === '/admin/files') {
            echo 'current_section';
        } ?>">Files</a></li>
                            <?php  } ?>


                                <li><a href="javascript:;" class="gradient
                                    <?php
                                    switch ($_SERVER['REQUEST_URI']) {
                                        case '/admin/pages';
                                        case '/admin/menus';
                                        case '/admin/users';
                                        case '/admin/redirects';
                                        case '/admin/forms';
                                            echo 'current_section';
                                            break;
                                    }
                                    ?>
                                       ">Site Management</a>
                                    <ul>
                                        <?php if(in_array("pages",$accessroles)){  ?>
                                        <li><a href="<?= PATH_BASE; ?>admin/pages" class="gradient">Pages</a></li>
                                        <?php  } ?>
                                        <?php if(in_array("menus",$accessroles)){  ?>
                                        <li><a href="<?= PATH_BASE; ?>admin/menus" class="gradient">Menus</a></li>
                                        <?php  } ?>
                                        <?php if(in_array("redirects",$accessroles)){  ?>
                                        <li><a href="<?= PATH_BASE; ?>admin/redirects" class="gradient">URL Redirects</a></li>
                                        <?php  } ?>
                                        <?php if(in_array("users",$accessroles)){  ?>
                                        <li><a href="<?= PATH_BASE; ?>admin/users" class="gradient">Users</a></li>
                                        <?php  } ?>   
                                    </ul>
                                </li>
          
                            <?php /* if (Auth::instance()->logged_in('designer') || Auth::instance()->logged_in('developer') ){ */ ?>


                                <?php if (in_array("developer", $accessroles)) { ?>

                                <li><a href="javascript:;" class="gradient 
                                    <?php
                                    switch ($_SERVER['REQUEST_URI']) {
                                        case '/admin/blocks';
                                        case '/admin/templates';
                                        case '/admin/menussetup';
                                        case '/admin/roles';
                                        case '/admin/versions';
                                            echo 'current_section';
                                            break;
                                    }
                                    ?>
                                       ">Developer</a>
                                    <ul class="lock-right">
                                        <li><a href="<?= PATH_BASE; ?>admin/blocks" class="gradient">Content Blocks</a></li>
                                        <li><a href="<?= PATH_BASE; ?>admin/templates" class="gradient">Templates</a></li>
                                        <li><a href="<?= PATH_BASE; ?>admin/menussetup" class="gradient">Menus Setup</a></li>
                                        <?php /* 	if (Auth::instance()->logged_in('developer') ){ */ ?>
   
                                            <li><a href="<?= PATH_BASE; ?>admin/roles" class="gradient">User Role Types</a></li>
                                            <li><a href="<?= PATH_BASE; ?>admin/versions" class="gradient">Content Versions</a></li>
                                            <li><a href="<?= PATH_BASE; ?>admin/tasks" class="gradient">Tasks</a></li>
                                    </ul>
                                </li>
<?php } ?>

                        </ul>
                    </div>
                </div> 
            </header> <!-- end #yb-header -->
<?= (isset($showContentMenu) && $showContentMenu) ? '<div id="yb-pages-menu-back"></div>' : '' ?>

            <div id="yb-subheader" class="tile"><div class="yb-container">CONTENT MANAGEMENT SYSTEM</div></div>

            <section id="yb-body" class="clearfix">
                <div class="yb-container">

                    <?php /* if(isset($showContentMenu) && $showContentMenu && 
                      ( Auth::instance()->logged_in('content') ||
                      Auth::instance()->logged_in('content-limited') ||
                      Auth::instance()->logged_in('developer')
                      )
                      ){ */
                    ?>
                            <?php if (isset($showContentMenu) && $showContentMenu && in_array("edit", $accessroles)) { ?>
                        <div id="yb-pages-menu">
                            <h3>EDIT PAGE CONTENT</h3>
                            <div class="sf-menu">
                                <?php
                                $default_block = 3;
                                $default_version = 1;

                                $pageObj = new Model_Page;
                                $menu = $pageObj->getPages(array('active_only' => false));
                                $li_format = '<a href=\"' . PATH_BASE . 'admin/edit?page_id=$item->thisID&block_id=' . $default_block . '&version_id=' . $default_version;
                                $li_format.= '\" data-active=\"$item->active\" data-role=\"$item->required_role\"';
                                $li_format.= 'data-startdate=\"$item->start_date\" data-enddate=\"$item->end_date\">$item->label</a>'; // raw php code to be eval'd in function
                                $block_id = ORM::factory("Contentblock")->where("name","=","Main Content")->find()->id;
                                echo ybr::makeCMSMenufromPage(ybr::getRootPages(), [], $block_id);
                          //      echo $pageObj->drawMenu_UL($menu, array('li_format' => $li_format));
                                ?>
                            </div>
                        </div><!-- end #yb-pages-menu -->

                        <?php } ?>

                    <div id="yb-page" <?= (isset($showContentMenu) && $showContentMenu) ? '' : 'class="full"' ?>>
<?php 
//var_dump($content);exit;
echo $content->bind('user', $user); ?>
                    </div><!-- end #yb-page -->

                </div>
            </section><!-- end #yb-body -->
            <footer id="yb-footer"><img src="<?= PATH_BASE; ?>yb-assets/images/logo-yellowbrick_sm.jpg" width="103" height="40" title="Yellow Brick - A Better Way to Website Management" alt="Yellow Brick - A Better Way to Website Management"></footer>
        <?php /* this hidden field is required, do not delete */ ?>
            <input type="hidden" id="user-username" value="<?= $user->username ?>">

            
            
            <div class="modal fade" id="reenter-password">
                <div class="modal-dialog">
                  <div class="modal-content">
                      <?=Form::open("#",array("novalidate","id"=>"reenter-password-form")); ?>
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                      <h4 class="modal-title">Session Timed Out</h4>
                    </div>
                    <div class="modal-body">
                       
                        <p>Your session has timed out and could not be extended.  Please enter your re-enter your password to continue.</p>
                        <?=Form::hidden('csrf', Security::token());?>
                        <?=Form::hidden('email', $user->username,array('id'=>'email'));?>
                        <div class="form-group">
                            <label for="rpassword"><span class="message"></span></label>
                         <?=Form::password("password",NULL,array('class'=>'form-control','required',"id"=>"password","placeholder"=>"Password"));?>
                        </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-primary" id="login-btn">Re-Login</button>
                    </div>
                         <?=Form::close(); ?>
                  </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
           </div><!-- /.modal -->
            
            
            
        </main><!-- end #yb-wrap -->
        <?php
//loop and load each script
        foreach ($_this->template->tscripts as $file) {
	echo HTML::script($file), "\n";
        }
        foreach ($_this->template->mscripts as $file) {
                echo HTML::script($file), "\n";
        }
        foreach ($_this->template->bscripts as $file) {
                echo HTML::script($file), "\n";
        }
        ?>

    </body>
</html>
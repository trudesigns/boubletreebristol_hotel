<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Default extends Controller_Admin {

    //public $auth_required = array('login', 'admin'); // both of these roles are required by default
    
    
    /**
     * lock down functions to more specific roles
     * name of the action =>array or permission roles
     */
       public $acl = [
           "index"              => ["read"]
           ,"files"               => ["upload"]
           ,"edit"                => ['write']
           ,"ckEditorLinks"  => ['write']
           ,"versions"          => ['publish']
           ,"diff"                 => ["publish"]
           ,'pages'              => ["pages"]
           ,'menus'             => ["menus"]
           ,"redirects"         => ["redirects"]
           ,"users"              => ["users"]
           ,"roles"               => ["developer"]
           ,"blocks"             => ["developer"]
           ,"templates"        => ["developer"]
           ,"menussetup"      => ["developer"]
           ,"forms"              => ['developer']
           ,'userCreate'        =>["users"]
       ];
    

    /*     * ** Admin Applications *** */

    public function __construct(\Request $request, \Response $response) {
      //  var_dump($this->user);exit;
        if(is_null($this->user)){
            if(is_array(Session::instance()->get("userdata"))){
                $this->user = (object) Session::instance()->get("userdata");
            } 
        } 
          //  echo "32423HHHHH";exit;
       
        
        if(!is_array($this->user_access_roles)){
            $this->user_access_roles = Session::instance()->get("accessroles");
        }
        if(!is_object($this->user)){
            //echo "123HERE";exit;
            $this->redirect('user/signin/?goto=' . $_SERVER['REQUEST_URI']);
        }
         if(!ybr::acl_group($this->acl)){
            die("You do not have access to this function.");
        }    
    
        
        //var_dump($this->user);
        parent::__construct($request, $response);
       
        
    }

    
    /**
     * Get the roles that the group (object or array or int) is assigned.
     * @param object/array/int $group
     */
  
    
    /**
     * load index "Dashboard" page
     */
    function action_index() {
   //print_r($this->user_access_roles);
       
        $view = new View('yellowbrick/pages/index');
       $view->set("userdata",(object)$this->user);
        $view->set("accessroles",$this->user_access_roles);
         $ext_conf = Kohana::$config->load('extension')->php;
        $view->set("extension",$ext_conf);
        
        
       // print_r(Session::instance()->get("accessroles"));
      
        $this->template->showContentMenu = true;
        $styles =[
            'yb-assets/css/dashboard.css'=>"screen,projection"
        ];
        $this->template->mstyles = $styles;
        //echo "shhshshs";exit;
        //$_SESSION['yb_status_message'] = 'Welcome, thanks for logging in.';		
        $this->template->content = $view;
    }

    /**
     * show the ckFinder File Manager in an iframe view
     */
    public function action_files() {
        //$_SESSION['ckfinder_baseURL'] = '';

        setcookie("ckfinder_baseURL","",0,"/");
        $view = new View('yellowbrick/pages/filemanager');
        $styles =[
            'yb-assets/css/files.css'=>"screen,projection"
        ];
        $this->template->mstyles = $styles;
        $this->template->content = $view;
    }

    /**
     * edit content for given block (and version, if applicable)
     *
     * Two ways to access this page:
     *
     * 1) if the last param of the url is a valid content ID, then that is the block that will load
     * - otherwise -
     * 2a) passing a page_id and block_id will return the most recent draft (of any version) of the given block for that given page.
     * 2b) optionally, passing a version ID will return the most recent draft of the specified version of the given block and page.
     *
     * eg 1 ) /admin/edit/43  	
     * or 2a) /admin/edit/?page_id=3&block_id=2
     * or 2b) /admin/edit/?page_id=3&block_id=2&version_id=1
     *
     * if no content already exists for a requested page, block & version then one will be created automatically
     */
    public function action_edit() {
        
        $post = $this->request->post();
//       echo "<prE>";
//        print_r($post);
//        exit;
     //  echo "ID: ".$this->request->param('id');exit;
        $content_id = ( $this->request->param('id') && is_numeric($this->request->param('id')) ) ? $this->request->param('id') : false;
        $content = ORM::factory('Content', $content_id);


        $contentObj = new Model_Content;

        // process $_POST data
        if ($content->loaded() && isset($_POST['content'])) {
            $newid = $contentObj->savecontent($_POST, $content);

            if (is_numeric($newid)) {
                $this->redirect(PATH_BASE . "admin/edit/" . $newid);
            } else {
                exit("Error: could not save content");
            }
        }

        // if content id is not provided but page and block IDs are passed, deteremine the content block to load
        if (!$content->loaded()) {
            $params = array('page_id' => $_GET['page_id'],
                'block_id' => $_GET['block_id'],
                'version_id' => (isset($_GET['version_id'])) ? $_GET['version_id'] : 1,
                'return_id' => true
            );
            $content = $contentObj->findRevision($params);

            if (is_numeric($content)) {
                $this->redirect(PATH_BASE . "admin/edit/" . $content);
            } else {
                exit("ERROR: could not find revision");
            }
        }

        $view = new View('yellowbrick/pages/edit');
        $view->page = ORM::factory('Page', $content->page_id);
 

        // if the page has a required role, default ckfinder to open the files in the correct secure folder
        if ($view->page->required_role != "") {
            setcookie('ckfinder_baseURL', $view->page->required_role,0,"/");
        } else {
            setcookie('ckfinder_baseURL', '',0,"/");
        }

        $template = ORM::factory('Template', $view->page->template_id);
        
        //all revisions for content block (as it relates to this page and version)
        $view->revisions = $content->getRevisions(array('page_id' => $content->page_id, 'block_id' => $content->block_id, 'version_id' => $content->version_id));

        
        
        //find other content blocks associated with this page's template
        $view->other_blocks = ORM::factory('Contentblock')
                ->where('id', 'IN', DB::Select('content_block_id')->from('template_content_blocks')->where('template_id', '=', $view->page->template_id))
                ->find_all();

        //a list of other versions available to any content block
        $view->contentversions = ORM::factory('Contentversion')->find_all();


        $this->template->showContentMenu = true;

        $this->template->mstyles['yb-assets/css/edit.css'] = 'screen, projection';
        $this->template->mscripts[] = 'yb-assets/js/edit.js';

        //these to object only need to be set if the edit view is also loading the editor view on load
        // if we're using javascrip/ajax to call in that view after the fact, this should be removed
        $view->content = $content;
        $view->block = ORM::factory('Contentblock', $content->block_id);

        $this->template->content = $view;
    }

    /**
     * show link picker tool in CKEditor with currently available pages
     * modifyable in ckeditor/plugins/link/dialogs/link.js
     */
    public function action_ckEditorLinks() {
        echo new View('yellowbrick/pages/ckeditor_links');
        exit();
    }

    public function action_versions() {
        $params = array('model' => 'Contentversion', // model for table tool is based on
            'view' => 'yellowbrick/pages/versions', // view of CRUD tool form
            'post' => (isset($_POST['submit'])) ? $_POST : false,
            'post_ignore' => array('submit'), // array of posted fields to ignore when saving to database
            'validate' => array('name' => 'not_empty'), // array of $_POST fields to validate
            'crud_all_order_by' => 'name', // optional column name to order "crud_all" list by
            'custom_errors' => '', // page with array of custom error messages
            'delete_confirmation_value' => 'delete'   // the value of $_POST['delete'] if this item is being deleted
        );

        $this->template->mscripts[] = 'yb-assets/js/versions.js';

        $this->CRUD($params);
    }

    // compare the source code of a given content block with its previous version
    public function action_diff() {
        $content_id = ( $this->request->param('id') && is_numeric($this->request->param('id')) ) ? $this->request->param('id') : false;
        $content = ORM::factory('Content', $content_id);
        $previous_content = ORM::factory('Content')->where('page_id', '=', $content->page_id)->where('revision_date', '<', $content->revision_date)->order_by('revision_date', 'asc')->limit(1)->find();

        $view = new View('yellowbrick/pages/diff');
        $view->content = $content;
        $view->previous_content = $previous_content;

        $this->template->mstyles['yb-assets/plugins/tablesorter/style.css'] = 'screen, projection';
        $this->template->mscripts[] = 'yb-assets/plugins/tablesorter/jquery.tablesorter.min.js';
        $this->template->mscripts[] = 'yb-assets/js/diff.js';
        $this->template->showContentMenu = false;
        $this->template->content = $view;
    }

    /**
     * Manage Pages  
     *
     * note, most functions on this page are passed through the request controller as XHR calls
     *
     */
    public function action_pages() {
        $view = new View('yellowbrick/pages/pages');
        $view->set("userdata",(object)$this->user);
        $view->set("accessroles",$this->user_access_roles);
      //  $view->set("pages",ybr::getAllPages());
        //$this->template->styles['yb-assets/css/pages.css'] = 'screen, projection';
        $view->set("pages",ybr::getRootPages());
        $templates = ORM::factory('Template')->where('type','=','page')->find_all();
        $temp =[];
        foreach($templates as $template){	
            $params = "";
            if(isset($template->parameters)){
                $params = "";
            }
            $parameters = json_decode($params);
            // don't show unavailable templates to non-developers

            if(isset($parameters->available) && $parameters->available == 0 && !in_array("config",$this->user_access_roles) && !Auth::instance()->logged_in('designer') ){	
                            continue;
            }
            $temp[$template->id] =$template->name;
        }
        
        $view->set("templates",$temp);
        
        $groups = ORM::factory("Group")->find_all();
        $g = [];
        foreach($groups as $gp){
            $g[$gp->name] =$gp->name; 
        }
        $view->set('groups',$g);
        //$this->template->mscripts[] = 'yb-assets/plugins/jquery.nestable.js';
       // $this->template->mscripts[] = 'yb-assets/js/pages.js';
        $this->template->content = $view;
    }

    /**
     * Manage Menus  
     *
     * note, most functions on this page are passed through the request controller as XHR calls
     *
     */
    public function action_menus() {
        $menu_id = ( $this->request->param('id') && is_numeric($this->request->param('id')) ) ? $this->request->param('id') : false;

        $view = new View('yellowbrick/pages/menus');

        //$this->template->styles['yb-assets/css/menus.css'] = 'screen, projection';
      //  $this->template->scripts[] = 'yb-assets/plugins/jquery.nestable.js';
       // $this->template->scripts[] = 'yb-assets/js/menus.js';

        if (Auth::instance()->logged_in('developer') || Auth::instance()->logged_in('designer')) {
            $view->crud_all = ORM::factory('Menus')->find_all();
        } else {
            $view->crud_all = ORM::factory('Menus')->where('active', '=', 1)->find_all();
        }

        $view->crud_selected = ($menu_id) ? ORM::factory('Menus', $menu_id) : false;
        $this->template->content = $view;
    }


    public function action_users() {
        $view = new View('yellowbrick/pages/users');
       $editid= null; 
        if(!is_null($this->request->param('id'))){
               $editid = $this->request->param('id');
            }
            
         //var_dump($editid);
        if(is_null($editid)){
            $q = ORM::factory('User')->with('groups')->order_by('last')->find_all();
        } else {
             $q = ORM::factory('User',(int)$editid)->with('groups');
        }
        $view->all_roles = ORM::factory('Group')->find_all();
        

        //$this->template->scripts[] = 'yb-assets/js/users.js';
     //   $this->template->styles['yb-assets/css/users.css'] = 'screen, projection';
       
        //echo $q->last_query();
        $view->users = $q;
         $view->set("userdata",(object)$this->user);
        $view->set("accessroles",$this->user_access_roles);
        
        $this->template->content = $view;
    }
    
    

    /*     * ** Developer Applications *** */

    /**
     * Manage user Roles using CRUD functionality
     */
    public function action_roles() {

        if (isset($_POST['name'])) {
            $_POST['role_type'] = (isset($_POST['role_type'])) ? $_POST['role_type'] : '';
        }

        $params = array('model' => 'Role', // model for table tool is based on
            'view' => 'yellowbrick/pages/roles', // view of CRUD tool form
            'post' => (isset($_POST['submit'])) ? $_POST : false,
            'post_ignore' => array('submit'), // array of posted fields to ignore when saving to database
            'validate' => array('name' => 'not_empty'), // array of $_POST fields to validate
            'crud_all_order_by' => 'name', // optional column name to order "crud_all" list by
            'custom_errors' => '', // page with array of custom error messages
            'delete_confirmation_value' => 'delete'   // the value of $_POST['delete'] if this item is being deleted
        );

        $this->template->mscripts[] = 'yb-assets/js/roles.js';

        $this->CRUD($params);
    }

    /**
     * Manage Content Blocks using CRUD functionality
     */
    public function action_blocks() {

        if (isset($_POST['input_parameters'])) {
            $_POST['input_parameters'] = html_entity_decode($_POST['input_parameters']);
        }

        $params = array('model' => 'Contentblock', // model for table tool is based on
            'view' => 'yellowbrick/pages/blocks', // view of CRUD tool form
            'post' => (isset($_POST['submit'])) ? $_POST : false,
            'post_ignore' => array('submit'), // array of posted fields to ignore when saving to database
            'validate' => array('name' => 'not_empty', // array of $_POST fields to validate
                'objectkey' => 'not_empty',
                'output_type' => 'not_empty'
            ),
            'delete_confirmation_value' => 'delete', // the value of $_POST['delete'] if this item is being deleted				   
            'crud_all_order_by' => 'name', // optional column name to order "crud_all" list by
            'custom_errors' => ''       // page with array of custom error messages
        );

        $this->template->mscripts[] = 'yb-assets/js/blocks.js';

        $this->CRUD($params);
    }

    /**
     * Manage Templates using CRUD functionality
     */
    public function action_templates() {

        $post_ignore = array('submit');
        if (isset($_POST['submit'])) {
            $parameters = array("shell", "layout", "page", "available", "controller", "controller_action", "dynamic_uri");
            foreach ($parameters as $parameter) {
                $postname = 'parameters_' . $parameter;
                $post_ignore[] = $postname;
                if (isset($_POST[$postname]) && $_POST[$postname] != "") {
                    $saveParameters[$parameter] = $_POST[$postname];
                }
            }

            if (!isset($saveParameters['available'])) {
                $saveParameters['available'] = 0; // if "available" wasn't sent, set it as 0
            }

            $_POST['parameters'] = ($_POST['type'] == "page") ? json_encode($saveParameters) : $_POST['parameters'];
        }

        $params = array('model' => 'Template', // model for table tool is based on
            'view' => 'yellowbrick/pages/templates', // view of CRUD tool form
            'post' => (isset($_POST['submit'])) ? $_POST : false,
            'post_ignore' => $post_ignore, // array of posted fields to ignore when saving to database
            'validate' => array('name' => 'not_empty'), // array of $_POST fields to validate
            'crud_all_order_by' => 'name', // optional column name to order "crud_all" list by
            'delete_confirmation_value' => 'delete', // the value of $_POST['delete'] if this item is being deleted
            'custom_errors' => ''       // page with array of custom error messages
        );

        $this->template->mscripts[] = 'yb-assets/js/templates.js?' . time();

        $this->CRUD($params);
    }

    /**
     * Manage Menu Setup using CRUD functionality
     */
    public function action_menussetup() {
        if (isset($_POST['submit'])) {
            $_POST['active'] = (isset($_POST['active'])) ? $_POST['active'] : 0;
        }

        $params = array('model' => 'Menus', // model for table tool is based on
            'view' => 'yellowbrick/pages/menus-setup', // view of CRUD tool form
            'post' => (isset($_POST['submit'])) ? $_POST : false,
            'post_ignore' => array('submit'), // array of posted fields to ignore when saving to database
            'validate' => array('name' => 'not_empty'  // array of $_POST fields to validate
            ),
            'delete_confirmation_value' => 'delete', // the value of $_POST['delete'] if this item is being deleted				   
            'crud_all_order_by' => 'name', // optional column name to order "crud_all" list by
            'custom_errors' => ''       // page with array of custom error messages
        );

        $this->template->mscripts[] = 'yb-assets/js/menussetup.js';

        $this->CRUD($params);
    }

    /**
     * Manage Forms
     */
    public function action_forms() {
        $form_id = ( $this->request->param('id') && is_numeric($this->request->param('id')) ) ? $this->request->param('id') : false;

        $view = new View('yellowbrick/pages/form');
        $view->crud_all = ORM::factory('Form')->find_all();
        $view->crud_selected = (isset($form_id) && is_numeric($form_id)) ? ORM::factory('Form', $form_id) : false;

        $this->template->mstyles['yb-assets/css/pages.css'] = 'screen, projection';
        $this->template->mscripts[] = 'yb-assets/js/form.js';

        $this->template->content = $view;
    }

    /*     * **** Applications Support ********
     * CRUD tool for standard Create, Read, Update & Delete functionality
     *
     */

    public function CRUD($params) {
        $id = ( $this->request->param('id') && is_numeric($this->request->param('id')) ) ? $this->request->param('id') : false;
        $view = new View($params['view']);
       // echo "DEFAULT: ".$params['model'];
        
        $crud_selected = ORM::factory($params['model'], $id);

        //	var_dump($params);
        //	die();

        if (isset($params['post']['delete']) && array_key_exists('delete_confirmation_value', $params) && $params['post']['delete'] == $params['delete_confirmation_value']) {
            // legacy delete process
            $crud_selected->delete();

            // set status message, if provided by the action
            $_SESSION['yb_status_message'] = isset($params['delete-status-msg']) ? $params['delete-status-msg'] : '';
        } elseif (isset($params['post']['delete']) && array_key_exists('delete_confirmation', $params) && $params['post']['delete'] == $params['delete_confirmation']) {

            // new delete process, currently just for action_redirects()
            $crud_selected->delete();

            // set status message, if provided by the action
            $_SESSION['yb_status_message'] = isset($params['delete-status-msg']) ? $params['delete-status-msg'] : '';
        } elseif (is_array($params['post'])) {

            foreach ($params['post'] as $key => $val) {
                $post[$key] = trim($val); // apply filter to each field
            }

            $valid = true; // presume valid unless proven otherwise below
            if (array_key_exists('validate', $params)) {
              //  echo "POST: ".$post;
                $validate = Validation::factory($post);
                foreach ($params['validate'] as $field => $rule) {
                    $validate->rule($field, $rule);
                }
                $valid = $validate->check();
                if (!$valid) {
                    $errmsgs = ( array_key_exists('custom_errors', $params)) ? $params['custom_errors'] : '';
                    $view->errors = $validate->errors($errmsgs); // get the errors
                    $crud_selected = (object) $post;  // pass the post data back to the view so the user can see their mistakes :)
                    $crud_selected->id = $id;   // re-assign the ID though
                }
            }

            if ($valid) {
                foreach ($post as $key => $val) {
                    if (array_key_exists('post_ignore', $params) && in_array($key, $params['post_ignore'])) {
                        continue;
                    }

                    $crud_selected->$key = $val;
                }
                // commit changes
                $crud_selected->save();

                // set status message, if provided by the action
                $_SESSION['yb_status_message'] = isset($params['update-status-msg']) ? $params['update-status-msg'] : '';

                $fwd = ($this->request->param('id') && is_numeric($this->request->param('id')) ) ? '' : '/' . $crud_selected->id;

                $this->redirect(rtrim($_SERVER['REQUEST_URI'], "/") . $fwd);
            }
        }// end check for post

        if (array_key_exists('crud_all_order_by', $params)) {
            if (is_array($params['crud_all_order_by'])) {
                $crud_all_order_by = key($params['crud_all_order_by']);
                $order_by_direction = $params['crud_all_order_by'][$crud_all_order_by];
            } else {
                $crud_all_order_by = $params['crud_all_order_by'];
                $order_by_direction = 'asc';
            }
        } else {
            $crud_all_order_by = 'id';
            $order_by_direction = 'asc';
        }

        $view->crud_all = ORM::factory($params['model'])->order_by($crud_all_order_by, $order_by_direction)->find_all();
        $view->crud_selected = (isset($crud_selected->id)) ? $crud_selected : false;
        $this->template->content = $view;
    }
    
    
    public function action_userCreate()
    {
            $view = new View('public/pages/user/form');
            $editid =null;
            if(!is_null($this->request->param('id'))){
               $editid = $this->request->param('id');
            }
           $user = null;
           //$member= null;
        //var_dump($_POST);
         //exit;
        //   echo $editid;exit;
        if(!is_null($editid)){
              $user = ORM::factory("User")->where("id","=",(int)$editid)->find();
              //echo $user->last_query();
             // echo $user->email;
        }
        $bio = "";
        $phone= "";
        $title = "";
        
        if ($_POST) {
            
            if (isset($_POST['csrf']) && Security::check($_POST['csrf'])) {
                $post = Validation::factory($_POST)
                        ->rule('first', 'not_empty')
                        ->rule('last', 'not_empty')
                       // ->rule('email', 'not_empty')
                        //->rule('email', 'email')
                        ->rule('password2','matches',array(':validation',':field','password'));
                //process post data (if applicable)
                   if ($post->check()) {
                    if(!is_null($editid)){
                         //$user = ORM::factory("User",(int)$editid)->find();
                         $force_pass = true;
                    } else {
                       $user = ORM::factory("User");
                        $force_pass  = false;
                    }
                    //$view->set("member", $user);
                    $purifier = ybr::loadPurifier();
                    $first   = $purifier->purify($_POST['first']);
                     $last   = $purifier->purify($_POST['last']);
                    // echo "uss: ".$user->find()->email;
                     $email = "";
                    // var_dump($email);exit;
                     if($_POST['email'] != $user->email){
                    $email = $purifier->purify($_POST['email']);
                     } elseif($user->email != ""){
                         $email = $user->email;
                     }
                    $pass   = $purifier->purify($_POST['password']);
                    $pass2 = $purifier->purify($_POST['password2']);
       
                    //$email_mes = array($first,$last, $email, $pass);
                    $user->first = $first;
                     $user->last = $last;
                     //echo "EMAIL: ".$email;exit;
                     if($email != ""){
                        $user->email = $email;
                        $user->username = $email;
                     }
                    if($force_pass || $pass != ""){
                            $user->password = $pass;
                    }
                    $user->auth_scheme =  Kohana::$config->load('siteconfig.auth_scheme');
                   //create user data
       
                    
                    //print_r($data);exit;
                    //$user->userid = $purifier->purify($_POST['userid']);
                    try{
                        $user->save();
                    //    $user->add('groups',ORM::factory('Group',array("name"=>'user')));
                    } catch(Database_Exception $e){
                         echo "DATABASE ERRORS<br /><pre>";
                        var_dump($e);
                        exit;
                    } catch(ORM_Validation_Exception $e){
                        echo "VALIDATION ERRORS<br /><pre>";
                        var_dump($e->errors());
                        exit;
                    }
                   
                    $view->msg = "The user has been created/updated...";
              //      exit;
                }  else {
          
                    $view->post = $_POST;
                   $view->msg = "The user you are creating/modifing has problems.";
//                    //collect the errors, run them through /application/messages/[file] for pretty output
                   $view->errors = $post->errors('forms/user_errors');
//                
                }
                
                
                
            } //end if csrf
       
        }
    
        $view->set("member", $user);
          
        $this->template->content = $view;

    }   

}

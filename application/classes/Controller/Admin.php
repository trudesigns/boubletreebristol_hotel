<?php defined('SYSPATH') or die('No direct script access.');


class Controller_Admin extends Controller_Template {

    //set template for this controller
    public $template = 'yellowbrick/admin_template';
    public $secure_actions = FALSE;
    public $auth_required = ['login']; // both of these roles are required by default
   // public $authorized_groups = array('admin','ldap_admin'); //GROUPS allowed to access the admin tool
    
    public $user = null;
    public $user_access_roles = null;
//echo "HERE";exit;
    
    
    
    public function before() {
        parent::before();
        
        //hybrid auth logic
        $this->user = Session::instance()->get('userdata');
        
        if(is_null($this->user) && !ybr::auto_login()){//no userdata that means you are not logged in
           $this->redirect( 'user/signin/?goto=' . $_SERVER['REQUEST_URI']);
           //exit;
        } 
        
        ybr::setAccessRoles($this->user);

        $this->user_access_roles = Session::instance()->get("accessroles"); 
        
        if(isset($_COOKIE['ybr_token']) && !isset($_COOKIE['ybr_loggedin'])){
            $user_id = $this->user['id'];
            $encrypt = Encrypt::instance('tripledes');

            $nt = Encrypt::instance()->encode($_COOKIE['ybr_token']);
            Session::instance()->set("ybr_loggedin",$nt);
            DB::query(Database::UPDATE, "UPDATE `session_token` SET `user_id` = '".$user_id."',`loggedintoken`= '".$nt."' WHERE `token` = '".$_COOKIE['ybr_token']."'")->execute();
            setcookie('ybr_loggedin',$nt,0,"/");
        }
        
        $_SESSION['ckfinder_access'] = 1;
        
        setcookie('ckfinder_baseURL' , '',0,"/");
        // check to see if var exists, if not, initialize empty value
        isset($_SESSION['yb_status_message']) ? $_SESSION['yb_status_message'] : '';

        if ($this->auto_render) {
            // initialize empty values
            $this->template->title = '';
            $this->template->content = '';
            $this->template->bind('_this', $this);
            $this->template->tstyles = [];
            $this->template->mstyles = [];
            $this->template->bstyles = [];
            $this->template->tscripts = [];
            $this->template->mscripts = [];
            $this->template->bscripts = [];
        }
    }

    public function after() {
        if ($this->auto_render) {
         $tstyles = [
                'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css'=>"all"
                ,'yb-assets/plugins/superfish/superfish.css' => 'screen, projection'  
                ,'yb-assets/plugins/superfish/superfish-navbar.css' => 'screen, projection'
                ,'yb-assets/plugins/jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.css' => 'screen, projection'
                ,'yb-assets/plugins/select2/select2.css' => 'screen, projection'
                ,'yb-assets/plugins/jquery-ui.1.11.0/jquery-ui.min.css'=>'screen,projection'
                ,'yb-assets/plugins/jquery-ui.1.11.0/jquery-ui.structure.min.css'=>'screen,projection'
                ,'yb-assets/plugins/jquery.nestable/jquery.nestable.css'=>"all"
                ,'yb-assets/plugins/jquery.datatable/css/jquery.dataTables.min.css'=>"all"
                ,'yb-assets/plugins/chosen/chosen.min.css'=>"all"
                ,'yb-assets/plugins/chosen/bootstrap-chosen.css'=>"all"
                ,'yb-assets/css/doubletree.css'=>"all"
                
            ];
            $mstyles = [];
            $bstyles = [
                'yb-assets/css/ybr.css' => 'screen, projection',
            ];
            //set global scripts - call any extra like above example, $this->template->scripts = array('file.js', 'file2.js')
            $tscripts = [
                'https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js'
                , 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js'
                , 'yb-assets/plugins/jquery-ui.1.11.0/jquery-ui.min.js'
                , 'yb-assets/plugins/jquery-ui-timepicker-addon.js'
                , 'yb-assets/plugins/hoverIntent.js'
                , 'yb-assets/plugins/superfish/superfish.js'
                , 'yb-assets/plugins/select2/select2.js'       
                , 'yb-assets/plugins/jquery.cookie.js'
                , 'yb-assets/plugins/jquery.datatable/js/jquery.dataTables.min.js'
                , 'yb-assets/plugins/dataTables.bootstrap.js'
                , 'yb-assets/plugins/jquery.validate/jquery.validate.js'
                , 'yb-assets/plugins/jquery.nestable/jquery.nestable.js'
                , 'yb-assets/plugins/chosen/chosen.jquery.min.js'
                , 'ckeditor/ckeditor.js'
                , 'ckfinder/ckfinder.js'
                , 'ckeditor/adapters/jquery.js'
                , 'yb-assets/js/functions.js'
                , 'yb-assets/js/security.js'
                , 'yb-assets/js/admin.js'

            ];
            $mscripts = [
                
            ];
            $bscripts = [
                   'yb-assets/js/ybr.js',
                 "yb-assets/js/doubletree.js"
            ];
            //pass the settings on to the template
            $this->template->tstyles = array_merge($tstyles, $this->template->tstyles);    // append STYLE TOP
            $this->template->mstyles = array_merge($mstyles, $this->template->mstyles);    // append STYLE MIDDLE
             $this->template->bstyles = array_merge($bstyles, $this->template->bstyles);    // append STYLE BOTTOM
            
            $this->template->tscripts = array_merge($tscripts, $this->template->tscripts); // append array of scripts TOP
            $this->template->mscripts = array_merge($mscripts, $this->template->mscripts); // append array of scripts MIDDLE
            $this->template->bscripts = array_merge($bscripts, $this->template->bscripts); // append array of scripts BOTTOM

            //$this->template->current_user = Auth::instance()->get_user();
            $this->template->user = (object)$this->user;
            $this->template->accessroles = $this->user_access_roles;
        }
        
         
        
        parent::after();
    }

    /**
     * require Super User role
     * call this function to check user's role and output standard error message
     */
    private function super_user_only() {
        if (!Auth::instance()->logged_in('su')) {
            exit("Permission Denied for this function");
        }
    }
    
    private function get_roles(){
        $roles = ORM::factory('Role')->where("role_type", "=", "permission")->find_all();
        foreach($roles as $r){
            $role[] = $r->name;
        }
        return $role;
        
    }

}


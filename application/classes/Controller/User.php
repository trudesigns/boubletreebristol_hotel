<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User extends Controller_Setup {

    public $auth_required = 'login';
    public $find_dynamic_page_data = FALSE; // don't use the "pages" table
    public $user = null;
    public $user_access_roles = null;
    public $user_id = 0;
    //public $user = null;//Will contain the user data array come form session.
    public $user_groups;
    //whether or not to insert in the db or update the first time someone form ldap gets authenticated they get an insert otherwise if they loggedin 
    //before they would get an update so that we can keep track by id no matter if the ldap data changed.
    private $ldap_auth = "insert";
    //Fields that we will query in the ldap server
    public $ldap_fields = [
        'displayname' //Display Name
        , 'sn'//LastName
        , 'givenName'//first Name
        , 'telephonenumber'
        , 'l'//city
        , 'st'//State
        , 'streetAddress'
        , 'postalCode'
        , 'co'//country
        , 'mail' //email
        , 'memberof'//groups
        , 'primarygroupid'//primary group
        , 'objectsid'//primary group
    ];
    //native roels equivalent from ldap server
//    public $ldap_native_roles = array(
//        'ldap_admin'=>'admin'
//        ,'ldap_user'=>'login'
//    ); 

    public $acl = [
        "index" => ["read"]
        , 'signout' => ["read"]
        , "profile" => ['read']
    ];

    public function __construct(\Request $request, \Response $response) {

        //print_r($this->acl);
        $action = $request->action();
        //echo "ACTIOn: ".$action;exit;
        if (array_key_exists($action, $this->acl)) {//is this action protected
            //echo "PAGE TRESPASS";exit;
            $this->auth_required = true;
            if (is_null($this->user)) {
                if (is_array(Session::instance()->get("userdata"))) {
                    $this->user = (object) Session::instance()->get("userdata");
                } 
                    if (!ybr::acl_group($this->acl)) {

                        $this->redirect('user/signin/?goto=' . $_SERVER['REQUEST_URI']);
                    }
                
            }
            if (!is_array($this->user_access_roles)) {
                $this->user_access_roles = Session::instance()->get("accessroles");
            }
            // $this->auth_required = FALSE;
//var_dump($this->user);
            if (!is_object($this->user)) {
                $this->redirect('user/signin/?goto=' . $_SERVER['REQUEST_URI']);
            }
        } else {
            $this->auth_required = false;
        }



        parent::__construct($request, $response);
    }

    public function before() {
//        $no_auth_required = array("signin", "register","signout");  // these pages can be loaded without requiring log in event though $auth_required is set
//        if (in_array(Request::initial()->action(), $no_auth_required)) {
//            $this->auth_required = FALSE;
//        }
        //var_dump($this->auth_required);exit;
        if ($this->auth_required) {
            //echo "SHSGHSKHVlmhvmhsgdljhgs,u g,sjfygv";exit;
            $this->setUserSession(); // see is session userdata exist sets it if it does not
        }



        //
        parent::before();
        //echo "HERE";exit;
        $this->template->mstyles = [
            // "yb-assets/css/cmstemplate.css" => "screen, projection",
            'yb-assets/css/ybr.css' => 'screen, projection',
        ];
        $this->template->mscripts = [
            'yb-assets/plugins/jquery.validate/jquery.validate.min.js'
        ];
    }

    public function after() {
        parent::after();
        // echo "tHERE";exit;
    }

    private function setUserSession() {
        //echo "BEFORE_ADMIN";
        //var_dump($this->user);exit;
        if (is_null($this->user)) {

            if (is_array(Session::instance()->get("userdata"))) {
                $this->user = (object) Session::instance()->get("userdata");
            } else {
                //echo "HSHGSHSHSHHSHS";
                //print_r($this->acl);
                ybr::acl_group($this->acl);
                $this->user = (object) Session::instance()->get("userdata");
            }
        }
        // echo "setUserSession(): ";var_dump(Session::instance()->get("userdata"));exit;
    }

    // USER Homepage (optional page)
    public function action_index() {

        /* if site doesn't have a landing page, forward to the profile managment form page instead */
        $this->redirect(PATH_BASE . 'user/profile');

        /* or show the landing page */
        #$this->template->innerView = new View('public/pages/user/index');
    }

    // Log In Page
    public function action_signin() {
        $goto = (isset($_GET['goto'])) ? ltrim($_GET['goto'], "/") : PATH_BASE . 'user';
        $content = $this->template->innerView = View::factory('public/pages/user/signin');
        $this->template->meta_title = 'Sign In';
        #If there is a post and $_POST is not empty
        $post = $this->request->post();
        if ($post) {
            // begin to log the sign in attempt
            $log = ORM::factory('Login');
            $log->ip_address = $_SERVER["REMOTE_ADDR"];
            $log->user_agent = $_SERVER["HTTP_USER_AGENT"];
            $log->timestamp = date("Y-m-d H:i:s");
            //print_r($_POST);exit;
            $auth = false;
            if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)){ //INVALID EMAIL ADDRESS FORMAT
                $auth = false;
               
            } else {//VALID EMAIL FORMAT
               
                // Attempt to find user without password, based on provided username
                $user = ORM::factory('User')->where('username', '=', trim($post["email"]))->where('status','>',1)->find();
                $groups = array();
                foreach ($user->groups->find_all() as $g) {
                    $groups[$g->id] = $g->name;
                }
                $this->user_groups = $groups;

            
                if ($user->loaded()) {// HAS THE USER ALREADY LOGGEDIN PREVIOUSLY OR ARE THEY NATIVE USER

                    $log->user_id = $user->id;
                    $this->user_id = $user->id;

                    if (strtotime($user->lock_login_until) > time()) {
                        $content->errors = "Rapid login attempt throttled. Please wait at least 2 seconds before attempting to log in again.";
                        return;
                    }
                   
                    $auth = true;            
                    if((bool)ldap_auth && (int) $user->auth_scheme === 1) { //USER NEEDS TO LOGIN WITH LDAP
                        $this->ldap_auth = "update";

                        $auth = $this->ldap_authenticate();
                        if($auth!==false){
                            $this->user = (object) Session::instance()->get("userdata");
                            $user = $this->user;
                        }
                    }   
                    
                    
               } else {//USER NEVER LOGGEDIN BEFORE AND DOES NOT EXIST IN THE DB AS A NATIVE USER
                         if((bool)ldap_auth){
                    //echo "LDAP";
                                 $auth = $this->ldap_authenticate();
                                 if($auth!==false){
                                    $this->user = (object) Session::instance()->get("userdata");
                                    $user = $this->user;
                                    $auth = true;
                                 }
                         }
                         $auth = false;
                }
            }
           //var_dump($user);
           //var_dump($auth);exit;
            if (!$auth && !Request::initial()->is_ajax() ) {
                //echo "NOT LOADED";exit; 
                $content->errors = "The username/email provided could not be found. Please try again.";
                return;
            }
            $status = false;
            $remember = (isset($post['remember'])) ? true : false;
             switch ((int) $user->auth_scheme) {
                case 1://LDAP
                    $status = is_object($this->user);
                    break;
                case 0://NATIVE
                    $this->addLoginRoleToUser();
                    $status = Auth::instance()->login($post['email'], $post['password'], $remember);
                    if ($status) {
                        $data = $this->userdata_native($post['email']);
                        Session::instance()->set("userdata", $data);
                        $this->user = (object) $data;
                    } 
                    break;
                case 2://OpenID in the future..

                    break;
                case 3://SINGLE SHARED USER DB
                    $this->addLoginRoleToUser();
                    //CAN"T USE THE NATIVE Auth::instance()->login($post['email'], $post['password'], $remember);
                    $user = ORM::factory("User")->where("email",'=',$post['email'])->where('password','=',Auth::instance()->hash($post['password']))->find();
                    //var_dump($user->id);exit;
                    if($user->id >0){

                            $user->last_login = time();
                            $user->logins = new Database_Expression('logins + 1');
                            $user->save();
                        
                     $status = true;   
                    //END OVERIDE OF Auth::instance()->login
                    
                    }
                    if ($status) {
                        // echo "HERE";exit;
                        $data = $this->userdata_native($post['email']);
                        Session::instance()->set("userdata", $data);
                        $this->user = (object) $data;
                    } 
                    break;
            }
            if (Request::initial()->is_ajax()) {
                 //if this log-in request was sent by ajax, just return "done" as a string
                   die( json_encode($status));
                  // exit;
                } else {

                    #If the post data validates using the rules setup in the user model
                    if ($status && $this->user->id > 0) {
                        
                        Cookie::set("ckfinder_access",md5(date("Y-m-d")));
                        //setcookie("ckfinder_access", md5(date("Y-m-d")));
                        //print_r($_COOKIE);exit;
                        
                        // log successfull attempt
                        $log->access_granted = 1;
                        $log->save();
                        
                        #redirect to goto url
                        $goto = "/";
                        if(isset($_GET['goto'])){
                            $goto = $_GET['goto'];
                        }
                        $this->redirect($goto);

                    } else { // if $status returned false
                        if (isset($this->user->id) && $this->user->id > 0) {
                            $user = ORM::factory("Users")->where("id","=",$this->user->id)->find();
                            $user->lock_login_until = date("Y-m-d H:i:s", strtotime("+2 seconds"));
                            $user->save();
                        }

                        // log the failed attempt
                        $log->access_granted = 0;
                        $log->save();

                        // pass errors for display in view
                        $content->errors = "Password does not match the username provided. Please try again."; //true;
                    }
                }
        }
    }


    
    // registration page
    public function action_register() {

        $goto = (isset($_GET['goto'])) ? ltrim($_GET['goto'], "/") : PATH_BASE . 'user';

        // if user already signed-in
        if (!is_null($this->user)) {
            $this->redirect($goto);
        }

        //add the jQuery Validation Plugin (add this script to the END of the array)
        //array_push($this->template->scripts, ltrim("/", PATH_BASE) . 'yb-assets/plugins/jquery.validate.min.js');


        $this->template->mscripts[] = 'yb-assets/js/register.js';
        $content = $this->template->innerView = View::factory('public/pages/user/register');
        $post = $this->request->post();
        if ($post) {

            // check for hidden input field - honey trap bot catcher
            if (isset($post['favorite']) && $post['favorite'] != '') {

                $msg = "It looks like some bot filled out the invisible field on " . $_SERVER['HTTP_HOST'] . "/user/register \n";
                $msg.= "Here's what they submitted from " . $_SERVER['REMOTE_ADDR'] . " (user agent: " . $_SERVER['HTTP_USER_AGENT'] . ")\n";
                $msg.= print_r($post, true);
                $msg.= "\nThis message was triggered from " . __FILE__;
                mail('webserver@thepitagroup.com', '' . $_SERVER['HTTP_HOST'] . ' honey trap triggered', $msg, "From:noreply@" . $_SERVER['HTTP_HOST'] . "");

                die();
            }

            $email = "";
            foreach ($post as $key => $value) {
                //since we got rid of the field username in the front end but kohana still needs it on th backend.
                if ($key === "email") {
                    $email = trim(htmlspecialchars($value));
                }
                $post[$key] = trim(htmlspecialchars($value));
            }
            $post['username'] = $email;
            $content->post = $post;
            try {
                $user = ORM::factory('User')->create_user($post, array('username', 'password', 'email', 'first', 'last'));

                $user->add('roles', ORM::factory('Role')->where('name', '=', 'login')->find());

               // $_POST = array();
                $this->redirect($goto);
                //Request::redirect($goto);
            } catch (ORM_Validation_Exception $e) {
                $content->errors = $e->errors('forms/register');
            }
        }
    }

    // manage user information
    public function action_profile() {
        //echo "USERSSSS";exit;
        //print_r( );
        //$this->setUserSession();


       //$current_user = $this->user; //lookup current logged in user
        $content = $this->template->innerView = View::factory('public/pages/user/profile');
        $content->set("user", $this->user);
        // $content->user = $current_user;
        //add the jQuery Validation Plugin (add this script to the END of the array)
       // array_push($this->template->scripts, ltrim("/", PATH_BASE) . 'yb-assets/plugin/jquery.validate.min.js');
        $this->template->mscripts[] = 'yb-assets/js/profile.js';
        $post = $this->request->post();
        if ($post) {
//            foreach ($post as $key => $value) {
//                $post[$key] = trim(htmlspecialchars($value));
//            }
            
            $purifier = ybr::loadPurifier();
            $id = $purifier->purify($post['userid']);
            $user = ORM::factory("User",$id);
            
            
            $user->email = $purifier->purify($post['email']);
            $user->first = $purifier->purify($post['first']);
            $user->last = $purifier->purify($post['last']);
            $password = $purifier->purify($post['password']);
            $password2 = $purifier->purify($post['password_confirm']);
           // var_dump($password);
            if(isset($password) && $password === $password2 && $password !== ""){
                $user->password = $password;
            }
            $content->post = $post;
            try {
               $user->save();
               //echo $user->last_query();
                $content->post['password'] = "";
                $content->post['password_confirm'] = "";

                #Request::current()->redirect($goto);
                //$content->errors = array("Your changes have been saved");
                $content->success = "Your changes have been saved.";
            } catch (ORM_Validation_Exception $e) {
                $content->errors = $e->errors('users');
            }
        }
    }

    // Log Out
    public function action_logout() {
        DB::query(Database::DELETE, "DELETE FROM`session_token` WHERE `token` = '".$_COOKIE['ybr_token']."'")->execute();
        Auth::instance()->logout(TRUE);
        Session::instance()->destroy();
        Cookie::delete('ybr_token');
        Cookie::delete('ybr_loggedin');
        Cookie::delete('ckfinder_access');
        $this->redirect("/admin");
    }

    // alias of log out
    public function action_signout() {
        $this->action_logout();
    }

    private function get_username($email) {
        $x = explode("@", $email);
        return array("username"=>$x[0],"domain"=>$x[1]);
    }
 

    /**
     * Will try to authenticate the user provided
     * @return boolean
     */
    private function ldap_authenticate() {
        $post = $this->request->post();
        $ldap = ybr::ad_connect();
        //var_dump($ldap);exit;
        if ($ldap) {
            $email = $post["email"];
            $password = $post["password"];
            $u = $this->get_username($email);
            $ldap_conf = Kohana::$config->load('ldap')->ldap;
          //  echo "<prE>";
           //print_r($ldap_conf);
            //exit;
            $auth =false;
            if(in_array("@".$u['domain'], $ldap_conf['allowed_domains'] )){
             $auth = $ldap->user()->authenticate($u['username'], $password);
            }
            if (!$auth) {
                return false;
            }
            $userdata = $this->userdata_ldap($ldap, $email);
         //   echo "<pre>BOOOM";
         //   print_r($userdata);
            Session::instance()->set("userdata", $userdata);
            return true;
        }
        return false;
    }

    /**
     * Function wich will add the login role to the user
     */
    private function addLoginRoleToUser() {
        //echo "USER_DI: ".(int)$this->user_id;
        $user = ORM::factory("User", (int) $this->user_id);
        if (!$user->has('roles', ORM::factory('role', array('name' => 'login')))) {
            //echo "ADDING ROLE";exit;
            $user->add('roles', ORM::factory('role', array('name' => 'login')));
            
        }
    }

     private function userdata_native($email) {
        // $this->addLoginRoleToUser();

        $q = ORM::factory('User')->where("email","=", $email)->where( "status" , '>','1');
        $res = $q->find();
        // echo "<prE>";
      //  print_r( $res->groups->find_all());
        //echo "NSNDNNDNDND</pre>";
        $groups = $res->groups->find_all();
        $arr = array();
        foreach ($groups as $g) {
            $arr[$g->id] = strtolower($g->name);
        }
        $userdata = [];
        if(isset($q->data)){
                $userdata = json_decode($q->data,true);
        }      
        $auth_scheme = Kohana::$config->load('siteconfig.auth_scheme');
        $data = [
            'last' => $q->last
            , 'first' => $q->first
            , 'logins' => $q->logins
            , 'last_login' => $q->last_login
            , 'username' => $q->username
            //,'password'    => "fakepassword_na"
            , 'email' => $q->email
            , 'auth_scheme' => $auth_scheme
            , 'id' => $q->id
            , 'fullname' => $q->first . " " . $q->last
            , 'roles' => $arr
        ];
        array_merge($data, $userdata);

        return $data;
    }

    private function userdata_ldap($ldap, $email) {
        $user_info = $ldap->user()->info($email, $this->ldap_fields);
        //echo "<prE>";var_dump($user_info);exit;
        $fullname = trim(htmlspecialchars($user_info[0]['displayname'][0]));
        $fname = "";
        if (isset($user_info[0]['givenname'])) {
            $fname = trim(htmlspecialchars($user_info[0]['givenname'][0]));
        }
        $lname = "";
        if (isset($user_info[0]['sn'])) {
            $lname = trim(htmlspecialchars($user_info[0]['sn'][0]));
        }
        $tel = "";
        if (isset($user_info[0]['telephonenumber'])) {
            $tel = trim(htmlspecialchars($user_info[0]['telephonenumber'][0]));
        }
        $city = "";
        if (isset($user_info[0]['l'])) {
            $city = trim(htmlspecialchars($user_info[0]['l'][0]));
        }
        $state = "";
        if (isset($user_info[0]['st'])) {
            $state = trim(htmlspecialchars($user_info[0]['st'][0]));
        }
        $country = "";
        if (isset($user_info[0]['co'])) {
            $country = trim(htmlspecialchars($user_info[0]['co'][0]));
        }
        $zip = "";
        if (isset($user_info[0]['postalcode'])) {
            $zip = trim(htmlspecialchars($user_info[0]['postalcode'][0]));
        }
        $address = "";
        if (isset($user_info[0]['streetaddress'])) {
            $address = trim(htmlspecialchars($user_info[0]['streetaddress'][0]));
        }


        // $email    = trim(htmlspecialchars($user_info[0]['mail'][0]));
   //     echo "NAME :".print_r($this->get_username($email));
        
        $uname = $this->get_username($email);
        $username = $uname['username'];
     //   echo "UNAME: ".$username;
        $roles = $ldap->user()->groups($username); //array of all the groups user belongs to
       // var_dump($roles);
      //$roles_l    = $ldap->user()->groups('Loshowilliams'); 
      //var_dump($roles_l);exit;
        $primary = "";
        if (isset($user_info[0]['primarygroupid']) && isset($user_info[0]["objectsid"])) {
            $primary = $ldap->group()->getPrimaryGroup($user_info[0]['primarygroupid'][0], $user_info[0]["objectsid"][0]);
        }

//echo "PRIMARY: ".$primary;exit;

        $data = [
            'last' => $lname
            , 'first' => $fname
            , 'telephone' => $tel
            , 'city' => $city
            , 'state' => $state
            , 'country' => $country
            , 'zip' => $zip
            , 'address' => $address
            , 'username' => $email
            , 'password' => "fakepassword_ldap"
            , 'email' => $email
            , 'auth_scheme' => 1
        ];
        $time = time();
        try {
            if ($this->ldap_auth === "update") {
                $q = DB::update('users')->set(array_merge($data, array(
                            'logins' => new Database_Expression('logins + 1')// Update the number of logins
                            , 'last_login' => $time// Set the last login date
                                        )
                                )
                        )
                        ->where("id", "=", $this->user_id);
            } else {
                $data = array_merge($data, array("dateCreated" => date('Y-m-d h:i:s')));
                $q = DB::insert('users', array_keys($data));
                $q->values($data);
            }
            $res = $q->execute();
            //var_dump($this->user_id);exit;
            if (is_null($this->user_id) || $this->user_id === 0) {
                $this->user_id = $res[0];
            }
           //  print_r($res);
            //exit;
        } catch (Validation_Exception $e) {
            die($e);
        }

        //Adding to the data array the fields that we want in our session bur that could not be stored in the DB
        $data['fullname'] = $fullname;
        $data['id'] = $this->user_id;
        $new_roles = array();

        foreach ($roles as $r) {
            $new_roles[] = $r;
        }
        //var_dump($new_roles);exit;
        $this->update_groups($new_roles);
        $grp = ORM::factory('User', $this->user_id)->groups->find_all();
        //echo "HELLOOOOOOO";
      //  var_dump($grp);
        $groups = array();
        foreach ($grp as $g){
            $groups[] = $g->name;
        }
       
        //$data['roles'] = 
     //   echo "ROLES: <br />";
        $data['roles'] = $groups;
       //var_dump($data['roles']);exit;
        $data['last_login'] = $time;
        $data['logins'] = ORM::factory("User", $this->user_id)->logins;

        //exit;
        // exit;
        $this->addLoginRoleToUser(); //gotta add the role login to the user.
        //exit;
        return $data;
    }

    /**
     * Function that sees if the native_groups ( the Group DB) has matching groups/roles pulled from ldap
     * if it does not exist it create the new groups and add that user to these new groups.
     * @param array $ldap_groups groups/roles coming from the LDAP Active Directory Server
     * @param array $native_groups Groups coming form the Local DB
     */
    private function update_groups($ldap_groups) {
        $grps = ORM::factory("Group")->find_all();
        $native_groups = array();
        foreach ($grps as $g) {
            $native_groups[$g->id] = $g->name;
        }
        //echo "GRP: <prE>";
        //print_r($ldap_groups);
        //echo "</prE>NATIVE:<pre>";
        // print_r($native_groups);
        // exit;
        $interect = array_diff($ldap_groups, $native_groups);
      
       //print_r($interect);exit;
        foreach ($interect as $grp) {//CREATE GRPS THAT DON"T EXSIT IN THE NATIVE DB
            $g = ORM::factory("Group");
            $g->name = $grp;
            $g->save();

            //$g->add('users', ORM::factory('User', (int) $this->user_id));
        }
        $u = ORM::factory('User', (int) $this->user_id);
        $u->remove("groups");
        foreach($ldap_groups as $ldap){
            $u->add("groups",ORM::factory("Group")->where("name","=",$ldap)->find());
        }
        
        
        
    }

}

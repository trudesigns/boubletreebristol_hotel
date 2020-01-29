<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Admin "AJAX" request controller 
 *
 */
class Controller_Admin_Request extends Controller_Admin {

//public $auth_required = array('login', 'admin'); // roles required to access this controller
    public $find_dynamic_page_data = FALSE;
    public $auto_render = FALSE;
    public $user = null;
    public $user_access_roles = null;


    public $acl = [
        "index" => ["read"]
        
        , 'getPageProperties'            =>['pages']
        , 'generateSlug'                 =>['write','pages']
        , 'checkSlug'                    =>['write','pages']
        , 'pages'                        =>['pages']
        , 'menus'                        =>['menus']
        , 'orderpages_manually'          =>['pages','edit']
        , 'buildlink'                    =>['edit']
        , 'getUrl'                       =>['write']
        , 'killSession'                  =>['read']
        , 'getRevision'                  =>['edit']
        , 'latestRevision'               =>['edit']
        , 'allRevisions'                 =>['edit']
        , 'setRole'                      =>['users']
        , 'changeStatus'                 =>['users','redirects','edit']
        , 'resetPassword'                =>['users']
        , 'publish'                      =>['publish']
        , 'delete_page_item'             =>['pages']
        , 'pages_menu'                   =>['menus']
        , 'delete_menu_item'             =>['menus']
        , 'menu_item'                    =>['menus']
        , 'custom_url'                   =>['menus']
        ,'copyMenus'                     =>['menus']
        , 'orderMenuPages'               =>['menus']
        , 'menuAllPages'                 =>['menus']
        , 'compare'                      =>['menus']
        , 'allPages'                     =>['pages']
        , 'menuAddPages'                 =>['pages']
        , 'checkAlias'                   =>['pages']
        ,'orderPages'                    =>['pages']
        ,'deletePage'                    =>['pages']
        , 'menuAddAllPages'              =>['menus']
        ,'saveMenuPageProperties'        =>['menus']
        , 'getMenuPageProperties'        =>['menus']
        ,'editor'                        =>['write']
        ,'addPages'                      =>['pages']
        ,'updatePages'                   =>['pages']
        
        
        
        
        
    ];
    
    public function __construct(Request $request, Response $response) {
        
         $ltoken = null;
            
        $p = $request->post();
        
     // var_dump($p);exit;
        if(count($p) >0){
            $ltoken =$p['ybr_loggedin'];
        }
        
        if (!Request::initial()->is_ajax() || is_null($ltoken)) {
            die("local XHR access only.");
        }// make sure this is an ajax call and not a direct browser view

        $action = $request->action();
        //echo "ACTIOn: ".$action;exit;
        if (array_key_exists($action, $this->acl)) {//is this action protected

            $this->auth_required = true;
            if (is_null($this->user)) {
                if (is_array(Session::instance()->get("userdata"))) {
                    $this->user = (object) Session::instance()->get("userdata");
                } 
                
                
            }
        //  var_dump(Session::instance()->get("userdata"));exit;  
            
            if (!ybr::acl_group($this->acl)) {
             //   echo ybr::acl_group($this->acl);exit;
          //  echo "ACL PROBLEMS";exit;
                $this->redirect('user/signin/?goto=' . $_SERVER['REQUEST_URI']);
            }
            
            if (!is_array($this->user_access_roles)) {
               // echo "USER ACCESS";exit; 
                $this->user_access_roles = Session::instance()->get("accessroles");
            }

            if (!is_object($this->user)) {
           //   echo "UUUUS";exit;
                $this->redirect('user/signin/?goto=' . $_SERVER['REQUEST_URI']);
            }
        } else {
            $this->auth_required = false;
        }



        parent::__construct($request, $response);
    }

    /**latestRevision
     * log user out
     *
     */
    public function action_killSession() {
        Auth::instance()->logout(); // Sign out the user
        Session::instance()->set('logout', TRUE);
        echo "done";
    }

//    function action_setYBmsg($message) {
//        $_SESSION['yb_status_message'] = $message;
//        echo $message;
//    }


    private function getRevisions($limit = 1) {
        $post = $this->request->post();
        if (!isset($post['page_id']) || !is_numeric($post['page_id']) ||  !isset($post['block_id']) || !is_numeric($post['block_id']) ||  !isset($post['version_id']) || !is_numeric($post['version_id'])) {
            exit("Error! invalid parameter set");
        }

        //$this->request->headers('Content-Type', 'application/javascript');
        $content = new Model_Content;
        $params = array('page_id' => $post['page_id'],
            'block_id' => $post['block_id'],
            'version_id' => $post['version_id'],
            'limit' => $limit
        );
        $latest = $content->getRevisions($params);
        if (!$latest) {
            exit("Error! block not found");
        }

        if ($limit == 1) {
            $latest = $latest[0];
            $name = ORM::factory('User', $latest->updated_by);
            $revisions = array('id' => $latest->id,
                'content' => $latest->content,
                'revision_date' => $latest->revision_date,
                'publish_date' => $latest->publish_date,
                'live' => $latest->live,
                'updated_by' => $latest->updated_by,
                'updated_name' => $name->first . " " . $name->last
            );
        } else {
            $revisions = array();
            foreach ($latest as $revision) {
                $name = ORM::factory('User', $revision->updated_by);
                $revisions[] = array('id' => $revision->id,
                    'content' => $revision->content,
                    'revision_date' => $revision->revision_date,
                    'publish_date' => $revision->publish_date,
                    'live' => $revision->live,
                    'updated_by' => $revision->updated_by,
                    'updated_name' => $name->first . " " . $name->last
                );
            }
            
        }
        echo json_encode($revisions);
    }

    /**
     * return the most recent revision for a given page's content-block version
     *
     */
    public function action_latestRevision() {
        $this->getRevisions();
    }

    public function action_allRevisions() {
        $limit = (isset($_GET['limit']) && is_numeric($_GET['limit'])) ? $_GET['limit'] : 500;
        $this->getRevisions($limit);
    }

    /**
     * update user roles 
     *
     */
    public function action_setRole() {
        if (!is_numeric($_POST['user']) || !is_numeric($_POST['role'])) {
            exit("Invalid user or role");
        }
        if (!isset($_POST['action'])) {
            exit("Status uncertain");
        }
        $act = $_POST['action'];

        $userid = (int) $_POST['user'];
        $grpid  = (int) $_POST['role'];
        $gp = ORM::factory('Group')->where("id","=", $grpid)->find();
        if (!$gp) {
            exit("Invalid Group");
        } elseif ($gp->name == "developer" && !in_array("config", $accessroles)) {
            exit("Cannot assign developer role"); //only developers can assign/unassign developer roles
        }

        $user = ORM::factory('User')->where("id","=", $userid)->find();
//print_r($_POST);

        if ($act == "false") {
            $user->add('groups', $gp);
        } else {
            $user->remove('groups', $gp);
          ///   $user->save();
        }
       
      //   echo $user->last_query();
        echo "done";
    }

    
    public function action_changeStatus()
    {
        $post = $this->request->post();
        $recid = $post['recordid'];
        $status = $post['status'];
        $model = $post['model'];
        if((int)$status === 4){
            $status = 2;
        }
        $obj = ORM::factory($model)->where("id","=",$recid)->find();
        $obj->status = $status;
        $obj->save();
      // exit($obj->last_query());
         die(ybr::statusMenu($status));
        
    }

    /**
     * reset users password
     *
     */
    public function action_resetPassword() {
        if (!is_numeric($_POST['user'])) {
            exit("invalid user id");
        }

        $userObj = new Model_User;
        if ($userObj->reset_password($_POST['user'])) {
            echo "The Reset Password has successfully been sent to user.";
        } else {
            echo "Error generating e-mail to user";
        }
    }
    

    public function action_publish()
    {
        $post =$this->request->post();
        $item = $post['item_name'];
        $recid   = $post['recordid'];
        $mdl     = $post['model'];
        $obj = ORM::factory($mdl);
        
        if($mdl == "Menupage"){
            $menus  = $obj->where('menu_id','=',$recid)->find_all();
           // echo $obj->last_query();exit;
            DB::delete("menus_pages_live")->where("menu_id","=",$recid)->execute();
            foreach ($menus as $ob){
  //              echo "LABEL: ".$ob->label;
                
               // var_dump($obj->link_type);
                $live = new Model_Menupagelive();
     
                $live->id = $ob->id;
                $live->parent_id = $ob->parent_id;
                $live->display_order = $ob->display_order;
                $live->menu_id = $ob->menu_id;
                $type = $ob->link_type;
                if(is_null($ob->link_type) ){
                    $type = "page_id";
                }
                $live->link_type = $type;
                $live->link_value = $ob->link_value;
                $live->label = $ob->label;
                $live->target = $ob->target;
                $live->link_attributes = $ob->link_attributes;
                $live->save();
                //echo $live->last_query();
                
            }
            $menu=  ybr::getMenus(["item_id"=>$recid]);
            $out = ybr::createMenus($menu);
        }

      //  exit;
        try{
            $mem = Cache::instance('file');
            $mem->set($item,$out);
            $res = true;
        }  catch (Exception $e){
            $res = false;
        }
        echo json_encode($res);
    }
    
    
    /**
     * PAGES
     */
     public function action_delete_page_item()
    {
        $post =$this->request->post();
        //print_r($post);exit;
        $mpage = ORM::factory("Page",$post['item_id']);
        $mpage->delete();
    }
    
    
  /**
   * THE ENTIRE PAGES LIST or the pages from specific page id
   */
    public function action_pages(){
        $post =$this->request->post();
        if(isset($post['page_id']) && !is_null($post['page_id'])){
            echo  json_encode(ybr::getPages($post['page_id']));
        } else {
            echo  json_encode(ybr::getRootPages());
        }
        
       
    }
    
    
    /**
     * Through request to this method passing post 
     * that contains a page_id will return a 
     * json encoded string with a link to that page 
     */
    public function action_buildlink()
    {
        $post =$this->request->post();
        $id = $post['page_id'];
        $p = new Model_Page();
        $link = $p->buildLink($id);
        echo json_encode($link);
    }
    
    public function action_orderpages_manually()
    {
        $post =$this->request->post();
        $purifier = ybr::loadPurifier();
        $pid   = $purifier->purify($post['parent']);
        $sid = null;
        if(isset($post['sibling'])){
            $sid   = $purifier->purify($post['sibling']);
        }
        $id    = $purifier->purify($post['editid']);
      
        //echo "SID: ".$sid." ID: ".$id." PID: ".$pid."<br />"; 
       if(is_null($sid)){
                $cpage = ORM::factory("Page",$id);
                $cpage->display_order = 0;
                $cpage->parent_id = $pid;
                $cpage->save();
       } else {
                $children = ORM::factory("Page")->where("parent_id","=",$pid)->order_by('display_order','ASC')->find_all();

                $cnt=0;
                $child =[];
                $idx = 0;

                
                foreach($children as $c){
                    $child[$c->display_order] = $c->id;
                }
                //echo "CHILDREN:<br />";
                 //print_r($child);
      
                foreach($child as $c=>$v){
                    if($v=== $sid){
                       $idx = $cnt;
                    } 
                    if($v=== $id){
                        unset($child[$c]);
                        //$child[]=$id;
                    }
                    $cnt++;
                }
               
                 //echo "<br />IDx: ".$idx."<br />";;
              //  echo "RES:<br />";
              //  print_r($res);
                
                 $rs2 = ybr::array_insert($child,$id,$idx);
                  // print_r($rs2);
                 foreach($rs2 as $k=>$v){
                     $p = ORM::factory("Page",(int)$v);
                     $p->display_order = (int)$k;
                     $p->parent_id = (int)$pid;
                    
                       try{
                         $p->save();
                                //echo $p->last_query();
                    } catch(Database_Exception $e){
                         echo "DATABASE ERRORS<br /><pre>";
                        var_dump($e);
                        exit;
                    } catch(ORM_Validation_Exception $e){
                        echo "VALIDATION ERRORS<br /><pre>";
                        var_dump($e->errors());
                        exit;
                    }
                    
                 }
       }
       echo json_encode(true);
        
    }
    
    
    public function action_saveproperties()
    {
        $post =$this->request->post();
        $purifier = ybr::loadPurifier();
        $id = $purifier->purify($post['editid']);
        $label = $purifier->purify($post['label']);
         $slug ="";
        if($id >1){
            $slug = $purifier->purify($post['slug']);
        }
        $template = $purifier->purify($post['template']);
        $status = $purifier->purify($post['status']);
        $sdate = "0000-00-00 00:00:00";
        if(isset($post['startDate']) && $post['startDate'] != ""){
           // echo "BBBBlins<br />".var_dump($post['startDate']);
            $sdate = $purifier->purify(date("Y-m-d H:i",strtotime($post['startDate'])));
        }
       // echo "<br/>".$sdate;
       //echo "FIRT: ".var_dump($post['endingDate']);exit;
        $edate = "0000-00-00 00:00:00";
        if(isset($post['endingDate']) && $post['endingDate'] != ""){
         //   echo "<br /><br />XNXNXNX<br />".print_r($post['endingDate'])."ENDATE";
            $eedate = strtotime($post['endingDate']);
           // echo "<br />EEDATE: ".var_dump($eedate)." BBBBOOOM";
            $edate = $purifier->purify(date("Y-m-d H:i",$eedate));
        }
       // echo "<br/>".$edate;
      //  exit;
       $sitemap =0;
        if(isset($post['sitemap'])){
            $sitemap=1;
        }
        $searchable =0;
        if(isset($post['searchable'])){
            $searchable = 1;
        }
        $lock =0;
        if(isset($post['lock'])){
            $lock = 1;
        }
        $groups = [];
        if(isset($post['groups'])){
//echo "HEHEHEHEH";    
            foreach($post['groups'] as $g){
                $groups[] = $purifier->purify($g);
            }
        }
        $gp =  implode(",", $groups);
       // echo "<br />gp<br />".$gp;

//exit;
        $page = ORM::factory("Page",$id);
        $page->label = $label;
        $page->slug = $slug;
        $page->template_id = $template;
        $page->start_date = $sdate;
        $page->end_date = $edate;
        $page->display_in_sitemap = $sitemap;
        $page->searchable = $searchable;
        $page->active = $status;
        $page->add_children_role = $lock;
        $page->required_role = $gp;
       $page->save();
      // echo $page->last_query();
       // exit;
       echo json_encode(true);
        //$purifier->purify();
        
    }
    
    
    /****
     * MENUS
     */
     public function action_menus()
    {
        echo json_encode(ybr::getMenus($this->request->post()));
    }
    
    public function action_pages_menu()
    {
        $post = $this->request->post();
        //print_r($post);exit;
        echo json_encode(ybr::getMenus($post));
    }
    
    public function action_delete_menu_item()
    {
        $post =$this->request->post();
        $mpage = ORM::factory("Menupage",$post['item_id']);
        $mpage->delete();
    }
    
    public function action_menu_item()
    {
          $post =$this->request->post();
          $mpage = ORM::factory("Menupage",$post['item_id']);
          echo json_encode([
                    "label"=>$mpage->label
                  ,"link_value"=>$mpage->link_value
                  ,"link_type"=>$mpage->link_type
                  ,"target"=>$mpage->target
                  ,"attributes"=>$mpage->link_attributes
          ]);
  
    }
    
    public function action_custom_url()
    {
        $post =$this->request->post();
       //print_r($post['link_id']);
       $lnk = (int)$post['link_id'];
        //var_dump($lnk);
       
       if($lnk > 0){
          //echo "shshshsh";
            $menu = new Model_Menupage($lnk);//ORM::factory("Menupage",$lnk);
        } else {
            $menu = new Model_Menupage();
        }
        $menu->link_value         = $post['link_value'];
        $menu->label                  = $post['label'];
        $menu->target                = $post['target'];
        $menu->link_attributes  = $post['attributes'];
        $menu->link_type           = $post['link_type'];
        $menu->menu_id            = $post['menu_id'];
        $menu->parent_id          = $post['parent_id'];
        $menu->display_order   = $post['display_order'];
        $menu->save();
        echo json_encode("done"); 
    }
    
    
    private function orderSubMenuPages($obj,$pid)
    {
        $cnt = 0;
        foreach($obj as $k=>$v){
             if(isset($v->children)){
                 $this->orderSubMenuPages($v->children,$v->id);
             }
            $mp = ORM::factory("Menupage",$v->id);
             $mp->display_order = $cnt;
             $mp->parent_id = $pid;
             $mp->save();
             $cnt++;
             //  echo "<br />";
          //  print_r($mp->last_query());
        
        }
    }
    
    public function action_copyMenus()
    {
        $post =$this->request->post();
        $from = $post['from'];
        $to = $post['to'];

        $fmenus = ORM::factory("Menupage")->where("menu_id","=",$from)->order_by("parent_id","asc")->order_by("display_order","asc")->find_all();
        
        //var_dump($fmenus);
        
        $m = [];
        foreach($fmenus as $menu){
            
            
            
            $mp = new Model_Menupage();
            $mp->parent_id = $menu->parent_id;
            $mp->display_order = $menu->display_order;
            $mp->menu_id = $to;
            $mp->link_type = $menu->link_type;
            $mp->link_value = $menu->link_value;
            $mp->label = $menu->label;
            $mp->target = $menu->target;
            $mp->link_attributes = $menu->link_attributes;
             try{
                        $mp->save();
                        $m[] = ["old_id"=>$menu->id,"parent_id"=>$menu->parent_id,"new_id"=>$mp->id];
                        //$call->add('pages',ORM::factory("Page",$link)->parent_id);
                        //echo$mp->last_query(); 
                       //$user->add('groups',ORM::factory('Group',array("name"=>'user')));
                    } catch(Database_Exception $e){
                         echo "DATABASE ERRORS<br /><pre>";
                        var_dump($e->errors());
                        exit;
                    } catch(ORM_Validation_Exception $e){
                        echo "VALIDATION ERRORS<br /><pre>";
                        var_dump($e->errors());
                        exit;
                    }
                      
            
                }

        foreach($m as $v){
            if((int)$v['parent_id'] >0){
               // echo "PARID: ".$v['parent_id'];
                $key = array_search($v['parent_id'], array_column($m, 'old_id'));
                if($key !== false){
                  $mp = ORM::factory("Menupage")->where("id","=",$v['new_id'])->find();
                  $mp->parent_id = $m[$key]['new_id'];
                  $mp->save();
                }

            }
        }
    }
    
    public function action_orderMenuPages()
    {
         $post =$this->request->post();
        // echo"<pre>";print_r($post);
       
         $list = json_decode($post['list_order']);
         //echo "<pre>";
        // print_r($list);exit;
         $cnt = 0;
         foreach($list as $k=>$v){
             if(isset($v->children)){
                 $this->orderSubMenuPages($v->children,$v->id);
             }
             $mp = ORM::factory("Menupage",$v->id);
             $mp->display_order = $cnt;
             $mp->parent_id = 0;
             $mp->save();
             $cnt++;
          //   echo "<br />";
           //print_r($mp->last_query());
        }
        
    }
    
    public function action_menuAllPages()
    {
            $post =$this->request->post();
            ybr::setAllPagesToMenu($post['menu_id']);
    }
    
     public function action_compare()
    {
        $post =$this->request->post();
        $what = $post['what'];
        $id     = $post['id'];
        switch($what){
            case "menus":
                //var_dump(ybr::compareMenus($id));
                echo json_encode(ybr::compareMenus($id));
                break;
        }
        
    }
    
    /**************END MENUS****************************/
    

    /**
     * functions for page manager
     */
    public function action_allPages() {
     //   echo "hererererere";exit;
        $excluded = (isset($_GET['exclude']) && is_numeric($_GET['exclude'])) ? array($_GET['exclude']) : false;
        $parent_id = (isset($_GET['parent_id']) && is_numeric($_GET['parent_id'])) ? $_GET['parent_id'] : false;
        $pageObj = new Model_Page;

        if (!isset($_GET['menu']) && !isset($_GET['menu_id'])) {
         //  echo "Parent_ID: ".var_dump($parent_id);exit;
            $pages = $pageObj->getPages(array('generate_uri' => true, 'active_only' => false, 'display_in_sitemap_only' => false, 'excluded_pages' => $excluded, 'parent_id' => $parent_id));
        } else {
            $menu_id = (isset($_GET['menu_id'])) ? $_GET['menu_id'] : $_GET['menu'];
            if (!is_numeric($menu_id)) {
                exit("ERROR! invalide menu id");
            }
            //echo "MENUID: ".$menu_id;exit;
            $pages = $pageObj->getPages(array('menu_id' => $menu_id));
        }

        echo json_encode($pages);
    }

    public function action_addPage() {
        $pages = new Model_Page;
        echo (int)$pages->add_or_update("add", $this->request->post());
    }

    public function action_updatePage() {
        $pages = new Model_Page;
        echo $pages->add_or_update("update",$this->request->post());
    }

    public function action_getPageProperties() {
        $post = $this->request->post();
        //print_r($post);
        $page = ORM::factory('Page', $post['page_id']);

        $active = ($page->active == 0) ? false : true;
        $searchable = ($page->searchable == 0) ? false : true;
        $display_in_sitemap = ($page->display_in_sitemap == 0) ? false : true;
        $template = ORM::factory('Template', $page->template_id);
        $templateParameters = json_decode($template->parameters);

        $array = array(
            "label" => $page->label,
            "slug" => $page->slug,
            "template_id" => $page->template_id,
            "template_available" => ($templateParameters) ? $templateParameters->available : "error",
            "template_name" => $template->name,
            "required_role" => $page->required_role,
            "add_children_role" => $page->add_children_role,
            "active" => $active,
            "display_in_sitemap" => $display_in_sitemap,
            "searchable" => $searchable,
            "start_date" => ($page->start_date == "0000-00-00 00:00:00") ? "" : $page->start_date,
            "end_date" => ($page->end_date == "0000-00-00 00:00:00") ? "" : $page->end_date,
            "id" => $page->id,
            "parent_id" => $page->parent_id
        );
        echo json_encode($array);
    }

    public function action_generateSlug() {
        $pages = new Model_Page;
        $post = $this->request->post();
        echo $pages->generateSlug($post['string']);
    }

    public function action_checkSlug() {
        
        $post = $this->request->post();
        //print_r($post);
        $check = ORM::factory('Page')->where('parent_id', '=', $post['parent_id'])->where('slug', '=', $post['slug'])->where('id', '<>', $post['page_id'])->find_all();
        
       // echo $check->last_query();
      //  echo count($check);exit;
        $ret  = true;
        if(count($check) > 0 ){
             $ret = false;
        }
        echo json_encode((bool)$ret);
    }
    
    private function addSubMenusPages($data){
        $data = (object)$data;
        $page = ORM::factory("Page",$data->pageid);
        $menuid  =  $this->addMenuPage($data->parent_id, $data->menu_id, $page->id, $page->label);
         if($data->add_children){
            $cpage  = ORM::factory("Page")->where("parent_id","=",$data->pageid)->find_all();
            foreach($cpage as $c){
                //echo "<br />HERE";
               // $data =;
                $this->addSubMenusPages( ["pageid"=>$c->id,"parent_id"=>$menuid,"menu_id"=>$data->menu_id,"add_children"=>$data->add_children,'link_type'=>'page_id']);
               // $menu_item_id = $this->addMenuPage($menuid, $post['menu_id'], $c->id, $c->label);
            } 
             
            
        }
        
    }
    
// public AJAX call from view 
    public function action_menuAddPages() {
        $post = $this->request->post();
        if (!isset($post['menu_id']) || !is_numeric($post['menu_id'])) {
            exit("Error: missing or invalid menu id");
        }
        if (!isset($post['page_id']) || !is_numeric($post['page_id'])) {
            exit("Error: missing or invalid page id");
        }
        if (!isset($post['parent_id']) || !is_numeric($post['parent_id'])) {
            exit("Error: missing or invalid parent id");
        }
        $add_children = (!isset($post['add_children']) || $post['add_children'] === 0 || strtolower($post['add_children']) == "false" ) ? false : true;
        
      //  var_dump($add_children);exit;
       // $page = ORM::factory("Page",$post['page_id']);
         $this->addSubMenusPages( ["pageid"=>$post['page_id'],"parent_id"=>0,"menu_id"=>$post['menu_id'],"add_children"=>$add_children]);
    }

    
    /***********END PAGES MANAGER **********************/
    
    /**
     * AJAX CALLS REGARDING THE REDIRECTS
     */
    public function action_checkAlias()
    {
        $post = $this->request->post();
         if (!isset($post['alias']) || !isset($post['alias_id'])) {
            exit("Error. missing required parameters");
        }

        $alias = trim($post['alias'], "/");
//var_dump($alias);exit;
        
        
        
        $page = new Model_Page;
        $res = $page->getPagefromURL($alias);
      //  var_dump($res);exit;
        if(is_object($res)){
             $res = true;
        } else {
            $res =false;
        } 
        //$page = ORM::factory("Page")->where('slug',"=",$alias)->find();
        
      //  var_dump($page->getPagefromURL($alias));exit;
        $res_red = false;
        $red = ORM::factory("Redirect")->where("alias",'=',$alias)->find_all();
        foreach($red as $r){
            if($r->alias == $alias)
                $res_red = true;
                    
        }
        
       echo json_encode(["pages"=>$res,"redirects"=>$res_red]);
        
//        $page_info = $page->getPagefromURL($alias);
//        $array['existing_page'] = ( $page_info && $page_info->active == 1 ) ? true : false;
//
//        $redirect = new Model_Redirect;
//        $existing_alias = $redirect->lookup($post['alias']);
//        $array['existing_alias'] = ( $existing_alias && $existing_alias->id != $post['alias_id'] ) ? true : false;
//
//        echo json_encode($array);
    }
    
    
    
    
    
    /**
     * helper function for action_orderPages()
     * 
     */
    private function orderPages($list_string, $menu_id = false, $current_parent = 0, $recursion = 0) {
        if ($recursion > 100) {
            return false;
        }
        $table = ($menu_id && is_numeric($menu_id)) ? 'Menupage' : 'Page';
        //echo "<prE>";
        //print_r($list_string);
      //  echo "<br />MENUID:  ".$current_parent;
        foreach ($list_string as $index => $data) {
            $orderpage = ORM::factory($table, $data['id']);

            if ($table === "Menupage") {
                $orderpage->menu_id = (int)$menu_id;
            }
            $current_parent = (int)$current_parent;
            $index = (int)$index;
            $orderpage->parent_id = $current_parent;
            $orderpage->display_order = $index; //$current_order;
            //Echo "<br />CURRSN: ".var_dump($current_parent);
//echo "DATAID :".$data['id'];
//echo "INDEX: ".var_dump($index);
  
            try{
                         $orderpage->save();
                                //echo $orderpage->last_query();
                    } catch(Database_Exception $e){
                         echo "DATABASE ERRORS<br /><pre>";
                        var_dump($e);
                        exit;
                    } catch(ORM_Validation_Exception $e){
                        echo "VALIDATION ERRORS<br /><pre>";
                        var_dump($e->errors());
                        exit;
                    }
           // var_dump($s);
            //echo "<br />".$orderpage->last_query();
            if (isset($data['children']) && is_array($data['children'])) {
                // recursively call this function again, setting this row's children as the new list
                // pass the current id as the new parent
                // recursion flag count is just for safety sake :)
                
                $this->orderPages($data['children'], $menu_id, $data['id'], $recursion++);
            }
        }
    }

    /**
     * function called after drag/drop of all pages in sitemap
     */
    public function action_orderPages() {
        $list_string = $_POST['list_order'];
        $menu_id = (isset($_POST['menu_id']) && is_numeric($_POST['menu_id'])) ? $_POST['menu_id'] : false;
        $this->orderPages($list_string, $menu_id);
        echo "true";
    }
 
    /**
     * Delete a page, all its associated content and menu link(s)
     *
     */
    public function action_deletePage() {
        $post = $this->request->post();
        if (!isset($post['page_id'])) {
            exit("invalid page");
        }
        $page_table = (isset($post['menu_id']) && is_numeric($post['menu_id'])) ? 'Menupage' : 'Page';

        $subpages = ORM::factory($page_table)->where('parent_id', '=', $post['page_id'])->count_all();
        if ($subpages > 0) {
            exit("Can not delete this page because it has " . $subpages . " subpage(s).  Please move or delete the child(ren) pages first.");
        }

        if ($page_table === 'Page') {
            // if this is a page being deleted (not a menu item) delete all the associated content
            $contents = ORM::factory('Content')->where('page_id', '=', $post['page_id'])->find_all();
            foreach ($contents as $content) {
                $content->delete();
            }

            // and delete all the entries of this page in the menus_pages table
            $menu_listings = ORM::factory('Menupage')->where('link_type', '=', 'pages_id')->where('link_value', '=', $post['page_id'])->find_all();
            foreach ($menu_listings as $listing) {
                $listing_menu = $listing->menu_id;
                $listing->delete();
            }
        }

        $page = ORM::factory($page_table, $post['page_id']);
        if (!$page->loaded()) {
            exit("Page does not exist"); // how did they get here, I have no idea.
        }

        $page->delete();
        echo "done";
    }

    /**
     * Functions used in the Menu Builder Tool
     * (note, this tool also uses many of the page manager functions as well)
     */
// add Menu Page to the Menu Pages table
    private function addMenuPage($parent_id, $menu_id, $page_id, $label) {
        $link = ORM::factory('Menupage', false);
        $link->parent_id = $parent_id;
        $link->menu_id = $menu_id;
        $link->link_type = 'pages_id';
        $link->link_value = $page_id;
        $link->target = '_self';
        $link->label = ''; //$label; // label should be blank unless user modifies it.
        $link->display_order = 0;
        $link->save();
       // echo $link->last_query();
        return $link->id;
    }


    
    public function action_menuAddAllPages() {
        if (!isset($_GET['menu_id']) || !is_numeric($_GET['menu_id'])) {
            exit("Error: missing or invalid menu id");
        }
        $toplevel = ORM::factory('Page')->where('parent_id', '=', 0)->find_all();
        foreach ($toplevel as $top) {
            $return_data[] = $this->menuAddPage(0, $_GET['menu_id'], $top->id, true);
        }
        echo json_encode($return_data);
    }

    public function action_saveMenuPageProperties() {
        if (!isset($_POST['page_id']) || (!is_numeric($_POST['page_id']) && $_POST['page_id'] !== "false")) {
            exit("Error: invalid or missing page id");
        }
        $page = ORM::factory('Menupage', $_POST['page_id']);
        $page->menu_id = $_POST['menu_id'];
        $page->label = $_POST['label'];
        $type = "page_id";
        if($_POST['link_type'] !=="page_id")$type= "url";
        $page->link_type = $type;
        $page->link_value = $_POST['link_value'];
        $page->link_attributes = $_POST['link_attributes'];
        $page->target = $_POST['target'];
        $page->save();
        echo "done";
    }

// return data for a given menu page item
    public function action_getMenuPageProperties() {
        if (!isset($_GET['page_id']) || !is_numeric($_GET['page_id'])) {
            exit(json_encode('false'));
        }
        $item = ORM::factory('Menupage', $_GET['page_id']);
        echo json_encode(($item->loaded()) ? $item->as_array() : false);
    }


    /**
     * Functions used in the form builder tool
     * 
     */
//    public function action_formfields() {
//        if (!isset($_GET['form_id']) || !is_numeric($_GET['form_id'])) {
//            exit("ERROR: Invalid Form ID");
//        }
//
//        $formfields = ORM::factory('Formfield')->where('form_id', '=', $_GET['form_id'])->order_by('field_order')->find_all();
//        foreach ($formfields as $field) {
//            $array[] = $field->as_array();
//        }
//
//        echo json_encode($array);
//    }
//
//    public function action_getFormFieldProperties() {
//        if (!isset($_GET['field_id']) || !is_numeric($_GET['field_id'])) {
//            exit("ERROR: Invalid field ID");
//        }
//        $field = ORM::factory('Formfield', $_GET['field_id'])->as_array();
//        echo json_encode($field);
//    }
//
//    public function action_saveFormField() {
//        if (!isset($_POST['field_id']) || (!is_numeric($_POST['field_id']) && $_POST['field_id'] !== "false")) {
//            exit("critical error: can not add field to unknown form");
//        }
//
//        $field = ORM::factory('Formfield', $_POST['field_id']);
//
//        if ($_POST['field_id'] == "false") {
//            if (!isset($_POST['form_id']) || !is_numeric($_POST['form_id'])) {
//                exit("critical error: can not add field to unknown form");
//            } else {
//                $field->form_id = $_POST['form_id'];
//            }
//        }
//
//        $field->label = $_POST['label'];
//        $field->field_type = $_POST['field_type'];
//        $field->field_options = $_POST['field_options'];
//        $field->value = $_POST['value'];
//        $field->max_length = $_POST['max_length'];
//        $field->required = $_POST['required'];
//        $field->active = $_POST['active'];
//        $field->save();
//        echo "done";
//    }
//
//    public function action_orderFormFields() {
//        $id_list = ltrim($_POST['list_order'], "fieldID=");
//        $fields = explode("&fieldID=", $id_list);
//        $i = 0;
//        foreach ($fields as $id) {
//            if (!is_numeric($id)) {
//                exit("ERROR PARSING FIELD ID");
//            }
//            $field = ORM::factory('formfield', $id);
//            $field->field_order = $i;
//            $field->save();
//            $i++;
//        }
//        echo "true";
//    }
//
//    public function action_deleteFormField() {
//        if (!isset($_GET['field_id']) || !is_numeric($_GET['field_id'])) {
//            exit("ERROR: invalid field id");
//        }
//
//        // delete all the collected data for this field
//        $alldata = ORM::factory('Formsubmissionfield')->where('field_id', '=', $_GET['field_id'])->find_all();
//        foreach ($alldata as $data) {
//            $data->delete();
//        }
//
//        // delete this field
//        ORM::factory('Formfield', $_GET['field_id'])->delete();
//
//        echo "done";
//    }

    
    
    
    
    /**
     * load the content block editor form view
     */
    public function action_editor() {
            $post = $this->request->post();
        //if(isset($_REQUEST['csrf']) && Security::check($_REQUEST['csrf'])){
        //echo "here";exit;
            if ((!isset($post['content_id']) || !is_numeric($post['content_id']) ) &&
                    isset($post['page_id']) && is_numeric($post['page_id']) &&
                    isset($post['block_id']) && is_numeric($post['block_id'])) {

                $params = array('page_id' => $post['page_id'],
                    'block_id' => $post['block_id'],
                    'version_id' => (isset($post['version_id'])) ? $post['version_id'] : 1
                );
                //print_r($params);
                //echo "HERE";
                $contentObj = new Model_Content;
                $content = $contentObj->findRevision($params);
            } elseif (isset($post['content_id']) && is_numeric($post['content_id'])) {
                $content = ORM::factory('Content', $post['content_id']);
            } else {
                exit("ERROR: malformed request");
            }

            if (!$content->loaded()) {
                exit("ERROR: could not find requested content");
            }
    //print_r(Session::instance()->get("accessroles") );exit;
            
            //echo "CONTENT: ".$content->block_id;
            //exit;
            $view = new View('yellowbrick/pages/editor');
            $view->block = ORM::factory('Contentblock', $content->block_id);
            $view->revisions = $content->getRevisions(array('page_id' => $content->page_id, 'block_id' => $content->block_id, 'version_id' => $content->version_id));
            $view->content = $content;
            $view->accessroles = Session::instance()->get("accessroles");
            $view->user = Session::instance()->get("userdata");
            //find other content blocks associated with this page's template
            $view->other_blocks = ORM::factory('Contentblock')
                    ->where('id', 'IN', DB::Select('content_block_id')->from('template_content_blocks')->where('template_id', '=', $content->page->template_id))
                    ->find_all();

            //a list of other versions available to any content block
            $view->contentversions = ORM::factory('Contentversion')->find_all();

            echo $view;
//        }else {
//            echo "Problems with csrf."
//        }
    }
    
 

    /**
     * template manager
     * add or remove a block to or from a template
     */
    public function action_templateblocks() {
        $post = $this->request->post();
       //var_dump($post);
        if (!isset($post['template_id']) || !isset($post['block_id']) || !is_numeric($post['template_id']) || !is_numeric($post['block_id'])) {
            exit("invalid request");
        }
        if (!isset($post['action'])) {
            exit("missing action");
        }

        switch ($post['action']) {
            case 'add' :
                $newBlock = ORM::factory('Templatecontentblock');
                $newBlock->template_id = $post['template_id'];
                $newBlock->content_block_id = $post['block_id'];
                $newBlock->save();
                break;

            case 'delete' :
                //ORM::fa
                DB::delete('template_content_blocks')
                        ->where('content_block_id', '=', $post['block_id'])
                        ->where('template_id', '=', $post['template_id'])
                        ->execute();

                break;

            default :
                exit("invalid action");
        }
        echo json_encode(true);
    }


    public function action_setCkfinderBaseURL() {
        $_SESSION['baseURL'] = $_GET['baseURL'];
        echo json_encode(true);
    }


    
    public function action_getUrl()
    {
        $post = $this->request->post();
        $pageid = $post['page_id'];
        $page = new Model_Page();
        echo $page->getPageURL($pageid);
      
    }
    
    
}


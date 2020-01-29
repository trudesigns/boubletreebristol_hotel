<?php defined('SYSPATH') OR die('No direct script access.');


class ybr extends Security{
    
    
    public static function ybr_token($length=15){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
    
    /**
     * Search a mutli-dimensional array for neddle
     * @param type $needle
     * @param type $haystack
     * @return boolean
     */
    public static function search_array($needle, $haystack) {
        if(in_array($needle, $haystack)) {
             return true;
        }
        foreach($haystack as $element) {
             if(is_array($element) && self::search_array($needle, $element))
                  return true;
        }
        return false;
    }
   
    public static function getRolesFromGroup($group){
          
        // var_dump($group);
          
        $grp_roles = array();
        if(is_array($group)){//ARRAY OF GROUP NAME

             
            foreach($group as $g){
               // echo "G: ".$g;
                $grp = ORM::factory("Group")->where("name",'=',$g)->find();
                //echo "<br /><br />".$grp->last_query();
                $arr = array();
                //echo "<br /><br />".$grp->last_query();
                foreach($grp->roles->find_all() as $gg){
                     $arr[$gg->id] = $gg->as_array(); 
                } 
                 $grp_roles[$grp->id] =  $arr;
            }

        } elseif(is_object($group)){//A GROUP OBJECT
            
            foreach($group->roles->find_all() as $g){
                 $arr[$g->id] = $g->as_array(); 
            }
            $grp_roles[$group->id] = $arr;
            
        } elseif(is_int($group)) { //SINGLE ID OF GROUP
            
            $grp = ORM::factory("Group",$group);
//           / echo $grp->last_query();
            foreach($grp->roles->find_all() as $g){
                $arr[$group] =$g->as_array();
            }
            $grp_roles[$group] =  $arr;
            
        } else {
            die("Make sure to send an Group object, an array or an int to this method.");
        }
        
        //UPDATE THE SESSION USERDATA TO REFLECT ALL THE ROLES THAT USERS GROUPS Get AS ROLES
       // $access_roles = array();
        $rols = array();
        foreach($grp_roles as $roles){

            foreach($roles as $r){
               $rols[$r['id']] = $r['name'];
            }
       
        }
       // $access_roles["access_roles"] = $rols;
        //$userdata = Session::instance()->get("userdata");
        Session::instance()->set('accessroles',$rols);
       
   
        return $grp_roles;
    }

    public static function acl_group($acl){
     
        $action = Request::initial()->action();

           //var_dump(Session::instance()->get("userdata"));exit;
        
        if(Session::instance()->get("userdata")){
            $ud = (object)Session::instance()->get("userdata");
        } else {
          // echo "JSHSHSH";exit;
            return false;
        }
//print_r($ud->roles);exit;
        $roles = array();
        if(count($ud->roles)>0){
                $roles = self::getRolesFromGroup($ud->roles);
        }
        
     //  print_r($roles);exit;
        $re = array();
        //var_dump($acl);exit;
        //is this action protected
        if(is_array($acl[$action])){
           //go thru each accepted role for the method and see if the one of the user groups has that access
            foreach($acl[$action] as $acl){
         //      echo "<br />ACL: ".$acl;
               $re[$acl] = (bool)self::search_array($acl,$roles);
            }

            if(in_array(true, $re)){
                return true;
            } 
            return false;
        }
        return true;
        
    }
   
    public static function setAccessRoles($user){
        $u = (object)$user;
        $grp_arr = array();
        foreach($u->roles as $k=>$v){
            $grp_arr[$k]= $v;
        }
        
        return self::getRolesFromGroup($grp_arr);
    }
    
    public static function auto_login()
    {
        if ($token = Cookie::get('authautologin')) {
            // Load the token and user
            $token = ORM::factory('User_Token', array('token' => $token));

            if ($token->loaded() AND $token->user->loaded()) {
                if ($token->user_agent === sha1(Request::$user_agent)) {
                    // Save the token to create a new unique token
                    $token->save();

                    // Set the new token
                    Cookie::set('authautologin', $token->token, $token->expires - time());


                    // Regenerate session_id
                    Session::instance()->regenerate();
                   // echo Kohana::config("auth");
                    $config = Kohana::$config->load('auth');
                    //echo "SESS: ".$config->session_key;
                    //exit;
                    Session::instance()->set($config->session_key, $token->user);
                   // Kohana::config("auth.session_key");
                    return TRUE;
                }

                // Token is invalid
                $token->delete();
            }
        }

        return FALSE;
    }
    
    public static function loadPurifier(){
        require Kohana::find_file("vendor/htmlpurifier", "HTMLPurifier.standalone");
        $config = HTMLPurifier_Config::createDefault();
        $config->set('Core.Encoding', 'UTF-8'); // replace with your encoding
        $config->set('HTML.Doctype', 'XHTML 1.0 Transitional'); // replace with your doctype
        $purifier = new HTMLPurifier($config);
        return $purifier;
    }
    
    public static function ad_connect()
    {
        require Kohana::find_file("vendor/adldap", "adLDAP");
        $config = Kohana::$config->load('ldap')->ldap;
        //print_r($config);
        try{
            $ad = new adLDAP($config);
        } catch(adLDAPException $e){
            die($e);
        }
        return $ad;
        
    }
    
    /**
     * Pass a page params from a route and will return the subpage
     * @param type $page
     * @return type string either the subpage/or the parent page url
     */
    public static function getSubPage($page){
        $ex = explode("/",$page);
       // var_dump($ex);
        if(count($ex) > 1){
       // if($ex !== false){
            return $ex[1];
        }
        return $ex[0];
    }  
  
      /**
     * This method allows the creation of dropdown that can handle status chnage request.
     * @param int $status 0=delete,1=inactive,2=active,3=featured,4=unfeatured
     * @param bool$featwher=ther or not to use the feaured functionality of STATUS
     * @return string
     */
    public static function statusMenu( $status,$feat=false)
    {
        //echo "STATUS:" .var_dump($status);
       // echo "<br />OBJ: ".var_dump($obj);
        $status =(int)$status;
        switch($status){
            case 0:
                $sta = "deleted";
                    break;
            case 1:
                $sta = "inactive";
                break;
            case 2:
            case 4:
                $sta = "active";
                break;
            case 3:
                $sta = "featured";
                break;
        }
       //print_r($status);
      // print_r($feat);
        $links = "<div class='status-box'>";//wrapper for styling the dropdown
        $links .= "<a href='#' class='btn btn-warning dropdown-toggle status' data-toggle='dropdown' ><span>".$sta."</span></a>";
        $links .= "<ul class='dropdown-menu' role='menu'>";
        
        switch($status){ 
            case 0:
                $links .= "<li><a href='#' class='ajax' data-action='status' data-actionid='2' ><span>Activate</span></a></li> ";
                $links .= "<li><a href='#' class='ajax' data-action='status' data-actionid='1' '><span>Inactivate</span></a></li> ";
                break;
            case 1:
                $links .= "<li><a href='#' class='ajax' data-action='status' data-actionid='2' ><span>Activate</span></a></li> ";
                $links .= "<li><a href='#' class='ajax' data-action='status'  data-actionid='0' ><span>Delete</span></a></li> ";
                break;
            case 2:
            case 4:
                switch($feat){
                    case 3:
                            $links .= "<li><a href='#' class='ajax' data-action='status' data-actionid='1' ><span>Inactivate</span></a></li> ";
                            $links .= "<li><a href='#' class='ajax' data-action='status' data-actionid='0' ><span>Delete</span></a></li> ";
                            $links .= "<li><a href='#' class='ajax' data-action='status' data-actionid='3' ><span>Featured</span></a></li> ";
                        break;
                    case 2:
                    case false:
                            $links .= "<li><a href='#' class='ajax' data-action='status' data-actionid='1' ><span>Inactivate</span></a></li> ";
                            $links .= "<li><a href='#' class='ajax' data-action='status' data-actionid='0' ><span>Delete</span></a></li> ";
                        break;
                    case 1:
                            $links .= "<li><a href='#' class='ajax' data-action='status' data-actionid='3' ><span>Featured</span></a></li> ";
                        break;
                    
                }
              
                break;
           case 3:
                $links .= "<li><a href='#' class='ajax' data-action='status' data-actionid='4' ><span>Unfeatured</span></a></li> ";
                break;
        } 
        $links .= "</ul>";
        $links .= "</div>";
        
      //print_r($links);
        return $links;
    }   
    
    
    public static function getChildrenPage($pageid){
        $children =  ORM::factory("Page")->where("parent_id",'=',$pageid)->order_by('display_order','ASC')->find_all();
        if(count($children)>0){
            $res = array();
            foreach($children as $child){
                $c = $child->content->where('live','=',1)->find() ;
                $childs = null;
                $childrens = ORM::factory("Page")->where("parent_id",'=',$child->id)->find_all();
                if(count($childrens)>0){
                  //  echo "CHILDID: ".$child->id;
                  $childs = self::getChildrenPage($child->id);
                
                }
                $res[] = array(
                    "page"=>array(
                         "id"=>$child->id
                        ,"template"=>$child->template_id
                        ,"parent_id"=>$child->parent_id
                        ,"label"=>$child->label
                        ,"slug"=>$child->slug
                        ,'access'=>$child->required_role
                        ,'status'=>$child->active
                        ,'startdate'=>$child->start_date
                        ,"enddate"=>$child->end_date
                        ,"searchable"=>$child->searchable
                        ,"sitemap"=>$child->display_in_sitemap
                        ,"order"=>$child->display_order
                        ,'children_role'=>$child->add_children_role
                        ,'children'=>$childs
                    ),
                    "content"=>array(
                         'published_by'=>$c->published_by
                        ,'revision_date'=>$c->revision_date
                        ,'publish_date'=>$c->publish_date
                    )
                );
            }
            return $res;
        }
        return null;
    }
    
    
      /*****USED IN THE SITE MGT/PAGES section of the cms *********/
    
    public static function getPages($id,$with_children = true)
    {
        
      //  var_dump($with_children);
        $r = ORM::factory("Page",$id);
        $c = $r->content->where('live','=',1)->find() ;
        
         $children = self::getChildrenPage($id);
         if(!$with_children){
             $children = null;
         }
        $res = [
                        "page"=>[
                             "id"=>$r->id
                            ,"template"=>$r->template_id
                            ,"parent_id"=>$r->parent_id
                            ,"label"=>$r->label
                            ,"slug"=>$r->slug
                            ,'access'=>$r->required_role
                            ,'status'=>$r->active
                            ,'startdate'=>$r->start_date
                            ,"enddate"=>$r->end_date
                            ,"searchable"=>$r->searchable
                            ,"sitemap"=>$r->display_in_sitemap
                            ,"order"=>$r->display_order
                            ,'children_role'=>$r->add_children_role
                          ,'children'=>$children
                        ],
                        "content"=>[
                             'published_by'=>$c->published_by
                            ,'revision_date'=>$c->revision_date
                            ,'publish_date'=>$c->publish_date
                        ]
        ];
        return $res;
    }
    
    public static function getRootPages()
    {
            $root = ORM::factory("Page")->where("parent_id",'=',0)->order_by('display_order','ASC')->find_all();
            $res = array();
            foreach($root as $r){
                $rootid = $r->id;
              //  if()
                $children = self::getChildrenPage($rootid);
              
                $c = $r->content->where('live','=',1)->find() ;
                $res[] = [
                        "page"=>[
                             "id"=>$r->id
                            ,"template"=>$r->template_id
                            ,"parent_id"=>$r->parent_id
                            ,"label"=>$r->label
                            ,"slug"=>$r->slug
                            ,'access'=>$r->required_role
                            ,'status'=>$r->active
                            ,'startdate'=>$r->start_date
                            ,"enddate"=>$r->end_date
                            ,"searchable"=>$r->searchable
                            ,"sitemap"=>$r->display_in_sitemap
                            ,"order"=>$r->display_order
                            ,'children_role'=>$r->add_children_role
                          ,'children'=>$children
                        ],
                        "content"=>[
                             'published_by'=>$c->published_by
                            ,'revision_date'=>$c->revision_date
                            ,'publish_date'=>$c->publish_date
                        ]
                 ];
            }     
            if(count($res)>0){
                return $res;
            }
            return null;
    }
    
    public static function loadChildrenPage($page,$templates,$groups)
    {
        
       // $allpages = self::getRootPages();
      //  print_r($templates);
         if(count($page)<1 ){
            return;
        }
        $out ="<ul class='dd-list '>";
        foreach($page as $p){
            $children = null;
         //  echo "<pre>"; print_r($p['page']['children']);
            if(!is_null($p['page']['children'])){
                $children = self::loadChildrenPage($p['page']['children'],$templates,$groups);
            }
         //   $contentid = ORM::factory('Content')->where("page_id","=",$p['page']['id'])->find_all();
            $out .= "<li class='dd-item dd3-item ' id='pageID_".$p['page']['id']."' data-label='".$p['page']['label']."' data-page-item='".$p['page']['id']."' data-id='".$p['page']['id']."'>";
            
                
                  
            
                if((int)$p['page']['id']===1){
                   // $out .= "<div class='dd-hand-no-drag pages_menu_item' ";
                     $out .= "<div class='dd-hand-no-drag dd3-handle' data='".$p['page']['id']."' data-page-item='".$p['page']['id']."' data-subpages='".$p['page']['children_role']."'>&nbsp;</div>";
                } else {
                  // $out .= "<div class=' dd-handle pages_menu_item' ";
                     $out .= "<div class='dd-handle  dd3-handle' data='".$p['page']['id']."' data-page-item='".$p['page']['id']."' data-subpages='".$p['page']['children_role']."'>&nbsp;</div>";
                }
                $out .= "<div class='page-item dd3-content' >";
                //$out .=" data='".$p['page']['id']."' data-page-item='".$p['page']['id']."' data-subpages='".$p['page']['children_role']."''>";
                
                    $out .= "<span title='".$p['page']['slug']."'>".$p['page']['label']."</span>";
               // $out .= "</div>";
                $out .= "<div class='yb-toolbar hide'>";
                    $out .="<a href='/admin/edit/?page_id=".$p['page']['id']."&block_id=3&version_id=1' class='glyphicon glyphicon-edit page-edit' title='Edit page content' >&nbsp;</a>";
                    $out .="<a href='#' class='glyphicon glyphicon-list-alt page-properties' data-toggle='modal' data-target='#page-prop' title='Edit page properties' >&nbsp;</a>";
                    
                    if($p['page']['id'] > 1){ //put the delete button only if not homepage (page id =1)
                        $out .="<a href='#' class='glyphicon glyphicon-chevron-down page-sub'  title='Create a sub page' data-parent-id='".$p['page']['id']."'>&nbsp;</a>";
                    $out .= "<a href='#' class='glyphicon glyphicon-sort page-move' data-toggle='modal' data-target='#page-move' title='Move page' >&nbsp;</a>";
                        if(is_null($children)){
                            $out .="<a href='#' class='glyphicon glyphicon-remove remove-item' title='Remove this page'>&nbsp;</a>";
                         } else {
                             $out .="<a href='#' class='glyphicon glyphicon-remove remove-item hide' title='Remove this page'>&nbsp;</a>";  
                         }
                    }
                $out .="</div>";
                
            $out .= "</div>";         
           
            
            if(!is_null($children)){
                $out .= $children;
            }
            $out .="</li>";
        }
        $out .="</ul>";
        return $out;
    
    }
    
     /**
     * Pass in the MENU object to get a list on html options to put in select element
     * @param object $menu from ybr::getMenus();
     * @param array $selecteds selected item on that list of options
     * @param int $dash how many dashes before the text
     * @return string list of options
     */
    public static function loadMenuPageSelect($menu,$selecteds=[],$dash=0)
    {
        
        if(!is_null($menu)){

            $out ="";
           
            foreach($menu as $c){
                $dashes="";
                for($i=1;$i<=$dash;$i++){
                    $dashes .= " - ";
                }

                $sel = "";
                if(in_array($c['menu']['id'], $selecteds)){
                   // echo "shshshshsh";
                    $sel = " selected='selected' ";
                }

                $children = null;
                if(!is_null($c['menu']['children'])){
                    $dash++;
                    $children = self::loadMenuPageSelect($c['menu']['children'],$selecteds,$dash );
                    $dash = $dash -1;
                } 
                $out .= "<option value='".$c['menu']['id']."' ".$sel.">".$dashes." " .$c['menu']['label']."</option>";
                if(!is_null($children)){
                    $out .= $children;
                }

            }
            
          return $out;
        }
    
  
     }
    
    private static function loadSubPageSelect($child,$it,$current_id)
    {
        
        
 // echo "<br />CID: ".var_dump($current_id);
        if(!is_null($child)){
           $parid = ORM::factory("Page",$current_id)->parent_id;
        //   echo "PPP: ".var_dump($parid);//exit;
            $out ="";
           
            foreach($child as $c){
                $dash = "";
                 for($i=0;$i<$it;$i++){
                    $dash .= " - ";
                }
                 if((int)$it===1){
                    $dash = " - ";
                }
                $sel = "";
               //echo "<br />".ORM::factory("Page",$c['page']['id'] )->parent_id ."===". $current_id;
                if((int)$c['page']['id'] === (int)$parid){
                    $sel = " selected='selected' ";
                }
                $children = null;
                if(!is_null($c['page']['children'])){
                    $children = self::loadSubPageSelect($c['page']['children'], $it++, $c['page']['id'] );
                }
                $out .= "<option data-test='CID: ".$c['page']['id']." PID: ".$parid."' value='".$c['page']['id']."' ".$sel.">".$dash." " .$c['page']['label']."</option>";
                if(!is_null($children)){
                    $out .= $children;
                }
                
            }
          return $out;
        }
    
  
     }
    
    public static function loadPageSelect($pages,$pid)
    {
        $out ="<select name='parent_".$pid."' class='form-control' >";
    
        
        $out .= self::loadSubPageSelect($pages, 0,$pid);
       
        
        $out .= "</select>";
        return $out;
    }
    
    
    /**
     * FEED IT AN OBJECT/ARRAY LIKE TEH ONE CREATED BY getRootPages()
     * will return a unordered list
     * array @pages is an array
     * array @props is array contianing the properties of the list for each level ["class='nav' id='test' ","class='sub-nav' "]
     * int @block_id is the id of the container  
     */
    public static function makeCMSMenufromPage($pages,$props,$block_id)
    {
        $prop = "";
        $ori_props = $props;
        if(is_array($props) && count($props) >0){
            $prop = $props[0];
        }
       
        $out ="<ul ".$prop.">";
        foreach($pages as $p){
            if((bool)$p['page']['sitemap'] === true){ // can these pages be displayed 
                $u = "/admin/edit?page_id=".$p['page']['id']."&block_id=".$block_id."&version_id=1";
                $out .="<li><a href='".$u."' >".$p['page']['label']."</a>";
                if(is_array($p['page']['children']) && count($p['page']['children']) >0){
                    array_shift($props);
                    $out .= self::makeCMSMenufromPage($p['page']['children'],$props,$block_id);
                }
                $out .="</li>";
                $props = $ori_props;
            }
        }
        $out .= "</ul>";
        return $out;
    }
    
    /**
     * Allows the insertion of array element by the position you give it
     * @param {array} $array
     * @param {string} $element
     * @param {int} $position
     * @return {array}
     */
    public static function array_insert(&$array,$element,$position=null) {
        if (count($array) == 0) {
          $array[] = $element;
        }
        elseif (is_numeric($position) && $position < 0) {
          if((count($array)+position) < 0) {
            $array = array_insert($array,$element,0);
          }
          else {
            $array[count($array)+$position] = $element;
          }
        }
        elseif (is_numeric($position) && isset($array[$position])) {
          $part1 = array_slice($array,0,$position,true);
          $part2 = array_slice($array,$position,null,true);
          $array = array_merge($part1,array($position=>$element),$part2);
          foreach($array as $key=>$item) {
            if (is_null($item)) {
              unset($array[$key]);
            }
          }
        }
        elseif (is_null($position)) {
          $array[] = $element;
        }  
        elseif (!isset($array[$position])) {
          $array[$position] = $element;
        }
        $array = array_merge($array);
        return $array;
      }
    
    
    /*****USED IN THE SITE MGT/MENUS section of the cms *********/
    
    
    public static function getSubMenus($pid)
    {
        //echo "PID: ".$pid;
        $menus = ORM::factory("Menupage")->where("parent_id","=",$pid)->order_by('display_order','ASC')->find_all();
      // echo"CNT: ". count($menus);
        $res = array();
        foreach($menus as $m){
                    $ul_attributes = ORM::factory("Menus",$m->menu_id)->ul_html;
                    $children = self::getSubMenus($m->id);
                    $page = null;
                     if($m->link_type == "pages_id" ){
                        $page =  self::getPages($m->link_value,false);
                         $p = new Model_Page();
                        $link = $p->buildLink($m->link_value);
                        $label = ORM::factory("Page",$m->link_value)->label;
                    } else {
                        $link = $m->link_value;
                        $label = $m->label;
                        //$page = self::setPageforMenu(["slug"=>$m->link_value,'label'=>$m->label]);
                    }
                   $res[] = [
                        "menu"=>[
                             'ul_attributes'=>$ul_attributes
                            ,"id"=>$m->id
                            ,"parent_id"=>$m->parent_id
                            ,"display_order"=>$m->display_order
                            ,"menu_id"=>$m->menu_id
                            ,"link_type"=>$m->link_type
                            ,"link_value"=>$m->link_value
                            ,"label"=>$label
                            ,"target"=>$m->target
                            ,"link_attributes"=>$m->link_attributes
                            ,"children"=>$children
                            ,'link'=>$link
                        ],
                        "pages"=>$page
                    ];
            
        }
        if(count($res)>0){
                return $res;
            }
            return null;
        
    }
    
    private static function setPageforMenu($data)
    {
        return [
                    "page"=>[
                             "id"=>0
                            ,"template"=>0
                            ,"parent_id"=>0
                            ,"label"=>$data['label']
                            ,"slug"=>$data['slug']
                            ,'access'=>""
                            ,'status'=>1
                            ,'startdate'=>""
                            ,"enddate"=>""
                            ,"searchable"=>0
                            ,"sitemap"=>0
                            ,"order"=>0
                            ,'children_role'=>""
                            ,'children'=>""
                        ],
                        "content"=>[]
                ];
    }
    
    public static function getMenus($post,$active = false)
    {
       //print_r($post);exit;
        if(isset($post['item_id'])){
            $id = $post['item_id'];
        } elseif(isset($post['item_name'])){
            $id = ORM::factory('Menus')->where("name",'=',$post['item_name'])->find()->id;
        }
        $ul_attributes = ORM::factory("Menus",$id)->ul_html;
        
        //echo "ID: ".$id;exit;
        $menus = ORM::factory("Menupage")->where("menu_id","=",$id)->where("parent_id","=",0)->order_by('display_order','ASC')->find_all();
       //echo "CNT: ".count($menus);
        //echo "<pre>";
       // var_dump( $menus);// last_query();
        //exit;
       //echo "MENUS:<pre>";var_dump($menus);exit;
        $res = array();
        foreach($menus as $m)
        {
          // var_dump($m->id);
            $children = self::getSubMenus($m->id);
            $page = null;
            $link = null;
            
            if($m->link_type == "pages_id" ){
                $page =  self::getPages($m->link_value,false);
                 $p = new Model_Page();
                $link = $p->buildLink($m->link_value);
                if($m->label == ""){
                    $label = ORM::factory("Page",$m->link_value)->label;
                } else {
                    $label = $m->label;
                }
            } else {
                $link = $m->link_value;
                //$page = self::setPageforMenu(["slug"=>$m->link_value,'label'=>$m->label]);
                $label = $m->label;
            }
           
            
             
            $res[] = [
                "menu"=>[
                    'ul_attributes'=>$ul_attributes
                    ,"id"=>$m->id
                    ,"parent_id"=>$m->parent_id
                    ,"display_order"=>$m->display_order
                    ,"menu_id"=>$m->menu_id
                    ,"link_type"=>$m->link_type
                    ,"link_value"=>$m->link_value
                    ,"label"=>$label
                    ,"target"=>$m->target
                    ,"link_attributes"=>$m->link_attributes
                    ,"children"=>$children
                    ,'link'=>$link
                ],
                "pages"=>$page
                
            ];
        }
      //  echo "<pre>";
      // print_r($res);exit;
         if(count($res)>0){
                return $res;
            }
            return null;
    }
    
    public static function getSiblingsMenuByParentID($pid)
    {

               $menus  = self::getSubMenus($pid);
               //print_r($menus);
               echo self::createMenus($menus); 
    }
    
     public static function retSiblingsMenuByParentID($pid)
    {

               $menus  = self::getSubMenus($pid);
               //print_r($menus);
               return self::createMenus($menus); 
    }
    
    public static function getSiblingsMenuByName($menu_name,$page_id)
    {
          
          $menu_id = ORM::factory("Menus")->where("name",'=',$menu_name)->find()->id;
          //echo "Menu ID: ".$menu_id;
          //echo "PAGEID: ".$page_id;
          $menu= ORM::factory('Menupagelive')
                    ->where('link_value','=',$page_id)
                    ->where("menu_id","=",$menu_id)
                    ->find();
         //echo $menu->last_query();
       //   echo "<br />PARENTID: ".$menu->parent_id;
         echo self::getSiblingsMenuByParentID($menu->parent_id);
            
    } 
    
    public static function getChildrenMenuFromPageID($menu_name,$page_id)
    {
        $menu_id = ORM::factory("Menus")->where("name",'=',$menu_name)->find()->id;
        // echo "Menu ID: ".$menu_id;
         // echo "PAGEID: ".$page_id;
        $menu= ORM::factory('Menupagelive')
                    ->where('link_value','=',$page_id)
                    ->where("menu_id","=",$menu_id)
                    ->find();
      //  echo "MENU ITEM: ".$menu->id;
        echo self::getSiblingsMenuByParentID($menu->id);
    }
    
    public static function retChildrenMenuFromPageID($menu_name,$page_id)
    {
         $menu_id = ORM::factory("Menus")->where("name",'=',$menu_name)->find()->id;
        // echo "Menu ID: ".$menu_id;
         // echo "PAGEID: ".$page_id;
        $menu= ORM::factory('Menupagelive')
                    ->where('link_value','=',$page_id)
                    ->where("menu_id","=",$menu_id)
                    ->find();
        return self::retSiblingsMenuByParentID($menu->id);
    }
 
     public static function setAllPagesToMenu($menu_id)
    {
        $pages = ORM::factory("Page")->where("display_in_sitemap","=",1)->where("active","=",1)->find_all();
        //echo "CNT: ".count($pages);
        //CREATE THE RECORDS in THE DB
        $res =[];
        foreach($pages as $p){
          // $children = self::setChildrenPagesToMenu($menu_id,$p->id);
            $mp = new Model_Menupage();
            
            $mp->parent_id = 0;
            $mp->display_order = $p->display_order;
            $mp->menu_id = $menu_id;
            $mp->link_type = "pages_id";
            $mp->link_value = $p->id;
            $mp->label = $p->label;
            $mp->target = "_self";
            $mp->save();
            $res[]= [
                     "id"=>$mp->id
                    ,"link_value"=>$p->id
                    ,"parent_id"=>$p->parent_id
                    ];
            
            
            
        }
     
        //print_r($res);exit;
        
        
        //UPDATE TEH RECORDS THAT WERE CREATED WITH THE RIGHT PARENT_ID FROM THE MENU NOT PAGES
        foreach($res as $v){
             $menus = ORM::factory("Menupage")->where("link_value","=",$v['parent_id'])->where("menu_id","=",$menu_id)->limit(1)->find();
           // echo "<br />MENU_ID: ".$v['id']." PAGEID ".$v['link_value']." PAGE_PARENTID:".$v['parent_id'];
            $menu = new Model_Menupage($v['id']);
           // echo "<br />MMMM: ".var_dump($menus->id);
            $menu->parent_id = 0;
            if((int)$menu->id > 0){
                    $menu->parent_id = (int)$menus->id;
            }
            $menu->save();
        }
    }
    
    /**
     * FUNCTION to generate cached version of every submenu
     * @param html $html contains data to be store into cache
     * @param string $path unique for that subemenu
     * @return bool 
     */
    private static function generateCacheSubmenu($html,$path)
    {
       // echo "<br />".$path;
           try{
                //echo "ahere";exit;
                  $mem = Cache::instance('file');

                  $mem->set($path,$html);
                  $res = true;
              }  catch (Exception $e){
                  var_dump($e);
                  $res = false;
              }
              return $res;
    }
    
    
    /**
     * HELPER Function that generates all the sub menu
     * @param object $data children object
     * @return string that will be inserted into the children ul
     */
    private static function  createSubMenu($data,$u)
    {
        $ul_attr = "";
//     if(isset($data[0]['menu']['ul_attributes']) &&$data[0]['menu']['ul_attributes'] !="" ){
//         $ul_attr = $data[0]['menu']['ul_attributes'];
//     }
        $out = "<ul ".$ul_attr.">";
        $total = count($data);
        $cnt=1;
        
        foreach($data as $v){
            $url = $v['menu']['link_value'];
            if($v['menu']['link_type'] == "pages_id"){
                $url =  ORM::factory("Page")->buildLink($v['pages']['page']['id']);
            } 
            $children = null;
            if(is_array($v['menu']['children']) ){
                $children = self::createSubMenu($v['menu']['children'],$url);
            }
            $label = $v['menu']['label'];
            if($label ==""){
                $label = $v['pages']['page']['label'];
            }
          
           $attributes = "";
            if(isset($v['menu']['link_attributes'])){
                $attributes = $v['menu']['link_attributes'];
            }
            
            $out .="<li ".$attributes."><a href='".$url."' target='".$v['menu']['target']."'><span class='text'>".$label."</span></a>";
            if(!is_null($children)){
                $out .= $children;
            }

            $out .="</li>";
            $cnt =$cnt+1;

        }
        $out .= "</ul>";
        
        if(self::generateCacheSubmenu($out, $u)){
            return $out;
        }
        die("THERE WAS A PROBLEM GENERATING CACHE SUBMENU");
        
    }
    
    
    public static function createMenus($data = array())
    {
       $out ="";
        $total = count($data);
        
//        print_r($data);
//        exit;
//       echo "<br />TOTAL: ".$total;
//        exit;
        $cnt = 1;
        if($total >0){
            
                $ul_attr = "";
                if(isset($data[0]['menu']['ul_attributes']) &&$data[0]['menu']['ul_attributes'] !="" ){
                    $ul_attr = $data[0]['menu']['ul_attributes'];
                }
                $out = "<ul ".$ul_attr.">";
            foreach($data as $v){
                
                $url = $v['menu']['link_value'];
                if($v['menu']['link_type'] == "pages_id"){
                    $url =  ORM::factory("Page")->buildLink($v['pages']['page']['id']);
                } 
                
                  $children = null;
                if(is_array($v['menu']['children']) ){
                    $children = self::createSubMenu($v['menu']['children'],$url);
                }
                $label = $v['menu']['label'];
                if($label ==""){
                    $label = $v['pages']['page']['label'];
                }
               
                $class ="";
               
               
                $attributes = "";
                if(isset($v['menu']['link_attributes'])){
                    $attributes = $v['menu']['link_attributes'];    
                }
                
                $out .="<li ".$attributes."><a href='".$url."' target='".$v['menu']['target']."'><span class='text'>".$label."</span></a>";
                if(!is_null($children)){
                    $out .= $children;
                }
                $out .="</li>";
                $cnt = $cnt+1;
            }
            $out .= "</ul>";
        }
        
        //echo $out;exit;
        return $out;
    }
    
    /**
     * Function comparing the menu from the live table to the regular/save table 
     * @param type $menu_id
     * @return boolean ( true if the items are same from the menus_pages_live table to the regular men_pages table
     */
    public static function compareMenus($menu_id)
    {
        $res_l = false;
        $l = ORM::factory("Menupagelive")->where("menu_id","=",$menu_id)->order_by('parent_id',"desc")->order_by('display_order')->find_all();
           
//$res_l = [];
       foreach($l as $live){
           $res_l[] = [
               "id"=>$live->id,
               "parent_id"=>$live->parent_id,
               "display_order"=>$live->display_order,
               "menu_id"=>$live->menu_id,
               "link_type" => $live->link_type,
               "link_value"=>$live->link_value,
               "label"=> $live->label,
               "target"=>$live->target,
               "attributes"=>$live->link_attributes
                ];
       }
       // print_r($l);
       $res_s = false;
       $s =ORM::factory("Menupage")->where("menu_id","=",$menu_id)->order_by('parent_id',"desc")->order_by('display_order')->find_all();
       foreach($s as $save){
           $res_s[] = [
               "id"=>$save->id,
               "parent_id"=>$save->parent_id,
               "display_order"=>$save->display_order,
               "menu_id"=>$save->menu_id,
               "link_type" => $save->link_type,
               "link_value"=>$save->link_value,
               "label"=> $save->label,
               "target"=>$save->target,
               "attributes"=>$save->link_attributes
                ];
       }
//      echo "<pre>";
//        print_r($res_l);
//        print_r($res_s);
//      //  $dif = array_diff($res_l,$res_s);
//        //print_r($dif);
//        exit;
        return ($res_l == $res_s);
        
        
    }
    
    /**
     * Function that actually does the generation of cache for all the menus. 
     * it goes in the db and looks in the live table  and creates the menus and stores it in the cache.
     * @return boolean 
     */ 
    public static function generateCacheMenus()
    {
        
       // die("DO WE GET HERE");
        $menus = ORM::factory("Menupagelive")->group_by("menu_id")->find_all();
         
         //$menus  = ORM::factory("Menupagelive")->order_by("menu_id")->find_all();
         
         
        //var_dump($menus);exit;
         foreach($menus as $menu){
          
            if(!is_null($menu->menu_id)){

                   $me = ORM::factory("Menus",$menu->menu_id)->name;
                /// echo "<br />ME: ".$me;
                  //exit;
                   $gmenus = self::getMenus(['item_id'=>$menu->menu_id]);
                   //echo "<prE>";
                  //print_r($gmenus);exit;
                   $out = self::createMenus($gmenus);
//                 var_dump($out);
//                 exit;
                    try{
                        //echo "ahere";exit;
                          $mem = Cache::instance('file');

                          $mem->set("Menu_".$me,$out);
                          $res = true;
                      }  catch (Exception $e){
                          //var_dump($e);
                          $res = false;
                      }
                   }
             }
         
         
        return $res;
         
    }
    
    /**
     * Function that will pull from cache the menu name and if it does not exist  force 
     * the regeneration of the cache and reloads and redirect you back to where you wanted to go.
     * @param string $menu_name isthe menu name has it appears in the db table menus
     * @return mixed return the menu or redirects you after the cache is generated
     */
     public static function menuHandler($menu_name)
    {
       // echo "MENU: ".$menu_name;
        $menu = Cache::instance('file')->get("Menu_".$menu_name);
        //var_dump($menu);exit;k
        if(!is_null($menu)){
            return $menu;
        } else {
            if(count(ORM::factory("Menus")->where("name","=",$menu_name)->find_all())>0){//does the menu called exist
                $menu_id = ORM::factory("Menus")->where("name","=",$menu_name)->find()->id;
                if(count(ORM::factory("Menupagelive")->where("menu_id","=",$menu_id)->find_all() ) > 0  ){//does the menu called has menu item
                    self::generateCacheMenus();
                    $current_url = Request::current()->url().URL::query();
                    try{
                        HTTP::redirect($current_url);
                    } catch (Exception $ex) {
                        header( 'Location:'.$current_url ) ;//IF FOR SOME REASON THE FRAMEWORK REDIRECT DOES NOT WORK LET'S USE TEH PHP BUILT IN 
                        die();
                    }
                }
            }
        }       
    }
    
    public static function menuHandlerID($id)
    {
        $menus = ORM::factory("menus",$id);
        self::menuHandler($menus->name);
    }
    
    
    
    /**
     * Function that will generate a submenu using the cache system
     * @param string $path path (url) of the menu you want to get to  ie:/product/sub 
     * @param id $page_id the current page id
     * @param string $menu the name from which the function will pull menu items from
     * @return html or you get redirected
     */
    public static function subMenuHandler($path,$page_id,$menu)
    {

        $menu_id = ORM::factory("Menus")->where("name","=",$menu)->find()->id;
        
        $id = ORM::factory("Menupagelive")->where("link_value","=",$page_id)->where("menu_id","=",$menu_id)->find()->id;
        $children = ORM::factory("Menupagelive")->where("parent_id","=",$id)->count_all();
        if($children !==0){
          
        
            $sub = Cache::instance('file')->get($path);
            if(!is_null($sub)){
                   return $sub;
            } else {
                 self::generateCacheMenus();//even though we only care about the submenu since they depend on cached menu
                $goto ="";
                if(isset($_GET['goto'])){
                    $goto="/".$_GET['goto'];
                } else {
                    $current_url = Request::current()->url().URL::query();
                    $goto = $current_url;
                }
                //echo "GOTO: ".$goto;exit;
                 HTTP::redirect($goto);  
            }
        }
    }
   
    
    /**
     * FUNCTIONS RETURN AN ARRAY OF PAGES to be displayed as side menu
     * The loginc shoudl only show the second level siblings as well as possible chidlren
     * @param int $page_id current page id 
     */
    public static function sideMenuHandler($page_id)
    {
        $page = ORM::factory("Page")->where('id','=',$page_id)->where("active",">",0)->order_by("display_order")->find();
        $parent_id = (int)$page->parent_id;
        $children = ORM::factory("Page")->where('parent_id','=',$page_id)->where("active",">",0)->order_by("display_order")->find_all();
        
        //var_dump($parent_id);
        if($parent_id > 0){
            $siblings = ORM::factory("Page")->where('parent_id','=',$parent_id)->where("active",">",0)->order_by("display_order")->find_all();
            $parents = ORM::factory("Page")->where('id','=',$parent_id)->where("active",">",0)->order_by("display_order")->find();
            $side = self::sideMenuBuilder($siblings,$children,$page_id);
        } else {
            $side = self::sideMenuBuilder($children,[],$page_id);
        }

        return self::formatMenuBuilder($side);
    }
    
      /**
     * FUNCTION building the array that contians the pertaining information reagding the side menu tiem
     * @param page $page
     * @param page $children
     * @param int $current_id
     * @return array
     */
    public static function sideMenuBuilder($page,$children=[],$current_id)
    {
        $menu = [];
        //var_dump($page);exit;
        foreach($page as $p){
           $childs = [];
            if((int)$p->id === (int)$current_id){
                foreach($children as $child){
                    $childs[$child->id]=['label'=>$child->label,"slug"=>$child->slug];
                }
            }
            $menu[$p->id] = ['label'=>$p->label,'slug'=>$p->slug ,'children'=>$childs];
        }
        return $menu;
    }
    
     /***
     * Pass in array of pages like the one from getRootPages
     * it will create array that contains url,label,children page array and the last publish date
     * @param array $arr
     * @return array
     */
    public static function PageList($arr)
    {
       $out = [];
        foreach($arr as $a){
            if((int)$a['page']['sitemap'] >0){
                $url= "/";
                if($a['page']['slug'] != ""){
                    $url = Model_Page::getPageURL($a['page']['id']);
                }
                $child = [];
                if(is_array($a['page']['children'])){
                    $child  = self::PageList($a['page']['children']);
                }
                array_push($out,["url"=>$url,"label"=>$a['page']['label'],"child"=>$child,"changed"=>$a['content']['publish_date']]);
            }
        }
        return $out;
    }
    
    /***
     * Gets the list from ybr::PageList and converts it to an html unordered list
     *  @param array $list
     *  @return string
     */
    public static function PageListFormated($list)
    {
        $out ="<ul>";
        foreach($list as $l){
            $l = (object)$l;
            $out .="<li><a href='".$l->url."'><span>".$l->label."</span></a>";
            if(count($l->child)>0){
                $out .= self::PageListFormated($l->child);
            }
            $out .= "</li>";
        }
        $out .= "</ul>";
        return $out;
    }
    
    /**
     * Pass in the list of pages from ybr::PageList and converts it to sitemap friendly
     * @param array $list
     * @return string
     */
     public static function PageListXML($list,$child=false)
    {
        $out ="";
        if($child === false){
         $out .= '<?xml version="1.0" encoding="UTF-8"?>';
         $out .='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        }
        foreach($list as $l){
            $l = (object)$l;
            $out .="<url>";
            $url = "/";
            if(isset($l->url)&& $l->url !=""){
                $url = $l->url;
            }
            $protocol = "http://";
            if((int)$_SERVER['SERVER_PORT'] === 443){
                $protocol = "https://";
            }
            $out .= "<loc>" .$protocol. $_SERVER["HTTP_HOST"].$url."</loc>";
            if(isset($l->changed)){
             $out .= "<lastmod>".gmdate('Y-m-d\TH:i:s\Z', strtotime($l->changed))."</lastmod>";
            }
            $out .="</url>";
            if(count($l->child)>0){
               $out .=  self::PageListXML($l->child, true);
            }
        }
        if($child === false){
            $out .= "</urlset>";
        }
        return $out;
    }
    
    
    /**
     * This will format the array into a list
     * @param array $menu
     * @return string
     */
    public static function formatMenuBuilder($menu)
    {
 //      var_dump($menu);exit;
        if(isset($menu) && count($menu)>0){
            $out ="<ul>";
            foreach($menu as $m){
                $out .= "<li><a href='".$m['slug']."'><span>".$m['label']."</span></a>";
                //var_dump($m['children']);exit;
                if(isset($m['children']) && count($m['children']) >0){
                    $out .= self::formatMenuBuilder($m['children']);
                }
                $out .="</li>";
            }
            $out .= "</ul>";
            return $out;
        } 
        return "";
    }    
    
    
    public static function validateToken($token,$type)
    {
      //  echo $token;exit;
        $userid = false;
        $ret = false;
        $status = 0;
        switch($type){
            case "ybr_loggedin":
                $q = DB::select('user_id')->from("session_token")->where("loggedintoken", "=", $token)->execute();
                //echo $q->last_query();
               foreach($q as $a){
                   $userid = $a['user_id'];
               }
               //echo $userid;exit;
              if($userid !== false && $userid >0){
                  $status = ORM::factory("User",$userid)->status;
              }  
              $ret =  ["userid"=>$userid,"status"=>(int)$status];
              break;
            case "ybr_token":
                $q = DB::select('IP')->from('session_token')->where('token','=',$token)->execute();
             //    var_dump($q);exit;
                $ip =false;
                 foreach($q as $a){
                   $ip = $a['IP'];
                 }
                 if($ip !== false){
                  $ret = ["ip"=>$ip];
                 }
                 //var_dump($ret);
                break;
        }
        
        return $ret;
    }
    
   public static function searchResultPagination($search_results,$q,$match,$token)
    {
                if ($search_results->offset > 10 || $search_results->offset > 0) { 
                    $uri = "/search/?q=".$q."&match=".$match."&limit=10&offset=0&csrf=".$token;
                    echo HTML::anchor($uri, "&lt;&lt;",["title"=>"Go to First Page"]);
                }

                if ($search_results->offset > 0) {
                    $offset = 0;
                    if($search_results->offset - 10 >0)$offset = $search_results->offset -10;
                    $uri = "/search/?q=".$q."&match=".$match."&limit=10&offset=".$offset."&csrf=".$token;
                    echo HTML::anchor($uri, "&lt; Prev",["title"=>"Go to Previous Page"]);
                }

                if ($search_results->offset + $search_results->limit < $search_results->total_pages) {
                    $offset = $search_results->total_pages - 10;
                    if($search_results->offset + 10  < $search_results->total_pages)$offset = $search_results->offset +10;
                    $uri = "/search/?q=".$q."&match=".$match."&limit=10&offset=".$offset."&csrf=".$token;
                    echo HTML::anchor($uri, "Next &gt;",["title"=>"Go to Next Page"]);
                }

                if ($search_results->offset + $search_results->limit < $search_results->total_pages - 10 || $search_results->offset + $search_results->limit < $search_results->total_pages) {
                    $offset = $search_results->total_pages - 10;
                    if($search_results->offset + 10  < $search_results->total_pages)$offset = $search_results->offset +10;
                    $uri = "/search/?q=".$q."&match=".$match."&limit=10&offset=".$offset."&csrf=".$token;
                    echo HTML::anchor($uri, "&gt;&gt;",["title"=>"Go to Last Page"]);  
                }
           
    }
    
    //    public static function createCacheObject
    public static function cacheObject($cache_name,$cache_type)
    {
        $obj = Cache::instance('file')->get($cache_name);
        if(!is_null($obj)){
            return $obj;
        } else {
            $cache = new Model_Cache;
            $cache->createCache();
        }
    }
    
     // detect HTTP:// vs. HTTPS://
    public static function serverProtocol()
    {
        $out ="http://";
        if($_SERVER['SERVER_PORT'] === '443'){
            $out = "https://";
        }
        return $out;
    }

}


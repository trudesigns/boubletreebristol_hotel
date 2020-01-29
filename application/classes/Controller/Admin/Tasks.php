<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Custom pages unique to this site
 * 
 */

class Controller_Admin_Tasks extends Controller_Admin {
          public $user;
          public $acl = [
           "index"              => ["developer"]
           ,'delete_cache'    => ["developer"]
           ,'generate_cache'=> ["developer"]
          ];
          
          public function __construct(\Request $request, \Response $response) {
            //var_dump($this->user);exit;
            if(is_null($this->user)){
                if(is_array(Session::instance()->get("userdata"))){
                    $this->user = (object) Session::instance()->get("userdata");
                } 
            } 
            if(!ybr::acl_group($this->acl)){
                die("You do not have access to this function.");
            }
            if(!is_array($this->user_access_roles)){
                $this->user_access_roles = Session::instance()->get("accessroles");
            }
            if(!is_object($this->user)){
                //echo "123HERE";exit;
                $this->redirect('user/signin/?goto=' . $_SERVER['REQUEST_URI']);
            }


            //var_dump($this->user);
            parent::__construct($request, $response);


        }
        
           /**
     * load index "Dashboard" page
     */
    public function action_index() 
    {        
             $this->template->content =  new View('yellowbrick/pages/tasks/list');   
    }
    
    public function action_delete_cache()
    {
        Cache::instance('file')->delete_all();
       // unlink()
         $view = new View('yellowbrick/pages/tasks/confirm');
         $msg['menus'] ="The Cache has been deleted for the entire application.";
         $view->set("msg",$msg);
         $this->template->content = $view;
        
    }
    public function action_generate_cache()
    {
       $errors = "";
        $menus = ybr::generateCacheMenus();
        //var_dump($menus);exit;
       if($menus){
           $errors["menus"] ="The Cache has been regenerated for the all application.";
       } else {
           $errors["menus"] ="There was problem and the cache has not been generated";
       }
       $view = new View('yellowbrick/pages/tasks/confirm');
         $view->set("msg",$errors);
         $this->template->content = $view;
        
        //  $menu=  ybr::getMenus($recid);
         //   $out = ybr::createMenus($menu);
    }
    
    
    
    
    /*
    public function action_create()
    {
        $post = $this->request->post();
        
        $view = new View('yellowbrick/pages/redirects/form');
            $editid =null;
            if(!is_null($this->request->param('id'))){
               $editid = $this->request->param('id');
            }
           $red = null;
       // var_dump($post);
        // exit;
        if(!is_null($editid)){
              $red = ORM::factory("Redirect",(int)$editid);
              //echo $red->last_query();
        }
       //print_r($post);exit;
        if ($post) {
            if (isset($post['csrf']) && Security::check($post['csrf'])) {
                $p = Validation::factory($post)
                        ->rule('path', 'not_empty')
                        ->rule('destination', 'not_empty');
                //process post data (if applicable)
                   if ($p->check()) {
                       $this->user = (object)$this->user;
                        if(!is_null($editid)){
                             $red = ORM::factory("Redirect",(int)$editid);
                             $red->modified_by =$this->user->id;
                        } else {
                           $red = ORM::factory("Redirect");
                            $red->status = 2;
                            $red->created_by = $this->user->id;
                            $red->created_date = date("Y-m-d H:m:s");
                        }
                        //$view->set("member", $user);
                        $purifier = ybr::loadPurifier();
                        $path   = $purifier->purify($post['path']);
                        $dest   = $purifier->purify($post['destination']);
                        $notes = $purifier->purify($post['notes']);
                        
                        $destid = $purifier->purify($post['destination_id']);
                        
                        if(isset($destid) && (int)$destid > 0){
                            $dest = ORM::factory("Page")->buildLink($destid);
                        }
                        
                        $s301 =0;
                        if(isset($post['s301'])){
                            $s301   = 1;
                        }
                          
                         // print_r($post);exit;
                        //$email_mes = array($first,$last, $email, $pass);
                        $red->alias = $path;
                         $red->destination = $dest;
                        $red->notes = $notes;
                        $red->is_301 = $s301;
                        $red->notes = $notes;
                        


                        //$user->userid = $purifier->purify($_POST['userid']);
                        try{
                            $red->save();
                           // echo$red->last_query(); exit;
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

                        $view->msg = "The user has been created/updated...";
                  //      exit;
                    }  else {

                        $view->post = $post;
                       $view->msg = "The user you are creating/modifing has problems.";
    //                    //collect the errors, run them through /application/messages/[file] for pretty output
                       $view->errors = $post->errors('forms/user_errors');
    //                
                    }
                
                
                
            } //end if csrf
        }//end $_POST
        //var_dump($red->alias);exit;
        
          $view->set("redirect", $red);
          $view->set("editid",$editid);
       
        //array_unshift($parent_this->template->scripts, '/yb-assets/plugin/jquery.validate.min.js');
       $this->template->content = $view;
        
        
        
    }
        
    */
}

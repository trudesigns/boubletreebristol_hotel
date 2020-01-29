<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Custom pages unique to this site
 * 
 */

class Controller_Admin_Callouts extends Controller_Admin {
        public $user;
        public $acl = [
         "create"        => ['write']
        ];
        
        public function __construct(\Request $request, \Response $response) {
            //var_dump($this->user);exit;
            if(is_null($this->user)){
                if(is_array(Session::instance()->get("userdata"))){
                    $this->user = (object) Session::instance()->get("userdata");
                } 
            } 
            
          //  print_r($this->acl);exit;
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
    
        public function action_create()
        {
        $post = $this->request->post();
        
        if ($post) {
            if (isset($post['csrf']) && Security::check($post['csrf'])) {
                $p = Validation::factory($post)
                        ->rule('name', 'not_empty')
                        ->rule('image', 'not_empty')
                        ->rule('caption', 'not_empty');
                //process post data (if applicable)
                   if ($p->check()) {
                       $this->user = (object)$this->user;
                        if(!is_null($editid)){
                             $call = ORM::factory("Callout",(int)$editid);
                             $call->modified_by =$this->user->id;
                        } else {
                           $call = ORM::factory("Callout");
                           $call->status = 2;
                           $call->created_by = $this->user->id;
                           $call->created_date = date("Y-m-d H:m:s");
                        }
                        //$view->set("member", $user);
                        $purifier = ybr::loadPurifier();
                        $name   = $purifier->purify($post['name']);
                        $cap   = $purifier->purify($post['caption']);
                        $image = $purifier->purify($post['image']);
                        $link = $purifier->purify($post['link']);
                        //$destid = $purifier->purify($post['destination_id']);
                        
                       
                          $page = new Model_Page;
                          $url = $page->buildLink($link);
                         // print_r($post);exit;
                        //$email_mes = array($first,$last, $email, $pass);
                         // $call->pages
                        $call->name = $name;
                         $call->caption = $cap;
                        $call->image = $image;
                        $call->link = $link;
                      //  $red->notes = $notes;
                        


                        //$user->userid = $purifier->purify($_POST['userid']);
                        try{
                            $call->save();
                            $call->add('pages',ORM::factory("Page",$link)->parent_id);
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

                        $view->msg = "The Callout has been updated/created...";
                  //      exit;
                    }  else {

                        $view->post = $post;
                       $view->msg = "The Callout you are creating/modifing has problems.";
    //                    //collect the errors, run them through /application/messages/[file] for pretty output
                       $view->errors = $post->errors('forms/callout_errors');
    //                
                    }
                
                
                
            } //end if csrf
        }//end $_POST
        //var_dump($red->alias);exit;
        
          $view->set("callout",$call);
          $view->set("editid",$editid);
       
        //array_unshift($parent_this->template->scripts, '/yb-assets/plugin/jquery.validate.min.js');
       $this->template->content = $view;
        
        
        
    }
    
    
}
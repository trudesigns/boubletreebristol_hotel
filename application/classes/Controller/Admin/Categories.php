<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Custom pages unique to this site
 * 
 */

class Controller_Admin_Categories extends Controller_Admin {
        public $user;
        //protected $school_id;
        public $acl = [
         "index"          => ["dashboard"],
         "create"         => ["dashboard"],
        
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
       
        
        public function action_index() {
            
            
            
         $view = new View('yellowbrick/custom-pages/categories/list');

         //$q = DB::select()->from('news')->execute($db);
         $q = ORM::factory("Categories")->find_all();
             $view->cats= $q;
             $this->template->content = $view;
       
        
    }
    
        public function action_create()
        {
                $post = $this->request->post();
                
               
                $view = new View('yellowbrick/custom-pages/categories/form');
                $editid =null;
                if(!is_null($this->request->param('id'))){
                   $editid = $this->request->param('id');
                }
                
                $cat = null;
               
                if(!is_null($editid)){
                   // echo "EDITID: ".var_dump($editid);
                      $cat = ORM::factory("Categories")->where("id","=",(int)$editid)->find();
                     //echo $new->last_query();
                     // echo "djhdhdhdhd";
                }
        
                if ($post) {
                    if (isset($post['csrf']) && Security::check($post['csrf'])) {
                        $p = Validation::factory($post)
                                ->rule('name', 'not_empty');
                        //process post data (if applicable)
                           if ($p->check()) {
                               $this->user = (object)$this->user;
                                if(!is_null($editid)){
                                     $cat->modified_by =$this->user->id;
                                } else {
                                   $cat = ORM::factory("Categories");
                                  // $new->status = 2;
                                   $cat->created_by = $this->user->id;
                                   $cat->created_date = date("Y-m-d H:m:s");
                                }
                                //$view->set("member", $user);
                                $purifier = ybr::loadPurifier();
                                $title   = $purifier->purify($post['name']);
                        
                                $cat->name  = $title;
          

                                try{
                                    $cat->save();
                                  

                                } catch(Database_Exception $e){
                                     echo "DATABASE ERRORS<br /><pre>";
                                    var_dump($e);
                                    exit;
                                } catch(ORM_Validation_Exception $e){
                                    echo "VALIDATION ERRORS<br /><pre>";
                                    var_dump($e->errors());
                                    exit;
                                }

                                $view->msg = "The Category has been updated/created...";
                          //      exit;
                            }  else {

                                $view->post = $post;
                               $view->msg = "The Category you are creating/modifing has problems.";
            //                    //collect the errors, run them through /application/messages/[file] for pretty output
                               $view->errors = $post->errors('forms/categories_errors');
            //                
                            }



                    } //end if csrf
                }//end $_POST
                //var_dump($red->alias);exit;

                $view->set("cat",$cat);
                $view->set("editid",$editid);

       
                //array_unshift($parent_this->template->scripts, '/yb-assets/plugin/jquery.validate.min.js');
                $this->template->content = $view;
        
        
        
    }

}
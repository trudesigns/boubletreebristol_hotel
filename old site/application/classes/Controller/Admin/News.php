<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Custom pages unique to this site
 * 
 */

class Controller_Admin_News extends Controller_Admin {
        public $user;
        //protected $school_id;
        public $acl = [
         "index"          => ["dashboard"],
         "create"         => ["dashboard"],
         "newsSlug"    => ["dashboard"]
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
            
            
            
         $view = new View('yellowbrick/custom-pages/news/list');

         //$q = DB::select()->from('news')->execute($db);
         $q = ORM::factory("News")->find_all();
         $view->news= $q;
         $cats  = ORM::factory("Categories")->find_all();
         $cc[] ="";
         foreach($cats as $c){
             $cc[$c->id]=$c->name ;
         }
         $view->cats = $cc;
         $this->template->content = $view;
       
        
    }
    
        public function action_create()
        {
                $post = $this->request->post();
                
               
                $view = new View('yellowbrick/custom-pages/news/form');
                $editid =null;
                if(!is_null($this->request->param('id'))){
                   $editid = $this->request->param('id');
                }
                
                $new = null;
                if(!is_null($editid)){
                   // echo "EDITID: ".var_dump($editid);
                      $new = ORM::factory("News")->where("id","=",(int)$editid)->find();
                     //echo $new->last_query();
                     // echo "djhdhdhdhd";
                }
        
                if ($post) {
                    if (isset($post['csrf']) && Security::check($post['csrf'])) {
                        $p = Validation::factory($post)
                                ->rule('title', 'not_empty')
                                ->rule('content', 'not_empty')
                                ->rule('short', 'not_empty')
                                ->rule('sdate', 'not_empty')
                                ->rule('slug','not_empty');
                        //process post data (if applicable)
                           if ($p->check()) {
                               $this->user = (object)$this->user;
                                if(!is_null($editid)){
                                     $new->modified_by =$this->user->id;
                                } else {
                                   $new = ORM::factory("News");
                                   $new->status = 2;
                                   $new->created_by = $this->user->id;
                                   $new->created_date = date("Y-m-d H:m:s");
                                }
                                //$view->set("member", $user);
                                $purifier = ybr::loadPurifier();
                                $title   = $purifier->purify($post['title']);
                                $content   = $purifier->purify($post['content']);
                                $short   = $purifier->purify($post['short']);
                                $sdate = $purifier->purify(date("Y-m-d H:i",strtotime($post['sdate'])));                           
                                $slug = $purifier->purify($post['slug']);
                                $cat       = $post['category'];
                                //print_r($post['category']);exit;
                                
             //                   $school_data = Session::instance()->get("school_data");
                                
                                $new->title = $title;
                                 $new->content = $content;
                                 $new->short = $short;
                                $new->start_date = $sdate;
                                $new->slug = $slug;
                                
                          //      $new->origin=  $school_data['id'];

                                try{
                                    $new->save();
                                    $new->remove("categories");
                                    $new->add("categories",$cat);
                        

                                } catch(Database_Exception $e){
                                     echo "DATABASE ERRORS<br /><pre>";
                                    var_dump($e);
                                    exit;
                                } catch(ORM_Validation_Exception $e){
                                    echo "VALIDATION ERRORS<br /><pre>";
                                    var_dump($e->errors());
                                    exit;
                                }

                                $view->msg = "The News/Event has been updated/created...";
                          //      exit;
                            }  else {

                                $view->post = $post;
                               $view->msg = "The News/Event you are creating/modifing has problems.";
            //                    //collect the errors, run them through /application/messages/[file] for pretty output
                               $view->errors = $post->errors('forms/news_errors');
            //                
                            }



                    } //end if csrf
                }//end $_POST
                //var_dump($red->alias);exit;

                $view->set("new",$new);
                $view->set("editid",$editid);
                $bcats  = ORM::factory("Categories")->find_all()->as_array("id","name");
               //$cats = [""=>""];
                foreach($bcats as $k=>$v){
                    $cats[$k]=$v;
                }
                
                $view->set("cats",$cats);//ALL CATEGORIES
       
                //array_unshift($parent_this->template->scripts, '/yb-assets/plugin/jquery.validate.min.js');
                $this->template->content = $view;
        
        
        
    }
    
        public function action_newsSlug()
        {
            $this->auto_render = FALSE;
            $post = $this->request->post();
         
            $ltoken = null;
            if(count($post) >0){
                $ltoken =$post['ybr_loggedin'];
            }
        
            if (!Request::initial()->is_ajax() || is_null($ltoken)) {
                die("local XHR access only.");
            }// make sure this is an ajax call and not a dire
            //echo "Ssksjsjsj";exit;
 
            $purifier = ybr::loadPurifier();
             $slug = $purifier->purify($post['slug']);
             $editid = $purifier->purify($post['editid']);
            $slugs = ORM::factory("News")->where('slug','=',$slug)->where('id','<>',$editid)->find_all();
            if(count($slugs)>0){
                $out = 'false';
            } else {
                $out = 'true';
            }
            echo $out;
            
        }
}
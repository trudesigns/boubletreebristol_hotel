<?php defined('SYSPATH') or die('No direct script access.');


class Controller_News {
    //public $school_id,$school_data;
    
//    public function __construct() {
//        $this->school_data =  Session::instance()->get("school_data");
//        $this->school_id = $this->school_data['id'];
//            
//    }
   
    
    public function action_index($parent_this)
    {
        //SETTING UP THE STYLES AND SCRIPTS FOR THIS PAGE
        $scripts =[
            "/yb-assets/plugins/jquery.datatable/js/jquery.dataTables.min.js",
            "/yb-assets/plugins/dataTables.bootstrap.js"
        ];
        $styles =[
            "/yb-assets/plugins/jquery.datatable/css/jquery.dataTables.min.css"=>"all",
            "/yb-assets/plugins/chosen/bootstrap-chosen.css"=>"all"
        ];
        $parent_this->template->mstyles= $styles;
        $parent_this->template->mscripts= $scripts; 
        
        //DB CNX
       // $db = Database::instance("common");

        //URL 
        $ex = explode("news/",$parent_this->request->param('page'));
       //print_r($ex);
       
       
        if(is_array($ex) &&  isset($ex[1]) && $ex[1] != ""){//VIEWING A SINGLE ARTICLE 
            $view = new View('public/pages/news/article');
            $cnt = ORM::factory("News")->where("slug","=",$ex[1])->count_all();
            //var_dump($q);
            if($cnt){
                $q = ORM::factory("News")->where("slug","=",$ex[1])->find();
                $view->article = $q;
            } else {
                //$this->load_404();
                //$this->redirect('404');
                //throw new Kohana_HTTP_Exception_404();
                $parent_this->request->status = 404;
                $parent_this->template->title = 'Error 404 - Not Found';
                $view = new View('/public/pages/404');
            }
            

           // $parent_this->template->meta_title = $q[0]['title'];
            
           // $view->school_id = $this->school_id;
            
        } else {//VIEWING A LIST OF ALL THE ARTICLES
           $view = new View('public/pages/news/list');
           $q = ORM::factory("News")->where("status",">","1")->find_all();
          //var_dump($q1);exit;
          $view->news_list = $q;
        }
        return $view;
        
    }
    
    
}
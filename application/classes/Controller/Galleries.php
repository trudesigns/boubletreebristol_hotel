<?php defined('SYSPATH') or die('No direct script access.');


class Controller_Galleries {
    
    
   
    
    public function action_index($parent_this)
    {
        
        
        //SETTING UP THE STYLES AND SCRIPTS FOR THIS PAGE
//        $scripts =[
//            "/assets/plugins/photobox/jquery.photobox.js",
//            "https://npmcdn.com/masonry-layout@4.0.0/dist/masonry.pkgd.min.js"
//           
//        ];
//        $styles =[
//            'assets/plugins/photobox/photobox.css'=>'all'
//        ];
//        $parent_this->template->mstyles= $styles;
//        $parent_this->template->mscripts= $scripts; 
        
        $view = new View('public/pages/gallery');
        $this->template = $view;   
         //FIND THE ID OF THIS BLOCK_ID
        $id = ORM::factory("Contentblock")->where("name","=","Gallery")->find()->id;
        $galleries = ORM::factory("Content")->where('block_id','=',$id)->where('live','>',0)->find_all();
      //  echo "<pre>";
        //var_dump($galleries);exit;
        $view->set("galleries", $galleries);

       return $view;
        
    }
    
    
}
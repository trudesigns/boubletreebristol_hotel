<?php defined('SYSPATH') or die('No direct script access.');

 class Callouts extends Kohana_Blockbuilder
 {
     public $controller;
     public $method;
     public $template = 'views/callouts/index';
     
     public function __construct() {
         parent::__construct();
         echo "NOPE";
         $this->load();
     }
//    public function before() {
//        print_r($this);exit;
//        $this->template->styles[]=array("'assets/css/test.css"=>"all");
//    }
     
     public function load()
     {
         echo "SHSREREE";
         $this->template->styles[]=array("'assets/css/test.css"=>"all");
          // print_r($this);exit;
     }
     
      public function create()
     {
         
     }
     
     public function index()
     {
         
     }
     
     public function form()
     {
         
     }
     
     
 }
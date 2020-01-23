<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Default extends Controller_Setup {

    public $auth_required = TRUE;
    public $find_dynamic_page_data = TRUE;
    public $user = null;
    public $user_access_roles = null;
    public $acl = [];

    public function __construct(\Request $request, \Response $response) {

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

    public function load_404() {
        $this->response->status(404); // throw header response code for "File not found"
        $this->page = (object) array("label" => "ERROR: 404 - Page Not Found");
        $this->template->innerView = new View('public/pages/404');    //use this link for a custom 404 page
        //$this->template->innerView = "<h1>Error 404 - Page Not Found</h1>";  //use this line if there is no 404.php view	
    }

    public function action_index() {
       // echo "HERE";exit;
        if (!isset($this->page) || !isset($this->page->active) || $this->page->active != 1 || (isset($this->page->start_date) && $this->page->start_date != "0000-00-00 00:00:00" && strtotime($this->page->start_date) > time() ) || (isset($this->page->end_date) && $this->page->end_date != "0000-00-00 00:00:00" && strtotime($this->page->end_date) < time() )) {
            $this->load_404();
            return;
        }

        if ($this->auth_required && is_null($this->user)) {
            $this->redirect(PATH_BASE . 'user/signin/?goto=' . $_SERVER['REQUEST_URI']);
        }

        // if page requires a user role, make sure the user has it or is an admin
//		if(	$this->page->required_role != '' &&
//			!Auth::instance()->logged_in($this->page->required_role) && 
//			!Auth::instance()->logged_in('admin')
//			)
//		{
//			if(Auth::instance()->logged_in('login') ) // see if user is at least logged in at all
//			{
//				//user is already logged in but doesn't have the neccessary role (and isn't an admin)
//				$this->request->status = 403;
//				$this->template->innerView = "<h1>403 Forbidden</h1>You do not have access to view this page";
//				return;
//			}
//			else
//			{
//				$this->redirect(PATH_BASE.'user/signin/?goto='.$_SERVER['REQUEST_URI']);
//				return;
//			}	
//		}

        $template_parameters = json_decode($this->pageTemplate->parameters);
       // print_r($template_parameters);exit;
        if (isset($template_parameters->controller) && $template_parameters->controller != "default" && $template_parameters->controller != "") {
            $controller = "Controller_" . ucfirst($template_parameters->controller);
            //echo"CONTROLLER: ".$controller;
            $action = (isset($template_parameters->controller_action) && $template_parameters->controller_action != "") ? $template_parameters->controller_action : 'action_index';
            if ($action == "search") {
                $action = "action_search";
            }
            // echo "ACTIONS: ".$action;exit;
            $sub_controller = new $controller;
            $view = $sub_controller->$action($this);
        } else {
            $view_file = (isset($template_parameters->page) && $template_parameters->page != "") ? $template_parameters->page : $this->defaultTemplates['page']['path'];
            $view = new View($view_file);
        }

        if (isset($_GET['json'])) {
            $data['page'] = $this->page;
            $data['pageContentBlocks'] = $this->pageContentBlocks;
            $data['pageContents'] = $this->pageContents;
            //	$data['view'] = "$view";

            $this->auto_render = false;
            echo json_encode($data);
            return;
        }

        if (isset($this->page->end_date) && $this->page->end_date != "0000-00-00 00:00:00") {
            $this->template->page_expiration = $this->page->end_date;
        }

        // set the "Layout" inner template view
        // check if the page template sets a "custom layout" id
        if (isset($template_parameters->layout) && is_numeric($template_parameters->layout)) {
            $innerViewTemplate = ORM::factory('Template', $template_parameters->layout);
            $innerViewPath = $innerViewTemplate->parameters;
        } else {
            $innerViewPath = $this->defaultTemplates['layout']['path'];
        }
        $this->template->innerView = new View($innerViewPath);
        $this->template->innerView->bind('_this', $this);

        $this->template->innerView->pageView = $view;
        $this->template->innerView->pageView->bind('_this', $this);


          /*********
         * CONTENT SPECIFC TO DOUBELTREE
         */
        
        //NEWS
        $news  = ORM::factory("News")->where("status",">",2)->order_by("start_date","DESC")->limit(3)->find_all();
        $this->template->innerView->pageView->set('news', $news);
        
        //CATEGORIES
        $cats  = ORM::factory("Categories")->find_all();
        $this->template->innerView->pageView->set('cats', $cats);
        
   

        
      
        
      
        
        
    }

//end action_index()
}

// end controller
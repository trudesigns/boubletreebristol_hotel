<?php defined('SYSPATH') or die('No direct script access.');

/**
 * NOTES:
 *
 * When extending this Setup, the new controller must include the following:
 *
 * $auth_required			BOOL	
 * $find_dynamic_page_data	BOOL wether or not to look up page data and content based on URL
 *
 */
class Controller_Setup extends Controller_Template {

    public $user = null;
    public $user_access_roles = null;
    
    
    
    //set array of default templates
    //this data is referencing rows in the "templates" database table
    //it is hard coded here to help mitigate the number of database calls required for page templates using these defaults
    public $defaultTemplates = [
        'shell' => ['id' => 1, 'path' => 'public/templates/shell_default']
        ,'layout' =>['id' => 2, 'path' => 'public/templates/layout_default']
        ,'page' => ['id' => 3, 'path' => 'public/pages/default']
    ];
    //set template for this controller
    public $template = '';  //the default outer shell (will be overridden by an individual page template)
    public $secure_actions = FALSE;



    
    /**
     * function to determine page info and content 
     */
    // return URI of this page; used in lookup of page data
    private function getURI() {
        $parts = explode("?", $_SERVER['REQUEST_URI']);
        return trim($parts[0], "/");
    }

    // return all the info about this "page" 
    private function getPage() {
        $page = new Model_Page();
        // echo "PAGE: ".var_dump($page->getPagefromURL($this->getURI() ));exit;
        return $page->getPagefromURL($this->getURI());
    }

    // return the data about the types of content blocks assosiated with this page
    private function getContentBlocks($page) {
        $blocks = false;
        $template_blocks = ORM::factory('Contentblock')
                ->where('id', 'IN', DB::Select('content_block_id')->from('template_content_blocks'))
                ->find_all();

        foreach ($template_blocks as $block) {
            $blocks[$block->objectkey] = $block;
        }
        return (object) $blocks;
    }

    // get the actual content for all the blocks associated with this page
    private function getContents($blocks, $page_id) {
        $contents = false;

        //echo "BLOCKS";
       // var_dump(content_versioning);
        // determing what content version types are available to the current user (if applicable)
        $version_id_list = (content_versioning) ? Model_Contentversion::getAvailableVersions() : false;
        //var_dump($version_id_list);exit;
//echo "SBXBBXBXB<br />";var_dump($blocks);exit;
        foreach ($blocks as $block) {
            // if content_versioning is on find the correct version
            if (content_versioning && count($version_id_list) > 0) {
                $contents[$block->objectkey] = ORM::factory('Content')->where('block_id', '=', $block->id)
                                ->where('page_id', '=', $page_id)
                                ->where('live', '=', 1)
                                ->where('version_id', 'in', $version_id_list)
                                ->find()->as_array();
            }

            
           // print_r($contents);
            
            //if the above query didn't find any version-specific content (or content versioning is not being used) 
            // then find the default version of the content
            if (!isset($contents[$block->objectkey]) || !$contents[$block->objectkey]) {
                $where = array('block_id' => $block->id, 'page_id' => $page_id, 'version_id' => 1, 'live' => 1);
                $contents[$block->objectkey] = ORM::factory('Content', $where)->as_array();
            }
//print_r($_GET);exit;
            //if "preview" content is being requested by the CMS, overwrite live content with the requested revision
            if (isset($_GET['preview_block_objectkey']) &&
                    $_GET['preview_block_objectkey'] == $block->objectkey &&
                 //   Auth::instance()->logged_in('admin') &&
                    is_numeric((int)$_GET['content_id'])) {
                $this->page->active = 1; // override exisiting active state
                $contents[$_GET['preview_block_objectkey']] = ORM::factory('Content', $_GET['content_id'])->as_array();
            }

            //extend $contents object with "wrapped" property (if applicable)
            if (content_wrapping) {
                $wrapBlockParameters = array('template' => $this->pageTemplate, 'block' => $block, 'content' => $contents[$block->objectkey]);
                $contents[$block->objectkey] = (object) array_merge($contents[$block->objectkey], array('wrapped' => Model_Contentblock::wrapBlock($wrapBlockParameters)));
            }
        }

        return (object) $contents;
    }

    /**
     * ^ END of page info functions ^ 
     */
    public function before() {
         if(is_null($this->user)){
            if(is_array(Session::instance()->get("userdata"))){
                $this->user = (object) Session::instance()->get("userdata");
            } 
        } 
        if(!is_array($this->user_access_roles)){
            $this->user_access_roles = Session::instance()->get("accessroles");
        }
        // check to see if there is a re-direct for this URL
        $redirectObj = new Model_Redirect();
        //echo "URL: ".$this->getURI()."<br /><br />";
        $redirect = $redirectObj->lookup("/".$this->getURI(), true);
        //var_dump($redirect);exit;
        if ($redirect && $redirect->status > 1) {
           
            $status_code = ($redirect->is_301 == 1) ? 301 : 302;
            $this->redirect($redirect->destination, $status_code);
        }

        if ($this->auto_render) {
              //  echo "AUTO_RENDER IN SETUP CONTROLLLER";exit;
                 // determin and set page information
                 if ($this->find_dynamic_page_data) {
                  //   echo "DYNAMIC PAGE";
                     $this->page = $this->getPage();
                    //echo "<prE>";var_dump($this->page);exit;
                     if ($this->page) {
                         $this->pageTemplate = ORM::factory('Template', $this->page->template_id);
                         $this->pageContentBlocks = $this->getContentBlocks($this->page);
                         $this->pageContents = $this->getContents($this->pageContentBlocks, $this->page->id);

                         // set the "Shell" Outer template view
                         // this must be done prior to calling the parent::before() method
                         $template_parameters = json_decode($this->pageTemplate->parameters);
                    //     print_r($template_parameters);exit;
                         if (isset($template_parameters->shell) &&
                                 is_numeric($template_parameters->shell) &&
                                 $template_parameters->shell != $this->defaultTemplates['shell']['id'] // make sure its not set to default anyway
                         ) {
                             $selectedShell = ORM::factory('Template', $template_parameters->shell);
                             $this->template = $selectedShell->parameters;
                         }
                     }
                 }

                 // if the default outer "shell" view hasn't been set yet, set it to default
                 if ($this->template == "") {
                     $this->template = $this->defaultTemplates['shell']['path'];
                 }

                 parent::before();

                 // initialize empty values
                 $this->template->bind('_this', $this);
                 $this->template->tstyles = [];
                $this->template->mstyles = [];
                $this->template->bstyles = [];
                 $this->template->tscripts = [];
                 $this->template->mscripts = [];
                 $this->template->bscripts = [];

         }
    }

    public function after() {
        if ($this->auto_render) {
              $tstyles = [
                  'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css'=>"all"
                  ,'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css'=>'all'
                  ,'assets/css/bootstrap.inline-responsive.css'=>'all'
                  ,'/yb-assets/plugins/chosen/chosen.min.css'=>'all'
                  ,'/yb-assets/plugins/chosen/bootstrap-chosen.css'=>'all'
                  ,'/yb-assets/plugins/jquery-ui.1.11.0/jquery-ui.min.css'=>'all'
                  ,'/yb-assets/plugins/jquery-ui.1.11.0/jquery-ui.theme.min.css'=>'all'
                  ,'assets/plugins/photobox/photobox.css'=>'all'
                  
            ];
            $mstyles = [];
            $bstyles = [
                 'assets/css/style.css'=>'all',
                'assets/css/owl.carousel.css'=>'all'
            ];
            //set global scripts - call any extra like above example, $this->$template->scripts
            $tscripts = [
                  'http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js'
                , 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js'
                , 'assets/js/owl.carousel.min.js'
                 , 'yb-assets/js/security.js'
                ,'yb-assets/plugins/chosen/chosen.jquery.min.js'
                ,'yb-assets/plugins/jquery-ui.1.11.0/jquery-ui.min.js'
                ,'yb-assets/plugins/jquery.validate/jquery.validate.min.js'
                ,'yb-assets/plugins/jquery.validate/additional-methods.min.js'
                ,"/assets/plugins/photobox/jquery.photobox.js"
                
            ];
            $mscripts = [];
            $bscripts = [
                  'assets/js/script.js'
            ];
            //pass the settings on to the template
            $this->template->tstyles = array_merge($tstyles, $this->template->tstyles);    // append STYLE TOP
            $this->template->mstyles = array_merge($mstyles, $this->template->mstyles);    // append STYLE MIDDLE
             $this->template->bstyles = array_merge($bstyles, $this->template->bstyles);    // append STYLE BOTTOM
            
            $this->template->tscripts = array_merge($tscripts, $this->template->tscripts); // append array of scripts TOP
            $this->template->mscripts = array_merge($mscripts, $this->template->mscripts); // append array of scripts MIDDLE
            $this->template->bscripts = array_merge($bscripts, $this->template->bscripts); // append array of scripts BOTTOM
        
        }
        parent::after();
    }

}

// end controller




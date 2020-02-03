<?php defined('SYSPATH') or die('No direct script access.');

class Model_Page extends ORM {

    protected $_has_many = array(
        'articles' => array(),
        'content' => array(),
    );

    /**
     *  INSERT or UPDATE a page with user submited POST data
     *   
     * 	@param	$action string	"update" or "add"
     * 	@param	$post	ARRAY	$_POST data	submited by user
     */
    public function add_or_update($action, $post) {

        //make sure a PARENTgetPagefromURL ID was set
        if (!isset($post['parent_id']) || !is_numeric($post['parent_id'])) {
            exit("Invalid Parent ID");
        }

        //make sure PAGE ID was set IF this is an "update" command
        if ($action == "update" && (!isset($post['id']) || !is_numeric($post['id']))) {
            exit("Invalid Page ID");
        }

        //if this is a child page (it has a parent id) make sure the parent isn't the homepage and that it's parent allows children
        if ($post['parent_id'] != 0) {
            $parent = ORM::factory('Page', $post['parent_id']);
            if ($parent->slug == "" || $parent->slug == "/") {
                exit("\nCan not create subpage of homepage or a page without a valid uri slug");
            }

           if( (int)$parent->add_children_role >0 && Auth::instance()->logged_in('config') === false ){
                exit("\nCan not create subpage of \"" . $parent->label . "\"");
            }
        }
       

        // TO DO: check auth to make sure user can add page
        // should have either a "master" role that allows adding pages
        // or their id should match the table of specific user id's that can edit specific page ids
        // if the parent id is zero, make sure they have access to add top level pages
        // update the pages table with this action
        if ($action == "add") {
            $page = ORM::factory('Page', false);
        } else {
            $page = ORM::factory('Page', $post['id']);
        }
        $page->parent_id = $post['parent_id'];

        // NOTE: Template ID "3" has been used here based on new shell/layout/page view construction and original installation SQL. 
        // Actual "Default" template ID may change later for various reasons. Not sure what to do about this except keep hard coding an ID here.
        $page->template_id = (isset($post['template_id']) && $post['template_id'] != "") ? $post['template_id'] : 4;
        $page->slug = (isset($post['slug']) && $post['slug'] != "") ? $post['slug'] : "new-" . date("YmdHis");
        $page->label = (isset($post['label']) && $post['label'] != "") ? $post['label'] : "New Page";

        
         //LETS GET THE MAX VALUE OF DISPALY ORDER FOR ALL THE PAGE THAT HAVE TEH SAME PARENT ID
       $last_order = ORM::factory("Page")->select([DB::expr('MAX(`page`.display_order)'), 'order'])->where("parent_id","=",$page->parent_id)->find_all();
        foreach($last_order as $l){
            $order = $l->order;
        }
        
        // if this is a top level page (parent_id = 0) then it can't have a display order of 0 because that is reserved for the homepage
        if (isset($post['display_order']) && $post['display_order'] != "") {
            $display_order = ( $post['parent_id'] == 0 && $post['display_order'] == 0 ) ? 1 : $post['display_order'];
        } else {
            $display_order =   intval($order)+ 1;
        }

        $page->display_order = $display_order;
        $page->required_role = (isset($post['required_role']) && $post['required_role'] != "") ? $post['required_role'] : '';
        $page->add_children_role = (isset($post['add_children_role']) && $post['add_children_role'] != "") ? $post['add_children_role'] : '';
        $page->active = (isset($post['active']) && is_numeric($post['active']) ) ? $post['active'] : 1;
        $page->start_date = (isset($post['start_date']) && $post['start_date'] != "") ? $post['start_date'] : '0000-00-00 00:00:00';
        $page->end_date = (isset($post['end_date']) && $post['end_date'] != "") ? $post['end_date'] : '0000-00-00 00:00:00';
        $page->searchable = (isset($post['searchable']) && is_numeric($post['searchable']) ) ? $post['searchable'] : 1;
        $page->display_in_sitemap = (isset($post['display_in_sitemap']) && is_numeric($post['display_in_sitemap']) ) ? $post['display_in_sitemap'] : 1;
        $page->save();

        // if boilerplate content is passed for this new page, add it now.
        if (isset($post['content']) && is_array($post['content'])) {
            foreach ($post['content'] as $index => $contents) {
                $content = ORM::factory('Content', false);
                $content->block_id = $contents['block_id'];
                $content->version_id = (array_key_exists('version_id', $contents)) ? $contents['version_id'] : 1;
                $content->page_id = $page->id;
                $content->content = $contents['content'];
                $content->revision_date = date("Y-m-d H:i:s");
                $content->updated_by = (array_key_exists('updated_by', $contents)) ? $contents['updated_by'] : Auth::instance()->get_user();
                $content->save();
            }
        }

        return $page->id;
    }

    /**
     * Convert a string of text into an SEO friendly URL slug
     * 
     */
    public function generateSlug($string) {
       $string = trim(strtolower($string));//REMOVE WHAT SPACE AROUND TEH LOWERCASE STRING

       $string = str_replace("of","",$string);//REMOVE OF 
       $string = str_replace("the","",$string);//REMOVE THE
       $string = str_replace("for","",$string);//REMOVE FOR
       
       $string = preg_replace("/[^a-z0-9 _-]/", "", $string); //REMOVE ANYTHING THAT IS NOT A SMALL NUMBER OR LETTER OR SPACES or dashes  
       $string = preg_replace("/\s+/", " ", $string);//REMOVE THE DOUBLE (OR MORE) SPACES AND CONVERTS TO 1 SPACE
       $string = str_replace("--","-",$string);//CONVERTS DOUBLE DASH TO 1 DASH
       $string = str_replace(" ","-",$string);//CONVERTS 1 SPACE TO 1 DASH
        return $string;
    }

    /**
     * Return the array of row data from the 'Pages' table for the given URI
     *
     * @param $route      			$route the URI after the http:///domain-name.com/
     * @param $recursionFlag		$recursionFlag is set to true if the function is being called in recursive loop.
     * @return OBJECT of page data		   
     *
     * assumes 'pages' table has these columns:
     *    'id'            the given row ID for a page
     *    'parent_id'   the page.id one level higher in the URL heiarchy (value is INT; 0 = page has no parent)
     *    'slug'    the given page's pseudo "filename" in the URL (example: "About-Us")
     */
    public function getPagefromURL($route, $recursionFlag = false) {
        //$route looks like:  page/subpage1/subpage2/subpage3
        // echo "Route: ".var_dump($route);exit;
        $route = ltrim($route, "/"); // remove and leading slashes (even though there shouldn't be one)
        if ($route == "") {
            $uri_parts = array('');
        } else {
            $uri_parts = explode("/", $route); //returns  [0] => page [1] => subpage1 [1] => subpage2 [2] => subpage3
        }
        //print_r($uri_parts);exit;
        // if url_locale is turned on, look for template content blocks that use the url to determine what version of the content to load
        // eg:  /about-us = default version vs /es/about-us = spanish version		
        if (url_locale) {
            $block_versions = ORM::factory('Contentversion')->where('selector', '=', 'url')->find_all();
            if (count($block_versions) > 0) {
                foreach ($block_versions as $version) {
                    if ($uri_parts[0] == $version->selector_key) {
                        array_shift($uri_parts); //remove the first element from the uri chain	
                        $route = ltrim($route, $version->selector_key . "/"); // and remove it from the start of the $route string as well	
                        break;
                    }
                }
            }
        }

        $uri_num_parts = count($uri_parts);
        // echo "PPPP: ".urldecode($uri_parts[$uri_num_parts - 1]);
        if ($uri_num_parts <= 1) {
            $sql_where = " slug = '{$route}' AND parent_id = 0";
        } else {
            $urlp =  urldecode($uri_parts[$uri_num_parts - 1]);
            $sql_where = " slug = '{$urlp}' "; //start with last page in array (-1 because array starts w/ 0)
            
            $sub_query_braces = "";
            for ($i = $uri_num_parts - 2; $i >= 0; $i--) {  //loop backwards through array of subpages.  $i = array key (-2 because we already added -1 above)
                //echo "URIPARTSA: ".$uri_parts[$i];
                $check4noParent = ($i == 0) ? "AND parent_id = 0" : "";
                $sql_where.= "AND parent_id = (SELECT id FROM " . _table_pages . " WHERE slug = '{$uri_parts[$i]}' $check4noParent ";
                $sub_query_braces.= ")";
            }//end loop through array
            $sql_where.= $sub_query_braces;
        }

        $sql = "SELECT * FROM " . _table_pages . " WHERE  " . $sql_where;
        // print_r($sql);

        //echo "SQL: ".$sql;exit;
        $results = DB::query(1, $sql)->as_object()->execute();
        // var_dump($results);exit;
       // echo "<br />CNT: ".count($results);
        if (count($results) == 0) { // if no page was found, maybe the end of the url is really a variable being passed to the controller.  lop it off and try again.
            array_pop($uri_parts);
            if (count($uri_parts) == 0) {
                return false;
            } // no page could be found, return FALSE here. controller should return 404
            $route = implode("/", $uri_parts);
            return $this->getPagefromURL($route, true); // recursivly re-run this function (and set $recursionFlag to TRUE)
        }  else {
            if ($recursionFlag) {
                $template = ORM::factory('Template', $results[0]->template_id);
                $template_parameters = json_decode($template->parameters);
                if (!isset($template_parameters->dynamic_uri) || $template_parameters->dynamic_uri != 1) {
                    return false; // this page's template is not set to have pseduo children. controller should return 404
                }
            } //end check for $recursionFlag
            // echo "shshshs";
            //print_r($results[0]);exit;
            return $results[0];
        }
    }

    /**
     * Get the Pages (and subpages) of a given Parent ID
     *
     * @param $params		ARRAY	'parent_id' = INT ID of top level pages for this tree; defaults to 0 if left out
* 				'generations' => INT of how many recursions this function should continue with. Leave blank/false for ALL children
* 				'selected_pages' => ARRAY (optional) of IDs of specific pages to include at the top level
* 				'excluded_pages' => ARRAY (optional) of IDs of specific pages to ignore. Will also ignore their children.
* 				'generate_uri'	=> BOOL, TRUE figures out the full URI for each page and returns it as a STRING in $uri
* 				+
* 				'active_only' => BOOL, TRUE (default) shows only pages marked as 'active' with 'start_date' in the past and 'end_date' in the future FALSE shows all pages regardless of 'active' state or time stamps 								 
* 				'display_in_sitemap_only' => BOOL, TRUE (default) shows only pages with 'display_in_sitemap' set to TRUE								 
* 				OR
* 				'menu_id' => INT of menu ID from `menus_pages` table
     *
     * @param $generation	INT		Leave Blank.  Used by recursive calls to this function
     *
     * @return	Array of stdClass "page" Objects 				
     */
    public function getPages($params = array(), $generation = 0) {
        $menu = array();
        $parent_id = (array_key_exists('parent_id', $params) && is_numeric($params['parent_id'])) ? $params['parent_id'] : 0;
        $params['toplevel_parent'] = (!isset($params['toplevel_parent']) ) ? $parent_id : $params['toplevel_parent'];

        // get page info from `menu_pages` table
        if (array_key_exists('menu_id', $params) && is_numeric($params['menu_id'])) {
            $sql = DB::query(Database::SELECT, 'SELECT id as thisID,												
												   parent_id as thisParentID,
												   link_type,link_value,	
												   label, target,
												   link_attributes,
												   (SELECT label FROM ' . _table_pages . ' WHERE id = link_value) as page_label,
												   (SELECT COUNT(id) FROM ' . _table_menus_pages . ' WHERE parent_id = thisID) as children												   												   
												 FROM ' . _table_menus_pages . ' WHERE menu_id = ' . $params['menu_id'] . ' AND parent_id = ' . $parent_id . ' ORDER BY display_order');

            // or get page info from default `pages` table
        } else {

            $active = " AND active = 1 AND (start_date < NOW() OR start_date = '0000-00-00 00:00:00') AND (end_date > NOW() OR end_date = '0000-00-00 00:00:00') ";

            if (array_key_exists('active_only', $params) && !$params['active_only']) {
                $active = "";
            }

            $sitemap = (array_key_exists('display_in_sitemap_only', $params) && $params['display_in_sitemap_only']) ? " AND display_in_sitemap = 1 " : "";
            $sql = DB::query(Database::SELECT, 'SELECT id as thisID,
												   slug,label,template_id,
												   parent_id as thisParentID,
												   required_role, add_children_role, 
												   active, display_in_sitemap,
												   start_date, end_date,
												   (SELECT COUNT(id) FROM pages WHERE parent_id = thisID) as children,
												   (SELECT slug FROM pages WHERE id = thisParentID) as parentName
											 	FROM ' . _table_pages . ' WHERE parent_id = ' . $parent_id . $active . $sitemap . ' ORDER BY display_order');
        }

        $pages = $sql->as_object()->execute();

        $i = 0;
        foreach ($pages as $page) {

            if (array_key_exists('selected_pages', $params) &&
                    is_array($params['selected_pages']) &&
                    $page->thisParentID == $params['toplevel_parent'] &&
                    !in_array($page->thisID, $params['selected_pages'])
            ) {
                continue; // if the given page is NOT in the array, skip to next page
            }

            if (array_key_exists('excluded_pages', $params) &&
                    is_array($params['excluded_pages']) &&
                    in_array($page->thisID, $params['excluded_pages'])) {
                continue; // if the given page IS in the array, skip to the next page
            }

            if ($i == 0) {
                $generation++;
            } // every time this function is called and $i is reset to 0, increment the generation count

            if (array_key_exists('generations', $params) && is_numeric($params['generations']) && $generation > $params['generations']) {
                return; // stop once we've reached the last specified generation
            }

            if (array_key_exists('generate_uri', $params) && $params['generate_uri']) {
                $page->uri = $this->buildLink($page->thisID);
            }

            $menu[$i] = $page;
            $menu[$i]->generation = $generation;

            if ($page->children > 0) {

                $params['parent_id'] = $page->thisID; //update ID to start this function with on next incursion 
                $menu[$i]->child_pages = $this->getPages($params, $generation); // call this function again
            }

            $i++;
        }

        return (count($menu) > 0 ) ? (object) $menu : false;
    }

    /**
     * helper function to recursively search nested array
     */
    private function in_array_r($needle, $haystack) {
        foreach ($haystack as $item) {
            if ($item == $needle || (is_array($item) && $this->in_array_r($needle, $item)))
                return true;
        }
    }

    /**
     * DRAW HTML Menu as an Unordered List (UL) from a pages Object
     *
     * $pages		OBJECT	data from getPages() or menus_pages table
     * $params		ARRAY	(optional)
     *  'li_format'	STRING	string of contents to be inserted into each list element
     * 						including variables from the menu object or this function
     *  'id_label' 	STRING	(optional) text to be prepended to the page ID and included as the <li> DOM ID
     *  'thisID'	INT		ID of the calling page  (or the menus_pages row ID that corrisponds to the current page)
     *  'currentChildrenOnly' BOOL	if set to TRUE, will only show child subpages for the current page, or if the current page is one of those subpages (requires 'thisID' to be set)
     *  'hasChildrenClass'	STRING	custom class name if a given item has children
     *  'currentPageClass'	STRING  custom class name if a given item is the current page (requires 'thisID' to be set)
     * 	'currentPageBreadcrumbClass'	STRING	custom class name if a given item is in the breadcrumb trail of the current page
     * 	'currentPageParentClass'	STRING	custom class name a given item is the parent of the current page (requires 'thisID' to be set)
     * 	'ul_html'	STRING	arbitrary HTML to be added to the top level UL
     * 
     * $slugRoot	STRING	used by recursive calls to this function
     * $html		STRING 	used by recursive calls to this function
     *
     */
    public function drawMenu_UL($pages, $params = array(), $slugRoot = "", $html = "") {


        if (isset($pages) && $pages != "" && count((array) $pages) > 0) {
            if (array_key_exists('ul_html', $params) && $params['ul_html'] != "") {
                $html.="<ul " . $params['ul_html'] . ">\n";
                $params['ul_html'] = ""; // reset or unset so it this isn't used on subsequent UL's within this list
            } else {
                $html.= "<ul>\n";
            }

            foreach ($pages as $item) {

                if (isset($item->link_type)) { // this is a "menus" table menu
                    if ($item->link_type == "pages_id") {
                        $newSlug = $this->buildLink($item->link_value);

                        if ($item->label == "") {
                            $item->label = $item->page_label; // if the label isn't set, overload the blank value with the label from the page's table
                        }
                    } elseif ($item->link_type == "url") {
                        $newSlug = $item->link_value;
                    }
                } else { // this is a "pages" table menu
                    // if this is drawing a menu thats starts with a sub page, the URL scheme needs to start with that pages URL
                    if ($slugRoot == "" && $item->thisParentID != 0) {
                        $slugRoot = $this->buildLink($item->thisParentID);
                    }

                    if (isset($item->slug)) {
                        $newSlug = $slugRoot . "/" . $item->slug;
                    } else {
                        $newSlug = $slugRoot; // keep the same path
                    }
                }

                $item->label = preg_replace("/&/", "&amp;", $item->label); //make ampersands HTML friendly

                $html.= (array_key_exists('id_label', $params) ) ? '<li id="' . $params['id_label'] . $item->thisID . '">' : '<li>';

                $params['li_format'] = (array_key_exists('li_format', $params) ) ? $params['li_format'] : '<a href=\"$newSlug\" $drawClass >$item->label</a>';

                $currentChildrenOnly = (array_key_exists('currentChildrenOnly', $params) ) ? $params['currentChildrenOnly'] : false;

                $currentParent = false; // assume $item is not the parent of the current page (unless changed below)

                $drawClass = 'class="';

                if (isset($item->link_type)) { // this is a menus_pages page
                    // look to see if additional class parameters have already been set
                    preg_match_all('/class="(.*)"/i', $item->link_attributes, $matches);
                    if (isset($matches[0][0]) && $matches[0][0] != "") {
                        // update the $drawClass string with the class info
                        $drawClass.= $matches[1][0] . " ";

                        // strip out the class="" string from the $item->link_attributes string
                        $item->link_attributes = preg_replace("/" . $matches[0][0] . "/", '', $item->link_attributes);
                    }
                }

                if ($item->children > 0 && count((array) $item->child_pages) > 0) {
                    // add "hasChildrenClass" as needed
                    $drawClass.= (array_key_exists('hasChildrenClass', $params) ) ? $params['hasChildrenClass'] . ' ' : 'menu_hasChildren ';

                    if (array_key_exists('thisID', $params)) {

                        // set an array of breadcrumb data for the current page
                        // TODO: move this to a one off function to be called once
                        //       when the drawMenuUL() function is first called.
                        //		 Optionally, allow programmer to set the $params['currentPageCrumb'] manually and skip this
                        //       step all together.
                        if (!array_key_exists('currentPageCrumbs', $params)) {
                            $breadcrumb_use_menus_pages = (isset($item->link_type)) ? true : false;
                            $params['currentPageCrumbs'] = $this->getBreadcrumbs($params['thisID'], $breadcrumb_use_menus_pages);
                            $params['currentPageCrumb'] = array_pop($params['currentPageCrumbs']);
                        }

                        // add "currentPageBreadcrumbClass" as needed
                        if ($this->in_array_r($item->thisID, $params['currentPageCrumbs'])) {
                            $drawClass.= (array_key_exists('currentPageBreadcrumbClass', $params) ) ? $params['currentPageBreadcrumbClass'] . ' ' : 'menu_currentpage_breadcrumb ';
                            $currentParent = true;
                        }

                        // add "currentPageParentClass" if this is the parent page of the current page							
                        if (isset($params['currentPageCrumb']['parent_id']) && $params['currentPageCrumb']['parent_id'] == $item->thisID) {
                            $drawClass.= (array_key_exists('currentPageParentClass', $params) ) ? $params['currentPageParentClass'] . ' ' : 'menu_currentPageParent ';
                        }
                    }
                }

                // add "currentPageClass" if this is the current page
                if (array_key_exists('thisID', $params) && $item->thisID == $params['thisID']) {
                    $drawClass.= (array_key_exists('currentPageClass', $params) ) ? $params['currentPageClass'] . ' ' : 'menu_currentPage ';
                }

                $drawClass.= '"';
                if ($drawClass == 'class=""') {
                    $drawClass = "";
                } // don't botther printing out anything 

                eval("\$li_content = \"$params[li_format]\";");
                $html.= $li_content;

                if ($item->children > 0 && count((array) $item->child_pages) > 0 && (!$currentChildrenOnly ||
                        $currentParent ||
                        (array_key_exists('thisID', $params) && $item->thisID == $params['thisID']) )
                ) {
                    $html = $this->drawMenu_UL($item->child_pages, $params, $newSlug, $html);
                }
                //	else { $html.= $params['thisID']; }

                $html.= " </li>\n";
            }
            $html.= "</ul>\n";
        }
        return (array_key_exists('as_array', $params) && $params['as_array']) ? $as_array : $html;
    }

    /**
     * DRAW HTML Menu as SELECT OPTIONS 
     * 
     *  ** DEPRECIATED	**
     *  Prefered method is to return a page object to client side and generate select menu w/ javascript
     *  **				**
     * $pages	Object	data from getPages()
     * $value	string	option value either "id" or "slug"
     * $selected_value 	value to pre-select in list
     *
     * returns HTML to be wrapped in <SELECT> tags
     *
     * 		example:
     * 			echo "<select>";
     * 			$pages = $this_page_object->getPages();
     * 			$this_page_object->drawMenu_select($pages);
     * 			echo "</select>";
     */
    public function OLD_drawMenu_select($pages, $value = 'slug', $selected_value = false, $slugRoot = "") {
        foreach ($pages as $item) {
            $newSlug = $slugRoot . "/" . $item->slug;
            $showvalue = ($value == 'id') ? $item->thisID : $newSlug;
            $selected = ($selected_value == $showvalue) ? " SELECTED " : "";

            $maxLabel = 65;
            $slugLength = strlen($newSlug);
            if ($slugLength > $maxLabel) {
                $label = substr($newSlug, 0, 30) . "...." . substr($newSlug, $slugLength - 35, $slugLength);
            } else {
                $label = $newSlug;
            }

            echo ' <option value="' . $showvalue . '" title="' . $newSlug . '"' . $selected . '>' . $label . '</option>';
            if ($item->children > 0) {
                $this->OLD_drawMenu_select($item->child_pages, $value, $selected_value, $newSlug);
            }
        }
    }

    /**
     * Return an array of page data for all hierarchical parent pages of a give page
     * 
     * $use_menu_pages (BOOL) OPTIONAL - if set to TRUE, uses the "menus_pages" table and ONLY returns id and parent_id
     * 
     */
    public function getBreadcrumbs($page_id, $use_menu_pages = false) {
        $parent = $page_id;

        if (!is_numeric($parent)) {
            return array();  // if no page ID is given, return empty array
        }

        $go = true;
        while ($go) {
            $sql = (!$use_menu_pages) ? DB::query(Database::SELECT, "SELECT id,slug,label,parent_id FROM " . _table_pages . " WHERE id = " . $parent) : DB::query(Database::SELECT, "SELECT id,parent_id,link_value FROM menus_pages WHERE id = " . $parent);

            $result = $sql->execute()->as_array();
            if ($result) {
                $breadcrumbs[] = (!$use_menu_pages) ? array('label' => $result[0]['label'], 'slug' => $result[0]['slug'], 'id' => $result[0]['id'], 'parent_id' => $result[0]['parent_id']) : array('page_id' => $result[0]['link_value'], 'id' => $result[0]['id'], 'parent_id' => $result[0]['parent_id']);
                $parent = $result[0]['parent_id'];
                $go = ($parent == 0) ? false : true;
            } else {
                $breadcrumbs[]['ERROR! URI NOT FOUND ON SITE'] = "#__BROKEN_INTERNAL_LINK";
                $go = false; // stop because there is an error
            }
        }
        return array_reverse($breadcrumbs);
    }

    /**
     * Return the top hierarchical level page (Absolute Parent) for a given page_id
     * 
     * $page_id 	INT		(required) ID of page to find parent of
     * $returnArray	BOOL	(optional) if TRUE, returns array of data about the page.
     * 						Flase (default) returns INT of absolute parent's ID
     */
    public function getAbsoluteParent($page_id, $returnArray = false) {
        $breadcrumbs = $this->getBreadcrumbs($page_id);
        return ($returnArray) ? $breadcrumbs[0] : $breadcrumbs[0]['id'];
    }

    /**
     * return URI of given page
     *
     */
    public function buildLink($page_id) {

        $breadcrumb_array = $this->getBreadcrumbs($page_id);
        $full_route = "";
        foreach ($breadcrumb_array as $crumb) {

            if (!isset($crumb['slug'])) {
                return "#_BROKEN_LINK";
            }

            $full_route.= ($crumb['slug'] != "/") ? "/" : ""; //don't add "/" pre-fix if the route in question is the homepage with a pre-set route of "/"
            $full_route.= $crumb['slug'];
        }
        return $full_route;
    }

    // Shortcut alias to buildLink() function
    public static function getPageURL($page_id) {
        $pageObj = new Model_Page;
        return $pageObj->buildLink($page_id);
    }

    /**
     * DRAW HTML Breadcrumbs as inline list
     *
     * page_id	INT	
     * 
     */
    public function drawBreadcrumbs($page_id, $delimiter = " &gt; ") {

        $breadcrumbs = $this->getBreadcrumbs($page_id);
        $crumb_html = '';
        $crumb_levels = count($breadcrumbs);
        $crumb_path = ""; // root of website
        $i = 1;
        foreach ($breadcrumbs as $crumb) {
            if ($i == $crumb_levels) {
                $crumb_html.= $crumb['label'];
            } else {
                $crumb_path.= '/' . $crumb['slug'];
                $crumb_html.= '<a href="' . $crumb_path . '">' . $crumb['label'] . '</a>' . $delimiter;
            }

            $i++;
        }

        return $crumb_html;
    }

}

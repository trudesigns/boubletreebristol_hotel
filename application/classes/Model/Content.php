<?php defined('SYSPATH') or die('No direct script access.');

class Model_Content extends ORM {

    public $user;
    public $user_access_roles;
    protected $_belongs_to = array(
        'page' => array(
            'model' => 'page',
            'foreign_key' => 'page_id',
        ),
        'block' => array(
            'model' => 'contentblock',
            'foreign_key' => 'block_id',
        ),
        'version' => array(
            'model' => 'contentversion',
            'foreign_key' => 'version_id',
        ),
            /* 	    'user' => array(
              'model'		=> 'user',
              'foreign_key' => 'updated_by',
              ),
             */
    );
    public function __construct($id = NULL) {
        $this->user = (object) Session::instance()->get("userdata");
        $this->user_access_roles = Session::instance()->get("accessroles");
        parent::__construct($id);
    }



    /**
     * Return  content revisions for a given page, block and version
     *
     * $params	array	page_id		int
     * 					block_id	int
     * 					version_id	int
     * 					b_operator	str (optional)	how to match the given block id.   eg. "=" or "!=" 
     * 					v_operator	str (optional)	how to match the given version id  eg. "=" or "!="
     * 					limit		int (optional)
     * 					order_by	array('column'=> 'column_name', 'direction'=>'DESC') (optional)   
     */
    public function getRevisions($params) {
        if (!is_numeric($params['page_id']) || !is_numeric($params['block_id']) || (!is_numeric($params['version_id']) && $params['version_id'] != "")) {
            return false;
        }

        //if not specified, order list by revision date
        if (!array_key_exists('order_by', $params)) {
            $params['order_by'] = array('column' => 'revision_date', 'direction' => 'DESC');
        }
        //if not specified, set limit to 10
        if (!array_key_exists('limit', $params)) {
            $params['limit'] = 10;
        }
        //if not specified, set the block operator to "="
        if (!array_key_exists('b_operator', $params)) {
            $params['b_operator'] = '=';
        }
        //if not specified, set the version operator to "="
        if (!array_key_exists('v_operator', $params)) {
            $params['v_operator'] = '=';
        }

        return ORM::factory('Content')
                        ->where('page_id', '=', $params['page_id'])
                        ->where('block_id', $params['b_operator'], $params['block_id'])
                        ->where('version_id', $params['v_operator'], $params['version_id'])
                        ->order_by($params['order_by']['column'], $params['order_by']['direction'])
                        ->limit($params['limit'])
                        ->find_all();
    }

    /**
     * helper function used by action_edit() to un-set the "live" flag on previously published revisions for a given page->block->version 
     *
     * $content OBJECT from the `contents` table
     */
    public function unsetLive($content) {
        $oldLive = ORM::factory('Content')
                ->where('block_id', '=', $content->block_id)
                ->where('page_id', '=', $content->page_id)
                ->where('version_id', '=', $content->version_id)
                ->where('live', '=', 1)
                ->find_all(); // there should only be one, but just to be safe, look for many

        foreach ($oldLive as $old) {
            $old->live = 0;
            $old->save();
        }

        return true;
    }

    public function getContentWCriteria($param)
    {
         if (!array_key_exists('page_id', $params) &&
                !is_numeric($params['page_id']) ||
                !array_key_exists('block_name', $params) &&
                $params['block_name'] !=""
        ) {
            exit("ERROR: getContent() function needs at least a page id and a block name");
        }
        $rev = $this->getContentRevisionWBlockName($params);
        $res = ORM::factory("Content")->where('id','=',$rev)->find();
        return $res;
    }
    
    
    public function getContentRevisionWBlockName($params){
       // print_r($params);
         if (!array_key_exists('page_id', $params) &&
                !is_numeric($params['page_id']) ||
                !array_key_exists('block_name', $params) &&
                $params['block_name'] !=""
        ) {
            exit("ERROR: getContent() function needs at least a page id and a block name");
        }
        //echo "fifififi";exit;
        $block_id = ORM::factory("Contentblock")->where('name','=',$params['block_name'])->limit(1)->find()->id;
        $p = [
            'page_id'=>$params['page_id']
                ,'block_id'=>$block_id
        ];
        
      //print_r($p);
       //exit;
        
        return $this->getContentRevision($p);
        //print_r($rev);
    }

    
    
    public function getContentRevision($params)
    {
         if (!array_key_exists('page_id', $params) ||
                !is_numeric($params['page_id']) ||
                !array_key_exists('block_id', $params) ||
                !is_numeric($params['block_id'])
        ) {
            exit("ERROR: getContent() function needs at least a page id and a block id");
        }
        
        
        if (array_key_exists('version_id', $params) && is_numeric($params['version_id'])) {
            $version_id = $params['version_id']; // match THIS version
            $v_operator = "=";
        } else {
            $version_id = ""; // match ALL versions because no content has a blank version_id
            $v_operator = "!=";
        }
//print_r($params);
        $findContent = $this->getRevisions([
                'page_id' => $params['page_id'],
                'block_id' => $params['block_id'],
                'version_id' => $version_id,
                'v_operator' => $v_operator,
                'limit' => 1
                ]);
       return $findContent;
        
    }
    
    /**
     * Find or Create the most recent revision of content based a given page, block and version.
     * 
     * $params	ARRAY	$page_id
     * 					$block_id
     * 					$version_id (optional ID of version)
     * 					$return_id	BOOL (optional)	if TRUE, function only returns the ID of the given content.
     * 					$create_new	BOOL (optional)	if FALSE, return false if the request content block doesn't already exist. 
     *
     * default behavior with create a new content row in the database if one doesn't exist.
     */
    public function findRevision($params) {
        if (!array_key_exists('page_id', $params) ||
                !is_numeric($params['page_id']) ||
                !array_key_exists('block_id', $params) ||
                !is_numeric($params['block_id'])
        ) {
            exit("ERROR: getContent() function needs at least a page id and a block id");
        }

        

        if (array_key_exists('version_id', $params) && is_numeric($params['version_id'])) {
            $version_id = $params['version_id']; // match THIS version
            $v_operator = "=";
        } else {
            $version_id = ""; // match ALL versions because no content has a blank version_id
            $v_operator = "!=";
        }

        $findContent = $this->getRevisions(array('page_id' => $params['page_id'],
            'block_id' => $params['block_id'],
            'version_id' => $version_id,
            'v_operator' => $v_operator,
            'limit' => 1
                )
        );
        if (count($findContent) == 1) {
            $content = $findContent[0];

            // no matching content revision found.  create a new one 
            // note: this creates the requested content block even if the given page's template does not include the specified block
            // 		 make sure that the "admin/edit?page_id=N&block_id=N" link is only available from qualified pages.
        } else {

            if (array_key_exists('create_new', $params) && $params['create_new'] === false) {
                return false;
            }

            $content = ORM::factory('Content', false);
            $content->page_id = $params['page_id'];
            $content->block_id = $params['block_id'];
            $content->version_id = (is_numeric($version_id)) ? $version_id : 1; // if not provided, assume default version
            $content->revision_date = date("Y-m-d H:i:s");

            $content->updated_by = $this->user->id;
            $content->live = 0;
            $content->save();
        }

        return (array_key_exists('return_id', $params) && $params['return_id']) ? $content->id : $content;
    }

    /**
     * add/update content block from $_POST data
     * 
     * $post		ARRAY	data sent from content editor form
     * $content	MIXED	INT of block id to bed modified OR a content OBJECT
     *
     */
    public function savecontent($post, $content) {
        //echo $this->user;
        //echo "HERE DAMN IT";exit;

        
        if (!isset($post) || !is_array($post)) {
            exit("bad post data");
//	 		return false;
        }

        // if a block ID was sent, create the content object
        if (is_numeric($content)) {
            $content = ORM::factory('Content', $content);
        } elseif (!is_object($content)) {
            exit("content not an object or numeric");
            return false; // otherwise, $content should already be an object. if its not, return false
        }


        //PRINT_R($_POST);
        //echo "USERID: ".$user_id;
        //exit;
        //// if $post data was set in a custom view with multiple fields, those fields should be defined here
        if (isset($post['serialze_fields'])) {
            $fields = explode(",", $post['serialze_fields']);
            $content_array = array();
            foreach ($fields as $field) {
                $content_array[$field] = (isset($post[$field])) ? $post[$field] : "";
            }

            $post['content'] = json_encode($content_array);
        }

        //check if anything changed, fork new draft
        if ($post['content'] != $content->content) {

            // if the given content ID being modified is the only revision and is blank, just use this ID again instead of forking a new version
            if (trim($content->content) == "" && $content->publish_date == "0000-00-00 00:00:00") {
                $otherRevisions = $content->getRevisions(array('page_id' => $content->page_id, 'block_id' => $content->block_id, 'version_id' => $content->version_id));
                $newContent_id = ( count($otherRevisions) == 1) ? $content->id : false;
            } else {
                $newContent_id = false;
            }
            $by = NULL;
            if(isset($post['view_by'])){
                $by = ORM::factory('Group')->where("name","=",$post['view_by'])->limit(1)->find()->id;
            
              
            }

            $newContent = ORM::factory('Content', $newContent_id);
            $newContent->block_id = $content->block_id;
            $newContent->version_id = $content->version_id;
            $newContent->page_id = $content->page_id;
            $newContent->content = $post['content'];
            $newContent->revision_date = date("Y-m-d H:i:s");
            $newContent->updated_by = $this->user->id;
             //$newContent->view_by = $by;
            if ($post['publish'] == 1 && ( in_array("config", $this->user_access_roles) || in_array("publish", $this->user_access_roles))) {
                $content->unsetLive($content);
                $newContent->live = 1;
                $newContent->published_by = $this->user->id;
                $newContent->publish_date = date("Y-m-d H:i:s");
               
            }
            $newContent->save();
            return $newContent->id;
        }
        // nothing has changed, but user is publishing something which has a publish date, probably a previously published revision
        elseif ($post['publish'] == 1 &&
                $content->publish_date != "0000-00-00 00:00:00" &&
                (in_array("config", $this->user_access_roles) || in_array("publish", $this->user_access_roles) )) {
            $content->unsetLive($content);
            $content->live = 1;
            $content->publish_date = date("Y-m-d H:i:s");
            $content->published_by = $this->user->id;
            $content->save();
        }
        // nothing changed, but user is publishing this draft
        elseif ($post['publish'] == 1 &&
                $content->publish_date == "0000-00-00 00:00:00" &&
                (in_array("config", $this->user_access_roles) || in_array("publish", $this->user_access_roles) )) {
            $content->unsetLive($content);
            $content->live = 1;
            $content->publish_date = date("Y-m-d H:i:s");
            $content->published_by = $this->user->id;
            $content->save();
        }
        return $content->id; // return original id 		
    }

    /**
     * get content obj of given row number when ordering by a given column name
     *
     * $page_id			Integer	REQUIRED
     * $column 			String	
     * $order_number	Interger
     *
     * example usage: $column = "revision_date" AND $order_number = 1 returns most recent revised item
     * note: returns FALSE when there are less rows than the number requested (requesting row 10 when therea re only 4 rows, returns false)
     * 
     */
    private function getRevisionByOrderNumber($page_id, $column = "revision_date", $order_number = 1) {
        if (!is_numeric($page_id)) {
            return false;
        }

        $count = ORM::factory('Content')->where('page_id', '=', $page_id)->count_all();
        if ($count < $order_number) {
            return false;
        }

        $limit = $order_number - 1;
        $sql = "SELECT * FROM " . $this->_table_name . " WHERE page_id = " . $page_id . " ORDER BY " . $column . " DESC LIMIT " . $limit . ",1";
        $result = DB::query(Database::SELECT, $sql, 1)->execute();

        //exit("row #".$result[0]);

        return $result[0];
    }

    /**
     * cleanup revisions
     *
     * $page_id	integer	REQUIRED
     * $keep	integer	number of revisions to save
     *
     */
    public function cleanupRevisions($page_id, $keep = 10) {
        if (!is_numeric($page_id)) {
            return false;
        }

        if ($getRevision_N = $this->getRevisionByOrderNumber($page_id, "revision_date", $keep)) {

            $oldest_date = $getRevision_N['revision_date'];
            if (!$oldest_date) {
                return false;
            }

            $sql = "DELETE FROM " . $this->_table_name . " 
					  WHERE revision_date < '" . $oldest_date . "'
					  	AND page_id = " . $page_id . "
						AND publish_date = '0000-00-00 00:00:00' ";
            return $this->_db->query(Database::DELETE, $sql, 1);
        }
    }

    /**
     * cleanup previously published versions, keeping only N number of revisions
     *
     * $page_id	integer	REQUIRED
     * $keep	integer	number of revisions to save
     *
     */
    public function cleanupPublished($page_id, $keep = 10) {
        if (!is_numeric($page_id)) {
            return false;
        }

        if ($getRevision_N = $this->getRevisionByOrderNumber($page_id, "publish_date", $keep)) {
            $oldest_date = $getRevision_N['publish_date'];
            if (!$oldest_date) {
                return false;
            }

            $sql = "DELETE FROM " . $this->_table_name . " 
						  WHERE publish_date < '" . $oldest_date . "'
						  AND page_id = " . $page_id . "
						  AND publish_date != '0000-00-00 00:00:00' ";
            return $this->_db->query(Database::DELETE, $sql, 1);
        }
    }

    // helper function for search()
    private function substring_count($needles, $haystack) {
        $needles = explode(" ", $needles);
        $count = 0;
        foreach ($needles as $needle) {
            $count = $count + substr_count(strtolower($haystack), strtolower($needle));
        }
        return $count;
    }

    /**
     * search for content
     *
     * 	$params	MIXED	STRING = keyword(s) to be found
     * 					ARRAY
     * 						"q" => keyword(s) to be found
     * 						"limit" => total results to return
     * 						"offset" => begin result (in conjunction with "limit")
     * 						"exactMatch" => search for a specific phrase instead of each word individually
     * 						"activeOnly" => if TRUE (default) page must be active and have a `start_date` in the past and future `end_date` 
     * 						"includeDrafts" => if TRUE, search results will include unpublished content (Not fully functional. page is returned, but draft id not included)
     * 						"additionalStopWords" => array of words to ignore (if exactMatch is false)
     *
     * returns an Object of pages, weighted by the blocks that the search terms appear in and then by the number of times the terms appear overall on that page.
     */
    public function search($params) {
        if (!is_array($params)) { // if $params is just a string, assume its the search term and use default values for all the paramaters.
            $params = array("q" => $params);
        }



        $params['q'] = $this->_db->escape(trim($params['q']));
        $q = (array_key_exists("q", $params)) ? $params['q'] : "";


        if ($q == "") {
            return false; // no keywords provided. don't bother searching
        }
        //print_r($q);
        $limit = (array_key_exists("limit", $params)) ? $params['limit'] : 100; // default returns 100 results
        $offset = (array_key_exists("offset", $params)) ? $params['offset'] : 0;
        $exactMatch = (array_key_exists("exactMatch", $params)) ? $params['exactMatch'] : false;
        $live_content = (array_key_exists("includeDrafts", $params) && $params['includeDrafts'] === true ) ? "" : " AND live = 1 ";
        $active = "AND active = 1 
					 AND (start_date < NOW() OR start_date = '0000-00-00 00:00:00')
					 AND (end_date > NOW() OR end_date = '0000-00-00 00:00:00') ";
        if (array_key_exists("activeOnly", $params) && !$params['activeOnly']) {
            $active = "";
        }

        // order is "publish_date" by default unless the user is requesting to "include drafts", then its ordered by revision date.
        $order_by = (array_key_exists("includeDrafts", $params) && $params['includeDrafts'] === true ) ? "revision_date" : "publish_date";

        // array of blocks to search for (by ID) and their weight within the results
        $blocks_weight = array(1 => 20, 2 => 15, 3 => 10);

        if (!$exactMatch) {
            $stopWords = array("i", "a", "about", "an", "are", "as", "at", "be", "by", "for", "from", "how", "in", "is", "it", "of", "on", "or", "that", "the", "this", "to", "was", "what", "when", "where", "who", "will", "with");
            if (array_key_exists("additionalStopWords", $params)) {
                $stopWords = array_merge($stopWords, $params['additionalStopWords']);
            }

            $words = explode(" ", $q);
            // print_r($words);
            $where = "";
            $pagesWhere = ""; // used in secondary query to find pages with these words in the label
            $newsWhere = ""; //  used in another query to find CRUD items
            foreach ($words as $word) {
                //echo "WORD: ".$word;exit;
                $word = str_replace("'", "", $word);
                if (in_array(strtolower($word), $stopWords) || trim($word) == "") {
                    continue;
                } // ignore these words
                $where.= $this->_table_name . ".content LIKE '%$word%' OR ";
                $pagesWhere.= "label LIKE '%$word%' OR ";
                $newsWhere.="headline LIKE '%$word%' OR full_content LIKE '%$word%' OR ";
            }
            $where = rtrim($where, "OR ");
            $pagesWhere = rtrim($pagesWhere, "OR ");
            $newsWhere = rtrim($newsWhere, "OR ");
        } // end $exactMatch == false
        else {
            $q = str_replace("'", "", $q);
            $where = "content LIKE '%$q%' "; // look for the string as it was entered.
            $pagesWhere = "label LIKE '%$q%' "; // used in secondary queary to find pages with these words in the label
            $newsWhere = "headline LIKE '%$q%' OR full_content LIKE '%$q%' ";
        }

        //if no "WHERE CLAUSE" was built, don't bother searching
        if ($where == "") {
            return false;
        }

        // 1)   create an "IF" statement to generate "weight" values for each result based on its block ID
        // eg:  IF (block_id = 1, 20, IF (block_id = 2, 10, IF (block_id = 3, 5, 0))) AS weight
        // 2)   update the "WHERE" clause to only include given blocks
        $if = "";
        $where.=" AND (";
        foreach ($blocks_weight as $block_id => $weight_value) {
            $if.= "IF (block_id = $block_id, $weight_value,";
            $where.= "block_id = $block_id OR ";
        }
        $if.= "0";
        for ($i = 0; $i < count($blocks_weight); $i++) {
            $if.=")";
        }
        $if.=" AS weight ";
        $where = rtrim($where, "OR ");
        $where.= ") ";


        //build SQL statement
        $sql = "SELECT "
                . $this->_table_name . ".*,
				pages.id as page_id,
				pages.label as page_label,
				$if
				FROM " . $this->_table_name . ",pages WHERE
		(" . $where . ")
		" . $live_content . "   
		AND pages.id = (SELECT id FROM pages WHERE id = " . $this->_table_name . ".page_id AND searchable = 1 $active )  
		ORDER BY weight DESC, " . $order_by . " DESC";

        //run query
        $SQLresults = $this->_db->query(Database::SELECT, $sql, FALSE)->as_array();

        $pageObj = new Model_Page;

        foreach ($SQLresults as $SQLresult) {
            if (isset($results[$SQLresult['page_id']])) { // this page has already been added so just update the array
                $results[$SQLresult['page_id']]['block_weight'] = $results[$SQLresult['page_id']]['block_weight'] + $SQLresult['weight'];
                $results[$SQLresult['page_id']]['substr_count'] = $results[$SQLresult['page_id']]['substr_count'] + $this->substring_count($params['q'], $SQLresult['content']);
                if ($results[$SQLresult['page_id']]['page_last_updated'] < $SQLresult['publish_date']) {
                    $results[$SQLresult['page_id']]['page_last_updated'] = $SQLresult['publish_date'];
                }

                continue;
            }

            $page_description = ORM::factory('Content')->where('page_id', '=', $SQLresult['page_id'])->where('block_id', '=', 2)->where('live', '=', 1)->find();
            $page_description_content = (isset($page_description->content)) ? $page_description->content : '';

            if (trim($page_description_content) == "") { // if there is no page description, use the main content area
                $page_description = ORM::factory('Content')->where('page_id', '=', $SQLresult['page_id'])->where('block_id', '=', 3)->where('live', '=', 1)->find();
                $page_description_content = substr(strip_tags($page_description->content), 0, 250);
            }

            $results[$SQLresult['page_id']]['block_weight'] = $SQLresult['weight'];
            $results[$SQLresult['page_id']]['substr_count'] = $this->substring_count($params['q'], $SQLresult['content']);
            $results[$SQLresult['page_id']]['page_label'] = $SQLresult['page_label'];
            $results[$SQLresult['page_id']]['page_id'] = $SQLresult['page_id'];
            $results[$SQLresult['page_id']]['page_description'] = $page_description_content;
            $results[$SQLresult['page_id']]['path'] = $pageObj->buildLink($SQLresult['page_id']);
            $results[$SQLresult['page_id']]['page_last_updated'] = $SQLresult['publish_date'];
        }//end link through each result

        /* now that we've gone through all the contents lets do some additional searching.
         */

        // search for page labels that have the keyword(s)
        $pagesSQL = "SELECT * FROM pages WHERE (" . $pagesWhere . ")  AND searchable = 1 $active";
        $pages = $this->_db->query(Database::SELECT, $pagesSQL, FALSE)->as_array();
        foreach ($pages as $page) {
            if (isset($results[$page['id']])) { // this page has already been added so just update the array
                $results[$page['id']]['block_weight'] = $results[$page['id']]['block_weight'] + 15;
                $results[$page['id']]['substr_count'] = $results[$page['id']]['substr_count'] + $this->substring_count($params['q'], $page['label']);
                continue;
            }

            $page_description = ORM::factory('Content')->where('page_id', '=', $page['id'])->where('block_id', '=', 2)->where('live', '=', 1)->find();
            $page_description_content = (isset($page_description->content)) ? $page_description->content : '';

            if (trim($page_description_content) == "") { // if there is no page description, use the main content area
                $page_description = ORM::factory('Content')->where('page_id', '=', $page['id'])->where('block_id', '=', 3)->where('live', '=', 1)->find();
                $page_description_content = substr(strip_tags($page_description->content), 0, 250);
            }

            $results[$page['id']]['block_weight'] = 15;
            $results[$page['id']]['substr_count'] = $this->substring_count($params['q'], $page['label']);
            $results[$page['id']]['page_label'] = $page['label'];
            $results[$page['id']]['page_id'] = $page['id'];
            $results[$page['id']]['page_description'] = $page_description_content;
            $results[$page['id']]['path'] = $pageObj->buildLink($page['id']);
            $results[$page['id']]['page_last_updated'] = $page_description->publish_date;
        }

        //search a custom CRUD table
        /*
          $newsSQL = "SELECT * FROM news WHERE (". $newsWhere .")";
          $news = $this->_db->query(Database::SELECT, $newsSQL, FALSE)->as_array();
          foreach($news as $news_item)
          {
          $results[34 .$news_item['id']]['block_weight'] = ($this->substring_count($params['q'],$news_item['headline'])) ? 15 : 5;
          $results[34 .$news_item['id']]['substr_count'] = $this->substring_count($params['q'],$news_item['full_content']);
          $results[34 .$news_item['id']]['page_label'] = $news_item['headline'];
          $results[34 .$news_item['id']]['page_id'] = 34;
          $results[34 .$news_item['id']]['page_description'] = $news_item['teaser'];
          $results[34 .$news_item['id']]['path'] = '/about-us/news/'.$news_item['id'];
          $results[34 .$news_item['id']]['page_last_updated'] = '';
          }
         */

        // if after all that, there are still no results, just give up.
        if (!isset($results)) {
            return false;
        }

        // order the array of results by block weight and string occurances
        function cmp($a, $b) {
            if ($a == $b) {
                return 0;
            }
            return ($a > $b) ? -1 : 1;
        }

        usort($results, "cmp");

        $return['results'] = array_slice($results, $offset, $limit);
        $return['total_pages'] = count($results);
        $return['limit'] = $limit;
        $return['offset'] = $offset;
        $return['sql'] = $sql;

        return (object) $return;
    }

}

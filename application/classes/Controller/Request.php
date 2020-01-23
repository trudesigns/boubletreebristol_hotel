<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * AJAX controller
 *
 */
class Controller_Request extends Controller_Setup {

    public $auth_required = FALSE;
    public $find_dynamic_page_data = TRUE;
    public $request;
    public $response;
    protected $valid_req = false;
    
    function __construct(Request $request, Response $response) {

        //echo "AFA: ".var_dump($request->param());exit;
        //echo "IS AJAX: ".var_dump(Request::initial()->is_ajax())." BLAH";
        // echo "<br /> TOKEN: ".$_GET['token'];exit;
        // make sure this is an ajax call and not a direct browser view (unless some sort of token consideration is included)
        
        
        $token = null;
            
        $p = $request->post();
        
     // var_dump($p);exit;
        if(count($p) >0){
            if(isset($p['ybr_token'])){
                $token =$p['ybr_token'];
                $type = "ybr_token";
            } elseif(isset($p['ybr_loggedin'])){
                $token =$p['ybr_loggedin'];
                $type = "ybr_loggedin";
            }
            if($token ==""){$token = null;}
        }
        //var_dump($token);
        //print_r();
        if (!Request::initial()->is_ajax() || is_null($token) !== false) {
            die("no access");
        }



        $this->request = $request;
        $this->response = $response;
        $this->valid_req = ybr::validateToken($token,$type);
        $this->auto_render = false; // don't render the output inside the site's template
        //if(!Request::$is_ajax OR $this->request !== Request::instance() && !isset($_GET['token'])){ exit("no AJAX access"); } 
//		
//		header("Expires: Sat, 07 Apr 1979 05:00:00 GMT");
//		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
//		header("Cache-Control: no-store, no-cache, must-revalidate");
//		header("Cache-Control: post-check=0, pre-check=0", false);
//		header("Pragma: no-cache");
    }

    public function action_menus() {
       
      //  print_r($post);
        
        echo json_encode(ybr::getMenus($this->request->post()));
    }

    /**
     * Return the contents of a given view
     *
     */
    public function action_loadView() {
        if (isset($_GET['view'])) {
            echo View::factory(htmlspecialchars(urldecode($_GET['view'])));
        }
    }

    /**
     * check if a given username is available
     *
     * returns TRUE/FALSE  
     *
     */
    public function action_usernameAvailable() {
        if (isset($_GET['username'])) {

            $user = Auth::instance()->get_user(); // if the requested username is this user, return true (because the name is available to them)
            if ($user && $_GET['username'] == $user->username) {
                echo json_encode(false);
                exit();
            }

            $find = ORM::factory('User')->where('username', '=', trim($_GET['username']))->find();
            echo ($find->loaded()) ? json_encode(false) : json_encode(true);
        }
    }

    /**
     * check if given email address is available
     */
    public function action_emailAvailable() {
         $post = $this->request->post();
         $userid = null;
         if(isset($post['userid'])){
             $userid = $post['userid'];
         }
        if (isset($post['email'])) {
            $find = ORM::factory('User')
                    ->where('email', '=', trim($post['email']))
                    ->where("status",">",0)//ONLY USERS THAT ARE INACTIVE OR ACTIVE 
                    ->where("id","<>",$userid)//and not the user email
                    ->find();
            if($find->loaded()){
                $output = false;
            } else {
                $output = true;
            }
            echo json_encode($output);
        }
    }

    /**
     * reset users password from a provided e-mail address (and send them an email with the log in credentials)
     *
     */
    public function action_resetPassword() {
        $post = $this->request->post();
      //print_r($post);exit;
        if (!isset($post['email'])) {
          //  exit("missing email address");
            $status = false;
        } else {
           
         //   echo "poat";
            $user = ORM::factory('User')->where('email', '=', $post['email'])->find();
//            echo "<prE>";
//var_dump($user);exit;
            if (!$user->loaded()) {
               // exit("Invalid E-mail Address");
                $status = false;
            } else {
                //echo "Djdjdhjd";
                //print_r($_SESSION);exit;
                $userObj = new Model_User;
                if ($userObj->reset_password($user->id)) {
                    $status = true;
                } else {
                    $status = false;
                }
            }
        }
        echo json_encode($status);
    }

    /**
     * "extend" a users session from timing out by just pinging the server
     *  if they're still logged in, their session will continue
     *  if they've been logged out, return false
     */
    public function action_extendSession() {
       if ( Auth::instance()->get_user() ) {
           $out = true;
       } else {
            $out = false;
       }
       echo json_encode($out);
    }

    /**
     * Read a session value from outside kohana by mimicking logged in user's session.
     *
     * 	requires:
     * 	$sesson_id		string	valid session id of logged in user
     * 	$token			string	salted and hashed string, passed for security
     * 	$session_key	string	array key of value to be returned
     *
     */
    public function action_readSession() {
        if (!isset($_GET['sid']) || !isset($_GET['token'])) {
            exit("invalid request");
        }
        if ($_GET['token'] != substr(md5($_GET['sid'] . "!SALTED_!"), 0, 8)) {
            exit("invalid token");
        }
        session_write_close();  // close existing session 
        session_id($_GET['sid']); // "hijack" users's session
        session_start();
        $key = (isset($_GET['session_key'])) ? $_GET['session_key'] : '';
        echo (isset($_SESSION[$key]) ) ? $_SESSION[$key] : "false";
    }

    /**
     * BEGIN CUSTOM AJAX for APPS on this site
     *
     */

    /**
     * lookup zipcode
     * check local cache first
     * if not there, use web service
     */
    public function action_zipcodeLookup() {
        if (!isset($_GET['z']) || !is_numeric($_GET['z'])) {
            exit("invalid zipcode");
        }

        $cache = ORM::factory('Zipcodecache')->where('zipcode', '=', $_GET['z'])->find();
        if ($cache->loaded()) {
            $cache->last_lookup = date("Y-m-d H:i:s");
            $cache->lookups = $cache->lookups + 1;
            $cache->save();

            $data['zipcode'] = $_GET['z'];
            $data['city'] = $cache->city;
            $data['state'] = $cache->state;
            $data['state_code'] = $cache->state_code;
            $data['county'] = $cache->county;
            $data['country_code'] = $cache->country_code;
            $data['lat'] = $cache->lat;
            $data['lng'] = $cache->lng;
            $data['source'] = "cache";
        } else {
            $url = "http://ws.geonames.org/postalCodeSearchJSON?postalcode=" . $_GET['z'] . "&country=US&maxRows=1&username=thepitagroup";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $lookup = curl_exec($ch);
            curl_close($ch);

            $lookup = json_decode($lookup);

            if (!isset($lookup->postalCodes) || count($lookup->postalCodes) == 0) {
                $data = false;
            } else {
                //prep Array to send
                $row = $lookup->postalCodes[0];
                $data['zipcode'] = $_GET['z'];
                $data['city'] = $row->placeName;
                $data['state'] = $row->adminName1;
                $data['state_code'] = $row->adminCode1;
                $data['county'] = $row->adminName2;
                $data['country_code'] = $row->countryCode;
                $data['lat'] = $row->lat;
                $data['lng'] = $row->lng;
                $data['source'] = "geonames.org";

                // save for future use
                $cache = ORM::factory('Zipcodecache', false);
                $cache->zipcode = $_GET['z'];
                $cache->city = $data['city'];
                $cache->state = $data['state'];
                $cache->state_code = $data['state_code'];
                $cache->county = $data['county'];
                $cache->country_code = $data['country_code'];
                $cache->lat = $data['lat'];
                $cache->lng = $data['lng'];
                $cache->first_lookup = date("Y-m-d H:i:s");
                $cache->last_lookup = date("Y-m-d H:i:s");
                $cache->lookups = 1;
                $cache->save();
            }
        }
        echo json_encode($data);
    }

    public function action_newsfeed() {
        if (!isset($_GET['grouping']) || !isset($_GET['limit']) || !isset($_GET['offset']) || !is_numeric($_GET['limit']) || !is_numeric($_GET['offset'])) {
            exit("ERROR: Invalid Request");
        }

        $limit = $_GET['limit'] + 1;

        switch ($_GET['grouping']) {

            case (is_numeric($_GET['grouping']) ) : // only show given year
                $grouping = $_GET['grouping'];
                $news = ORM::factory('News')
                        ->where('dateline', '>', $_GET['grouping'] . '-01-01')
                        ->where('dateline', '<=', $_GET['grouping'] . '-12-31')
                        ->order_by('dateline', 'asc')
                        ->limit($limit)
                        ->offset($_GET['offset'])
                        ->find_all();
                break;

            default:
                $news_categories = array("General RRI News", "Park News", "Event News", "Fundraising", "Rowing");
                if (in_array($_GET['grouping'], $news_categories)) {
                    $grouping = $_GET['grouping'];
                    $news = ORM::factory('News')
                            ->where('category', '=', $_GET['grouping'])
                            ->order_by('dateline', 'desc')
                            ->limit($limit)
                            ->offset($_GET['offset'])
                            ->find_all();
                } else {

                    $news = ORM::factory('News')
                            ->order_by('dateline', 'desc')
                            ->limit($limit)
                            ->offset($_GET['offset'])
                            ->find_all();
                }
        }

        $i = 0;
        foreach ($news as $article) {
            if ($i >= $_GET['limit']) {
                break;
            }
            $i++;

            $grouping = ($_GET['grouping'] == "Date") ? date("F, Y", strtotime($article->dateline)) : $grouping;

            $slug = preg_replace("/\-/", " ", $article->headline);
            $slug = preg_replace("/[^A-Za-z0-9\s]/", "", $slug);
            $slug = preg_replace("/ /", "-", $slug);
            $slug = substr($slug, 0, 50);

            $send_news['articles'][] = array("grouping_header" => $grouping,
                "headline" => $article->headline,
                "teaser" => $article->teaser,
                "dateline" => (date("Y", strtotime($article->dateline)) != date('Y') ) ? date("F j, Y", strtotime($article->dateline)) : date("l, F j", strtotime($article->dateline)),
                "category" => $article->category,
                "url" => ($article->full_content != "") ? "/about-us/news/" . $article->id . '-' . $slug : ''
            );
        }

        $send_news['grouping'] = $_GET['grouping'];
        $send_news['more_to_load'] = ( count($news) == $limit ) ? true : false;

        echo json_encode($send_news);
    }

    /**
     * forward user to the CMS to edit the current page
     *
     */
    public function action_kcode() {

        $uri_parts = explode($_SERVER['HTTP_HOST'] . "/", $_SERVER['HTTP_REFERER']);
        $uri = $uri_parts[1];

        $pageObj = new Model_Page;
        $page = $pageObj->getPagefromURL($uri);

        echo "document.location = '/admin/edit/?block_id=3&version_id=1&page_id=" . $page->id . "';";
        exit();
    }

    /**
     * EVENTS FEED
     */
    public function action_eventsfeed() {
        if (!isset($_GET['grouping']) || !isset($_GET['limit']) || !isset($_GET['offset']) || !is_numeric($_GET['limit']) || !is_numeric($_GET['offset'])) {
            exit("ERROR: Invalid Request");
        }

        // this list is also hard coded into the CMS tool to generate events in the feed.
        // any changes made here need to be made there as well
        $event_categories = array("Performances", "Concerts", "Festivals", "Sporting Events", "Fundraising Events", "Riverfront Dragon Boat and Asian Festival", "Fishing", "Charity Walks", "Running Events");

        $limit = $_GET['limit'] + 1;

        switch ($_GET['grouping']) {

            case ("Category") :  //group by category


                break;

            case ("Venue") :
                $events = ORM::factory('Event')
                        ->where('end_date', '>=', date("Y-m-d"))
                        ->order_by('venue', 'desc')
                        ->order_by('start_date', 'asc')
                        ->limit($limit)
                        ->offset($_GET['offset'])
                        ->find_all();
                break;

            default:

                if (in_array($_GET['grouping'], $event_categories)) {

                    $events = ORM::factory('Event')
                            ->where('end_date', '>=', date("Y-m-d"))
                            ->where('category', 'LIKE', '%' . $_GET['grouping'] . '%')
                            ->order_by('start_date', 'asc')
                            ->limit($limit)
                            ->offset($_GET['offset'])
                            ->find_all();
                } else { // by default, sort by date

                    $events = ORM::factory('Event')
                            ->where('end_date', '>=', date("Y-m-d"))
                            ->order_by('start_date', 'asc')
                            ->limit($limit)
                            ->offset($_GET['offset'])
                            ->find_all();
                }
        }

        $i = 0;
        foreach ($events as $event) {
            if ($i >= $_GET['limit']) {
                break;
            }
            $i++;

            switch ($_GET['grouping']) {
                case('Category'):
                    $grouping = $event->category;
                    break;
                case('Venue'):
                    $grouping = $event->venue;
                    break;
                default:

                    if (in_array($_GET['grouping'], $event_categories)) {
                        $grouping = $_GET['grouping'];
                    } else {
                        $grouping = date("F, Y", strtotime($event->start_date));
                    }
            }

            if (date("Y-m-d", strtotime($event->start_date)) == date("Y-m-d", strtotime($event->end_date))) {
                $start_date = ( date("Y", strtotime($event->start_date)) != date("Y") ) ? date("F j, Y", strtotime($event->start_date)) : date("l, F j", strtotime($event->start_date));
            } else {
                if (date("Y", strtotime($event->start_date)) == date("Y") && date("Y", strtotime($event->end_date))) {
                    $start_date = date("l", strtotime($event->start_date)) . " - " . date("l", strtotime($event->end_date)) . ", ";
                    $start_date.= date("F j", strtotime($event->start_date)) . " - " . date("F j", strtotime($event->end_date));
                } else {
                    $start_date = date("F j, Y", strtotime($event->start_date)) . " - " . date("F j, Y", strtotime($event->end_date));
                }
            }


            $send_events['events'][] = array("grouping_header" => $grouping,
                "name" => $event->name,
                "description" => $event->description,
                "start_date" => $start_date,
                "url" => "/events" . $event->url
            );
        }

        $send_events['grouping'] = $_GET['grouping'];
        $send_events['more_to_load'] = ( count($events) == $limit ) ? true : false;

        echo json_encode($send_events);
    }

}

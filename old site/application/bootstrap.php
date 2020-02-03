<?php defined('SYSPATH') or die('No direct script access.');



// -- Environment setup --------------------------------------------------------
// Load the core Kohana class
require SYSPATH . 'classes/Kohana/Core' . EXT;

if (is_file(APPPATH . 'classes/Kohana' . EXT)) {
    // Application extends the core
    require APPPATH . 'classes/Kohana' . EXT;
} else {
    // Load empty core extension
    require SYSPATH . 'classes/Kohana' . EXT;
}

/**
 * Set the default time zone.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/timezones
 */
date_default_timezone_set('America/New_York');

/**
 * Set the default locale.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/function.setlocale
 */
setlocale(LC_ALL, 'en_US.utf-8');

/**
 * Enable the Kohana auto-loader.
 *
 * @link http://kohanaframework.org/guide/using.autoloading
 * @link http://www.php.net/manual/function.spl-autoload-register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Optionally, you can enable a compatibility auto-loader for use with
 * older modules that have not been updated for PSR-0.
 *
 * It is recommended to not enable this unless absolutely necessary.
 */
//spl_autoload_register(array('Kohana', 'auto_load_lowercase'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @link http://www.php.net/manual/function.spl-autoload-call
 * @link http://www.php.net/manual/var.configuration#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

/**
 * Set the mb_substitute_character to "none"
 *
 * @link http://www.php.net/manual/function.mb-substitute-character.php
 */
mb_substitute_character('none');

// -- Configuration and initialization -----------------------------------------

/**
 * Set the default language
 */
I18n::lang('en-us');


if (isset($_SERVER['SERVER_PROTOCOL'])) {
    // Replace the default protocol.
    HTTP::$protocol = $_SERVER['SERVER_PROTOCOL'];
}

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */

if (isset($_SERVER['KOHANA_ENV'])) {
    Kohana::$environment = constant('Kohana::' . strtoupper($_SERVER['KOHANA_ENV']));
}

/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   sedefine('content_versioning',false);t the internal cache directory                   APPPATH/cache
 * - integer  cache_life  lifetime, in seconds, of items cached              60
 * - boolean  errors      enable or disable errorurl_locale handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 * - boolean  expose      set the X-Powered-By header                        FALSE
 */
 Kohana::init(array(
    'base_url' => '/'
    ,'index_file' => FALSE
    ,'errors'=>  Kohana::$environment !== Kohana::PRODUCTION//turns the error only on the non PRODUCTION env
));
// Kohana::init(array(
//     'base_url' => '/'
//     , 'index_file' => FALSE
//      ,'errors'=>  Kohana::$environment !== Kohana::PRODUCTION//turns the error only on the non PRODUCTION env
// ));

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_File(APPPATH . 'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);




// define some table names
define("_table_pages", "pages");
define("_table_menus_pages", "menus_pages");
/**
 * Additional Site Considerations
 *
 */
define('content_versioning', false); // display different versions of content based on predfined user actions (role, session or URL based)
define('url_locale', false);    // modify URL with locale prefix for content versioning. ex: "/about-us" & "/es/about-us" both return the same page
define('content_wrapping', true);  // wrap content in additional HTML to support in-line CMS editing 
define('ldap_auth',false);//true will allow for hybrid commnucation and if not successful authentaction on native db server it then queries the ldap 



/**
 * Remember me life 
 *
 * length of cookie when "remember me" box is checked at sign in
 * 60*60*24*7 = 604800 = 1 week
 */
define('REMEMBER_ME_LIFE', 604800);

/**
 * PATH_BASE
 */
define('PATH_BASE', URL::base());


/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules(array(
    'auth' => MODPATH . 'auth', // Basic authentication
    'cache'      => MODPATH.'cache',      // Caching with multiple backends
    // 'codebench'  => MODPATH.'codebench',  // Benchmarking tool
    'database' => MODPATH . 'database', // Database access
    // 'image'      => MODPATH.'image',      // Image manipulation
    // 'minion'     => MODPATH.'minion',     // CLI Tasks
    'orm' => MODPATH . 'orm'//,        // Object Relationship Mapping
        // 'unittest'   => MODPATH.'unittest',   // Unit testing
        // 'userguide'  => MODPATH.'userguide',  // User guide and API documentation
));


//DYNAMIC DB CONFIG
Kohana::$config->attach(new Config_File('config/'.$_SERVER['KOHANA_ENV']));//looks into the htaccess to find out the name

/***CACHE */
Cache::$default = 'default';


/* * ROUTES* */

/**
 * ADMIN CONTROLLERS
 *
 */



//use the "custom" admin controller when specific custom actions are defined
//this should be where all the customized, site-specific CMS Tools live
//Route::set('admin_custom', 'admin/<action>(/<params>)', array('action' => 'SomeCustomApplication'))
//        ->defaults(array(
//            'directory' => 'admin',
//            'controller' => 'custom',
//            'action' => 'index',
//        ));

//use a given admin controller when specified
// ***** MOD - Commented all routes
Route::set('admin_controllers', '<directory>/<controller>(/<action>(/<id>))', array('directory' => 'admin', 'controller' => 'menubuilder|pagemanager|request|redirects|tasks|news|categories'))
->defaults(array(
    'directory' => 'admin',
    'action' => 'index',
));

//use the "custom" or "default" admin controllers for all other "admin" pages
Route::set('admin', 'admin(/<action>(/<id>))')
->defaults(array(
    'directory' => 'admin',
    'controller' => 'custom', // the "custom" controller extends the "default" controller where all the CMS logic lives
    'action' => 'index',
));

/**
 * USER MANAGEMENT CONTROLLER
 *
 */
Route::set('user', 'user(/<action>(/<id>))')
->defaults(array(
    'controller' => 'user',
    'action' => 'index',
));

/**
 * REQUEST "AJAX" CONTROLLER
 *
 */
Route::set('ajax', 'request(/<action>(/<params>))')
->defaults(array(
    'controller' => 'request',
    'action' => 'index',
));

/**
 * display captach and update session with captcha value
 *
 */
Route::set('captcha', 'captcha')
->defaults(array(
    'controller' => 'captcha',
    'action' => 'index',
));

/**
 * Output files based on session roles
 *
 */
Route::set('readfile', 'readfile(/<path>)', array('path' => '.+'))
->defaults(array(
    'controller' => 'readfile',
    'action' => 'index',
));

/**
 * ALL OTHER PAGES GO THROUGH THE "Default" CONTROLLER
 *
 */

// ***** MOD
// Route::set('testing', 'Custom(/<action>)')
// ->defaults(array(
//     'controller' => 'default',
//     'action' => 'index',
// ));
// print_r($_SERVER["DOCUMENT_ROOT"]);

Route::set('normal', '(/<page>(/<subpages>))', array('page' => '.+', 'subpages' => '.+'))
->defaults(array(
    'controller' => 'default',
    'action' => 'index'
));

Cookie::$salt = "Les clefs de la reussite sont joie et amour!..";


$session = Session::instance();
$cookie = null;
if(isset($_COOKIE['ybr_token']) && $_COOKIE['ybr_token']){
    $cookie = $_COOKIE['ybr_token'];
    Session::instance()->set("ybr_token",$cookie);
}
//echo "COOKIE: ".var_dump($cookie);
if( is_null($cookie)) {
    $token = ybr::ybr_token();
    $session->set("ybr_token",$token);
    if(!isset($_COOKIE['ybr_token'])){
        setcookie('ybr_token',$token,0,"/");
    }
    //Cookie::set("ybr_token", $token);
    //echo "TOEK: ".$token;
    $ua = "No User Agent";
    if(isset($_SERVER['HTTP_USER_AGENT'])){
        $ua = json_encode($_SERVER['HTTP_USER_AGENT']);
    }
    
    $retention = Kohana::$config->load('siteconfig.session_logs');
    //echo "CONF:" .$retention;
    $d = date("Y-m-d",  strtotime($retention));
    DB::query(Database::DELETE, "DELETE FROM `session_token` WHERE `timestamp` <= '".$d."' ")->execute();
    DB::query(Database::INSERT, "INSERT INTO `session_token` SET `IP` ='".$_SERVER['REMOTE_ADDR']."', `UA`='".$ua."',`token` ='".$token."', `user_id` = '0' ")->execute();
}
//$cookie2 = Cookie::get('ybr_token');
//echo "<br />COOKIE2: ".var_dump($cookie2);



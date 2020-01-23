<?php defined('SYSPATH') or die('No direct access allowed.');

//return array(
//     'default' => array(                    // Driver group
//         'driver'         => 'memcache',         // using APC driver
//         'servers'        => array(             // Available server definitions
//                    // First memcache server server
//                    array(
//                         'host'             => 'localhost'
//                    )
//             )
//      ),
//);
return array(
     'file'   => array(                          // File driver group
             'driver'         => 'file',         // using File driver
             'cache_dir'     => APPPATH.'cache/app/', // Cache location
      ),
);
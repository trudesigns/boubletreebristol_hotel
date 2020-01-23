<?php defined('SYSPATH') or die('No direct access allowed.');

//$config =  parse_ini_file('/var/www/config/yellow.php');
//print_r($config);
return array
    (
    'default' => array
        (
        'type' => 'MySQL',
        'connection' => array(
            /**
             * The following options are available for MySQL:
             *
             * string   hostname     server hostname, or socket
             * string   database     database name
             * string   username     database username
             * string   password     database password
             * boolean  persistent   use persistent connections?
             *
             * Ports and sockets may be appended to the hostname.
             */
            'hostname' => "localhost",
            'database' => "dbname", //pita_sandbox 
            'username' => "username", //pita_sandbox
            'password' => "pssword", //p!taS@ndb0x
        ),
        'table_prefix' => '',
        'charset' => 'utf8',
        'caching' => FALSE,
        'profiling' => TRUE,
    ),
    'alternate' => array(
        'type' => 'pdo',
        'connection' => array(
            /**
             * The following options are available for PDO:
             *
             * string   dsn         Data Source Name
             * string   username    database username
             * string   password    database password
             * boolean  persistent  use persistent connections?
             */
            'dsn' => 'mysql:host=localhost;dbname=kohana',
            'username' => 'root',
            'password' => 'r00tdb',
            'persistent' => FALSE,
        ),
        /**
         * The following extra options are available for PDO:
         *
         * string   identifier  set the escaping identifier
         */
        'table_prefix' => '',
        'charset' => 'utf8',
        'caching' => FALSE,
        'profiling' => TRUE,
    ),
);

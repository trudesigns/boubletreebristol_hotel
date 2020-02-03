<?php 
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
            'database' => "dt_bristol", //pita_sandbox 
            'username' => "dt_bristol", //pita_sandbox
            'password' => "3Ldv:9#*-", //p!taS@ndb0x
        ),
        'table_prefix' => '',
        'charset' => 'utf8',
        'caching' => FALSE,
        'profiling' => TRUE,
    )
    );
    
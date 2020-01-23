<?php
return array(
    'default' => array(
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
            'database' => "doubletreebristol_com", //pita_sandbox
            'username' => "doubletreebristol_admin", //pita_sandbox
            'password' => "lXU.jZyrVWhO@qW+cUrq2N6QTzF5N%Ut", //p!taS@ndb0x
        ),
        'table_prefix' => '',
        'charset' => 'utf8',
        'caching' => false,
        'profiling' => true,
    )
    );

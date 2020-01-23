<?php

function check_subdomain($subdomain)
{
    $subdomainarr = explode('.', $subdomain);
    if (count($subdomainarr) > 2) {
        return true;
    } else {
        return false;
    }
}


$subdomain = explode('.', $_SERVER['SERVER_NAME'])[0];

if (!check_subdomain($_SERVER['HTTP_HOST']) || "www" == $subdomain) {
    $_SERVER['KOHANA_ENV'] = 'PRODUCTION';
} else {
    $_SERVER['KOHANA_ENV'] = strtoupper($subdomain);
}

/**
 * Get Apache Version
 */
if (!function_exists('apache_get_version')) {
    function apache_get_version()
    {
        if (!isset($_SERVER['SERVER_SOFTWARE']) || strlen($_SERVER['SERVER_SOFTWARE']) == 0) {
            return false;
        }
        return $_SERVER["SERVER_SOFTWARE"];
    }
}

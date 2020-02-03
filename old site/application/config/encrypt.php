<?php

defined('SYSPATH') or die('No direct script access.');

return array(
    'default' => array(
        /**
         * The following options must be set:
         *
         * string   key     secret passphrase
         * integer  mode    encryption mode, one of MCRYPT_MODE_*
         * integer  cipher  encryption cipher, one of the Mcrpyt cipher constants
         */
        'key' => '6D61858BE65B3732924BFB44D9A1059D1573DEF665BBA1192A4B4AF26964070D',
        'cipher' => MCRYPT_RIJNDAEL_128,
        'mode' => MCRYPT_MODE_NOFB,
    ),
    'blowfish' => array(
        'key' => 'kjgsefhfgakjgJKGKkIG767faSDQO0POAhAiAJKSnAGRQN90234hGPAKABSGmhbvzxcPh',
        'cipher' => MCRYPT_BLOWFISH,
        'mode' => MCRYPT_MODE_ECB,
    ),
    'tripledes' => array(
        'key' => '6yh47jka9leo02iejdnSnGK7KAJ4faQOA9AJS7HKgsh1pLoUJg1FSGqJJkLOWP876',
        'cipher' => MCRYPT_3DES,
        'mode' => MCRYPT_MODE_CBC,
    ),
);

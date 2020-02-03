<?php 
return [
    'google'=>[
        'analytics'=>[
            'id'=>null
        ]
    ],
    'contact'=>[
        'email'=>["markb@thepitagroup.com"]
    ],
    'rfp'=>[
        'email'=>['markb@thepitagroup.com']
    ],
    'news'=>[
        "total_items_per_page"=>10
    ],
    'auth_scheme'=>0,
    'session_logs'=>'-1 days',
    'ckeditor_cachebuster'=> time() // could a string 'abcd' or time() or ""
    ,'mailgun'=>[
        "type"=>"api" //choice are api or smtp. the smtp relies on the setting at the server level.
        ,"api_domain" => "mg.thepitagroup.com"
        ,"api_baseurl" => "https://api.mailgun.net/v3/"
        ,"api_key" => "key-007085800872e8cc8e8c5359dbc1aaa4"
    ]
];
   
    
<?php
$list= ybr::PageList(ybr::getRootPages());

// if "xml-sitemap" page is requested, the custom controller echo's this view and sets $xml to TRUE
if( isset($xml) && $xml){

        echo ybr::PageListXML($list);
        exit;
} else {
        echo ybr::PageListFormated($list);
}
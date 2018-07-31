<?php
function jsonToAttribute($arr){
    if(!is_array($arr)){
        die('must be an array ! (jsonToAttribute)');
    }
    return htmlspecialchars(json_encode($arr), ENT_QUOTES, 'UTF-8');
}

function putHashTagInURL($url){
    // echo site_url();
    $urlr = wp_make_link_relative($url);
    if (strpos($url,'/./')){
        $urlr = preg_replace('/\/.\//','/#/',$urlr);
    }else{
        // suppr site url
        $siteurl = site_url();
        $urlr = str_replace ( $siteurl , '#', $url);
        // $urlr = '#'.$urlr;
    }
    return $urlr;
}

?>
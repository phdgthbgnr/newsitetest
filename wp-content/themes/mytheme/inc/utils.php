<?php
function jsonToAttribute($arr){
    if(!is_array($arr)){
        die('must be an array ! (jsonToAttribute)');
    }
    return htmlspecialchars(json_encode($arr), ENT_QUOTES, 'UTF-8');
}

?>
<?php

function vres($path){
    echo $path . (strpos($path,'?') === false ? '?' : '&') . filemtime(WEBROOT.$path);
}

function _g($p,$k,$d=null) {
    //slightly faster to not convert to array first
    //if (!is_array($k)) $k = array($k);
    if (!is_array($k)) $d = isset($p[$k]) ? $p[$k] : $d;
    else {
        for ($i=0,$c=count($k);$i<$c;$i++) {
            if (isset($p[$k[$i]])) {
                $d = $p[$k[$i]];
                break;
            }
        }
    }
    return $d;
}
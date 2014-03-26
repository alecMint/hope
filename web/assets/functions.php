<?
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );

function vres($path){
    echo $path . (strpos($path,'?') === false ? '?' : '&') . filemtime($_SERVER['DOCUMENT_ROOT'].$path);
}
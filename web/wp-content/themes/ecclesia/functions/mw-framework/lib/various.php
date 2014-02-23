<?php if (!defined('MW_THEME')) die('No direct script access allowed');

/**
 * Get WordPress pages as an array.
 * The returned array is intended for use in a drop-down list.
 *
 * @access public
 * @since 1.0.0
 *
 * @param array $default
 * @return array
 */
function mw_get_pages_array ( $default = NULL ) {
    $pages = is_array($default) ? $default : array();
    foreach (get_pages() as $page) {
        $pages[$page->ID] = $page->post_title;
    }
    return $pages;
}

/**
 * Recursive stripslashes - from single value or array.
 *
 * @access public
 * @since 1.0.0
 *
 * @param mixed $input
 * @return mixed
 */
function rstripslashes( $input ) {
    if ( is_array( $input ) ) {
        foreach ( $input as &$val ) {
            if ( is_array( $val ) ) {
                $val = rstripslashes( $val );
            } else {
                $val = stripslashes( $val );
            }
        }
    } else {
        $input = stripslashes( $input );
    }
    return $input;
}


/**
 * Generate an excerpt from text
 *
 * @access public
 * @since 1.0.0
 *
 * @param string $text
 * @param int $length
 * @param string $more
 * @return string
 */
function mw_make_excerpt( $text, $length, $more = '...' ) {
    $text = strip_tags($text);
    $words = preg_split("/[\n\r\t ]+/", $text, $length + 1, PREG_SPLIT_NO_EMPTY);
    if ( count($words) > $length ) {
        array_pop($words);
        $text = implode(' ', $words);
        $text .= $more;
    } else {
        $text = implode(' ', $words);
    }
    return $text;
}
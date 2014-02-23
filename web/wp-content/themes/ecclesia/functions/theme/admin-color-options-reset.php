<?php if (!defined('MW_THEME')) die('No direct script access allowed');

/**
 * Functions to handle color options reset in the Options Panel
 * when a different color scheme is selected
 */

/**
 * Hook functions into WordPress
 */
add_action( 'admin_print_scripts-toplevel_page_mw-options-admin', 'mw_reload_color_options' );
add_action( 'wp_ajax_mw_get_color_scheme_colors', 'mw_ajax_get_color_scheme_colors' );

/**
 * Add a script to the Options Panel to handle the color settings reset via AJAX
 */
function mw_reload_color_options () {
    wp_enqueue_script( 'mw-reload-color-options', MW_THEME_URL . 'admin-assets/jquery.mw-reload-color-options.js', array('jquery') );
}

/**
 * Retrieve colors for given color scheme and return via AJAX
 */
function mw_ajax_get_color_scheme_colors () {
    $color_scheme = $_GET['color_scheme'];
    if ( isset($color_scheme) && $color_scheme != '' ) {
        $colors = MW_Color_Schemes::get_instance()->get_scheme_colors($color_scheme);
    } else {
        $colors = MW_Color_Schemes::get_instance()->get_default_scheme_colors();
    }
    echo json_encode($colors);
    die();
}
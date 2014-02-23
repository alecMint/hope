<?php if (!defined('MW_THEME')) die('No direct script access allowed');


/** Check if current file is contained in correct directory, should be "theme" */
if ( basename(dirname(__FILE__)) != 'theme' ) die( "Error: Incorrect directory name in " . __FILE__ . " on line " . __LINE__ );


/** Define MW_INC_DIR and MW_INC_URL as this directory's parent directory */
define( 'MW_INC_DIR', dirname(dirname(__FILE__)) . '/' );
define( 'MW_INC_URL', get_bloginfo('template_url') . '/' . basename(dirname(dirname(__FILE__))) . '/' );


/** Define paths and URLs */

define( 'MW_FRM_DIR',   MW_INC_DIR . 'mw-framework/' );
define( 'MW_FRM_URL',   MW_INC_URL . 'mw-framework/' );
define( 'MW_THEME_DIR', MW_INC_DIR . 'theme/' );
define( 'MW_THEME_URL', MW_INC_URL . 'theme/' );

define( 'MW_ADMIN_OPTIONS_CONFIG_FILE',    MW_THEME_DIR . 'config-theme-options.php' );
define( 'MW_COLOR_SCHEMES_FILE',           MW_THEME_DIR . 'config-color-schemes.php' );
define( 'MW_DYNAMIC_STYLESHEET',           MW_THEME_DIR . 'dynamic-stylesheet.php' );
//define( 'MW_THEME_DOCS_FILE',              MW_THEME_DIR . 'theme-documentation.php' );


/**
 *  This theme can easily be translated to other languages.
 *  It will require two translation files:
 *  1) translations for the theme files, located in "/functions/lang/theme"
 *  2) translations for the framework files, located in "/functions/lang/framework"
 */
load_theme_textdomain( 'mw_theme', TEMPLATEPATH . '/functions/lang/theme' );
load_theme_textdomain( 'mw_frm', TEMPLATEPATH . '/functions/lang/framework' );


/** Load and initate MW Framework */
require_once( MW_FRM_DIR . '/init.php' );

/** Load theme functions */
require_once( MW_THEME_DIR . 'functions.php' );

/** Register widgetised areas */
require_once( MW_THEME_DIR . 'sidebar-init.php' );

/** Load comment template */
require_once( MW_THEME_DIR . 'comment-template.php' );

/** Load events manager */
require_once( MW_THEME_DIR . 'events-manager/init.php' );

/** Load functions for the color settings in the Options Panel */
require_once( MW_THEME_DIR . 'admin-color-options-reset.php' );



/**
 * Registers support for various WordPress features
 */
add_action( 'after_setup_theme', 'mw_theme_setup' );
function mw_theme_setup() {

    /* This theme uses post thumbnails */
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'post-thumbnail-small', 40, 40, TRUE );
    add_image_size( 'post-featured-img', 231, 139, TRUE );
    
    /* Register main navigation */
    register_nav_menus(array(
        'main-nav' => __( 'Primary Navigation', 'mw_theme' ),
        'top-nav' => __( 'Secondary Navigation', 'mw_theme' ),
    ));
    
}


/**
 * Add theme version and framework version info to site head
 */
add_action('wp_head','mw_set_version_info');
function mw_set_version_info(){
    $theme_info = get_theme_data(TEMPLATEPATH . '/style.css');
    echo '<meta name="generator" content="' . $theme_info['Name'] . ' ' . $theme_info['Version'] . '" />' ."\n";
    echo '<meta name="generator" content="MW Framework ' . MW_FRAMEWORK_VERSION . '" />' ."\n";
}


/**
 * Enqueue jQuery
 */
add_action( 'wp_enqueue_scripts', 'mw_theme_enqueue_scripts' );
function mw_theme_enqueue_scripts() {
    if( !is_admin()){
        wp_deregister_script('jquery');
        wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"), false, '1.5.1', true);
        wp_enqueue_script('jquery');
    }
}


/**
 * Register contact forms AJAX handler
 */
mw_register_contact_form_ajax_handler();


/**
 * Unregister some default WordPress widgets
 * replaced with custom versions by MW Framework
 */
add_action( 'widgets_init', 'mw_remove_widgets' );
function mw_remove_widgets() {
    unregister_widget( 'WP_Widget_Recent_Posts' );
    unregister_widget( 'WP_Widget_Text' );
    unregister_widget( 'WP_Widget_Pages' );
    unregister_widget( 'WP_Widget_Calendar' );
    unregister_widget( 'WP_Widget_Archives' );
    unregister_widget( 'WP_Widget_Links' );
    unregister_widget( 'WP_Widget_Meta' );
    unregister_widget( 'WP_Widget_Search' );
    unregister_widget( 'WP_Widget_Categories' );
    unregister_widget( 'WP_Widget_Recent_Comments' );
    unregister_widget( 'WP_Widget_Tag_Cloud' );
    unregister_widget( 'WP_Nav_Menu_Widget' );
}

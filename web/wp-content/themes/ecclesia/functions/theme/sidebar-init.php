<?php if (!defined('MW_THEME')) die('No direct script access allowed');

/**
 * Register widgetized areas
 */
add_action( 'init', 'widgets_init' );
function widgets_init() {
    if ( !function_exists('register_sidebar') ) return;
    
    /** Global sidebar */
    
    register_sidebar(array(
        'name'=> __('Global Sidebar', 'mw_theme'),
        'id' => 'sidebar',
        'description'   => '',
        'before_widget' => '<div id="%1$s" class="widget sidebar-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));
    
    
    /** Frontpage > Panel 1 */
    
    register_sidebar(array(
        'name'=> __('Frontpage &rsaquo; Panel 1 &rsaquo; Featured', 'mw_theme'),
        'id' => 'frontpage-p1-featured',
        'description'   => '',
        'before_widget' => '<div id="%1$s" class="widget frontpage-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name'=> __('Frontpage &rsaquo; Panel 1 &rsaquo; Sidebar', 'mw_theme'),
        'id' => 'frontpage-p1-sidebar',
        'description'   => '',
        'before_widget' => '<div id="%1$s" class="widget frontpage-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));
    
    
    /** Frontpage > Panel 2 */

    register_sidebar(array(
        'name'=> __('Frontpage &rsaquo; Panel 2 &rsaquo; Column 1', 'mw_theme'),
        'id' => 'frontpage-p2-col-1',
        'description'   => '',
        'before_widget' => '<div id="%1$s" class="widget frontpage-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name'=> __('Frontpage &rsaquo; Panel 2 &rsaquo; Column 2', 'mw_theme'),
        'id' => 'frontpage-p2-col-2',
        'description'   => '',
        'before_widget' => '<div id="%1$s" class="widget frontpage-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name'=> __('Frontpage &rsaquo; Panel 2 &rsaquo; Column 3', 'mw_theme'),
        'id' => 'frontpage-p2-col-3',
        'description'   => '',
        'before_widget' => '<div id="%1$s" class="widget frontpage-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));
    
    
    /** Footer */
    
    register_sidebar(array(
        'name'=> __('Footer &rsaquo; Column 1', 'mw_theme'),
        'id' => 'footer-col-1',
        'description'   => '',
        'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name'=> __('Footer &rsaquo; Column 2', 'mw_theme'),
        'id' => 'footer-col-2',
        'description'   => '',
        'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name'=> __('Footer &rsaquo; Column 3', 'mw_theme'),
        'id' => 'footer-col-3',
        'description'   => '',
        'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));
}

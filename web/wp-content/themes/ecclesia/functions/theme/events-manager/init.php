<?php if (!defined('MW_THEME')) die('No direct script access allowed');

/** Load files */
require_once( MW_THEME_DIR . 'events-manager/mw-events.php' );
require_once( MW_THEME_DIR . 'events-manager/mw-events-widget.php' );

/** Initiate events manager class */
$mw_events = MW_Events::get_instance();

/** Register events widgets*/
add_action( 'widgets_init', 'mw_register_events_widget' );

function mw_register_events_widget () {
    register_widget( 'MW_Widget_Upcoming_Events' );
}
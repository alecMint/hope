<?php if (!defined('MW_THEME')) die('No direct script access allowed');

/** Set the MW Framework version constant */
define( 'MW_FRAMEWORK_VERSION', '1.1' );

/** Load MW Framework files */
if ( is_admin() ) {
    require_once( MW_FRM_DIR . 'lib/mw-options-admin/export.php' );
    require_once( MW_FRM_DIR . 'lib/mw-options-admin/heading.php' );
    require_once( MW_FRM_DIR . 'lib/mw-options-admin/heading2.php' );
    require_once( MW_FRM_DIR . 'lib/mw-options-admin/input.php' );
    require_once( MW_FRM_DIR . 'lib/mw-options-admin/checkbox.php' );
    require_once( MW_FRM_DIR . 'lib/mw-options-admin/checkbox-group.php' );
    require_once( MW_FRM_DIR . 'lib/mw-options-admin/radio.php' );
    require_once( MW_FRM_DIR . 'lib/mw-options-admin/select.php' );
    require_once( MW_FRM_DIR . 'lib/mw-options-admin/textarea.php' );
    require_once( MW_FRM_DIR . 'lib/mw-options-admin/upload.php' );
    require_once( MW_FRM_DIR . 'lib/mw-options-admin/colorpicker.php' );
    require_once( MW_FRM_DIR . 'lib/mw-options-admin/textblock.php' );
    require_once( MW_FRM_DIR . 'lib/mw-options-admin/post.php' );
    require_once( MW_FRM_DIR . 'lib/mw-options-admin/page.php' );
    require_once( MW_FRM_DIR . 'lib/mw-options-admin/category.php' );
    require_once( MW_FRM_DIR . 'lib/mw-options-admin/tag.php' );
    require_once( MW_FRM_DIR . 'lib/mw-options-admin/custom-post.php' );
    require_once( MW_FRM_DIR . 'lib/mw-options-admin/measurement.php' );
    require_once( MW_FRM_DIR . 'lib/mw-options-admin/slider.php' );
}
require_once( MW_FRM_DIR . 'classes/mw-theme-options.php' );
require_once( MW_FRM_DIR . 'classes/mw-theme-options-option.php' );
require_once( MW_FRM_DIR . 'classes/mw-config.php' );
require_once( MW_FRM_DIR . 'classes/mw-color-schemes.php' );
require_once( MW_FRM_DIR . 'classes/mw-dynamic-styles.php' );
require_once( MW_FRM_DIR . 'classes/mw-widget.php' );
require_once( MW_FRM_DIR . 'classes/mw-contact-form.php' );
require_once( MW_FRM_DIR . 'lib/various.php' );
require_once( MW_FRM_DIR . 'lib/mw-helpers.php' );
require_once( MW_FRM_DIR . 'lib/mw-widgets.php' );
require_once( MW_FRM_DIR . 'lib/mw-shortcodes.php' );


/** Instantiate theme configuration class */
$MWCONF = MW_Config::get_instance();

/** Instantiate color schemes class */
$MWCOL = MW_Color_Schemes::get_instance();

/** Instantiate dynamic styles class */
$MWSTL = MW_Dynamic_Styles::get_instance();

/** Instantiate theme options admin class */
$MWOP = MW_Theme_Options::get_instance();

/** Load MW widgets */
mw_load_custom_widgets();

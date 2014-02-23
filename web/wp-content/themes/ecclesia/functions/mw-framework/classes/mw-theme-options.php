<?php if (!defined('MW_THEME')) die('No direct script access allowed');

/**
 * MW Framework - Theme Options Class
 *
 * @package     WordPress
 * @subpackage  MW_framework
 */
class MW_Theme_Options
{
    private static $instance;
    
    private $wp_option_name = 'mw_theme_options';
    private $capability = 'edit_theme_options';
    
    private $options = array();
    private $options_data = array();
    
    
    private function __construct() 
    {
        $this->options = $this->get_options();
        $this->options_data = $this->fetch_options_data();
        $this->add_action_hooks();
    }
    
    
    /**
     * Return singleton instance
     *
     * @access public
     * @since 1.0.0
     *
     * @return obj
     */
    public static function get_instance ()
    {
        if ( empty( self::$instance ) ) {
            self::$instance = new MW_Theme_Options();
        }
        return self::$instance;
    }
    
    
    // -------------------- INTERFACE  FUNCTIONS ---------------------- //
    
    
    /**
     * Get all theme options data
     *
     * @access public
     * @since 1.0.0
     *
     * @return array
     */
    public function get_options_data ()
    {
        return $this->options_data;
    }
    
    
    /**
     * Get a single theme option
     *
     * @access public
     * @since 1.0.0
     *
     * @return string
     */
    public function get_theme_option ( $id )
    {
        return isset( $this->options_data[$id] ) ? $this->options_data[$id] : FALSE;
    }
    
    
    // ---------------- END OF INTERFACE FUNCTIONS ------------------- //
    
    
    // ---------------------- SETUP  FUNCTIONS ----------------------- //
    
    
    /**
     * Add functions to WP action hooks
     *
     * @access public
     * @since 1.0.0
     *
     * @return void
     */
    public function add_action_hooks ()
    {
        add_action( 'admin_menu', array( $this, 'setup_admin' ) );
        add_action( 'wp_ajax_mw_save_theme_options', array( $this, 'save_options' ) );
        add_action( 'wp_ajax_mw_reset_theme_options', array( $this, 'reset_options' ) );
        add_action( 'wp_ajax_mw_import_theme_options', array( $this, 'import_options' ) );
        //add_action( 'wp_ajax_option_tree_add_slider', array( $this, 'option_tree_add_slider' ) );
    }
    
    
    /**
     * Get theme options from configuration class
     *
     * @access public
     * @since 1.0.0
     *
     * @return array
     */
    public function get_options () 
    {
        $options = MW_Config::get_instance()->admin_options;
        
        // Convert each option from array to object
        // Required for compatibility with the original Option Tree
        foreach ( $options as &$op ) {
            $op = new MW_Theme_Options_Option($op);
        }
        
        return $options;
    }
    
    /**
     * Check if option exists in wp_options
     * if not, create it and set default values
     *
     * @access public
     * @since 1.0.0
     *
     * @return array
     */
    public function fetch_options_data ()
    {
        $wp_option = get_option( $this->wp_option_name );
        
        if ( $wp_option == FALSE ) {
        
            // options data not found in wp_options, set defaults
            $options_data = array();
            foreach ( $this->options as $op ) {
                if ( !in_array( $op->item_type, array('heading', 'heading2', 'textblock') ) ) { // skip headings and text blocks
                    $options_data[ $op->item_id ] = $op->default;
                }
            }
            
            // save options data in db
            add_option( $this->wp_option_name, $options_data );
            
            return $options_data;
            
        } else {
            // found options data in wp_options
            $wp_option = rstripslashes( $wp_option );
            
            // update options data from default values, just in case there are any changes
            $options_data = array();
            foreach ( $this->options as $op ) {
                if ( !in_array( $op->item_type, array('heading', 'heading2', 'textblock') ) ) { // skip headings and text blocks
                    if ( isset($wp_option[$op->item_id]) ) {
                        $options_data[ $op->item_id ] = $wp_option[$op->item_id];
                    } else {
                        $options_data[ $op->item_id ] = $op->default;
                    }
                }
            }
            
            return $options_data;
        }
    }
  
  
    /**
     * Add Admin Menu Items & Test Actions
     *
     * @access public
     * @since 1.0.0
     *
     * @return void
     */
    public function setup_admin ()
    {
        $icon = MW_FRM_URL.'admin-assets/images/mw-logo-small-18x17.png';
        
        // create menu items
        add_menu_page( 'Theme options', 'Ecclesia', $this->capability, 'mw-options-admin', array( $this, 'options_page' ), $icon );
        $options_page = add_submenu_page( 'mw-options-admin', 'Theme options', 'Theme options', $this->capability, 'mw-options-admin', array( $this, 'options_page' ) );
        //$docs_page = add_submenu_page( 'mw-options-admin', 'Documentation', 'Documentation', $this->capability, 'mw-theme-docs', array( $this, 'docs_page' ) );
        $migrate_page = add_submenu_page( 'mw-options-admin', 'Import / export theme options', 'Import / export theme options', $this->capability, 'mw-options-migrate', array( $this, 'migrate_page' ) );
        
        // add menu items to wp admin
        add_action( "admin_print_styles-$options_page", array( $this, 'load_admin_styles' ) );
        add_action( "admin_print_styles-$docs_page", array( $this, 'load_admin_styles' ) );
        add_action( "admin_print_styles-$migrate_page", array( $this, 'load_admin_styles' ) );
    }


    /**
     * Load admin styles and scripts
     *
     * @access public
     * @since 1.0.0
     *
     * @return void
     */
    public function load_admin_styles () 
    {
        // enqueue styles
        wp_enqueue_style( 'mw-options-admin-style', MW_FRM_URL.'admin-assets/css/theme-options-panel.css', false, $this->version, 'screen');

        // classic admin theme styles
        if ( get_user_option( 'admin_color') == 'classic' ) 
            wp_enqueue_style( 'mw-options-admin-style-classic', MW_FRM_URL.'admin-assets/css/theme-options-panel-classic.css', array( 'mw-options-admin-style' ), $this->version, 'screen');

        // enqueue scripts
        add_thickbox();
        wp_enqueue_script( 'jquery-table-dnd', MW_FRM_URL.'admin-assets/js/jquery.table.dnd.js', array('jquery'), $this->version );
        wp_enqueue_script( 'jquery-color-picker', MW_FRM_URL.'admin-assets/js/jquery.color-picker.js', array('jquery'), $this->version );
        wp_enqueue_script( 'jquery-mw-options-admin', MW_FRM_URL.'admin-assets/js/jquery.theme-options.js', array('jquery','media-upload','thickbox','jquery-ui-core','jquery-ui-tabs','jquery-table-dnd','jquery-color-picker', 'jquery-ui-sortable'), $this->version );

        // remove GD star rating conflicts
        wp_deregister_style( 'gdsr-jquery-ui-core' );
        wp_deregister_style( 'gdsr-jquery-ui-theme' );
    }
    
    
    /**
     * Theme Options Page
     *
     * @access public
     * @since 1.0.0
     *
     * @return string
     */
    public function options_page () 
    {
        $options = $this->options;
        $options_data = $this->options_data;
        include( MW_FRM_DIR . 'templates/mw-theme-options-admin.php' );
    }
    
    
    /**
     * Settings Page
     *
     * @access public
     * @since 1.0.0
     *
     * @return string
     */
    public function migrate_page () 
    {
        $options = $this->options;
        $options_data = $this->options_data;
        include( MW_FRM_DIR . 'templates/mw-theme-options-migrate.php' );
    }
    
    
    /**
     * Documentation Page
     *
     * @access public
     * @since 1.0.0
     *
     * @return string
     */
    public function docs_page () 
    {
        include( MW_FRM_DIR . 'templates/mw-theme-docs.php' );
    }
    
    
    // ------------------ END OF SETUP  FUNCTIONS -------------------- //
    
    
    // ----------------------- AJAX FUNCTIONS ------------------------ //
    
    
    /**
     * Save Theme Options via AJAX
     *
     * @access public
     * @since 1.0.0
     *
     * @return void
     */
    public function save_options () 
    {
        check_ajax_referer( '_mw_theme_options', '_ajax_nonce' );
        
        $new_options_data = array();
        foreach ( $this->options as $op ) {
            if ( $op->item_type != 'heading' && $op->item_type != 'heading2' && $op->item_type != 'textblock' ) { // skip headings
                $key = $op->item_id;
                $new_options_data[$key] = isset( $_REQUEST[$key] ) ? $_REQUEST[$key] : '';
            }
        }
        
        // save options data in db
        update_option( $this->wp_option_name, $new_options_data );
        
        die();
    }
    
    
    /**
     * Reset Theme Options via AJAX to default values
     *
     * @access public
     * @since 1.0.0
     *
     * @return void
     */
    public function reset_options () 
    {
        check_ajax_referer( '_mw_theme_options', '_ajax_nonce' );
        
        $options_data = array();
        foreach ( $this->options as $op ) {
            if ( $op->item_type != 'heading' && $op->item_type != 'heading2' && $op->item_type != 'textblock' ) { // skip headings
                $options_data[ $op->item_id ] = $op->default;
            }
        }
        
        // update options data in db
        update_option( $this->wp_option_name, $options_data );
        
        die();
    }
    
    
    /**
     * Import Option Data via AJAX
     *
     * @access public
     * @since 1.0.0
     *
     * @return void
     */
    public function import_options () 
    {
        check_ajax_referer( '_import_data', '_ajax_nonce' );
        
        if ( !isset($_REQUEST['import_options']) ) {
            die(-1); // no data, return failure
        }
        
        // decode and unserialize data
        $import_options = unserialize( base64_decode( $_REQUEST['import_options'] ) );
        
        // check if provided data is an array
        if ( !is_array( $import_options ) ) {
            die(-1); // data is not an array, return failure
        }
        
        $new_options_data = array();
        foreach ( $this->options as $op ) {
            if ( $op->item_type != 'heading' && $op->item_type != 'heading2' && $op->item_type != 'textblock' ) { // skip headings
                $key = $op->item_id;
                $new_options_data[$key] = isset( $import_options[$key] ) ? $import_options[$key] : $op->default;
            }
        }
        
        // save options data in db
        update_option( $this->wp_option_name, $new_options_data );
        
        die();
    }
    
    
    public function option_tree_add_slider ()
    {
        $count = $_GET['count'] + 1;
        $id = $_GET['slide_id'];
        $image = array(
            'order'       => $count,
            'title'       => '',
            'image'       => '',
            'link'        => '',
            'description' => ''
        );
        slider_view( $id, $image, $count );
        die();
    }
    
    
    // -------------------- END OF AJAX FUNCTIONS -------------------- //
    
}

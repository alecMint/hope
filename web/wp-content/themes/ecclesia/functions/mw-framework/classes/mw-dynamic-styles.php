<?php if (!defined('MW_THEME')) die('No direct script access allowed');

/**
 * MW Framework - Dynamic Styles Class
 *
 * @package     WordPress
 * @subpackage  MW_framework
 */
class MW_Dynamic_Styles
{
    private static $instance;
    
    public $default_styles = array();
    
    
    private function __construct() 
    {
        $this->load_default_styles();
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
            self::$instance = new MW_Dynamic_Styles();
        }
        return self::$instance;
    }
    
    
    /**
     * Load from config file default settings for dynamic styles
     *
     * @access private
     * @since 1.0.0
     */
    private function load_default_styles ()
    {
        if ( defined( 'MW_DYNAMIC_STYLES_DEFAULTS_FILE' ) ) {
            require_once( MW_DYNAMIC_STYLES_DEFAULTS_FILE );
            $this->default_styles = $styles;
        }
    }
    
    
    /**
     * Get default styles array
     *
     * @access public
     * @since 1.0.0
     *
     * @return array
     */
    public function get_default_styles ()
    {
        return $this->default_styles;
    }
    
    
    /**
     * Get single style value from default styles
     *
     * @access public
     * @since 1.0.0
     *
     * @return string|FALSE
     */
    public function get_default_style ( $id )
    {
        if ( isset($this->default_styles[$id]) )
            return $this->default_styles[$id];
        else
            return FALSE;
    }
    
    
    /**
     * Load dynamic stylesheet and output to the browser
     *
     * @access public
     * @since 1.0.0
     *
     * @return string
     */
    public function load_dynamic_css ()
    {
        if ( defined( 'MW_DYNAMIC_STYLESHEET' ) ) {
            include( MW_DYNAMIC_STYLESHEET );
        }
    }
}
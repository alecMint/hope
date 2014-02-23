<?php if (!defined('MW_THEME')) die('No direct script access allowed');

/**
 * MW Framework - Color Schemes Class
 *
 * @package     WordPress
 * @subpackage  MW_framework
 */
class MW_Color_Schemes
{
    private static $instance;
    
    public $schemes = array();
    public $default_scheme = '';
    
    
    private function __construct() 
    {
        $this->load_color_schemes();
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
            self::$instance = new MW_Color_Schemes();
        }
        return self::$instance;
    }
    
    
    /**
     * Load color schemes from config file
     *
     * @access private
     * @since 1.0.0
     */
    private function load_color_schemes ()
    {
        if ( defined( 'MW_COLOR_SCHEMES_FILE' ) ) {
            require_once( MW_COLOR_SCHEMES_FILE );
            $this->schemes = $color_schemes;
            $this->default_scheme = $default_scheme;
        }
    }
    
    
    /**
     * Get scheme colors
     *
     * @access public
     * @since 1.0.0
     *
     * @return array
     */
    public function get_scheme_colors ( $scheme_name )
    {
        if ( isset($this->schemes[$scheme_name]) )
            return $this->schemes[$scheme_name];
        else
            return FALSE;
    }
    
    
    /**
     * Get default scheme name
     *
     * @access public
     * @since 1.0.0
     *
     * @return array
     */
    public function get_default_scheme ()
    {
        return $this->default_scheme;
    }
    
    
    /**
     * Get default scheme colors
     *
     * @access public
     * @since 1.0.0
     *
     * @return array
     */
    public function get_default_scheme_colors ()
    {
        return $this->get_scheme_colors( $this->default_scheme );
    }
    
    
    /**
     * Get color schemes array
     *
     * @access public
     * @since 1.0.0
     *
     * @return array
     */
    public function get_color_schemes ()
    {
        return array_keys($this->schemes);
    }
    
}
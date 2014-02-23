<?php if (!defined('MW_THEME')) die('No direct script access allowed');

/**
 * MW Framework - Configuration Class
 *
 * This class is a sigleton. Loads all the config data 
 * and makes it available to the rest of the application.
 *
 * @package     WordPress
 * @subpackage  MW_framework
 */
class MW_Config
{
    private static $instance;
    
    public $admin_options = array();
    public $editable_colors = array();
    
    private function __construct ()
    {
        $this->load_admin_options_config();
    }
    
    public static function get_instance ()
    {
        if ( empty( self::$instance ) ) {
            self::$instance = new MW_Config();
        }
        return self::$instance;
    }
    
    /**
     * Loads theme options panel configuration
     */
    public function load_admin_options_config ()
    {
        if ( defined( 'MW_ADMIN_OPTIONS_CONFIG_FILE' ) ) {
            require_once( MW_ADMIN_OPTIONS_CONFIG_FILE );
            $this->admin_options = $options;
        }
    }
}
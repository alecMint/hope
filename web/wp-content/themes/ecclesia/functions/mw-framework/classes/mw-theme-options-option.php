<?php if (!defined('MW_THEME')) die('No direct script access allowed');

/**
 * MW Framework - Theme Options Option Class
 *
 * This is required for compatibility with the original Option Tree
 *
 * @package     WordPress
 * @subpackage  MW_framework
 */
class MW_Theme_Options_Option
{
    function __construct ( $args )
    {
        foreach ( $args as $arg_name => $arg_val ) {
            $this->$arg_name = $arg_val;
        }
    }
}
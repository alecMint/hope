<?php if (!defined('MW_THEME')) die('No direct script access allowed');
/**
 * Heading 2 Option
 *
 * @access public
 * @since 1.0.0
 *
 * @param array $value
 * @param array $settings
 * @param int $int
 *
 * @return string
 */
function option_tree_heading2( $value, $settings, $int ) 
{
  echo '<h3 class="group-heading">' . htmlspecialchars_decode( $value->item_title ) . '</h3>';
}
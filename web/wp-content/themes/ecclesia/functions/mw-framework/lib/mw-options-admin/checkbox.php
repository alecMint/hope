<?php if (!defined('MW_THEME')) die('No direct script access allowed');
/**
 * ColorPicker Option
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
function option_tree_checkbox( $value, $settings, $int ) 
{
?>
  <div class="option option-checbox">
    <h3><?php echo htmlspecialchars_decode( $value->item_title ); ?></h3>
    <div class="section">
      <div class="element">
        <?php
        $checked = '';
        if ( $settings[$value->item_id] == TRUE || $settings[$value->item_id] == $value->item_options ) { 
            $checked = ' checked="checked"'; 
        }
        echo '<div class="input_wrap">';
        echo '<input name="' .$value->item_id. '" id="' .$value->item_id. '" type="checkbox" value="' .$value->item_options. '"' .$checked. ' />';
        echo '<label for="' .$value->item_id. '">' .$value->item_options. '</label>';
        echo '</div>'
        ?>
      </div>
      <div class="description">
        <?php echo htmlspecialchars_decode( $value->item_desc ); ?>
      </div>
    </div>
  </div>
<?php
}
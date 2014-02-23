<?php if (!defined('MW_THEME')) die('No direct script access allowed'); ?>

<div id="framework_wrap" class="wrap">
	
    <div id="header">
        <h1><?php _e('Theme Options', 'mw_frm'); ?></h1>
        <span class="icon">&nbsp;</span>
    </div>
  
    <div id="content_wrap">
  
        <form method="post" id="the-theme-options">
          
        <div class="info top-info">
            <input type="submit" value="<?php _e('Save All Changes', 'mw_frm') ?>" class="button-framework save-options" name="submit"/>
        </div>
        
        <div class="ajax-message<?php if ( isset( $message ) ) { echo ' show'; } ?>">
            <?php if ( isset( $message ) ) { echo $message; } ?>
        </div>
      
      <div id="content">
        <div id="options_tabs">
        
            <ul class="options_tabs">
            <?php 
            foreach ( $options as $op ) {
                if ( $op->item_type == 'heading' ) {
                    echo '<li><a href="#option_'.$op->item_id.'">' . htmlspecialchars_decode( $op->item_title ).'</a><span></span></li>';
                }
            }
            ?>
            </ul>
            
            <?php
            // set count        
            $count = 0;
            // loop through options & load corresponding function   
            foreach ( $options as $op ) {
                $count++;
                if ( $op->item_type == 'upload' ) {
                    $int = $post_id;
                } elseif ( $op->item_type == 'textarea' ) {
                    $int = ( is_numeric( trim( $op->item_options ) ) ) ? trim( $op->item_options ) : 8;
                } else {
                    $int = $count;
                }
                call_user_func_array( 'option_tree_' . $op->item_type, array( $op, $options_data, $int ) );
            }
            // close heading
            echo '</div>';
            ?>
            
            <br class="clear" />
            
        </div>
    </div>
    
    <div class="info bottom">
        <input type="submit" value="<?php _e('Reset Options', 'mw_frm') ?>" class="button-framework reset" name="reset"/>
        <input type="submit" value="<?php _e('Save All Changes', 'mw_frm') ?>" class="button-framework save-options" name="submit"/>
    </div>
      
    <?php wp_nonce_field( '_mw_theme_options', '_ajax_nonce', false ); ?>
      
    </form>
    
    </div>

</div><!-- /#framework_wrap -->
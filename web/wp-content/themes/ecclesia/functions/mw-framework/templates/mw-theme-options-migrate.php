<?php if (!defined('MW_THEME')) die('No direct script access allowed'); ?>

<div id="framework_wrap" class="wrap">

    <div id="header">
        <h1><?php _e('Import / Export Theme Options', 'mw_frm'); ?></h1>
        <span class="icon">&nbsp;</span>
    </div>
  
    <div id="content_wrap">
  
    <div class="info top-info"></div>
    
    <div class="ajax-message<?php if ( isset( $message ) ) { echo ' show'; } ?>">
        <?php if ( isset( $message ) ) { echo $message; } ?>
    </div>
    
    <div id="content">
        <div id="options_tabs">
      
        <ul class="options_tabs">
          <li><a href="#export_options"><?php _e('Export', 'mw_frm'); ?></a><span></span></li>
          <li><a href="#import_options"><?php _e('Import', 'mw_frm'); ?></a><span></span></li>
        </ul>
        
        <div id="export_options" class="block">
          <h2><?php _e('Export', 'mw_frm'); ?></h2>
          <div class="option option-input">
            <h3><?php _e('Theme Options Data', 'mw_frm'); ?></h3>
            <div class="section">
              <div class="element">
                <textarea name="export_options" rows="20"><?php echo base64_encode(serialize($options_data)); ?></textarea>
              </div>
              <div class="description">
                <?php _e('Export your saved Theme Options data by highlighting this text and doing a copy/paste into a blank .txt file. Then save the file for importing into another install of WordPress later.<br/><br/>To import the data back into WordPress just paste it into the "Theme Options Data" field in the "Import tab".', 'mw_frm'); ?>
              </div>
            </div>
          </div>
        </div>
        
        <div id="import_options" class="block">
          <h2><?php _e('Import', 'mw_frm'); ?></h2>
          <form method="post" id="import-data">
            <div class="option option-input">
              <h3><?php _e('Theme Options Data', 'mw_frm'); ?></h3>
              <div class="section">
                <div class="element">
                  <textarea name="import_options" rows="20" id="import_options" class="import_options"></textarea>
                </div>
                <div class="description">
                  <?php _e('<p>To import the values of your theme options copy and paste what appears to be a random string of alpha numeric characters into this textarea and press the "Import Data" button below.', 'mw_frm'); ?></p>
                </div>
              </div>
              <input type="submit" value="<?php _e('Import Data', 'mw_frm') ?>" class="ob_button right import-data" />
            </div>
            <?php wp_nonce_field( '_import_data', '_ajax_nonce', false ); ?>
          </form>
        </div>
        
        <br class="clear" />
      </div>
    </div>
    <div class="info bottom">
      <input type="hidden" name="action" value="save" />
    </div>   
  </div>

</div><!-- /#framework_wrap -->
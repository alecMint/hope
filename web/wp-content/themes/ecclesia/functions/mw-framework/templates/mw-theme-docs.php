<?php if (!defined('MW_THEME')) die('No direct script access allowed'); ?>

<div id="framework_wrap" class="wrap">

    <div id="header">
        <h1><?php _e('Theme Documentation', 'mw_frm'); ?></h1>
        <span class="icon">&nbsp;</span>
    </div>

    <div id="content_wrap">
        <div class="info top-info"></div>
        <div id="content">
            <div id="options_tabs" class="docs">
                <?php include( MW_THEME_DOCS_FILE ); ?>
                <br class="clear" />
            </div>
        </div>
        <div class="info bottom"></div>   
    </div>

</div><!-- /#framework_wrap -->
<?php if (!defined('MW_THEME')) die('No direct script access allowed'); ?>

<b class="grid-divider"></b>
    
</div><!-- /#content-wrap-inner -->
    
<!-- Footer -->

<div class="footer-wrap" id="footer-wrap">
    <div class="grid-wrap" id="footer">

        <div class="grid-2 col-1 widgets-container">
            <?php if (!dynamic_sidebar('footer-col-1')) _e('This is widgetised area:<br/>Footer &rsaquo; Column 1', 'mw_theme'); ?>
        </div>
        
        <div class="grid-2 col-2 widgets-container">
            <?php if (!dynamic_sidebar('footer-col-2')) _e('This is widgetised area:<br/>Footer &rsaquo; Column 2', 'mw_theme'); ?>
        </div>
        
        <div class="grid-2 col-3 widgets-container">
            <?php if (!dynamic_sidebar('footer-col-3')) _e('This is widgetised area:<br/>Footer &rsaquo; Column 3', 'mw_theme'); ?>
        </div>

    </div>
</div><!-- /#content-wrap-inner -->
    
<!-- End of footer -->
    
</div><!-- /#content-wrap -->

<?php if ( mw_theme_option('footnotes_content') != '' ): ?>
<div id="footnotes">
<?php echo wptexturize( mw_theme_option('footnotes_content') ); ?>
</div>
<?php endif; ?>

</div><!-- /#page-wrap -->


<?php wp_footer(); ?>

<?php get_template_part('template-parts/mod-footer-scripts'); ?>

<?php if ( mw_theme_option('tracking_code') != '' ): ?>
    <?php echo mw_theme_option('tracking_code'); ?>
<?php endif; ?>

</body>
</html>

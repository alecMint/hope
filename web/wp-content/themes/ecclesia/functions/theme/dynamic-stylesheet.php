<?php if (!defined('MW_THEME')) die('No direct script access allowed');

/**
 * This file contains dynamically generated CSS styles.
 * It is loaded into the theme via Dynamic Styles Class
 * in the MW Framework.
 */

?>

<style type="text/css">

/* Dynamic styles */

<?php if ( mw_theme_option('use_custom_site_bg') == 'Yes' ): ?>

/* Site background options */
html {
    background-color: <?php echo mw_theme_option('site_bg_col'); ?>;
    <?php if ( mw_theme_option('site_bg_img') != '' ): ?>
    background-image: url('<?php echo mw_theme_option('site_bg_img'); ?>');
    background-repeat: <?php echo mw_theme_option('site_bg_img_repeat'); ?>;
    background-position: <?php echo mw_theme_option('site_bg_img_pos'); ?>;
    <?php endif; ?>
}

<?php endif; ?>

<?php if ( mw_theme_option('use_custom_content_bg') == 'Yes' ): ?>

/* Content area options */
#content-wrap {
    background-color: <?php echo mw_theme_option('content_bg_col'); ?>;
    <?php if ( mw_theme_option('content_bg_img') != '' ): ?>
    background-image: url('<?php echo mw_theme_option('content_bg_img'); ?>');
    background-repeat: <?php echo mw_theme_option('content_bg_img_repeat'); ?>;
    background-position: <?php echo mw_theme_option('content_bg_img_pos'); ?>;
    <?php endif; ?>
    border-color: <?php echo mw_theme_option('content_border_col'); ?>;
}

<?php endif; ?>

<?php if ( mw_theme_option('use_custom_text_col') == 'Yes' ): ?>

/* Text color options */

html, body { color: <?php echo mw_theme_option('col_text'); ?>; }
h1, h2, h3, h4, h5, h6 { color: <?php echo mw_theme_option('col_headings'); ?>; }
a { color: <?php echo mw_theme_option('col_link'); ?>; }
a:hover, a:active { color: <?php echo mw_theme_option('col_link_hover'); ?>; }

#top-nav a, #main-nav a { color: <?php echo mw_theme_option('col_nav_link'); ?>; }
#top-nav a:hover , #main-nav a:hover { color: <?php echo mw_theme_option('col_nav_link_hover'); ?>; }
.subnav-list a:hover, .subnav-list a:active, .subnav-list > .item-hover a { background: <?php echo mw_theme_option('col_subnav_link_hover'); ?>; }

input.submit, input.reset, .link-btn, .more-link, .pagination a, .pagination .current, #cancel-comment-reply-link, .comment-reply-link, #older-comments a, #newer-comments a {
    background-color: <?php echo mw_theme_option('col_btn'); ?>;
}

/* Elements using Headings color */
h1 a, h2 a, h3 a, h4 a, h5 a, h6 a { color: <?php echo mw_theme_option('col_headings'); ?>; }
#header .text-logo { color: <?php echo mw_theme_option('col_headings'); ?>; }
.pagination .current { background: <?php echo mw_theme_option('col_headings'); ?>; border-color: <?php echo mw_theme_option('col_headings'); ?>; }

<?php endif; ?>

<?php if ( mw_theme_option('use_custom_widget_styles') == 'Yes' ): ?>

/* Widget color options */

.widget-boxed {
    background: <?php echo mw_theme_option('widget_bg_col'); ?>;
    border-color: <?php echo mw_theme_option('widget_border_col'); ?>;
}

<?php endif; ?>


</style>
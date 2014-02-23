<?php if (!defined('MW_THEME')) die('No direct script access allowed'); ?>

<?php get_header(); ?>

<!-- Main content -->

<div class="grid-wrap with-sidebar">

    <?php if ( mw_theme_option('sidebar_position') == 'left' ): ?>
    <div class="grid-2 widgets-container">
        <?php get_sidebar(); ?>
    </div>
    <?php endif; ?>

    <div class="grid-4">
        <h1><?php _e('Blog', 'mw_theme') ?></h1>
        <?php get_template_part('template-parts/mod-the-loop'); ?>
    </div>
    
    <?php if ( mw_theme_option('sidebar_position') == 'right' ): ?>
    <div class="grid-2 widgets-container">
        <?php get_sidebar(); ?>
    </div>
    <?php endif; ?>

</div>

<!-- End of main content -->

<?php get_footer(); ?>
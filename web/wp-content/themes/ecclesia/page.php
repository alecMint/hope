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
        
        <?php if(have_posts()) : while(have_posts()) : the_post(); ?>
            <h1><?php the_title() ?></h1>
            <?php the_content(); ?>
        
        <?php endwhile; else : ?>
            <?php get_template_part( 'not-found' ); ?>
        <?php endif; ?>
    
    </div>
    
    <?php if ( mw_theme_option('sidebar_position') == 'right' ): ?>
    <div class="grid-2 widgets-container">
        <?php get_sidebar(); ?>
    </div>
    <?php endif; ?>

</div>

<!-- End of main content -->

<?php get_footer(); ?>
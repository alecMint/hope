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
    
        <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
        <?php if (is_category()): ?>
            <h1><?php single_cat_title(); ?></h1>
        <?php elseif (is_tag()): ?>
            <h1><?php _e('Tag:', 'mw_theme'); ?> <?php single_tag_title(); ?></h1>
        <?php elseif (is_day()): ?>
            <h1><?php _e('Date:', 'mw_theme'); ?> <?php the_time(get_option('date_format')); ?></h1>
        <?php elseif (is_month()): ?>
            <h1><?php _e('Month:', 'mw_theme'); ?> <?php the_time('F, Y'); ?></h1>
        <?php elseif (is_year()): ?>
            <h1><?php _e('Year:', 'mw_theme'); ?> <?php the_time('Y'); ?></h1>
        <?php elseif(is_author()): 
            if (have_posts()) {
                if(isset($_GET['author_name'])) {
                    $author = get_userdatabylogin(get_the_author_login());
                } else {
                    $author = get_userdata(intval($author));
                }
            } ?>
            <h1><?php _e('Posts by', 'mw_theme'); ?> <?php echo $author->display_name; ?></h1>
        <?php elseif (isset($_GET['paged']) && !empty($_GET['paged'])): ?>
            <h1><?php _e('Archives', 'mw_theme'); ?></h1>
        <?php endif; ?>
        
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
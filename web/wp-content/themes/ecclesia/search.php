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
        <h2><?php _e('Search results for: ', 'mw_theme'); echo '<em>'; the_search_query(); echo '</em>'; ?></h2>
        
        <?php if(have_posts()) : while(have_posts()) : the_post(); ?>
            <div class="post post-search-results">
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <?php
                the_excerpt();
                printf('<a href="%s" class="search-results-more-link">%s</a>', get_permalink(), __('Read more &rarr;', 'mw_theme'));
                ?>
            </div>
        <?php endwhile; ?>
            <?php mw_pagination( NULL, 2 ); ?>
        <?php else : ?>
            <div id="not-found"><p class="large"><?php _e('We haven\'t found any content matching your search criteria.', 'mw_theme'); ?></p></div>
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
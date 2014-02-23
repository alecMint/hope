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

            <div class="post">
                <h1><?php the_title(); ?></h1>
                <div class="post-meta">
                    <ul>
                        <li class="post-meta-date"><?php the_time(get_option('date_format')); ?></li>
                        <li class="post-meta-author"><?php _e('by', 'mw_theme') ?> <?php the_author_posts_link(); ?></li>
                        <li class="post-meta-category"><?php _e('in', 'mw_theme') ?> <?php the_category(','); ?></li>
                        <li class="post-meta-comments"><a href="<?php the_permalink(); ?>#comments"><?php comments_number( __('No comments yet', 'mw_theme'), __('1 comment', 'mw_theme'), __('% comments', 'mw_theme') );
         ?></a></li>
                    </ul>
                </div>
                <?php
                if(has_post_thumbnail()) {
                    echo '<a class="post-thumb-link" href="' . get_permalink() . '">';
                    the_post_thumbnail( 'post-featured-img', array('class'=>'alignleft post-featured-img') );
                    echo '</a>';
                }
                the_content();
                ?>
            </div>
            
            <?php if ( mw_theme_option('posts_show_author_box') == 'Yes' ): ?>
            <div id="author-info">
                <?php echo get_avatar(get_the_author_email(), '60'); ?>
                <div id="author-info-txt">
                    <h3><?php the_author_posts_link(); ?></h3>
                    <p><?php the_author_meta('description'); ?></p>
                </div>
            </div>
            <?php endif; ?>
            
            <?php comments_template(); ?>

        <?php endwhile; else : ?>
            <?php get_template_part( 'template-parts/mod-not-found' ); ?>
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
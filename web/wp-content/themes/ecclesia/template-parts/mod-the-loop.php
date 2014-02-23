<?php if (!defined('MW_THEME')) die('No direct script access allowed'); ?>

<?php if(have_posts()) : while(have_posts()) : the_post(); ?>

    <div class="post">
    
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        
        <?php if(!is_search()) { ?>
            <div class="post-meta">
                <ul>
                    <li class="post-meta-date"><?php the_time(get_option('date_format')); ?></li>
                    <li class="post-meta-author"><?php _e('by', 'mw_theme') ?> <?php the_author_posts_link(); ?></li>
                    <li class="post-meta-category"><?php _e('in', 'mw_theme') ?> <?php the_category(', '); ?></li>
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
        }
        if( has_excerpt() || is_search() ) {
            the_excerpt();
            printf('<a href="%s" class="more-link excerpt-more-link">%s</a>', get_permalink(), __('Read more &rarr;', 'mw_theme'));
        } else {
            the_content(__('Read more &rarr;', 'mw_theme'));
        } 
        ?>
        
    </div>

<?php endwhile; ?>

<?php mw_pagination( NULL, 2 ); ?>

<?php else : ?>
    <?php get_template_part( 'template-parts/mod-not-found' ); ?>
<?php endif; ?>

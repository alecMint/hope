<?php if (!defined('MW_THEME')) die('No direct script access allowed');

$time_format = get_option('time_format');
$date_format = get_option('date_format');
$data        = get_post_custom($post->ID);
$start_date  = $data['mw_event_start_date'][0];
$end_date    = $data['mw_event_end_date'][0];
?>

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
            <p>
            <strong><?php _e('Starts', 'mw_theme') ?>:</strong> <?php echo date_i18n( $date_format, $start_date ); ?> <?php _e('at', 'mw_theme') ?> <?php echo date_i18n( $time_format, $start_date ); ?><br/>
            <strong><?php _e('Ends', 'mw_theme') ?>:</strong> <?php echo date_i18n( $date_format, $end_date ); ?> <?php _e('at', 'mw_theme') ?> <?php echo date_i18n( $time_format, $end_date ); ?><br/>
            </p>
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
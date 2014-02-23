<?php if (!defined('MW_THEME')) die('No direct script access allowed');

$time_format = get_option('time_format');
$events = MW_Events::get_instance()->get_events();
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
        <h1><?php _e('Upcoming events', 'mw_theme') ?></h1>
        
        <?php if ( count($events) > 0 ): foreach ( $events as $event ): ?>

            <div class="event-container">
                <div class="event-date-container">
                    <span class="day-month"><?php echo date_i18n( 'd', $event->start_date ); ?></span>
                    <span class="day-week"><?php echo date_i18n( 'l', $event->start_date ); ?></span>
                    <span class="month-year"><?php echo date_i18n( 'F', $event->start_date ); ?> <?php echo date_i18n( 'Y', $event->start_date ); ?></span>
                </div>
                <div class="event-info-container">
                    <h2><a href="<?php echo get_permalink($event->ID); ?>"><?php echo $event->post_title; ?></a></h2>
                    <div>
                        <span class="event-start-time"><?php _e('Starts', 'mw_theme') ?>: <?php echo date_i18n( $time_format, $event->start_date ); ?></span> &bull;
                        <span class="event-end-time"><?php _e('Ends', 'mw_theme') ?>: <?php echo date_i18n( $time_format, $event->end_date ); ?></span> &bull;
                        <span class="event-more"><a href="<?php echo get_permalink($event->ID); ?>"><?php _e('Read more &rarr;', 'mw_theme') ?></a></span>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
        <?php else : ?>
            <?php _e('We don\'t have any events scheduled at the moment. Please check again soon.', 'mw_theme'); ?>
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
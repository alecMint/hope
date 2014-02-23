<?php if (!defined('MW_THEME')) die('No direct script access allowed');

/*
Template Name: Frontpage
*/

$slides = mw_get_slides();
$slides_html = '';
foreach ( $slides as $slide ) {
    $img = '<img src="' . $slide['img'] . '" alt="" width="612" height="290"/>';
    if ( isset($slide['link']) )
        $img = '<a href="' . $slide['link'] . '">' . $img . '</a>';
    
    $content = '';
    if ( isset($slide['heading']) )
        $content = sprintf( '<span class="heading">%s</span>', wptexturize($slide['heading']) );
    if ( isset($slide['text']) )
        $content .= $slide['text'];
    if ( $content != '' ) {
        if ( isset($slide['link']) ) {
            $content = sprintf( '<a href="%s" class="slide-content">%s</a>', $slide['link'], wptexturize($content) );
        } else {
            $content = sprintf( '<div class="slide-content">%s</div>', $content );
        }
    }
    
    $slide_html = '<div class="slide">' . $img . $content . '</div>';
    $slides_html .= $slide_html;
}

?>

<?php get_header(); ?>

<!-- Main content -->

<?php
    // Panel 1 layout options:
    //   sr_s_f - Sidebar on right + Slider + Featured
    //   sr_s   - Sidebar on right + Slider
    //   sr_f   - Sidebar on right + Featured
    //   sl_s_f - Sidebar on left + Slider + Featured
    //   sl_s   - Sidebar on left + Slider
    //   sl_f   - Sidebar on left + Featured
    //   full   - Only Featured (no Sidebar, no Slider)
?>

<?php if ( mw_theme_option('show_panel_1') == 'Yes' ): ?>
<!-- Panel 1 -->
<div class="grid-wrap">

    <?php if ( in_array( mw_theme_option('panel_1_layout'), array('sl_s_f', 'sl_s', 'sl_f')) ): ?>
    <div class="grid-2 widgets-container">
        <?php if (!dynamic_sidebar('frontpage-p1-sidebar')) _e('This is widgetised area:<br/>Frontpage &rsaquo; Panel 1 &rsaquo; Sidebar', 'mw_theme'); ?>
    </div>
    <?php endif; ?>

    <?php $featured_grid = ( mw_theme_option('panel_1_layout') == 'full' ) ? 'grid-6' : 'grid-4'; ?>
    <div class="<?php echo $featured_grid; ?>">
        
        <?php if ( in_array( mw_theme_option('panel_1_layout'), array('sr_s_f', 'sr_s', 'sl_s_f', 'sl_s')) ): ?>
        <!-- Slider -->
        <div id="front-slider<?php if ( count($slides) < 2 ) echo '-static' ?>" class="front-slider">
            <div class="slides_container">
            <?php echo $slides_html; ?>
            </div>
            <?php if ( count($slides) > 1 ): ?>
            <a href="#" class="prev">Previous slide</a>
			<a href="#" class="next">Next slide</a>
            <?php endif; ?>
        </div>
        <!-- End of Slider -->
        <?php endif; ?>
        
        <?php if ( in_array( mw_theme_option('panel_1_layout'), array('sr_s_f', 'sr_f', 'sl_s_f', 'sl_f', 'full')) ): ?>
            <?php if (!dynamic_sidebar('frontpage-p1-featured')) _e('This is widgetised area:<br/>Frontpage &rsaquo; Panel 1 &rsaquo; Featured', 'mw_theme'); ?>
        <?php endif; ?>
        
    </div>
    
    <?php if ( in_array( mw_theme_option('panel_1_layout'), array('sr_s_f', 'sr_s', 'sr_f')) ): ?>
    <div class="grid-2 widgets-container">
        <?php if (!dynamic_sidebar('frontpage-p1-sidebar')) _e('This is widgetised area:<br/>Frontpage &rsaquo; Panel 1 &rsaquo; Sidebar', 'mw_theme'); ?>
    </div>
    <?php endif; ?>

</div>
<!-- End of Panel 1 -->
<?php if ( mw_theme_option('show_panel_2') == 'Yes' ): ?><b class="grid-divider"></b><?php endif; ?>
<?php endif; ?>


<?php
    // Panel 2 layout options:
    //   3col - 3 equal columns
    //   2col - 2 equal columns
    //   sr   - Column + sidebar on right
    //   sl   - Column + sidebar on left
    //   full - Full width column
    
    switch ( mw_theme_option('panel_2_layout') ) {
        case '3col':
            $col_1_grid = 'grid-2';
            $col_2_grid = 'grid-2';
            $col_3_grid = 'grid-2';
            break;
        case '2col':
            $col_1_grid = 'grid-3';
            $col_2_grid = 'grid-3';
            break;
        case 'sr':
            $col_1_grid = 'grid-4';
            $col_2_grid = 'grid-2';
            break;
        case 'sl':
            $col_1_grid = 'grid-2';
            $col_2_grid = 'grid-4';
            break;
        case 'full':
            $col_1_grid = 'grid-6';
            break;
    }
?>

<?php if ( mw_theme_option('show_panel_2') == 'Yes' ): ?>
<!-- Panel 2 -->
<div class="grid-wrap">

    <div class="<?php echo $col_1_grid; ?> widgets-container">
        <?php if (!dynamic_sidebar('frontpage-p2-col-1')) _e('This is widgetised area:<br/>Frontpage &rsaquo; Panel 2 &rsaquo; Column 1', 'mw_theme'); ?>
    </div>
    
    <?php if ( in_array( mw_theme_option('panel_2_layout'), array('3col', '2col', 'sr', 'sl')) ): ?>
    <div class="<?php echo $col_2_grid; ?> widgets-container">
        <?php if (!dynamic_sidebar('frontpage-p2-col-2')) _e('This is widgetised area:<br/>Frontpage &rsaquo; Panel 2 &rsaquo; Column 2', 'mw_theme'); ?>
    </div>
    <?php endif; ?>
    
    <?php if ( mw_theme_option('panel_2_layout') == '3col' ): ?>
    <div class="<?php echo $col_3_grid; ?> widgets-container">
        <?php if (!dynamic_sidebar('frontpage-p2-col-3')) _e('This is widgetised area:<br/>Frontpage &rsaquo; Panel 2 &rsaquo; Column 3', 'mw_theme'); ?>
    </div>
    <?php endif; ?>

</div>
<!-- End of Panel 2 -->
<?php endif; ?>


<!-- End of main content -->

<?php get_footer(); ?>
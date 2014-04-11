<?php if (!defined('MW_THEME')) die('No direct script access allowed'); ?>

<?php get_template_part('template-parts/mod-document-head'); ?>

<body class="page-<?php echo preg_replace('/[^a-zA-Z0-9_\-]/','',bloginfo('name')); ?>">

<div class="page-wrap" id="page-wrap">

<div class="content-wrap" id="content-wrap">
<div class="content-wrap-inner" id="content-wrap-inner">

    <!-- Header -->

    <div class="grid-wrap" id="header">

        <div class="grid-6 inner">
            
            <?php $logo_style = ( mw_theme_option('logo_pos_y') != '' ) ? 'style="bottom:' . mw_theme_option('logo_pos_y') . 'px"' : '' ; ?>
            <?php if ( mw_theme_option('use_site_title') == 'Yes' ): ?>
                <a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>" <?php echo $logo_style; ?> class="text-logo"><?php bloginfo('name'); ?></a>
            <?php else: ?>
                <?php
                if ( mw_theme_option('logo_img') != '' ) {
                    $logo_img = mw_theme_option('logo_img');
                } else {
                    if ( mw_theme_option('color_scheme') == 'Red + Teal' ) {
                        $logo_img = get_bloginfo('template_directory') . '/img/logo-red.png';
                    } elseif ( mw_theme_option('color_scheme') == 'Green' ) {
                        $logo_img = get_bloginfo('template_directory') . '/img/logo-green.png';
                    } elseif ( mw_theme_option('color_scheme') == 'Gold' ) {
                        $logo_img = get_bloginfo('template_directory') . '/img/logo-gold.png';
                    } elseif ( mw_theme_option('color_scheme') == 'Purple' ) {
                        $logo_img = get_bloginfo('template_directory') . '/img/logo-purple.png';
                    } else {
                        $logo_img = get_bloginfo('template_directory') . '/img/logo.png';
                    }
                }
                ?>
                <a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><img src="<?php echo $logo_img; ?>" alt="<?php bloginfo('name'); ?>" <?php echo $logo_style; ?> class="logo"/></a>
            <?php endif; ?>
            
            <?php
            if ( mw_theme_option('show_top_nav') == 'Yes' ) {
                wp_nav_menu(array(
                    'theme_location' => 'top-nav',
                    'depth' => 1,
                    'sort_column' => 'menu_order',
                    'container' => 'div', 
                    'container_class' => 'top-nav', 
                    'container_id' => 'top-nav', 
                    'menu_class' => 'top-nav-list'
                ));
            }
            ?>
            <?php
            wp_nav_menu(array(
                'theme_location' => 'main-nav',
                'sort_column' => 'menu_order',
                'container' => 'div', 
                'container_class' => 'main-nav', 
                'container_id' => 'main-nav', 
                'menu_class' => 'main-nav-list root',
                'walker' => new MW_main_nav_walker()
            ));
            ?>
        </div>

    </div>
    
    <!-- End of header -->
    
    <b class="grid-divider"></b>
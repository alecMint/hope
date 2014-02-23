<?php if (!defined('MW_THEME')) die('No direct script access allowed'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<title><?php
	/**
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( ' - ', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " - $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' - ' . sprintf( __( 'Page %s', 'mw_theme' ), max( $paged, $page ) );

?></title>

<link rel="shortcut icon" href="favicon.ico" />

<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/css/template.css" />

<!-- Load CSS fixes for IE7 -->
<!--[if IE 7]>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/css/ie7.css" />
<![endif]-->

<?php if ( mw_theme_option('color_scheme') == 'Red + Teal' ): ?>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/css/colors/red-teal.css" />
<?php elseif ( mw_theme_option('color_scheme') == 'Green' ): ?>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/css/colors/green.css" />
<?php elseif ( mw_theme_option('color_scheme') == 'Gold' ): ?>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/css/colors/gold.css" />
<?php elseif ( mw_theme_option('color_scheme') == 'Purple' ): ?>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/css/colors/purple.css" />
<?php endif; ?>


<!-- Load font from Google Fonts API -->
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=PT+Serif:regular,italic,bold,bolditalic" />

<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/css/prettyPhoto.css" />

<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php echo MW_Dynamic_Styles::get_instance()->load_dynamic_css(); ?>

<?php
//get_template_part('template-parts/mod-dynamic-styles');
if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) wp_enqueue_script('comment-reply');
wp_head();
?>

<?php if ( mw_theme_option('custom_css') != '' ): ?>
<style type="text/css">
    <?php echo mw_theme_option('custom_css'); ?>
</style>
<?php endif; ?>

</head>
<?php flush(); ?>
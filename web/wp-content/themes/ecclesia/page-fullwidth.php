<?php if (!defined('MW_THEME')) die('No direct script access allowed');

/*
Template Name: Full-width Page
*/

get_header();
?>

<!-- Main content -->

<div class="grid-wrap">
    <div class="grid-6">
        
        <?php if(have_posts()) : while(have_posts()) : the_post(); ?>
            <h1><?php the_title() ?></h1>
            <?php the_content(); ?>
        
        <?php endwhile; else : ?>
            <?php get_template_part( 'not-found' ); ?>
        <?php endif; ?>
    
    </div>
</div>

<!-- End of main content -->

<?php get_footer(); ?>
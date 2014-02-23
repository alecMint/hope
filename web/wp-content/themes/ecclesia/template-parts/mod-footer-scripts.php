<?php if (!defined('MW_THEME')) die('No direct script access allowed'); ?>


<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/slides.min.jquery.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.plugins.js"></script>

<script type="text/javascript">
jQuery(document).ready(function(){

    /* Main navigation */
    jQuery('#main-nav').pragmaNavigation();
    
    /* Front page slider */
    jQuery("#front-slider").slides({
        preload: true,
        preloadImage: '<?php bloginfo('template_directory'); ?>/img/loading.gif',
        play: 5000,
        pause: 2500,
        hoverPause: true,
        pagination: false,
        generatePagination: false
    });
    
    /* Validate and submit contact forms using AJAX */
    jQuery("form.ajax-form").each(function(){
        var cform = jQuery(this);
        cform.validate({
            submitHandler: function(form) {
                var callback = function(ajaxResp) {
                    cform.before(ajaxResp).slideUp('slow');
                };
                var url = '<?php echo admin_url('admin-ajax.php'); ?>';
                var formData = jQuery(form).serialize();
                jQuery.post(url, formData, callback, 'html');
            }
        });
    });

});
</script>
/**
 * Add colour picker to custom widgets
 */
jQuery(document).ready(function() {
    jQuery('a.mw-widget-show-color-options').live('click', function(){
        jQuery(this).slideUp();
        show_colour_options(jQuery(this));
    });
    jQuery('input.mw-widget-boxed-radio').live('click', function(){
        show_colour_options(jQuery(this));
    });
    jQuery('input.mw-set-custom-styles-checkbox').live('click', function(){
        if( jQuery(this).is(':checked') ) {
            show_colour_options(jQuery(this));
        } else {
            hide_colour_options(jQuery(this));
        }
    });
    jQuery('input.mw-widget-unboxed-radio').live('click', function(){
        hide_colour_options(jQuery(this));
    });
    jQuery('input.mw-widget-colour-option').live('focus', function() {
        var colour_picker = jQuery(this).parent().siblings('div.mw-widget-colour-picker');
        var cp = jQuery.farbtastic(colour_picker);
        cp.linkTo(jQuery(this));
    });
    function show_colour_options(el){
        var display_options = el.parents('div.mw-widget-display-options');
        var boxed_radio_status = display_options.find('input.mw-widget-boxed-radio').is(':checked');
        var custom_styles_status = display_options.find('input.mw-set-custom-styles-checkbox').is(':checked');
        if( boxed_radio_status == true && custom_styles_status == true && !display_options.hasClass('custom-colors') ) {
            var bg_field = display_options.find('input.mw-widget-bg-color');
            var border_field = display_options.find('input.mw-widget-border-color');
            var colour_picker = display_options.find('div.mw-widget-colour-picker');
            var cp = jQuery.farbtastic(colour_picker);
            cp.linkTo(border_field);
            cp.linkTo(bg_field);
            display_options.addClass('custom-colors');
            display_options.children('p.mw-widget-show-color-options-link-container').slideUp();
            display_options.children('div.mw-widget-color-options').slideDown();
        }
    };
    function hide_colour_options(el){
        var display_options = el.parents('div.mw-widget-display-options');
        display_options.children('p.mw-widget-show-color-options-link-container').slideUp();
        display_options.children('div.mw-widget-color-options').slideUp();
        display_options.removeClass('custom-colors');
    };
});
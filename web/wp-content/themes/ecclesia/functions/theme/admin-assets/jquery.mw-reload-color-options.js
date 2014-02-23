/**
 * Reset color settings in the Options Panel when a color scheme is changed
 *  - fetch new colors from the server via AJAX
 *  - update color pickers
 */
jQuery(document).ready(function() {

    jQuery('#color_scheme').change(function(){
        var colorScheme = jQuery(this).val();
        jQuery.getJSON(
            ajaxurl,
            { action: 'mw_get_color_scheme_colors', color_scheme: colorScheme },
            function(data){
                jQuery.each(data, function(key,value){
                    jQuery('#'+key).val(value);
                    jQuery('#cp_'+key+' div').css({backgroundColor:value, borderColor:value});
                    jQuery('#desc_'+key).text(value);
                });
            }
        );
    });

});
<?php if (!defined('MW_THEME')) die('No direct script access allowed');

/**
 * Get all theme options
 */
function mw_theme_options() {
    return MW_Theme_Options::get_instance()->get_options_data();
}


/**
 * Get a single theme option
 */
function mw_theme_option( $id ) {
    return MW_Theme_Options::get_instance()->get_theme_option( $id );
}


/**
 * Get frontpage slider options
 */
function mw_get_slides () {
    $options = mw_theme_options();
    
    $slides = array();
    for ( $i = 1; $i <= 6; $i++ ) {
        $pfx = 'slide_' . $i;
        if ( isset($options[$pfx.'_img']) && $options[$pfx.'_img'] != '' ) {
            $slide = array( 'img' => $options[$pfx.'_img'] );
            if ( isset($options[$pfx.'_heading']) && $options[$pfx.'_heading'] != '' ) $slide['heading'] = $options[$pfx.'_heading'];
            if ( isset($options[$pfx.'_text']) && $options[$pfx.'_text'] != '' ) $slide['text'] = $options[$pfx.'_text'];
            if ( isset($options[$pfx.'_link']) && $options[$pfx.'_link'] != '' ) $slide['link'] = $options[$pfx.'_link'];
            $slides[] = $slide;
        }
    }
    
    return $slides;
}


/**
 * Display pagination
 */
function mw_pagination ( $pages = NULL, $range = 2 ) {
    global $wp_query, $paged;
    
    if ( empty($paged) ) $paged = 1;
    
    $showitems = ($range * 2)+1;  
    
    if ( $pages == NULL ) {
        $pages = $wp_query->max_num_pages;
        if(!$pages) {
            $pages = 1;
        }
    }   
    
    if( $pages > 1 ) {
        echo '<div class="pagination">';
        
        if( $paged > 2 && $paged > $range+1 && $showitems < $pages )
            printf( '<a href="%s">&laquo; %s</a>', get_pagenum_link(1), __('First', 'mw_theme') );
            
        if( $paged > 1 && $showitems < $pages )
            printf( '<a href="%s">&lsaquo; %s</a>', get_pagenum_link( $paged - 1 ), __('Previous', 'mw_theme') );

        for ( $i=1; $i <= $pages; $i++ ) {
            if ( 1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ) ) {
                if ( $paged == $i ) {
                    echo '<span class="current">' . $i . '</span>';
                } else {
                    echo '<a href="' . get_pagenum_link($i) . '">' . $i . '</a>';
                }
            }
        }

        if ( $paged < $pages && $showitems < $pages )
            printf( '<a href="%s">%s &rsaquo;</a>', get_pagenum_link( $paged + 1 ), __('Next', 'mw_theme') );
        
        if ( $paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages )
            printf( '<a href="%s">%s &raquo;</a>', get_pagenum_link( $pages ), __('Last', 'mw_theme') );
        
        echo "</div>\n";
    }
}


/**
 * Extends the WP class Walker_Nav_Menu and overloads 2 methods: start_lvl and start_el
 * Add classes and id's to menu lists and list items
 */
class MW_main_nav_walker extends Walker_Nav_Menu
{
	function start_lvl(&$output, $depth)
    {
		$indent = str_repeat("\t", $depth);
        $level = ($depth == 0) ? '1' : 'n';
		$output .= "\n$indent<ul class=\"main-nav-list subnav-list level-{$level}\">\n";
	}
    
	function start_el(&$output, $item, $depth, $args)
    {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
		$class_names = ' class="main-nav-list-item ' . esc_attr( $class_names ) . '"';

		$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;
        
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}


/**
 *  Register contact form AJAX handler
 */
function mw_register_contact_form_ajax_handler ()
{
    add_action( 'wp_ajax_nopriv_mw-contact-form-submit', 'mw_contact_form_ajax_handler' );
    add_action( 'wp_ajax_mw-contact-form-submit', 'mw_contact_form_ajax_handler' );
}

/**
 *  Contact form AJAX handler
 */
function mw_contact_form_ajax_handler ()
{
    header( "Content-Type: text/html" );
    mw_theme_contact_form('ajax');
    die();
}

/**
 * Display contact form
 */
function mw_theme_contact_form ( $requestType = 'normal' ) {
    
    $requestType = in_array($requestType, array('normal', 'ajax')) ? $requestType : 'normal';
    
    $op = array(
        'contact_email'         => mw_theme_option('contact_email'),
        'contact_subject_pfx'   => mw_theme_option('contact_subject_pfx'),
        'contact_ajax_submit'   => mw_theme_option('contact_ajax_submit'),
        'contact_msg_thanks'    => mw_theme_option('contact_msg_thanks'),
        'contact_msg_error'     => mw_theme_option('contact_msg_error'),
        
        'contact_show_name'     => mw_theme_option('contact_show_name'),
        'contact_req_name'      => mw_theme_option('contact_req_name'),
        'contact_label_name'    => mw_theme_option('contact_label_name'),
        
        'contact_show_email'    => mw_theme_option('contact_show_email'),
        'contact_req_email'     => mw_theme_option('contact_req_email'),
        'contact_label_email'   => mw_theme_option('contact_label_email'),
        
        'contact_show_phone'    => mw_theme_option('contact_show_phone'),
        'contact_req_phone'     => mw_theme_option('contact_req_phone'),
        'contact_label_phone'   => mw_theme_option('contact_label_phone'),
        
        'contact_show_subject'  => mw_theme_option('contact_show_subject'),
        'contact_req_subject'   => mw_theme_option('contact_req_subject'),
        'contact_label_subject' => mw_theme_option('contact_label_subject'),
        
        'contact_label_message' => mw_theme_option('contact_label_message'),
        'contact_submit_btn_label' => mw_theme_option('contact_submit_btn_label')
    );
    
    $formOp = array();
    $formOp['emailTo'] = $op['contact_email'];
    $formOp['subject'] = $op['contact_subject_pfx'];
    
    $formOp['form_id'] = 'mw_contact_form';
    $formOp['ajax_action'] = 'mw-contact-form-submit';
    $formOp['nonce'] = 'mw_contact_form_submit';
    $formOp['ajax'] = $op['contact_ajax_submit']=='Yes' ? TRUE : FALSE;
    $formOp['error_msg'] = $op['contact_msg_error'];
    $formOp['thanks_msg'] = $op['contact_msg_thanks'];
    $formOp['submit_label'] = $op['contact_submit_btn_label'];
    
    if( $op['contact_show_name'] == 'Yes' ) {
        $rules = array();
        if ($op['contact_req_name']=='Yes') $rules[] = 'required';
        $formOp['fields'][] = array( 'name'=>'contact_name', 'type'=>'input', 'label'=>$op['contact_label_name'], 'rules'=>$rules );
    }
    if( $op['contact_show_email'] == 'Yes' ) {
        $rules = array('email');
        if ($op['contact_req_email']=='Yes') $rules[] = 'required';
        $formOp['fields'][] = array( 'name'=>'contact_email', 'type'=>'input', 'label'=>$op['contact_label_email'], 'rules'=>$rules );
    }
    if( $op['contact_show_phone'] == 'Yes' ) {
        $rules = array();
        if ($op['contact_req_phone']=='Yes') $rules[] = 'required';
        $formOp['fields'][] = array( 'name'=>'contact_phone', 'type'=>'input', 'label'=>$op['contact_label_phone'], 'rules'=>$rules );
    }
    if( $op['contact_show_subject'] == 'Yes' ) {
        $rules = array();
        if ($op['contact_req_subject']=='Yes') $rules[] = 'required';
        $formOp['fields'][] = array( 'name'=>'contact_subject', 'type'=>'input', 'label'=>$op['contact_label_subject'], 'rules'=>$rules );
    }
    $formOp['fields'][]= array( 'name'=>'contact_message', 'label'=>$op['contact_label_message'], 'type'=>'textarea', 'rules'=>array('required') );
    
    
    $form = new MW_Contact_Form($formOp);
    if ($form->isPosted()) {
        $form->getUserInput();
        if ($form->validate()) {
            $form->send();
        }
    }
    
    if ( $requestType == 'normal' )
        $form->outputForm();
    elseif ( $requestType == 'ajax' )
        $form->outputResultAjax();
    
}
<?php if (!defined('MW_THEME')) die('No direct script access allowed');

/**
 * *******************
 *  Custom shortcodes
 * *******************
 */
 

/**
 * Custom caption shortcode
 * Overrides the deafult WordPress caption. It solves the problem of 10px
 * added to the caption width in the original WordPress caption templete.
 */
add_filter ( 'img_caption_shortcode', 'mw_scode_img_caption_shortcode', 10, 3 );
function mw_scode_img_caption_shortcode ( $dummy, $attr, $content = null ) {
    extract(shortcode_atts(array(
        'id'	=> '',
        'align'	=> 'alignnone',
        'width'	=> '',
        'caption' => ''
    ), $attr));
    if ( 1 > (int) $width || empty($caption) ) return $content;
    $id = !empty($id) ? " id=\"{$id}\"" : '';
    return sprintf(
        '<div %s class="wp-caption %s" style="width: %spx">%s<p class="wp-caption-text">%s</p></div>',
        $id, esc_attr($align), $width, do_shortcode( $content ), $caption
    );
}


/**
 *  Column layouts
 */
add_shortcode('one_half',          'mw_scode_one_half');
add_shortcode('one_half_last',     'mw_scode_one_half_last');
add_shortcode('one_third',         'mw_scode_one_third');
add_shortcode('one_third_last',    'mw_scode_one_third_last');
add_shortcode('two_third',         'mw_scode_two_third');
add_shortcode('two_third_last',    'mw_scode_two_third_last');
add_shortcode('one_fourth',        'mw_scode_one_fourth');
add_shortcode('one_fourth_last',   'mw_scode_one_fourth_last');
add_shortcode('three_fourth',      'mw_scode_three_fourth');
add_shortcode('three_fourth_last', 'mw_scode_three_fourth_last');
function mw_scode_one_half ( $attr, $content=null ) {
    $content = preg_replace('/^<\/p>|<p>$/', '', trim($content));
    return '<div class="one-half">' . wptexturize(wpautop(do_shortcode($content))) . '</div>';
}
function mw_scode_one_half_last ( $attr, $content=null ) {
    $content = preg_replace('/^<\/p>|<p>$/', '', trim($content));
    return '<div class="one-half last">' . wptexturize(wpautop(do_shortcode($content))) . '</div><span class="clear"></span>';
}
function mw_scode_one_third ( $attr, $content=null ) {
    $content = preg_replace('/^<\/p>|<p>$/', '', trim($content));
    return '<div class="one-third">' . wptexturize(wpautop(do_shortcode($content))) . '</div>';
}
function mw_scode_one_third_last ( $attr, $content=null ) {
    $content = preg_replace('/^<\/p>|<p>$/', '', trim($content));
    return '<div class="one-third last">' . wptexturize(wpautop(do_shortcode($content))) . '</div><span class="clear"></span>';
}
function mw_scode_two_third ( $attr, $content=null ) {
    $content = preg_replace('/^<\/p>|<p>$/', '', trim($content));
    return '<div class="two-third">' . wptexturize(wpautop(do_shortcode($content))) . '</div>';
}
function mw_scode_two_third_last ( $attr, $content=null ) {
    $content = preg_replace('/^<\/p>|<p>$/', '', trim($content));
    return '<div class="two-third last">' . wptexturize(wpautop(do_shortcode($content))) . '</div><span class="clear"></span>';
}
function mw_scode_one_fourth ( $attr, $content=null ) {
    $content = preg_replace('/^<\/p>|<p>$/', '', trim($content));
    return '<div class="one-fourth">' . wptexturize(wpautop(do_shortcode($content))) . '</div>';
}
function mw_scode_one_fourth_last ( $attr, $content=null ) {
    $content = preg_replace('/^<\/p>|<p>$/', '', trim($content));
    return '<div class="one-fourth last">' . wptexturize(wpautop(do_shortcode($content))) . '</div><span class="clear"></span>';
}
function mw_scode_three_fourth ( $attr, $content=null ) {
    $content = preg_replace('/^<\/p>|<p>$/', '', trim($content));
    return '<div class="three-fourth">' . wptexturize(wpautop(do_shortcode($content))) . '</div>';
}
function mw_scode_three_fourth_last ( $attr, $content=null ) {
    $content = preg_replace('/^<\/p>|<p>$/', '', trim($content));
    return '<div class="three-fourth last">' . wptexturize(wpautop(do_shortcode($content))) . '</div><span class="clear"></span>';
}


/**
 *  Boxes - download, warning, info and note
 */

add_shortcode("ok_box", "mw_scode_ok_box");
add_shortcode("download_box", "mw_scode_download_box");
add_shortcode("warning_box", "mw_scode_warning_box");
add_shortcode("info_box", "mw_scode_info_box");
add_shortcode("note_box", "mw_scode_note_box");

function mw_scode_ok_box ($attr, $content = null) {
    return mw_scode_content_box_helper( 'ok-box', $attr, $content );
}
function mw_scode_download_box ($attr, $content = null) {
    return mw_scode_content_box_helper( 'download-box', $attr, $content );
}
function mw_scode_warning_box ($attr, $content = null) {
    return mw_scode_content_box_helper( 'warning-box', $attr, $content );
}
function mw_scode_info_box ($attr, $content = null) {
    return mw_scode_content_box_helper( 'info-box', $attr, $content );
}
function mw_scode_note_box ($attr, $content = null) {
    return mw_scode_content_box_helper( 'note-box', $attr, $content );
}

function mw_scode_content_box_helper ($boxClass, $attr, $content) {
	extract(shortcode_atts(array(
        "class" => '',
        "id" => ''
	), $attr));
    $class = !empty($class) ? $boxClass . ' ' .$class : $boxClass;
    $id = !empty($id) ? " id=\"{$id}\"" : '';
    return sprintf( '<div class="%s" %s>%s</div>', $class, $id, wptexturize(wpautop(do_shortcode($content))) );
}


/**
 *  Pullquotes
 */

add_shortcode("pullquote_right", "mw_scode_pullquote_right");
add_shortcode("pullquote_left", "mw_scode_pullquote_left");
add_shortcode("pullquote_center", "mw_scode_pullquote_center");

function mw_scode_pullquote_right ($attr, $content = null) {
    return mw_scode_quote_helper( 'pullquote-right', $attr, $content );
}
function mw_scode_pullquote_left ($attr, $content = null) {
    return mw_scode_quote_helper( 'pullquote-left', $attr, $content );
}
function mw_scode_pullquote_center ($attr, $content = null) {
    return mw_scode_quote_helper( 'pullquote-center', $attr, $content );
}

function mw_scode_quote_helper ($quoteClass, $attr, $content) {
	extract(shortcode_atts(array(
        "class" => '',
        "id" => '',
        "width" => '',
	), $attr));
    $class = empty($class) ? $quoteClass : $quoteClass . ' ' .$class;
    $id = empty($id) ? '' : " id=\"{$id}\"";
    $width = empty($width) ? '33%' : $width;
    return sprintf( '<blockquote class="%s"%s style="width:%s">%s</blockquote>', $class, $id, $width, wptexturize(wpautop(do_shortcode($content))) );
}


/**
 *  Lists
 */

add_shortcode("check_list","mw_scode_check_list");
add_shortcode("arrow_list","mw_scode_arrow_list");

function mw_scode_check_list ($attr, $content = null) {
    return mw_scode_list_helper( 'check-list', $attr, $content );
}
function mw_scode_arrow_list ($attr, $content = null) {
    return mw_scode_list_helper( 'arrow-list', $attr, $content );
}

function mw_scode_list_helper ($listClass, $attr, $content) {
	extract(shortcode_atts(array(
        "class" => '',
        "id" => ''
	), $attr));
    $class = empty($class) ? $listClass : $listClass . ' ' .$class;
    $id = empty($id) ? '' : " id=\"{$id}\"";
    $tag = sprintf('<ul class="%s" %s>', $class, $id);
    $content = str_replace('<ul>', $tag, $content);
    return wptexturize(wpautop(do_shortcode($content)));
}


/**
 *  Frames
 */

add_shortcode("frame_left","mw_scode_frame_left");
add_shortcode("frame_right","mw_scode_frame_right");
add_shortcode("frame_center","mw_scode_frame_center");

function mw_scode_frame_left ($attr, $content = null) {
    return mw_scode_frame_helper( 'frame-left', $attr, $content );
}
function mw_scode_frame_right ($attr, $content = null) {
    return mw_scode_frame_helper( 'frame-right', $attr, $content );
}
function mw_scode_frame_center ($attr, $content = null) {
    return mw_scode_frame_helper( 'frame-center', $attr, $content );
}

function mw_scode_frame_helper ($frameClass, $attr, $content) {
	extract(shortcode_atts(array(
        "class" => '',
        "id" => '',
        "width" => '',
	), $attr));
    $class = empty($class) ? $frameClass : $frameClass . ' ' .$class;
    $id = empty($id) ? '' : " id=\"{$id}\"";
    $width = empty($width) ? '33%' : $width;
    $content = preg_replace('/^<\/p>|<p>$/', '', trim($content));
    return sprintf( '<div class="%s"%s style="width:%s">%s</div>', $class, $id, $width, wptexturize(wpautop(do_shortcode($content))) );
}


/**
 *  Dividers
 */

add_shortcode("divider","mw_scode_divider");
function mw_scode_divider ($attr) {
	extract(shortcode_atts(array(
        "class" => '',
        "id" => ''
	), $attr));
    $class = empty($class) ? 'divider' : 'divider '.$class;
    $id = empty($id) ? '' : " id=\"{$id}\"";
    return sprintf( '<div class="%s"%s></div>', $class, $id );
}

add_shortcode("divider_top","mw_scode_divider_top");
function mw_scode_divider_top ($attr) {
	extract(shortcode_atts(array(
        "class" => '',
        "id" => ''
	), $attr));
    $class = empty($class) ? 'divider-top' : 'divider-top '.$class;
    $id = empty($id) ? '' : " id=\"{$id}\"";
    return sprintf( '<div class="%s"%s><a href="#">%s</a></div>', $class, $id, __('Top', 'mw_frm') );
}


/**
 *  Miscellaneous
 */

add_shortcode("button", "mw_scode_button");
function mw_scode_button ($attr, $content = null) {
	extract(shortcode_atts(array(
		"link" => '#',
        "class" => '',
        "id" => ''
	), $attr));
    $class = empty($class) ? 'link-btn' :  'link-btn '.$class;
    $id = empty($id) ? '' : " id=\"{$id}\"";
    return sprintf( '<a href="%s" class="%s"%s>%s</a>', $link, $class, $id, wptexturize($content) );
}

add_shortcode("contact_form", "mw_scode_contact_form");
function mw_scode_contact_form ($attr, $content = null) {
    ob_start();
    mw_theme_contact_form();
    $form = ob_get_contents();
    ob_end_clean();
    return $form;
}
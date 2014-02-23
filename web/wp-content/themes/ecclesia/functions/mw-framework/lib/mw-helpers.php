<?php

/**
 * ***************************
 * Library of helper functions
 * ***************************
 */

/* Message box */
function message ($message, $return = FALSE) {
    return outputElement( sprintf('<div class="updated"><p><strong>%s</strong></p></div>', $message) , $return);
}

/* Error message box */
function error_message ($message, $return = FALSE) {
    return outputElement( sprintf('<div class="error"><p><strong>%s</strong></p></div>', $message) , $return);
}

/* Anchor tag */
function anchor ($link, $text = NULL, $attr = NULL, $return = FALSE) {
    if ($text === NULL)
        $text = $link;
    if (isset($attr['title'])) {
        $title = form_esc($attr['title']);
        unset($attr['title']);
    } else {
        $title = form_esc($text);
    }
    $attributes = parseAttr($attr);
    $tag = '<a href="%s" title="%s"%s>%s</a>';
    $tag = sprintf($tag, $link, $title, $attributes, $text);
    return outputElement($tag , $return);
}

/* Image tag */
function img ($src, $attr = NULL, $return = FALSE) {
    $attributes = parseAttr($attr);
    $tag = '<img src="%s"%s />';
    $tag = sprintf($tag, $src, $attributes);
    return outputElement($tag , $return);
}

/* Link buttons */
function anchor_btn ($link, $text, $attr = NULL, $return = FALSE) {
    if (isset($attr['title'])) {
        $title = form_esc($attr['title']);
        unset($attr['title']);
    } else {
        $title = form_esc($text);
    }
    $class = getClass($attr);
    $attributes = parseAttr($attr);
    $tag = '<a href="%s" title="%s" class="button-primary%s"%s>%s</a>';
    $tag = sprintf($tag, $link, $title, $class, $attributes, $text);
    return outputElement($tag , $return);
}
function anchor_btn_2 ($link, $text, $attr = NULL, $return = FALSE) {
    if (isset($attr['title'])) {
        $title = form_esc($attr['title']);
        unset($attr['title']);
    } else {
        $title = form_esc($text);
    }
    $class = getClass($attr);
    $attributes = parseAttr($attr);
    $tag = '<a href="%s" title="%s" class="button-secondary%s"%s>%s</a>';
    $tag = sprintf($tag, $link, $title, $class, $attributes, $text);
    return outputElement($tag , $return);
}

/* Form label tag */
function form_label ($text, $forAttr, $attr = NULL, $return = FALSE) {
    $attributes = parseAttr($attr);
    $tag = '<label for="%s"%s>%s</label>';
    $tag = sprintf($tag, $forAttr, $attributes, $text);
    return outputElement($tag , $return);
}

/* Form hidden field */
function form_hidden ($name, $value, $attr = NULL, $return = FALSE) {
    $tag = '<input type="hidden" name="%s" value="%s"%s />';
    $tag = get_form_input_field ($tag, $name, $value, $attr);
    return outputElement($tag , $return);
}

/* Form input text field tag */
function form_input ($name, $value, $attr = NULL, $return = FALSE) {
    $tag = '<input type="text" name="%s" value="%s" class="%s"%s />';
    $tag = get_form_input_field ($tag, $name, $value, $attr);
    return outputElement($tag , $return);
}

/* Form submit button (primary) */
function form_submit ($name, $value, $attr = NULL, $return = FALSE) {
    $tag = '<input type="submit" name="%s" value="%s" class="button-primary%s"%s />';
    $tag = get_form_input_field ($tag, $name, $value, $attr);
    return outputElement($tag , $return);
}

/* Form submit button (secondary) */
function form_submit_2 ($name, $value, $attr = NULL, $return = FALSE) {
    $tag = '<input type="submit" name="%s" value="%s" class="button-secondary%s"%s />';
    $tag = get_form_input_field ($tag, $name, $value, $attr);
    return outputElement($tag , $return);
}

/* Generic form field function */
function get_form_input_field ($format, $name, $value, $attr = NULL) {
    $class = getClass($attr);
    $attributes = parseAttr($attr);
    $value = form_esc($value);
    return sprintf($format, $name, $value, $class, $attributes);
}

/* Form check box */
function form_checkbox ($name, $value, $checked = FALSE, $attr = NULL, $return = FALSE) {
    if ($checked===TRUE) { $attr['checked'] = 'checked'; }
    $attributes = parseAttr($attr);
    $value = form_esc($value);
    $tag = '<input type="checkbox" name="%s" value="%s"%s />';
    $tag = sprintf($tag, $name, $value, $attributes);
    return outputElement($tag , $return);
}

/* Form check box with label */
function form_checkbox_labeled ($name, $value, $label, $id, $checked = FALSE, $attr = NULL, $return = FALSE) {
    if ($checked===TRUE) { $attr['checked'] = 'checked'; }
    $attributes = parseAttr($attr);
    $value = form_esc($value);
    $tag = '<label for="%s"><input type="checkbox" name="%s" value="%s" id="%s"%s /> %s</label>';
    $tag = sprintf($tag, $id, $name, $value, $id, $attributes, $label);
    return outputElement($tag , $return);
}

/* Form radio btn */
function form_radio ($name, $value, $checked = FALSE, $attr = NULL, $return = FALSE) {
    if ($checked===TRUE) { $attr['checked'] = 'checked'; }
    $attributes = parseAttr($attr);
    $value = form_esc($value);
    $tag = '<input type="radio" name="%s" value="%s"%s />';
    $tag = sprintf($tag, $name, $value, $attributes);
    return outputElement($tag , $return);
}

/* Form radio btn with label */
function form_radio_labeled ($name, $value, $label, $id, $checked = FALSE, $attr = NULL, $return = FALSE) {
    if ($checked===TRUE) { $attr['checked'] = 'checked'; }
    $attributes = parseAttr($attr);
    $value = form_esc($value);
    $tag = '<label for="%s"><input type="radio" name="%s" value="%s" id="%s"%s /> %s</label>';
    $tag = sprintf($tag, $id, $name, $value, $id, $attributes, $label);
    return outputElement($tag , $return);
}

/* Form dropdown box with options */
function form_dropdown ($name, $options, $selectedValue, $attr = NULL, $return = FALSE) {
    $opTags = '';
    foreach ($options as $value=>$text) {
        $selected = ((string)$selectedValue == (string)$value) ? ' selected="selected"' : '';
        $tag = '<option value="%s"%s>%s</option>';
        $tag = sprintf($tag, $value, $selected, $text);
        $opTags .= $tag;
    }
    $attributes = parseAttr($attr);
    $tag = '<select name="%s"%s>%s</select>';
    $tag = sprintf($tag, $name, $attributes, $opTags);
    return outputElement($tag , $return);
}

/* Form textarea tag */
function form_textarea($name, $text, $attr = NULL, $return = FALSE) {
    $class = getClass($attr);
    $attributes = parseAttr($attr);
    $text = form_esc($text);
    $tag = '<textarea name="%s" class="large-text code%s"%s>%s</textarea>';
    $tag = sprintf($tag, $name, $class, $attributes, $text);
    return outputElement($tag , $return);
}



/**
 *  Utility functions used by the helpers
 */

/* Format text so that it can be safely placed in a form field in the event it has HTML tags */
function form_esc ($str = '') {
    $str = htmlspecialchars($str);
    $str = str_replace(array("'", '"'), array("&#39;", "&quot;"), $str); // In case htmlspecialchars missed these
    return $str;
}

/* Echo or return an element */
function outputElement ($element, $return = FALSE) {
    if ($return === true) {
        return $element;
    } else {
        echo $element;
        return TRUE;
    }
}

/* Parse element attributes (array to string) */
function parseAttr ($attr) {
    $attributes = '';
    if (is_array($attr)) {
        foreach ( $attr as $attrName=>$attrValue ) {
            $attributes .= " {$attrName}=\"{$attrValue}\"";
        }
    }
    return $attributes;
}

/* Get class attribute from an array of attributes */
function getClass (&$attr) {
    $class = '';
    if (isset($attr['class'])) {
        $class .= ' ' . $attr['class'];
        unset($attr['class']);
    }
    return $class;
}

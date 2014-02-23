<?php

/**
 *  Extends the core WprdPress WP_Widget classs adding
 *  new functionality commonly used in our widgets
 */

class MW_Widget extends WP_Widget
{
    public $isCachable = FALSE;
    public $wpCacheKey = 'mw_widget';
    public $wpCacheCopy = null;
    public $default = array();
    
    /**
     * Initiate the widget
     */
    public function setupWidget ($attr)
    {
        $widgetWidth = isset($attr['width']) ? $attr['width'] : 400;
        $widgetHeight = isset($attr['height']) ? $attr['height'] : 300;
        $widgetOps = array('classname' => $attr['classname'], 'description' => $attr['description']);
        $controlOps = array('id_base' => $attr['id_base'], 'width' => $widgetWidth, 'height' => $widgetHeight);
        $this->WP_Widget($attr['id_base'], $attr['name'], $widgetOps, $controlOps);
        
        if ($this->isCachable === TRUE)
            $this->flushWpCacheActions();
    }
    
    /**
     * Set widget options to default values
     */
    function setDefalutValues ( $instance )
    {
        foreach ($this->default as $key=>$val) {
            $instance[$key] = (isset($instance[$key]) && $instance[$key] != '') ? $instance[$key] : $val;
        }
        return $instance;
    }
    
    /**
     * Update widget options
     */
    function updateWidgetOptions ( $newInstance, $oldInstance )
    {
        $instance = $oldInstance;
        foreach (array_keys($this->default) as $key) {
            $instance[$key] = $newInstance[$key];
        }
        return $instance;
    }
    
    /**
     * Update widget diplay options
     */
	function updateWidgetDisplayOptions ( $instance, $newInstance )
    {
        $instance['show_widget_title'] = (isset($newInstance['show_widget_title']) && $newInstance['show_widget_title'] == 'yes') ? 'yes' : 'no';
        $instance['show_boxed'] = (isset($newInstance['show_boxed']) && $newInstance['show_boxed'] == 'yes') ? 'yes' : 'no';
        $instance['set_custom_styles'] = (isset($newInstance['set_custom_styles']) && $newInstance['set_custom_styles'] == 'yes') ? 'yes' : 'no';
        $instance['bg_color'] = $newInstance['bg_color'];
        $instance['border_color'] = $newInstance['border_color'];
        return $instance;
    }
    
    /**
     * Alias for the WP_Widget::get_field_name() method
     */
	function fnm ( $field_name )
    {
        return parent::get_field_name($field_name);
	}
    
    /**
     * Alias for the WP_Widget::get_field_id() method
     */
	function fid ( $field_name )
    {
        return parent::get_field_id($field_name);
	}
    
    /**
     * Returns a form with the widget diplay options
     */
	function widgetDisplayOptionsForm ( $instance )
    {
        /* Get checkbox states */
        $titleChbox = (!isset($instance['show_widget_title']) || $instance['show_widget_title'] == 'yes') ? TRUE : FALSE;
        $radioBoxed = (!isset($instance['show_boxed']) || $instance['show_boxed'] == 'yes') ? TRUE : FALSE;
        $radioUnboxed = (isset($instance['show_boxed']) && $instance['show_boxed'] == 'no') ? TRUE : FALSE;
        $colorsChbox = (!isset($instance['set_custom_styles']) || $instance['set_custom_styles'] == 'yes') ? TRUE : FALSE;
        
        $no_custom_colors_class = ($radioUnboxed || $colorsChbox === FALSE) ? ' no-custom-colors' : '';
        
        $html .= '<div class="mw-widget-display-options' . $no_custom_colors_class . '">';
        $html .= '<h4>' . __('Widget display options', 'mw_frm') . '</h4><p>';
        $html .= form_checkbox_labeled($this->fnm('show_widget_title'), 'yes', __('Show widget title', 'mw_frm'), $this->fid('show_widget_title'), $titleChbox, NULL, TRUE);
        $html .= '</p><p>';
        $html .= form_radio_labeled($this->fnm('show_boxed'), 'yes', __('Boxed', 'mw_frm'), $this->fid('show_boxed'), $radioBoxed, array('class'=>'mw-widget-boxed-radio'), TRUE);
        $html .= ' &nbsp; ';
        $html .= form_radio_labeled($this->fnm('show_boxed'), 'no', __('Un-boxed', 'mw_frm'), $this->fid('show_unboxed'), $radioUnboxed, array('class'=>'mw-widget-unboxed-radio'), TRUE);
        $html .= '</p><p>';
        $html .= form_checkbox_labeled($this->fnm('set_custom_styles'), 'yes', __('Set individual colors (<em>boxed</em> widget only)', 'mw_frm'), $this->fid('set_custom_styles'), $colorsChbox, array('class'=>'mw-set-custom-styles-checkbox'), TRUE);
        $html .= '</p><p class="mw-widget-show-color-options-link-container">';
        $html .= anchor('#', __('Show colour options', 'mw_frm'), array('class'=>'mw-widget-show-color-options'), TRUE);
        $html .= '</p>';
        $html .= '<div class="mw-widget-color-options">';
        $html .= '<div class="mw-widget-colour-picker"></div>';
        $html .= '<h4>' . __('Colour options', 'mw_frm') . '</h4><p>';
        $html .= form_label(__('Background colour:', 'mw_frm'), $this->fid('bg-color'), NULL, TRUE);
        $html .= form_input($this->fnm('bg_color'), $instance['bg_color'], array('id'=>$this->fid('bg-color'), 'class'=>'mw-widget-bg-color mw-widget-colour-option'), TRUE);
        $html .= '</p><p>';
        $html .= form_label(__('Border colour:', 'mw_frm'), $this->fid('border-color'), NULL, TRUE);
        $html .= form_input($this->fnm('border_color'), $instance['border_color'], array('id'=>$this->fid('border-color'), 'class'=>'mw-widget-border-color mw-widget-colour-option'), TRUE);
        $html .= '</p>';
        $html .= '<p class="mw-widget-colour-comment">' . __('1) Click inside a field above<br/>2) Select colour using the wheel on the right.', 'mw_frm') . '</p>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Get default values for widget style options
     */
    function get_default_widget_style ( $style_id )
    {
        $use_theme_options = MW_Theme_Options::get_instance()->get_theme_option( 'use_custom_widget_styles' );
        
        if ( $use_theme_options == 'Yes' ) {
            // Get style value from theme options
            return MW_Theme_Options::get_instance()->get_theme_option( $style_id );
        } else {
            // Get default style value from dynamic styles
            return MW_Dynamic_Styles::get_instance()->get_default_style( $style_id );
        }
    }
    
    /**
     * Wrap widget content and title in widget HTML structure and return it or output to the browser
     */
    function makeWidget ( $widgetContent, $instance, $sidebarArgs, $return = FALSE )
    {
        extract($sidebarArgs);
        
        $widgetTitle = (isset($instance['title']) && $instance['title'] != '') ? $instance['title'] : '';
        
        if ($instance['show_widget_title'] == 'yes') {
            $widgetTitle = apply_filters('widget_title', $widgetTitle );
            $widgetHead = '<div class="widget-head">' . $before_title . $widgetTitle . $after_title . '</div>';
        } else {
            $widgetHead = '';
        }
        
        $widgetBody = '<div class="widget-body">' . $widgetContent . '</div>';
        
        $widget = $widgetHead . $widgetBody;
        
        $headClass = ($instance['show_widget_title'] == 'yes') ? 'has-head' : 'no-head';
        $boxedClass = ($instance['show_boxed'] == 'yes') ? 'widget-boxed' : 'widget-unboxed';
        $customStyles = '';
        if ( $instance['show_boxed'] == 'yes' && $instance['set_custom_styles'] == 'yes' ) {
            $customStyles = sprintf( ' style="background-color:%s; border-color:%s"', $instance['bg_color'], $instance['border_color'] );
        }
        $widget = sprintf('<div class="%s %s"%s>%s</div>', $boxedClass, $headClass, $customStyles, $widget);
        
        $widget = $before_widget . $widget . $after_widget;
        
        /* Return or echo the widget */
        if ( $return === TRUE ) {
            return $widget;
        } else {
            $this->outputWidget($widget);
            return TRUE;
        }
    }
    
    /**
     * If widget is setup to use the Wp Object Cache use this to get the widget
     */
    public function getCachableWidget ( $instance, $sidebarArgs, $return = FALSE )
    {
        $widget = '';
        $widgetId = $sidebarArgs['widget_id'];
        
        if ( $this->isInWpCache($widgetId) ) {
            $widget = $this->getFromWpCache($widgetId);
        } else {
            $widgetContent = $this->getWidgetContent($instance);
            $widget = $this->makeWidget( $widgetContent, $instance, $sidebarArgs, TRUE );
            $this->setWpCache($widgetId, $widget);
        }
        
        /* Return or output the widget */
        if ($return === true) {
            return $widget;
        } else {
            $this->outputWidget($widget);
            return TRUE;
        }
    }
    
	/**
	 * Get the widget content.
	 * Subclasses must override this function.
	 */
	function getWidgetContent ($instance)
    {
		die('function MW_Widget::getWidgetContent() must be overridden in a sub-class.');
	}
    
    /**
     * Echo widget control form to the browser
     */
    public function outputWidgetControl ($html)
    {
        echo $html;
    }
    
    /**
     * Run filters and echo widget to the browser
     */
    public function outputWidget ($widget)
    {
        echo $widget;
    }
    
    
    /**
     *  ********************************************************
     *
     *  WP OBJECT CACHE
     *
     *  Below methods provide interface to the WP_Object_Cache class. 
     *  According to Codex the purpose of this class is "caching data 
     *  which may be computationally expensive to regenerate, such as 
     *  the result of complex database queries."
     *  IMPORTANT: Please note this is NOT persistent cache!
     *  For more information on WP Cache look here:
     *  http://codex.wordpress.org/Function_Reference/WP_Cache
     *  ********************************************************
     */
    
    public function setWpCacheCopy ()
    {
        if ( !isset($this->wpCacheCopy) ) {
            $wpCache = wp_cache_get($this->wpCacheKey, 'widget');
            $this->wpCacheCopy = is_array($wpCache) ? $wpCache : array();
        }
    }
    
    public function isInWpCache ($widgetId)
    {
        $this->setWpCacheCopy;
        return isset($this->wpCacheCopy[$widgetId]) ? TRUE : FALSE;
    }
    
    public function getFromWpCache ($widgetId)
    {
        if ($this->isInWpCache($widgetId))
            return $this->wpCacheCopy[$widgetId];
        else
            return FALSE;
    }
    
    public function setWpCache ($widgetId, $widget)
    {
        $this->setWpCacheCopy;
        $this->wpCacheCopy[$widgetId] = $widget;
        wp_cache_set($this->wpCacheKey, $this->setWpCacheCopy, 'widget');
    }
    
    function flushWidgetCache()
    {
        wp_cache_delete($this->wpCacheKey, 'widget');
    }
    
    public function flushWpCacheActions ()
    {
        add_action( 'save_post', array(&$this, 'flushWidgetCache') );
        add_action( 'deleted_post', array(&$this, 'flushWidgetCache') );
        add_action( 'switch_theme', array(&$this, 'flushWidgetCache') );
    }
}
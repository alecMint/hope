<?php if (!defined('MW_THEME')) die('No direct script access allowed');

/**
 *  *********************************
 *   Display list of upcoming events
 *  *********************************
 */
class MW_Widget_Upcoming_Events extends MW_Widget
{
    public $isCachable = TRUE;
    public $wpCacheKey = 'mw_widget_upcoming_events';
    
    /**
     * Widget setup.
     */
    public function MW_Widget_Upcoming_Events ()
    {
        $this->setupWidget(array(
            'name' => __('MW - Upcoming Events', 'mw_theme'),
            'description' => __("Display upcoming events", 'mw_theme'),
            'classname' => 'mw-widget-upcoming-events',
            'id_base' => 'mw-widget-upcoming-events'
        ));
        
        $this->default = array(
            'title' => __('Upcoming events', 'mw_theme'),
            'number' => 5,
            'show_boxed' => 'no',
            'set_custom_styles' => 'no',
            'bg_color' => $this->get_default_widget_style('widget_bg_col'),
            'border_color' => $this->get_default_widget_style('widget_border_col')
        );
    }
    
    /**
     * Display widget on the frontend.
     */
    public function widget ($sidebarArgs, $instance)
    {
        $this->getCachableWidget( $instance, $sidebarArgs ); // uses the getWidgetContent method below to get the actual widget content
    }
    
    /**
     * Get the content for the widget
     */
    public function getWidgetContent ($instance)
    {
        $instance = $this->setDefalutValues( $instance );
        
        $time_format = get_option('time_format');
        $events = MW_Events::get_instance()->get_events();
        
        $widgetContent = '';
        
        if ( count($events) > 0 ) {
            $counter = 1;
            foreach ( $events as $event ) {
                $date = sprintf(
                    '<div class="event-date-container"><span class="day-month">%s</span><span class="day-week">%s</span></div>',
                    date_i18n('d', $event->start_date), date_i18n('M', $event->start_date)
                );
                $info = sprintf(
                    '<div class="event-info-container"><a href="%s">%s</a><span class="event-time">%s: %s &ndash; %s</span></div>',
                    get_permalink($event->ID), $event->post_title,
                    __('Time', 'mw_theme'), date_i18n($time_format, $event->start_date), date_i18n($time_format, $event->end_date)
                );
                $widgetContent .= '<div class="mw-widget-event">' . $date . $info . '</div>';
                
                if ( $counter == $instance['number'] ) break;
                $counter++;
            }
            
            if ( count($events) > $instance['number'] ) {
                $widgetContent .= sprintf(
                    '<div class="view-all"><a href="%s">%s</a></div>',
                    home_url('/events/'), __('View all events &rsaquo;', 'mw_theme')
                );
            }
        } else {
            $widgetContent = __('We don\'t have any events scheduled at the moment.', 'mw_theme');
        }
        
        return $widgetContent;
    }
    
    /**
     * Update the widget settings.
     */
    public function update ($newIns, $oldIns)
    {
        $newIns['number'] = is_numeric($newIns['number']) ? absint($newIns['number']) : $this->default['number'];
        
        if ( $newIns['number'] < 1 )
            $newIns['number'] = 1;
        elseif ( $newIns['number'] > 50 )
            $newIns['number'] = 50;
        
        $instance = $oldIns;
        $instance['title'] = $newIns['title'];
        $instance['number'] = (int) $newIns['number'];
        $instance = $this->updateWidgetDisplayOptions($instance, $newIns);
        $this->flushWidgetCache();
        return $instance;
    }
    
    /**
     * Display widget settings controls on the widget panel.
     */
    public function form ($instance)
    {
        $instance = $this->setDefalutValues( $instance );
        
        $html  = '<p>';
        $html .= form_label(__('Title:', 'mw_theme'), $this->fid('title'), NULL, TRUE);
        $html .= form_input($this->fnm('title'), $instance['title'], array('id'=>$this->fid('title'), 'class'=>'widefat'), TRUE);
        $html .= '</p><p>';
        $html .= form_label(__('Number of events to show:', 'mw_theme'), $this->fid('number'), NULL, TRUE) . ' ';
        $html .= form_input($this->fnm('number'), $instance['number'], array('id'=>$this->fid('number'), 'size'=>'3'), TRUE);
        $html .= '</p>';
        $html .= $this->widgetDisplayOptionsForm($instance);
        $this->outputWidgetControl($html);
    }
}
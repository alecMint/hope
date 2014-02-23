<?php if (!defined('MW_THEME')) die('No direct script access allowed');

/**
 *  **********************************
 *   Load and register custom widgets
 *  **********************************
 */

/**
 * Add actions to load widget styles/js and register custom widgets
 */
function mw_load_custom_widgets () {
    add_action( 'admin_enqueue_scripts', 'mw_load_custom_widget_assets' );
    add_action( 'widgets_init', 'mw_register_custom_widgets' );
}

/**
 * Load widget admin styles and js
 */
function mw_load_custom_widget_assets () {
    wp_enqueue_script( 'mw-farbtastic-color-picker', MW_FRM_URL.'admin-assets/js/jquery.farbtastic-color-picker/farbtastic.js', array('jquery') );
    wp_enqueue_style( 'mw-farbtastic-styles', MW_FRM_URL.'admin-assets/js/jquery.farbtastic-color-picker/farbtastic.css');
    wp_enqueue_script( 'mw-widgets-js', MW_FRM_URL.'admin-assets/js/jquery.widgets.js', array('jquery'));
    wp_enqueue_style( 'mw-widgets-styles', MW_FRM_URL.'admin-assets/css/widgets.css');
}

/**
 * Register custom widgets
 */
function mw_register_custom_widgets () {

    /** Replace WordPress widgets */
    register_widget( 'MW_Widget_Custom_Content' );
    register_widget( 'MW_Widget_Latest_Posts' );
    register_widget( 'MW_Widget_Pages' );
    register_widget( 'MW_Widget_Links' );
    register_widget( 'MW_Widget_Archives' );
    register_widget( 'MW_Widget_Categories' );
    register_widget( 'MW_Widget_Comments' );
    register_widget( 'MW_Widget_Tag_Cloud' );
    register_widget( 'MW_Widget_Calendar' );
    register_widget( 'MW_Widget_Search' );
    register_widget( 'MW_Widget_Meta' );
    register_widget( 'MW_Widget_Nav_Menu' );
    
    /** Add new custom widgets */
    register_widget( 'MW_Widget_WP_Page' );
    register_widget( 'MW_Widget_Twitter_Feed' );
    register_widget( 'MW_Widget_Contact_Form' );
    register_widget( 'MW_Widget_Follow' );
    register_widget( 'MW_Widget_Flickr' );
}

/**
 *  ==============================================
 *                 WIDGET CLASSES
 *  ==============================================
 */


/**
 *  **********************
 *  Flickr photos
 *  **********************
 */
class MW_Widget_Flickr extends MW_Widget
{
    /**
     * Widget setup.
     */
    function MW_Widget_Flickr ()
    {
        $this->setupWidget(array(
            'name' => __('MW - Flickr', 'mw_frm'),
            'description' => __("Your latest photos from Flickr", 'mw_frm'),
            'classname' => 'mw-widget-flickr',
            'id_base' => 'mw-widget-flickr',
            'width' => 400,
            'height' => 400
        ));
        
        $this->default = array(
            'title' => __('Photos on Flickr', 'mw_frm'),
            'flickr_id' => '',
            'number' => 6,
            'show_boxed' => 'no',
            'set_custom_styles' => 'no',
            'bg_color' => $this->get_default_widget_style('widget_bg_col'),
            'border_color' => $this->get_default_widget_style('widget_border_col'),
        );
    }
    
    /**
     * Display widget on the frontend.
     */
    function widget ($sidebarArgs, $instance)
    {
        $widgetContent = '<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=' . $instance['number'] . '&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=' . $instance['flickr_id'] . '"></script>';
        $this->makeWidget( $widgetContent, $instance, $sidebarArgs );
    }
    
    /**
     * Update the widget settings.
     */
    public function update ($newIns, $oldIns)
    {
        $newIns['number'] = is_numeric($newIns['number']) ? absint($newIns['number']) : $this->default['number'];
        
        if ( $newIns['number'] < 1 )
            $newIns['number'] = 1;
        elseif ( $newIns['number'] > 100 )
            $newIns['number'] = 100;
        
        $instance = $this->updateWidgetOptions( $newIns, $oldIns );
        $instance = $this->updateWidgetDisplayOptions($instance, $newIns);
        $this->flushWidgetCache();
        return $instance;
    }

    /**
     * Display widget settings controls on the widget panel.
     */
    function form ($instance)
    {
        $instance = $this->setDefalutValues( $instance );
        $html  = '<p>';
        $html .= form_label(__('Title:', 'mw_frm'), $this->fid('title'), NULL, TRUE);
        $html .= form_input($this->fnm('title'), $instance['title'], array('id'=>$this->fid('title'), 'class'=>'widefat'), TRUE);
        $html .= '</p><p>';
        $html .= form_label(__('Flickr ID (<a href="http://www.idgettr.com">idGettr</a>):', 'mw_frm'), $this->fid('flickr_id'), NULL, TRUE);
        $html .= form_input($this->fnm('flickr_id'), $instance['flickr_id'], array('id'=>$this->fid('flickr_id'), 'class'=>'widefat'), TRUE);
        $html .= '</p><p>';
        $html .= form_label(__('Number of photos:', 'mw_frm'), $this->fid('number'), NULL, TRUE);
        $html .= form_input($this->fnm('number'), $instance['number'], array('id'=>$this->fid('number'), 'class'=>'widefat'), TRUE);
        $html .= '</p>';
        $html .= $this->widgetDisplayOptionsForm($instance);
        $this->outputWidgetControl($html);
    }
}


/**
 *  ************************************************
 *  Widget which displays content of a selected page
 *  ************************************************
 */
class MW_Widget_WP_Page extends MW_Widget
{
    /**
     * Widget setup.
     */
    function MW_Widget_WP_Page ()
    {
        $this->setupWidget(array(
            'name' => __('MW - WordPress Page Content', 'mw_frm'),
            'description' => __("Display content from any WordPress page", 'mw_frm'),
            'classname' => 'mw-widget-wp-page',
            'id_base' => 'mw-widget-wp-page'
        ));
        
        $this->default = array(
            'title' => '',
            'wp_page' => 'none',
            'show_boxed' => 'no',
            'set_custom_styles' => 'no',
            'bg_color' => $this->get_default_widget_style('widget_bg_col'),
            'border_color' => $this->get_default_widget_style('widget_border_col')
        );
    }
    
    /**
     * Display widget on the frontend.
     */
    function widget ($sidebarArgs, $instance)
    {
        $widgetContent = '';
        if ( $instance['wp_page'] != 'none' ) {
            $page = get_page( $instance['wp_page'] );
            $widgetContent = apply_filters('the_content', $page->post_content); 
        }
        $this->makeWidget( $widgetContent, $instance, $sidebarArgs );
    }
    
    /**
     * Update the widget settings.
     */
    public function update ($newIns, $oldIns)
    {
        $newIns = $this->setDefalutValues( $newIns );
        $instance = $this->updateWidgetOptions( $newIns, $oldIns );
        $instance = $this->updateWidgetDisplayOptions( $instance, $newIns );
        return $instance;
    }

    /**
     * Display widget settings controls on the widget panel.
     */
    function form ($instance)
    {
        $instance = $this->setDefalutValues( $instance );
        $wp_pages = mw_get_pages_array( array( 'none' => '-----') );
        $html  = '<p>';
        $html .= form_label(__('Title:', 'mw_frm'), $this->fid('title'), NULL, TRUE);
        $html .= form_input($this->fnm('title'), $instance['title'], array('id'=>$this->fid('title'), 'class'=>'widefat'), TRUE);
        $html .= '</p><p>';
        $html .= form_label(__('Select your page:', 'mw_frm'), $this->fid('wp_page'), NULL, TRUE);
        $html .= form_dropdown($this->fnm('wp_page'), $wp_pages, $instance['wp_page'], array('id'=>$this->fid('wp_page'), 'class'=>'widefat'), TRUE);
        $html .= '</p>';
        $html .= $this->widgetDisplayOptionsForm($instance);
        $this->outputWidgetControl($html);
    }
}
    
    

/**
 *  **********************
 *  Display custom content
 *  **********************
 */
class MW_Widget_Custom_Content extends MW_Widget
{
    /**
     * Widget setup.
     */
    function MW_Widget_Custom_Content ()
    {
        $this->setupWidget(array(
            'name' => __('MW - Custom Content', 'mw_frm'),
            'description' => __("Display any content (text, HTML, JavaScript, etc.)", 'mw_frm'),
            'classname' => 'mw-widget-custom-content',
            'id_base' => 'mw-widget-custom-content',
            'width' => 400,
            'height' => 400
        ));
        
        $this->default = array(
            'title' => '',
            'text' => '',
            'show_boxed' => 'no',
            'set_custom_styles' => 'no',
            'bg_color' => $this->get_default_widget_style('widget_bg_col'),
            'border_color' => $this->get_default_widget_style('widget_border_col'),
        );
    }
    
    /**
     * Display widget on the frontend.
     */
    function widget ($sidebarArgs, $instance)
    {
        $widgetContent = $instance['text'];
        $widgetContent = do_shortcode($widgetContent);
        $this->makeWidget( $widgetContent, $instance, $sidebarArgs );
    }
    
    /**
     * Update the widget settings.
     */
    public function update ($newIns, $oldIns)
    {
        $newIns = $this->setDefalutValues( $newIns );
        $instance = $this->updateWidgetOptions( $newIns, $oldIns );
        $instance = $this->updateWidgetDisplayOptions( $instance, $newIns );
        return $instance;
    }

    /**
     * Display widget settings controls on the widget panel.
     */
    function form ($instance)
    {
        $instance = $this->setDefalutValues( $instance );
        $html  = '<p>';
        $html .= form_label(__('Title:', 'mw_frm'), $this->fid('title'), NULL, TRUE);
        $html .= form_input($this->fnm('title'), $instance['title'], array('id'=>$this->fid('title'), 'class'=>'widefat'), TRUE);
        $html .= '</p><p>';
        $html .= form_label(__('Content:', 'mw_frm'), $this->fid('text'), NULL, TRUE);
        $html .= form_textarea($this->fnm('text'), $instance['text'], array('id'=>$this->fid('text'), 'rows'=>10, 'cols'=>40, 'class'=>'widefat'), TRUE);
        $html .= '</p>';
        $html .= $this->widgetDisplayOptionsForm($instance);
        $this->outputWidgetControl($html);
    }
}


/**
 *  *********************************
 *  Display list of latest blog posts
 *  *********************************
 */
class MW_Widget_Latest_Posts extends MW_Widget
{
    public $isCachable = TRUE;
    public $wpCacheKey = 'mw_widget_recent_posts';
    
    /**
     * Widget setup.
     */
    public function MW_Widget_Latest_Posts ()
    {
        $this->setupWidget(array(
            'name' => __('MW - Latest Blog Posts', 'mw_frm'),
            'description' => __("Display most recent blog posts", 'mw_frm'),
            'classname' => 'mw-widget-recent-posts',
            'id_base' => 'mw-widget-recent-posts'
        ));
        
        $this->default = array(
            'title' => __('Recent blog posts', 'mw_frm'),
            'category' => -1, // All categories
            'number' => 5,
            'thumbs' => 'yes',
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
        
        $widgetContent = '';
        $queryArgs = array('showposts' => $instance['number'], 'nopaging' => 0, 'post_status' => 'publish', 'caller_get_posts' => 1);
        if ( $instance['category'] != -1 ) {
            $queryArgs['cat'] = $instance['category'];
        }
        $r = new WP_Query($queryArgs);
        if ($r->have_posts()) {
            while ($r->have_posts()) {
                $r->the_post();
                $permalink = get_permalink();
                $postID = get_the_ID();
                $postTitle = esc_attr( get_the_title() ? get_the_title() : $postID );
                $postLink = sprintf('<a href="%s" title="%s" class="post-link">%s</a>', $permalink, $postTitle, $postTitle);
                $thumbnail = '';
                $postDate = '';
                if ( $instance['thumbs'] == 'yes' ) {
                    $postDate = sprintf( '<span class="widget-post-meta">%s &bull; %s</span>', get_the_author_link(), get_the_time(get_option('date_format')) );
                }
                if ( $instance['thumbs'] == 'yes' && has_post_thumbnail() ) {
                    $thumbnail = get_the_post_thumbnail( $postID, 'post-thumbnail-small', array('class'=>'thumbnail') );
                    $thumbnail = sprintf('<a href="%1$s" class="thumb-post-link">%2$s</a>', $permalink, $thumbnail);
                }
                $widgetContent .=  '<li>' . $thumbnail . $postLink . $postDate . '</li>';
            }
            $thumbsClass = ( $instance['thumbs'] == 'yes' ) ? ' with-thumbnails' : ' no-thumb';
            $widgetContent = sprintf('<ul class="mw-widget-recent-posts-list%s">%s</ul>', $thumbsClass, $widgetContent);
            wp_reset_postdata();
        }
        return $widgetContent;
    }
    
    /**
     * Update the widget settings.
     */
    public function update ($newIns, $oldIns)
    {
        $newIns['number'] = is_numeric($newIns['number']) ? absint($newIns['number']) : $this->default['number'];
        $newIns['thumbs'] = isset($newIns['thumbs']) && $newIns['thumbs'] == 'yes' ? 'yes' : 'no';
        
        if ( $newIns['number'] < 1 )
            $newIns['number'] = 1;
        elseif ( $newIns['number'] > 50 )
            $newIns['number'] = 50;
        
        $instance = $oldIns;
        $instance['title'] = $newIns['title'];
        $instance['number'] = (int) $newIns['number'];
        $instance['thumbs'] = $newIns['thumbs'];
        $instance['category'] = $newIns['category'];
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
        $checkedThumbs = $instance['thumbs'] == 'yes' ? TRUE : FALSE;
        
        $cat_dropdown = wp_dropdown_categories(array(
            'show_option_none' => __('-- All categories --', 'mw_frm'),
            'echo'         => 0,
            'orderby'      => 'name',
            'hide_empty'   => 0,
            'hierarchical' => 1, 
            'selected'     => $instance['category'],
            'name'         => $this->fnm('category'),
            'id'           => $this->fid('category'),
            'class'        => 'widefat',
            'taxonomy'     => 'category'
        ));
        
        $html  = '<p>';
        $html .= form_label(__('Title:', 'mw_frm'), $this->fid('title'), NULL, TRUE);
        $html .= form_input($this->fnm('title'), $instance['title'], array('id'=>$this->fid('title'), 'class'=>'widefat'), TRUE);
        $html .= '</p><p>';
        $html .= form_label(__('Select category:', 'mw_frm'), $this->fid('wp_page'), NULL, TRUE);
        $html .= $cat_dropdown;
        $html .= '</p><p>';
        $html .= form_label(__('Number of posts to show:', 'mw_frm'), $this->fid('number'), NULL, TRUE) . ' ';
        $html .= form_input($this->fnm('number'), $instance['number'], array('id'=>$this->fid('number'), 'size'=>'3'), TRUE);
        $html .= '</p><p>';
        $html .= form_checkbox_labeled($this->fnm('thumbs'), 'yes', __('Show thumbnails', 'mw_frm'), $this->fid('thumbs'), $checkedThumbs, NULL, TRUE);
        $html .= '</p>';
        $html .= $this->widgetDisplayOptionsForm($instance);
        $this->outputWidgetControl($html);
    }
}


/**
 *  ********************************************
 *  "Follow us" widget with social media buttons
 *  ********************************************
 */
class MW_Widget_Follow extends MW_Widget
{
    public $isCachable = TRUE;
    public $wpCacheKey = 'mw_widget_follow';
    
    /**
     * Widget setup.
     */
    public function MW_Widget_Follow  ()
    {
        $this->setupWidget(array(
            'name' => __('MW - Follow Us', 'mw_frm'),
            'description' => __('Social media buttons', 'mw_frm'),
            'classname' => 'mw-widget-follow',
            'id_base' => 'mw-widget-follow',
            'width' => 400,
            'height' => 350
        ));
        
        $this->default = array(
            'title' => __('Follow us', 'mw_frm'),
            'buzz_url' => '',
            'facebook_url' => '',
            'flickr_url' => '',
            'twitter_url' => '',
            'vimeo_url' => '',
            'youtube_url' => '',
            'rss_url' => '',
            'show_boxed' => 'no',
            'set_custom_styles' => 'no',
            'bg_color' => $this->get_default_widget_style('widget_bg_col'),
            'border_color' => $this->get_default_widget_style('widget_border_col'),
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
     * Get content for the widget
     */
    public function getWidgetContent ($instance)
    {
        $instance = $this->setDefalutValues( $instance );
        
        $facebook   = anchor( $instance['facebook_url'], 'Facebook', array('class'=>'mw-widget-follow-link facebook'),  TRUE );
        $buzz       = anchor( $instance['buzz_url'],     'Buzz',     array('class'=>'mw-widget-follow-link buzz'),      TRUE );
        $twitter    = anchor( $instance['twitter_url'],  'Twitter',  array('class'=>'mw-widget-follow-link twitter'),   TRUE );
        $flickr     = anchor( $instance['flickr_url'],   'Flickr',   array('class'=>'mw-widget-follow-link flickr'),    TRUE );
        $vimeo      = anchor( $instance['vimeo_url'],    'Vimeo',    array('class'=>'mw-widget-follow-link vimeo'),     TRUE );
        $youtube    = anchor( $instance['youtube_url'],  'YouTube',  array('class'=>'mw-widget-follow-link youtube'),   TRUE );
        $rss        = anchor( $instance['rss_url'],      'RSS feed', array('class'=>'mw-widget-follow-link rss'),       TRUE );

        $widgetContent = '';
        $widgetContent .= ($instance['facebook_url'] != '') ? $facebook : '';
        $widgetContent .= ($instance['buzz_url'] != '')     ? $buzz     : '';
        $widgetContent .= ($instance['twitter_url'] != '')  ? $twitter  : '';
        $widgetContent .= ($instance['flickr_url'] != '')   ? $flickr   : '';
        $widgetContent .= ($instance['vimeo_url'] != '')    ? $vimeo    : '';
        $widgetContent .= ($instance['youtube_url'] != '')  ? $youtube  : '';
        $widgetContent .= ($instance['rss_url'] != '')      ? $rss      : '';
        
        return $widgetContent;
    }
    
    /**
     * Update the widget settings.
     */
    public function update ($newIns, $oldIns)
    {
        $newIns = $this->setDefalutValues( $newIns );
        $instance = $this->updateWidgetOptions( $newIns, $oldIns );
        $instance = $this->updateWidgetDisplayOptions( $instance, $newIns );
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
        $html .= form_label(__('Title:', 'mw_frm'), $this->fid('title'), NULL, TRUE);
        $html .= form_input($this->fnm('title'), $instance['title'], array('id'=>$this->fid('title'), 'class'=>'widefat'), TRUE);
        $html .= '</p><p>';
        
        $html .= form_label(__('Facebook URL:', 'mw_frm'), $this->fid('facebook_url'), NULL, TRUE);
        $html .= form_input($this->fnm('facebook_url'), $instance['facebook_url'], array('id'=>$this->fid('facebook_url'), 'class'=>'widefat'), TRUE);
        $html .= '</p><p>';
        
        $html .= form_label(__('Buzz URL:', 'mw_frm'), $this->fid('buzz_url'), NULL, TRUE);
        $html .= form_input($this->fnm('buzz_url'), $instance['buzz_url'], array('id'=>$this->fid('buzz_url'), 'class'=>'widefat'), TRUE);
        $html .= '</p><p>';
        
        $html .= form_label(__('Twitter URL:', 'mw_frm'), $this->fid('twitter_url'), NULL, TRUE);
        $html .= form_input($this->fnm('twitter_url'), $instance['twitter_url'], array('id'=>$this->fid('twitter_url'), 'class'=>'widefat'), TRUE);
        $html .= '</p><p>';
        
        $html .= form_label(__('Flickr URL:', 'mw_frm'), $this->fid('flickr_url'), NULL, TRUE);
        $html .= form_input($this->fnm('flickr_url'), $instance['flickr_url'], array('id'=>$this->fid('flickr_url'), 'class'=>'widefat'), TRUE);
        $html .= '</p><p>';
        
        $html .= form_label(__('Vimeo URL:', 'mw_frm'), $this->fid('vimeo_url'), NULL, TRUE);
        $html .= form_input($this->fnm('vimeo_url'), $instance['vimeo_url'], array('id'=>$this->fid('vimeo_url'), 'class'=>'widefat'), TRUE);
        $html .= '</p><p>';
        
        $html .= form_label(__('YouTube URL:', 'mw_frm'), $this->fid('youtube_url'), NULL, TRUE);
        $html .= form_input($this->fnm('youtube_url'), $instance['youtube_url'], array('id'=>$this->fid('youtube_url'), 'class'=>'widefat'), TRUE);
        $html .= '</p><p>';
        
        $html .= form_label(__('RSS feed URL:', 'mw_frm'), $this->fid('rss_url'), NULL, TRUE);
        $html .= form_input($this->fnm('rss_url'), $instance['rss_url'], array('id'=>$this->fid('rss_url'), 'class'=>'widefat'), TRUE);

        $html .= '</p>';
        $html .= $this->widgetDisplayOptionsForm($instance);
        $this->outputWidgetControl($html);
    }
}


/**
 *  *********************
 *   Twitter feed Widget
 *  *********************
 */
class MW_Widget_Twitter_Feed extends MW_Widget
{
    public $isCachable = TRUE;
    public $wpCacheKey = 'mw_widget_twitter_feed';
    public $twitterApiUrl = 'http://api.twitter.com/1/statuses/user_timeline.xml?screen_name=%s&count=%s&trim_user=true&include_rts=true';
    public $permaCacheKey = null;
    public $permaCacheTime = 60;
    
    /**
     * Widget setup.
     */
    public function MW_Widget_Twitter_Feed ()
    {
        $this->setupWidget(array(
            'name' => __('MW - Twitter Feed', 'mw_frm'),
            'description' => __("Display your recent tweets", 'mw_frm'),
            'classname' => 'mw-widget-twitter-feed',
            'id_base' => 'mw-widget-twitter-feed'
        ));
        
        $this->default = array(
            'title' => __('Recent tweets', 'mw_frm'),
            'twitter_user' => '',
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
        $this->permaCacheKey = str_replace('-', '_', $sidebarArgs['widget_id']);
        $this->getCachableWidget( $instance, $sidebarArgs ); // uses the getWidgetContent method below to get the actual widget content
    }
        
    /**
     * Get the content for the widget
     */
    public function getWidgetContent ($instance)
    {
        if ( !extension_loaded('simplexml') )
            return __('This widget requires SimpleXML PHP extension. It is enabled by default in PHP5. Please check your server configuration.', 'mw_frm');
        if ( !isset($instance['twitter_user']) || $instance['twitter_user'] == '' ) {
            return __('Please configure this widget in the WordPress admin panel.', 'mw_frm');
        }
        
        $feed = '';
        $cacheContent = get_option($this->permaCacheKey);
        if ($cacheContent == FALSE) {
            $feed = $this->getTwitterFeed($instance);
        } else {
            if ( !is_array($cacheContent)
                 || !isset($cacheContent['timestamp'])
                 || !isset($cacheContent['feed'])
                 || ( $cacheContent['timestamp'] + $this->permaCacheTime < time() )
                 || count($cacheContent['feed']) != (int)$instance['number']
            ) {
                $feed = $this->getTwitterFeed($instance);
            } else {
                $feed = $cacheContent['feed'];
            }
        }
        
        $widgetContent = '';
        if (!is_array($feed) || (count($feed) == 0)) return $widgetContent;
        foreach ( $feed as $status ) { 
            $widgetContent .= '<li>' . $this->reformatTweet($status['text']) . '</li>';
        }
        $widgetContent = '<ul class="mw-widget-twitter-feed-list">' . $widgetContent . '</ul>';
        return $widgetContent;
    }

    /**
     * Utitlity function to turn parts of the tweet content into links
     */
    private function reformatTweet ( $tweet )
    {
        $tweet = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" >\\2</a>", $tweet);
        $tweet = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" >\\2</a>", $tweet);
        $tweet = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" >@\\1</a>", $tweet);
        $tweet = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" >#\\1</a>", $tweet);
        return $tweet;
    }
    
    /**
     * Get XML feed from Twitter and cache it in wp_options
     */
    public function getTwitterFeed ( $instance )
    {
        $instance['twitter_user'] = urlencode($instance['twitter_user']);
        $instance['number'] = urlencode($instance['number']);
        $twitterUrl = sprintf( $this->twitterApiUrl, $instance['twitter_user'], $instance['number'] );
        
        $xml = @file_get_contents($twitterUrl);
        if ($xml === FALSE)
            return $feed;
        
        $xml = new SimpleXMLElement($xml);
        if ($xml === FALSE)
            return $feed;
        
        $feed = array();
        foreach($xml->status as $status) {
            $feed[] = array(
                'created' => (string) $status->created_at,
                'text' => (string) $status->text
            );
        }
        
        $cacheContent['timestamp'] = time();
        $cacheContent['feed'] = $feed;
        update_option( $this->permaCacheKey, $cacheContent );
        return $feed;
    }
    
    /**
     * Update the widget settings.
     */
    public function update ($newIns, $oldIns)
    {
        $newIns = $this->setDefalutValues( $newIns );
        $newIns['number'] = is_numeric($newIns['number']) ? absint($newIns['number']) : $this->default['number'];
        
        if ( $newIns['number'] < 1 )
            $newIns['number'] = 1;
        elseif ( $newIns['number'] > 50 )
            $newIns['number'] = 50;
    
        $instance = $this->updateWidgetOptions( $newIns, $oldIns );
        $instance = $this->updateWidgetDisplayOptions( $instance, $newIns );
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
        $html .= form_label(__('Title:', 'mw_frm'), $this->fid('title'), NULL, TRUE);
        $html .= form_input($this->fnm('title'), $instance['title'], array('id'=>$this->fid('title'), 'class'=>'widefat'), TRUE);
        $html .= '</p><p>';
        $html .= form_label(__('Twitter username, e.g. wordpress:', 'mw_frm'), $this->fid('twitter_user'), NULL, TRUE);
        $html .= form_input($this->fnm('twitter_user'), $instance['twitter_user'], array('id'=>$this->fid('twitter_user'), 'class'=>'widefat'), TRUE);
        $html .= '</p><p>';
        $html .= form_label(__('Number of tweets to show:', 'mw_frm'), $this->fid('number'), NULL, TRUE) . ' ';
        $html .= form_input($this->fnm('number'), $instance['number'], array('id'=>$this->fid('number'), 'size'=>'3'), TRUE);
        $html .= '</p>';
        $html .= '<p class="small">' . __('Please note that the Twitter feed is cached on your server for 60 seconds. Therefore it can take up to 1 minute until the change in the number of tweets is visible on your site.', 'mw_frm') . '</p>';
        $html .= $this->widgetDisplayOptionsForm($instance);
        
        if ( !extension_loaded('simplexml') )
            $html = __('This widget requires SimpleXML PHP extension. It is enabled by default in PHP5. Please check your server configuration.', 'mw_frm');
        
        $this->outputWidgetControl($html);
    }
    
}


/**
 *  *******************************
 *  Display list of WordPress pages
 *  *******************************
 */
class MW_Widget_Pages extends MW_Widget
{
    public $isCachable = TRUE;
    public $wpCacheKey = 'mw_widget_pages';
    
    /**
     * Widget setup.
     */
    public function MW_Widget_Pages ()
    {
        $this->setupWidget(array(
            'name' => __('MW - Pages', 'mw_frm'),
            'description' => __("Your site's WordPress pages", 'mw_frm'),
            'classname' => 'mw-widget-pages',
            'id_base' => 'mw-widget-pages'
        ));
        
        $this->default = array(
            'title' => __('Pages', 'mw_frm'),
            'sortby' => 'menu_order',
            'exclude' => '',
            'depth' => 0,
            'level' => '',
            'show_boxed' => 'no',
            'set_custom_styles' => 'no',
            'bg_color' => $this->get_default_widget_style('widget_bg_col'),
            'border_color' => $this->get_default_widget_style('widget_border_col'),
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
        $sortby = $instance['sortby'] == 'menu_order' ? 'menu_order, post_title' : $instance['sortby'];
        
        $depth = 0;
        if ( in_array($instance['depth'], array('-1','0','1')) )
            $depth = (int) $instance['depth'];
        elseif ( $instance['depth'] == 'depth' && isset($instance['level']) )
            $depth = absint($instance['level']);
        
        $args = array('title_li' => '', 'echo' => 0, 'sort_column' => $sortby, 'exclude' => $instance['exclude'], 'depth' => $depth);
		$pages = wp_list_pages(apply_filters('widget_pages_args', $args));
        
        $widgetContent = '';
		if ( !empty( $pages ) )
            $widgetContent = '<ul class="mw-widget-pages-list">' . $pages . '</ul>';
        return $widgetContent;
    }
    
    /**
     * Update the widget settings.
     */
    public function update ($newIns, $oldIns)
    {
        $newIns = $this->setDefalutValues( $newIns );
		if ( !in_array( $newIns['sortby'], array('post_title', 'menu_order', 'ID', 'post_date') ) )
            $newIns['sortby'] = $this->default['sortby'];
        
        $instance = $this->updateWidgetOptions( $newIns, $oldIns );
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
        
        $sortbyOptions = array(
            'post_title' => __('Page title', 'mw_frm'),
            'menu_order' => __('Page order', 'mw_frm'),
            'ID' => __('Page ID', 'mw_frm'),
            'post_date' => __('Date published', 'mw_frm')
        );
        
        $radioAllPages = (!isset($instance['depth']) || $instance['depth'] == '0') ? TRUE : FALSE;
        $radioAllFlat  = (!isset($instance['depth']) || $instance['depth'] == '-1') ? TRUE : FALSE;
        $radioTopLevel = (!isset($instance['depth']) || $instance['depth'] == '1') ? TRUE : FALSE;
        $radioDepth    = (!isset($instance['depth']) || $instance['depth'] == 'depth') ? TRUE : FALSE;
        
        $html  = '<p>';
        $html .= form_label(__('Title:', 'mw_frm'), $this->fid('title'), NULL, TRUE);
        $html .= form_input($this->fnm('title'), $instance['title'], array('id'=>$this->fid('title'), 'class'=>'widefat'), TRUE);
        $html .= '</p><p>';
        $html .= form_label(__('Sort by:', 'mw_frm'), $this->fid('sortby'), NULL, TRUE);
        $html .= form_dropdown($this->fnm('sortby'), $sortbyOptions, $instance['sortby'], array('id'=>$this->fid('sortby'), 'class'=>'widefat'), TRUE);
        $html .= '</p><p>';
        $html .= form_label(__('Exclude:', 'mw_frm'), $this->fid('exclude'), NULL, TRUE);
        $html .= form_input($this->fnm('exclude'), $instance['exclude'], array('id'=>$this->fid('exclude'), 'class'=>'widefat'), TRUE);
        $html .= '<br/><span class="description">' . __('Page IDs, separated by commas.', 'mw_frm') . '</span>';
        $html .= '</p>';
        $html .= '<fieldset>';
        $html .= '<p><strong>' . __('How would you like the pages displayed?', 'mw_frm') . '</strong></p><p>';
        $html .= form_radio_labeled($this->fnm('depth'), '0', __('All pages hierarchically', 'mw_frm'), $this->fid('all'), $radioAllPages, NULL, TRUE);
        $html .= '<br/>';
        $html .= form_radio_labeled($this->fnm('depth'), '-1', __('All pages as a flat list', 'mw_frm'), $this->fid('all-flat'), $radioAllFlat, NULL, TRUE);
        $html .= '<br/>';
        $html .= form_radio_labeled($this->fnm('depth'), '1', __('Top-level pages only', 'mw_frm'), $this->fid('top-level'), $radioTopLevel, NULL, TRUE);
        $html .= '<br/>';
        $html .= form_radio_labeled($this->fnm('depth'), 'depth', __('Pages up to level', 'mw_frm'), $this->fid('depth'), $radioDepth, NULL, TRUE);
        $html .= '&nbsp;';
        $html .= form_input($this->fnm('level'), $instance['level'], array('id'=>$this->fid('level'), 'size'=>'3'), TRUE);
        $html .= '</p>';
        $html .= '</fieldset>';
        $html .= $this->widgetDisplayOptionsForm($instance);
        $this->outputWidgetControl($html);
    }
}



/**
 *  ************************************
 *  Display list of links (the blogroll)
 *  ************************************
 */
class MW_Widget_Links extends MW_Widget
{
    public $isCachable = TRUE;
    public $wpCacheKey = 'mw_widget_links';
    
    /**
     * Widget setup.
     */
    public function MW_Widget_Links ()
    {
        $this->setupWidget(array(
            'name' => __('MW - Links', 'mw_frm'),
            'description' => __("Your blogroll", 'mw_frm'),
            'classname' => 'mw-widget-links',
            'id_base' => 'mw-widget-links'
        ));
        
        $this->default = array(
            'title' => __('Blogroll', 'mw_frm'),
            'name' => 0,
            'images' => 0,
            'description' => 0,
            'category' => 'all',
            'show_boxed' => 'no',
            'set_custom_styles' => 'no',
            'bg_color' => $this->get_default_widget_style('widget_bg_col'),
            'border_color' => $this->get_default_widget_style('widget_border_col'),
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
		$show_name = isset($instance['name']) ? $instance['name'] : false;
		$show_images = isset($instance['images']) ? $instance['images'] : true;
		$show_description = isset($instance['description']) ? $instance['description'] : false;
		$category = isset($instance['category']) && $instance['category'] != 'all' ? $instance['category'] : false;
        
        $args = array(
            'categorize' => 0,
            'title_li' => NULL,
			'show_images' => $show_images,
            'show_description' => $show_description,
			'show_name' => $show_name,
			'category' => $category,
            'between' => "\n\n",
            'echo' => 0
		);
		$links = wp_list_bookmarks(apply_filters('widget_links_args', $args));
        
        $widgetContent = '';
		if ( !empty( $links ) )
            $widgetContent = '<ul class="mw-widget-links-list">' . wpautop($links) . '</ul>';
        return $widgetContent;
    }
    
    /**
     * Update the widget settings.
     */
    public function update ($newIns, $oldIns)
    {
        $newIns = $this->setDefalutValues( $newIns );
        $instance['title']       = $newIns['title'];
        $instance['images']      = $newIns['images'] == 1       ? '1' : '0';
        $instance['name']        = $newIns['name'] == 1         ? '1' : '0';
        $instance['description'] = $newIns['description'] == 1  ? '1' : '0';
        $instance['category'] = $newIns['category'] == 'all' ? 'all' : intval($newIns['category']);
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
        $checkedImages = (int) $instance['images'] == 1 ? TRUE : FALSE;
        $checkedName = (int) $instance['name'] == 1 ? TRUE : FALSE;
        $checkedDesc = (int) $instance['description'] == 1 ? TRUE : FALSE;
        
        $linkCats = array( $this->default['category'] => __('All Links', 'mw_frm') );
        $linkTaxonomy = get_terms('link_category');
		foreach ( $linkTaxonomy as $cat ) {
            $linkCats[$cat->term_id] = $cat->name;
		}
        
        $html  = '<p>';
        $html .= form_label(__('Title:', 'mw_frm'), $this->fid('title'), NULL, TRUE);
        $html .= form_input($this->fnm('title'), $instance['title'], array('id'=>$this->fid('title'), 'class'=>'widefat'), TRUE);
        $html .= '</p><p>';
        $html .= form_label(__('Select link category:', 'mw_frm'), $this->fid('category'), NULL, TRUE);
        $html .= form_dropdown ($this->fnm('category'), $linkCats, $instance['category'], array('id'=>$this->fid('category'), 'class'=>'widefat'), TRUE);
        $html .= '</p><p>';
        $html .= form_checkbox_labeled($this->fnm('images'), '1', __('Show link image', 'mw_frm'), $this->fid('images'), $checkedImages, NULL, TRUE);
        $html .= '</p><p>';
        $html .= form_checkbox_labeled($this->fnm('name'), '1', __('Show link name', 'mw_frm'), $this->fid('name'), $checkedName, NULL, TRUE);
        $html .= '</p><p>';
        $html .= form_checkbox_labeled($this->fnm('description'), '1', __('Show link description', 'mw_frm'), $this->fid('description'), $checkedDesc, NULL, TRUE);
        $html .= '</p>';
        $html .= $this->widgetDisplayOptionsForm($instance);
        $this->outputWidgetControl($html);
    }
}



/**
 *  *****************************
 *  Display blog monthly archives
 *  *****************************
 */
class MW_Widget_Archives extends MW_Widget
{
    public $isCachable = TRUE;
    public $wpCacheKey = 'mw_widget_archives';
    
    /**
     * Widget setup.
     */
    public function MW_Widget_Archives ()
    {
        $this->setupWidget(array(
            'name' => __('MW - Archives', 'mw_frm'),
            'description' => __("A monthly archive of your posts", 'mw_frm'),
            'classname' => 'mw-widget-archives',
            'id_base' => 'mw-widget-archives'
        ));
        
        $this->default = array(
            'title' => __('Archives', 'mw_frm'),
            'count' => 0,
            'show_boxed' => 'no',
            'set_custom_styles' => 'no',
            'bg_color' => $this->get_default_widget_style('widget_bg_col'),
            'border_color' => $this->get_default_widget_style('widget_border_col'),
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
        
        $args = array(
            'type' => 'monthly',
            'show_post_count' => $instance['count'],
            'echo' => 0
        );
		$links = wp_get_archives(apply_filters('widget_archives_args', $args));
        
        // If counts are included, move them inside the anchor tags and wrap with <span>
        if ((int)$instance['count'] == 1)
            $links = preg_replace('/((<\/a>)&nbsp;(\([0-9]+\)))/', ' <span class="count">$3</span>$2', $links);
        
        $widgetContent = '';
		if ( !empty( $links ) )
            $widgetContent = '<ul class="mw-widget-archives-list">' . $links . '</ul>';
        return $widgetContent;
    }
    
    /**
     * Update the widget settings.
     */
    public function update ($newIns, $oldIns)
    {
        $newIns = $this->setDefalutValues( $newIns );
        $newIns['count'] = (int) $newIns['count'] == 1 ? '1' : '0';
        $instance = $this->updateWidgetOptions( $newIns, $oldIns );
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
        $checkedCount = (int) $instance['count'] == 1 ? TRUE : FALSE;
        
        $html  = '<p>';
        $html .= form_label(__('Title:', 'mw_frm'), $this->fid('title'), NULL, TRUE);
        $html .= form_input($this->fnm('title'), $instance['title'], array('id'=>$this->fid('title'), 'class'=>'widefat'), TRUE);
        $html .= '</p><p>';
        $html .= form_checkbox_labeled($this->fnm('count'), '1', __('Show post counts', 'mw_frm'), $this->fid('count'), $checkedCount, NULL, TRUE);
        $html .= '</p>';
        $html .= $this->widgetDisplayOptionsForm($instance);
        $this->outputWidgetControl($html);
    }
}



/**
 *  **************************
 *  Display list of categories
 *  **************************
 */
class MW_Widget_Categories extends MW_Widget
{
    public $isCachable = TRUE;
    public $wpCacheKey = 'mw_widget_categories';
    
    /**
     * Widget setup.
     */
    public function MW_Widget_Categories ()
    {
        $this->setupWidget(array(
            'name' => __('MW - Categories', 'mw_frm'),
            'description' => __("A list of categories", 'mw_frm'),
            'classname' => 'mw-widget-categories',
            'id_base' => 'mw-widget-categories'
        ));
        
        $this->default = array(
            'title' => __('Categories', 'mw_frm'),
            'count' => 0,
            'hierarchy' => 0,
            'show_boxed' => 'no',
            'set_custom_styles' => 'no',
            'bg_color' => $this->get_default_widget_style('widget_bg_col'),
            'border_color' => $this->get_default_widget_style('widget_border_col'),
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
        
		$args = array(
            'show_count' => $instance['count'],
            'hierarchical' => $instance['hierarchy'],
            'orderby' => 'name',
            'title_li' => '',
            'echo' => 0
        );
		$categories = wp_list_categories(apply_filters('widget_categories_args', $args));
        
        // If counts are included, move them inside the anchor tags and wrap with <span>
        if ((int)$instance['count'] == 1)
            $categories = preg_replace('/((<\/a>) (\([0-9]+\)))/', ' <span class="count">$3</span>$2', $categories);
        
        $widgetContent = '';
		if ( !empty( $categories ) )
            $widgetContent = '<ul class="mw-widget-categories-list">' . $categories . '</ul>';
        return $widgetContent;
    }
    
    /**
     * Update the widget settings.
     */
    public function update ($newIns, $oldIns)
    {
        $newIns = $this->setDefalutValues( $newIns );
        $newIns['count'] = (int) $newIns['count'] == 1 ? '1' : '0';
        $newIns['hierarchy'] = (int) $newIns['hierarchy'] == 1 ? '1' : '0';
        $instance = $this->updateWidgetOptions( $newIns, $oldIns );
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
        $checkedCount = (int) $instance['count'] == 1 ? TRUE : FALSE;
        $checkedHierarchy = (int) $instance['hierarchy'] == 1 ? TRUE : FALSE;
        
        $html  = '<p>';
        $html .= form_label(__('Title:', 'mw_frm'), $this->fid('title'), NULL, TRUE);
        $html .= form_input($this->fnm('title'), $instance['title'], array('id'=>$this->fid('title'), 'class'=>'widefat'), TRUE);
        $html .= '</p><p>';
        $html .= form_checkbox_labeled($this->fnm('count'), '1', __('Show post counts:', 'mw_frm'), $this->fid('count'), $checkedCount, NULL, TRUE);
        $html .= '</p><p>';
        $html .= form_checkbox_labeled($this->fnm('hierarchy'), '1', __('Show hierarchy:', 'mw_frm'), $this->fid('hierarchy'), $checkedHierarchy, NULL, TRUE);
        $html .= '</p>';
        $html .= $this->widgetDisplayOptionsForm($instance);
        $this->outputWidgetControl($html);
    }
}



/**
 *  ****************************
 *  Display recent blog comments
 *  ****************************
 */
class MW_Widget_Comments extends MW_Widget
{
    public $isCachable = TRUE;
    public $wpCacheKey = 'mw_widget_comments';
    
    /**
     * Widget setup.
     */
    public function MW_Widget_Comments ()
    {
        $this->setupWidget(array(
            'name' => __('MW - Comments', 'mw_frm'),
            'description' => __("Recent blog comments", 'mw_frm'),
            'classname' => 'mw-widget-comments',
            'id_base' => 'mw-widget-comments'
        ));
        
        $this->default = array(
            'title' => __('Recent comments', 'mw_frm'),
            'number' => 5,
            'words' => 10,
            'gravatars' => 1,
            'show_boxed' => 'no',
            'set_custom_styles' => 'no',
            'bg_color' => $this->get_default_widget_style('widget_bg_col'),
            'border_color' => $this->get_default_widget_style('widget_border_col'),
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
        
        $args = array(
            'number' => $instance['number'],
            'status' => 'approve'
        );
		$comments = get_comments(apply_filters('widget_comments_args', $args));
        
        $commentsList = '';
        foreach ( (array) $comments as $comment) {
            $author = get_comment_author( $comment->comment_ID );
            $cmtLink = esc_url( get_comment_link( $comment->comment_ID ) );
            $cmtPostTitle = get_the_title( $comment->comment_post_ID );
            $text = mw_make_excerpt( $comment->comment_content, $instance['words'] );
            
            $gravatar = '';
            $gravatarClass = '';
            if ( (int)$instance['gravatars'] == 1 ) {
                $gravatar = get_avatar( $comment, 30);
                $gravatar = sprintf('<a href="%1$s" class="avatar-comment-link">%2$s</a>', $cmtLink, $gravatar);
                $gravatarClass = ' with-gravatar';
            }
            
            $cmtHtmlFormat = '<a href="%1$s" title="on &lsquo;%2$s&rsquo;" class="comment-link"><span class="comment-author">%3$s</span>: <span class="comment-text">%4$s</span></a>';
            $cmtHtml = sprintf( $cmtHtmlFormat, $cmtLink, $cmtPostTitle, $author, $text );
            $commentsList .=  '<li>' . $gravatar . $cmtHtml . '</li>';
        }  
        
        $widgetContent = '';
        if ($commentsList != '')
            $widgetContent = sprintf('<ul class="mw-widget-comments-list%s">%s</ul>', $gravatarClass, $commentsList);
        return $widgetContent;
        
    }
    
    /**
     * Update the widget settings.
     */
    public function update ($newIns, $oldIns)
    {
        $newIns['title'] = isset($newIns['title']) ? $newIns['title'] : $this->default['title'];
        $newIns['gravatars'] = isset($newIns['gravatars']) && (int)$newIns['gravatars'] == 1 ? '1' : '0';
        $newIns['number'] = is_numeric($newIns['number']) ? absint($newIns['number']) : $this->default['number'];
        $newIns['words'] = is_numeric($newIns['words']) ? absint($newIns['words']) : $this->default['words'];
        
        if ( $newIns['number'] < 1 )
            $newIns['number'] = 1;
        elseif ( $newIns['number'] > 50 )
            $newIns['number'] = 50;
        
        if ( $newIns['words'] < 1 ) 
            $newIns['words'] = 1;
        elseif ( $newIns['words'] > 30 )
            $newIns['words'] = 30;
        
        $instance = $this->updateWidgetOptions( $newIns, $oldIns );
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
        $checkedGravatars = (int) $instance['gravatars'] == 1 ? TRUE : FALSE;
        
        $html  = '<p>';
        $html .= form_label(__('Title:', 'mw_frm'), $this->fid('title'), NULL, TRUE);
        $html .= form_input($this->fnm('title'), $instance['title'], array('id'=>$this->fid('title'), 'class'=>'widefat'), TRUE);
        $html .= '</p><p>';
        $html .= form_label(__('Number of comments:', 'mw_frm'), $this->fid('number'), NULL, TRUE) . ' ';
        $html .= form_input($this->fnm('number'), $instance['number'], array('id'=>$this->fid('number'), 'size'=>'3'), TRUE);
        $html .= '</p><p>';
        $html .= form_label(__('Comment words:', 'mw_frm'), $this->fid('words'), NULL, TRUE) . ' ';
        $html .= form_input($this->fnm('words'), $instance['words'], array('id'=>$this->fid('words'), 'size'=>'3'), TRUE);
        $html .= '</p><p>';
        $html .= form_checkbox_labeled($this->fnm('gravatars'), '1', __('Show gravatars', 'mw_frm'), $this->fid('gravatars'), $checkedGravatars, NULL, TRUE);
        $html .= '</p>';
        $html .= $this->widgetDisplayOptionsForm($instance);
        $this->outputWidgetControl($html);
    }
}



/**
 *  ******************
 *   Tag cloud widget
 *  ******************
 */
class MW_Widget_Tag_Cloud extends MW_Widget
{
    public $isCachable = TRUE;
    public $wpCacheKey = 'mw_widget_tag_cloud';
    
    /**
     * Widget setup.
     */
    public function MW_Widget_Tag_Cloud ()
    {
        $this->setupWidget(array(
            'name' => __('MW - Tag Cloud', 'mw_frm'),
            'description' => __("Your most used tags in cloud format", 'mw_frm'),
            'classname' => 'mw-widget-tag-cloud',
            'id_base' => 'mw-widget-tag-cloud'
        ));
        
        $this->default = array(
            'title' => __('Tag cloud', 'mw_frm'),
            'taxonomy' => 'post_tag',
            'show_boxed' => 'no',
            'set_custom_styles' => 'no',
            'bg_color' => $this->get_default_widget_style('widget_bg_col'),
            'border_color' => $this->get_default_widget_style('widget_border_col'),
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
		$taxonomy = $this->getCurrentTaxonomy($instance);
        $args = array(
            'taxonomy' => $taxonomy,
            'unit'      => 'pt',
            'smallest'  => 10, 
            'orderby' => 'count',
            'order' => 'DESC',
            'echo' => 0
        );
		$tags = wp_tag_cloud(apply_filters('widget_tag_cloud_args', $args));
        $widgetContent = !empty($tags) ? $tags : '';
        return $widgetContent;
    }
    
	function getCurrentTaxonomy ($instance)
    {
		if ( !empty($instance['taxonomy']) && taxonomy_exists($instance['taxonomy']) )
			return $instance['taxonomy'];
		return 'post_tag';
	}
    
	function getTaxonomies ()
    {
        $taxonomies = array();
        foreach ( get_object_taxonomies('post') as $taxName ) {
            $tax = get_taxonomy($taxName);
            if ( !$tax->show_tagcloud || empty($tax->labels->name) )
                continue;
            $taxonomies[$taxName] = $tax->labels->name;
        }
        return $taxonomies;
	}
    
    /**
     * Update the widget settings.
     */
    public function update ($newIns, $oldIns)
    {
        $newIns = $this->setDefalutValues( $newIns );
        $instance = $this->updateWidgetOptions( $newIns, $oldIns );
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
        $taxonomy = $this->getCurrentTaxonomy($instance);
        $taxonomies = $this->getTaxonomies();
        
        $html  = '<p>';
        $html .= form_label(__('Title:', 'mw_frm'), $this->fid('title'), NULL, TRUE);
        $html .= form_input($this->fnm('title'), $instance['title'], array('id'=>$this->fid('title'), 'class'=>'widefat'), TRUE);
        $html .= '</p><p>';
        $html .= form_label(__('Taxonomy:', 'mw_frm'), $this->fid('taxonomy'), NULL, TRUE);
        $html .= form_dropdown($this->fnm('taxonomy'), $taxonomies, $taxonomy, array('id'=>$this->fid('taxonomy'), 'class'=>'widefat'), TRUE);
        $html .= '</p>';
        $html .= $this->widgetDisplayOptionsForm($instance);
        $this->outputWidgetControl($html);
    }
}



/**
 *  ******************
 *    Search widget
 *  ******************
 */
class MW_Widget_Search extends MW_Widget
{
    /**
     * Widget setup.
     */
    public function MW_Widget_Search ()
    {
        $this->setupWidget(array(
            'name' => __('MW - Search', 'mw_frm'),
            'description' => __("A search form for your site", 'mw_frm'),
            'classname' => 'mw-widget-search',
            'id_base' => 'mw-widget-search'
        ));
        
        $this->default = array(
            'title' => __('Search', 'mw_frm'),
            'show_boxed' => 'no',
            'set_custom_styles' => 'no',
            'bg_color' => $this->get_default_widget_style('widget_bg_col'),
            'border_color' => $this->get_default_widget_style('widget_border_col'),
        );
    }
    
    /**
     * Display widget on the frontend.
     */
    function widget ($sidebarArgs, $instance)
    {
        $searchFieldId = $sidebarArgs['widget_id'] . '-search-field';
        $widgetContent  = '<form method="get" action="' . get_bloginfo('url') . '/"><fieldset>';
        $widgetContent .= form_label(__('Search', 'mw_frm'), $searchFieldId, array('class' => 'label'), TRUE);
        $widgetContent .= form_input('s', '', array('size' => '30', 'class' => 'text', 'id' => $searchFieldId), TRUE);
        $widgetContent .= form_submit ('', __('Search', 'mw_frm'), array('class' => 'submit'), TRUE);
        $widgetContent .= '</fieldset></form>';
        $this->makeWidget( $widgetContent, $instance, $sidebarArgs );
    }
    
    /**
     * Update the widget settings.
     */
    public function update ($newIns, $oldIns)
    {
        $newIns = $this->setDefalutValues( $newIns );
        $instance = $this->updateWidgetOptions( $newIns, $oldIns );
        $instance = $this->updateWidgetDisplayOptions( $instance, $newIns );
        return $instance;
    }

    /**
     * Display widget settings controls on the widget panel.
     */
    function form ($instance)
    {
        $instance = $this->setDefalutValues( $instance );
        $html  = '<p>';
        $html .= form_label(__('Title:', 'mw_frm'), $this->fid('title'), NULL, TRUE);
        $html .= form_input($this->fnm('title'), $instance['title'], array('id'=>$this->fid('title'), 'class'=>'widefat'), TRUE);
        $html .= '</p>';
        $html .= $this->widgetDisplayOptionsForm($instance);
        $this->outputWidgetControl($html);
    }
}


/**
 *  ******************
 *    Calendar widget
 *  ******************
 */
class MW_Widget_Calendar extends MW_Widget
{
    /**
     * Widget setup.
     */
    public function MW_Widget_Calendar ()
    {
        $this->setupWidget(array(
            'name' => __('MW - Calendar', 'mw_frm'),
            'description' => __("A calendar of your site's posts", 'mw_frm'),
            'classname' => 'mw-widget-calendar',
            'id_base' => 'mw-widget-calendar'
        ));
        
        $this->default = array(
            'title' => __('Calendar', 'mw_frm'),
            'show_boxed' => 'no',
            'set_custom_styles' => 'no',
            'bg_color' => $this->get_default_widget_style('widget_bg_col'),
            'border_color' => $this->get_default_widget_style('widget_border_col'),
        );
    }
    
    /**
     * Display widget on the frontend.
     */
    function widget ($sidebarArgs, $instance)
    {
        ob_start();
        get_calendar();
        $widgetContent  = ob_get_contents();
        ob_end_clean();
        $this->makeWidget( $widgetContent, $instance, $sidebarArgs );
    }
    
    /**
     * Update the widget settings.
     */
    public function update ($newIns, $oldIns)
    {
        $newIns = $this->setDefalutValues( $newIns );
        $instance = $this->updateWidgetOptions( $newIns, $oldIns );
        $instance = $this->updateWidgetDisplayOptions( $instance, $newIns );
        return $instance;
    }

    /**
     * Display widget settings controls on the widget panel.
     */
    function form ($instance)
    {
        $instance = $this->setDefalutValues( $instance );
        $html  = '<p>';
        $html .= form_label(__('Title:', 'mw_frm'), $this->fid('title'), NULL, TRUE);
        $html .= form_input($this->fnm('title'), $instance['title'], array('id'=>$this->fid('title'), 'class'=>'widefat'), TRUE);
        $html .= '</p>';
        $html .= $this->widgetDisplayOptionsForm($instance);
        $this->outputWidgetControl($html);
    }
}


/**
 *  *************************
 *  Display custom navigation
 *  *************************
 */
class MW_Widget_Nav_Menu extends MW_Widget
{
    /**
     * Widget setup.
     */
    public function MW_Widget_Nav_Menu ()
    {
        $this->setupWidget(array(
            'name' => __('MW - Custom Menu', 'mw_frm'),
            'description' => __("Use this widget to add one of your custom menus as a widget", 'mw_frm'),
            'classname' => 'mw-widget-nav-menu',
            'id_base' => 'mw-widget-nav-menu'
        ));
        
        $this->default = array(
            'title' => __('Navigation menu', 'mw_frm'),
            'nav_menu' => '',
            'depth' => 'all',
            'level' => '',
            'show_boxed' => 'no',
            'set_custom_styles' => 'no',
            'bg_color' => $this->get_default_widget_style('widget_bg_col'),
            'border_color' => $this->get_default_widget_style('widget_border_col'),
        );
    }
    
    /**
     * Display widget on the frontend.
     */
    public function widget ($sidebarArgs, $instance)
    {
		$nav_menu = wp_get_nav_menu_object( $instance['nav_menu'] );
		if ( !isset($nav_menu) ) return;
        
        $depth = 0;
        if ( $instance['depth'] == 'all' )
            $depth = 0;
        elseif ( $instance['depth'] == 'toplevel' )
            $depth = 1;
        elseif ( $instance['depth'] == 'depth' && isset($instance['level']) )
            $depth = absint($instance['level']);
        
        $widgetContent = wp_nav_menu(array(
            'fallback_cb' => '',
            'echo' => 0,
            'container' => 0,
            'menu_class' => 'mw-widget-nav-menu-list',
            'depth' => $depth,
            'menu' => $nav_menu
        ));
        
		if ( !empty( $widgetContent ) )
            $this->makeWidget( $widgetContent, $instance, $sidebarArgs );
    }
    
    /**
     * Update the widget settings.
     */
    public function update ($newIns, $oldIns)
    {
        $newIns = $this->setDefalutValues( $newIns );
        $instance = $this->updateWidgetOptions( $newIns, $oldIns );
        $instance = $this->updateWidgetDisplayOptions($instance, $newIns);
        return $instance;
    }
    
    /**
     * Display widget settings controls on the widget panel.
     */
    public function form ($instance)
    {
        $instance = $this->setDefalutValues( $instance );
        $radioAllPages = (!isset($instance['depth']) || $instance['depth'] == 'all') ? TRUE : FALSE;
        $radioTopLevel = (!isset($instance['depth']) || $instance['depth'] == 'toplevel') ? TRUE : FALSE;
        $radioDepth    = (!isset($instance['depth']) || $instance['depth'] == 'depth') ? TRUE : FALSE;
        
        $menus = array();
        $tax = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
		foreach ( $tax as $menu ) {
            $menus[$menu->term_id] = $menu->name;
		}
        
        if ( count($menus) == 0 ) {
            $html = '<p>'. sprintf( __('No menus have been created yet. <a href="%s">Create some</a>.'), admin_url('nav-menus.php') ) .'</p>';
            $this->outputWidgetControl($html);
            return;
        }
        
        $html  = '<p>';
        $html .= form_label(__('Title:', 'mw_frm'), $this->fid('title'), NULL, TRUE);
        $html .= form_input($this->fnm('title'), $instance['title'], array('id'=>$this->fid('title'), 'class'=>'widefat'), TRUE);
        $html .= '</p><p>';
        $html .= form_label(__('Select menu:', 'mw_frm'), $this->fid('nav_menu'), NULL, TRUE);
        $html .= form_dropdown($this->fnm('nav_menu'), $menus, $instance['nav_menu'], array('id'=>$this->fid('nav_menu'), 'class'=>'widefat'), TRUE);
        $html .= '</p>';
        $html .= '<fieldset>';
        $html .= '<p><strong>' . __('How would you like the menu displayed?', 'mw_frm') . '</strong></p><p>';
        $html .= form_radio_labeled($this->fnm('depth'), 'all', __('All links hierarchically', 'mw_frm'), $this->fid('all'), $radioAllPages, NULL, TRUE);
        $html .= '<br/>';
        $html .= form_radio_labeled($this->fnm('depth'), 'toplevel', __('Top-level links only', 'mw_frm'), $this->fid('top-level'), $radioTopLevel, NULL, TRUE);
        $html .= '<br/>';
        $html .= form_radio_labeled($this->fnm('depth'), 'depth', __('Links up to level', 'mw_frm'), $this->fid('depth'), $radioDepth, NULL, TRUE);
        $html .= '&nbsp;';
        $html .= form_input($this->fnm('level'), $instance['level'], array('id'=>$this->fid('level'), 'size'=>'3'), TRUE);
        $html .= '</p>';
        $html .= '</fieldset>';
        $html .= $this->widgetDisplayOptionsForm($instance);
        $this->outputWidgetControl($html);
    }
}


/**
 *  *****************************************
 *  Meta widget - log in/out, RSS links, etc.
 *  *****************************************
 */
class MW_Widget_Meta extends MW_Widget
{
    /**
     * Widget setup.
     */
    public function MW_Widget_Meta ()
    {
        $this->setupWidget(array(
            'name' => __('MW - Meta', 'mw_frm'),
            'description' => __("Log in/out, admin, feed and WordPress links", 'mw_frm'),
            'classname' => 'mw-widget-meta',
            'id_base' => 'mw-widget-meta'
        ));
        
        $this->default = array(
            'title' => __('Meta', 'mw_frm'),
            'show_boxed' => 'no',
            'set_custom_styles' => 'no',
            'bg_color' => $this->get_default_widget_style('widget_bg_col'),
            'border_color' => $this->get_default_widget_style('widget_border_col'),
        );
    }
    
    /**
     * Display widget on the frontend.
     */
    function widget ($sidebarArgs, $instance)
    {
        $links = array();
        $links[] = wp_register('', '', FALSE);
        
        ob_start();
        wp_loginout();
        $links[] = ob_get_contents();
        ob_end_clean();
        
        $links[] = anchor( get_bloginfo('rss2_url'), __('Entries RSS', 'mw_frm'), array('title' => __('Syndicate this site using RSS 2.0', 'mw_frm')), TRUE );
        $links[] = anchor( get_bloginfo('comments_rss2_url'), __('Comments RSS', 'mw_frm'), array('title' => __('Latest comments in RSS', 'mw_frm')), TRUE );
        $links[] = anchor( 'http://wordpress.org/', 'WordPress.org', array('title' => __('Powered by WordPress', 'mw_frm')), TRUE );
        
        $widgetContent  = '<ul class="mw-widget-meta-list"><li>';
        $widgetContent .= implode('</li><li>', $links);
        $widgetContent .= '</li></ul>';
        $this->makeWidget( $widgetContent, $instance, $sidebarArgs );
    }
    
    /**
     * Update the widget settings.
     */
    public function update ($newIns, $oldIns)
    {
        $newIns = $this->setDefalutValues( $newIns );
        $instance = $this->updateWidgetOptions( $newIns, $oldIns );
        $instance = $this->updateWidgetDisplayOptions( $instance, $newIns );
        return $instance;
    }

    /**
     * Display widget settings controls on the widget panel.
     */
    function form ($instance)
    {
        $instance = $this->setDefalutValues( $instance );
        $html  = '<p>';
        $html .= form_label(__('Title:', 'mw_frm'), $this->fid('title'), NULL, TRUE);
        $html .= form_input($this->fnm('title'), $instance['title'], array('id'=>$this->fid('title'), 'class'=>'widefat'), TRUE);
        $html .= '</p>';
        $html .= $this->widgetDisplayOptionsForm($instance);
        $this->outputWidgetControl($html);
    }
}


/**
 *  ********************
 *  Display contact form
 *  ********************
 */
class MW_Widget_Contact_Form extends MW_Widget
{
    public $isCachable = TRUE;
    public $wpCacheKey = 'mw_widget_contact_form';
    
    /**
     * Widget setup.
     */
    public function MW_Widget_Contact_Form ()
    {
        $this->setupWidget(array(
            'name' => __('MW - Contact Form', 'mw_frm'),
            'description' => __("Display contact form", 'mw_frm'),
            'classname' => 'mw-widget-contact-form',
            'id_base' => 'mw-widget-contact-form'
        ));
        
        $this->default = array(
            'title' => __('Contact us', 'mw_frm'),
            'show_boxed' => 'no',
            'set_custom_styles' => 'no',
            'bg_color' => $this->get_default_widget_style('widget_bg_col'),
            'border_color' => $this->get_default_widget_style('widget_border_col'),
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
        ob_start();
        mw_theme_contact_form();
        $form = ob_get_contents();
        ob_end_clean();
        return $form;
    }
    
    /**
     * Update the widget settings.
     */
    public function update ($newIns, $oldIns)
    {
        $newIns = $this->setDefalutValues( $newIns );
        $instance = $this->updateWidgetOptions( $newIns, $oldIns );
        $instance = $this->updateWidgetDisplayOptions( $instance, $newIns );
        return $instance;
    }
    
    /**
     * Display widget settings controls on the widget panel.
     */
    public function form ($instance)
    {
        $instance = $this->setDefalutValues( $instance );
        $html  = '<p>';
        $html .= form_label(__('Title:', 'mw_frm'), $this->fid('title'), NULL, TRUE);
        $html .= form_input($this->fnm('title'), $instance['title'], array('id'=>$this->fid('title'), 'class'=>'widefat'), TRUE);
        $html .= '</p>';
        $html .= '<p>' . __('Contact form options are located in the Theme Options panel.', 'mw_frm') . '</p>';
        $html .= $this->widgetDisplayOptionsForm($instance);
        $this->outputWidgetControl($html);
    }
}

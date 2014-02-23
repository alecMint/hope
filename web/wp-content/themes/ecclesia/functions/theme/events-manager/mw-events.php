<?php if (!defined('MW_THEME')) die('No direct script access allowed');

/**
 * MW Events - Events Manager Class
 *
 * @package     MW_Theme
 * @subpackage  MW_Events
 *
 * @since 1.2
 */
class MW_Events
{
    private static $instance;
    public $now;
    
    public function __construct ()
    {
        // Set current time according to WordPress time zone settings
        $this->now = time() + (get_option('gmt_offset')*3600);
        
        // Initiate all functionality
        $this->setup();
    }
    
    /**
     * Return singleton instance
     *
     * @access public
     * @since 1.0
     *
     * @return obj
     */
    public static function get_instance ()
    {
        if ( empty( self::$instance ) ) {
            self::$instance = new MW_Events();
        }
        return self::$instance;
    }
    
    
    /**
     * Hook methods into WordPress
     *
     * @access private
     * @since 1.0
     */
    private function setup ()
    {
        /** SETUP CUSTOM POST TYPE AND TAXONOMY */
        
        // Register custom post type and taxonomy
        add_action( 'init', array( $this, 'add_event_postype' ) );
        add_action( 'init', array( $this, 'add_event_taxonomy' ), 0 );
        
        
        /** SETUP ADMIN INTERFACES */
        
        // Add custom meta box for entering event dates
        add_action( 'admin_init', array( $this, 'add_events_meta_box' ) );
        
        // Add columns to the Events admin page
        add_filter( 'manage_edit-mw_events_columns', array( $this, 'add_admin_columns' ) );
        add_action( 'manage_posts_custom_column',    array( $this, 'admin_column_data' ) );
        
        // Make the custom date columns sortable
        add_filter( 'manage_edit-mw_events_sortable_columns', array( $this, 'register_sortable_columns') );
        
        // Save custom fields with the events posts
        add_action( 'save_post',  array( $this, 'save_event' ) );
        
        // Customised update messages
        add_filter( 'post_updated_messages', array( $this, 'events_updated_messages' ) );
        
        
        /**  ADD CUSTOM FILTERS TO THE EVENTS ADMIN */
        
        // Add custom filters interface (taxonomy and event date) to the events table
        add_action( 'restrict_manage_posts', array( $this, 'add_custom_filters_interface' ) );
        
        // Convert event category id to taxonomy term to enable filtering by event category
        add_filter( 'parse_query', array( $this, 'event_cat_id_to_taxonomy_term' ) );
        
        // Add event date filter (past/future) to the events table
        add_filter( 'posts_join', array( $this, 'get_custom_field_posts_join' ) );
        add_filter( 'posts_where', array( $this, 'get_custom_field_posts_where' ) );
        
        
        /**  LOAD CSS AND JS FOR EVENTS ADMIN PAGES */
        
        add_action( 'admin_print_styles-edit.php',      array( $this, 'load_admin_styles' ),  1000 );
        add_action( 'admin_print_styles-post.php',      array( $this, 'load_post_editor_styles' ),  1000 );
        add_action( 'admin_print_styles-post-new.php',  array( $this, 'load_post_editor_styles' ),  1000 );
        add_action( 'admin_print_scripts-post.php',     array( $this, 'load_post_editor_scripts' ), 1000 );
        add_action( 'admin_print_scripts-post-new.php', array( $this, 'load_post_editor_scripts' ), 1000 );
    }
    
    
    
    //////////////////////////////////////////////
    //  SETUP CUSTOM POST TYPE AND TAXONOMY
    //////////////////////////////////////////////
    
    
    /**
     * Register "mw_events" custom post type
     *
     * @access public
     * @since 1.0
     */
    public function add_event_postype ()
    {
        $labels = array(
            'name'               => _x( 'Events', 'post type general name', 'mw_theme' ),
            'singular_name'      => _x( 'Event', 'post type singular name', 'mw_theme' ),
            'add_new'            => _x( 'Add New', 'events', 'mw_theme' ),
            'add_new_item'       => __( 'Add New Event', 'mw_theme' ),
            'edit_item'          => __( 'Edit Event', 'mw_theme' ),
            'new_item'           => __( 'New Event', 'mw_theme' ),
            'view_item'          => __( 'View Event', 'mw_theme' ),
            'search_items'       => __( 'Search Events', 'mw_theme' ),
            'not_found'          => __( 'No events found', 'mw_theme' ),
            'not_found_in_trash' => __( 'No events found in Trash', 'mw_theme' ),
            'parent_item_colon'  => '',
        );
        $args = array(
            'label'              => __( 'Events', 'mw_theme' ),
            'labels'             => $labels,
            'public'             => true,
            'show_ui'            => true,
            'capability_type'    => 'post',
            'menu_icon'          => MW_THEME_URL . 'events-manager/images/calendar.png',
            'hierarchical'       => false,
            'rewrite'            => array( "slug" => "events", 'with_front' => FALSE ),
            'has_archive'        => true,
            'supports'           => array( 'title', 'editor' ) ,
            'show_in_nav_menus'  => true,
            'taxonomies'         => array( 'mw_event_category' )
        );
        register_post_type( 'mw_events', $args);
    }
    
    
    /**
     * Register "mw_event_category" custom taxonomy
     *
     * @access public
     * @since 1.0
     */
    public function add_event_taxonomy ()
    {
        $labels = array(
            'name'                       => _x( 'Event Categories', 'taxonomy general name', 'mw_theme' ),
            'singular_name'              => _x( 'Event Category', 'taxonomy singular name', 'mw_theme' ),
            'search_items'               => __( 'Search Categories', 'mw_theme' ),
            'popular_items'              => __( 'Popular Categories', 'mw_theme' ),
            'all_items'                  => __( 'All Categories', 'mw_theme' ),
            'parent_item'                => NULL,
            'parent_item_colon'          => NULL,
            'edit_item'                  => __( 'Edit Category', 'mw_theme' ),
            'update_item'                => __( 'Update Category', 'mw_theme' ),
            'add_new_item'               => __( 'Add New Category', 'mw_theme' ),
            'new_item_name'              => __( 'New Category Name', 'mw_theme' ),
            'separate_items_with_commas' => __( 'Separate categories with commas', 'mw_theme' ),
            'add_or_remove_items'        => __( 'Add or remove categories', 'mw_theme' ),
            'choose_from_most_used'      => __( 'Choose from the most used categories', 'mw_theme' ),
        );
        register_taxonomy('mw_event_category','mw_events', array(
            'label'             => __( 'Event Category', 'mw_theme' ),
            'labels'            => $labels,
            'hierarchical'      => TRUE,
            'show_ui'           => TRUE,
            'show_in_nav_menus' => FALSE,
            'query_var'         => TRUE,
            'rewrite'           => array( 'slug' => 'event-category' ),
        ));
    }
    
    
    
    //////////////////////////////////////////////
    //  SETUP ADMIN INTERFACES
    //////////////////////////////////////////////
    
    
    /**
     * Add custom meta box for entering event dates
     *
     * @access public
     * @since 1.0
     */
    public function add_events_meta_box ()
    {
        add_meta_box('mw-events-meta', __('Event date and time', 'mw_theme' ), array( $this, 'event_dates_meta_box' ), 'mw_events', 'normal', 'core');
    }
    
    /**
     * Create event dates meta box
     *
     * @access public
     * @since 1.0
     */
    public function event_dates_meta_box ()
    {
        global $post;
        
        // get the data
        $data = get_post_custom($post->ID);
        $start_date = $data['mw_event_start_date'][0];
        $end_date   = $data['mw_event_end_date'][0];
        $start_time = $start_date;
        $end_time   = $end_date;
        
        // populate today if empty, 00:00 for time
        if ( $start_date == NULL ) {
            $start_date = $this->now;
            $start_time = 0;
        }
        if ( $end_date == NULL ) {
            $end_date = $this->now;
            $end_time = 0;
        }
        
        // apply date and time formats for display
        $start_date        = date( 'Y-m-d', $start_date );
        $start_time_hour   = date( 'H',     $start_time );
        $start_time_minute = date( 'i',     $start_time );
        $end_date          = date( 'Y-m-d', $end_date );
        $end_time_hour     = date( 'H',     $end_time );
        $end_time_minute   = date( 'i',     $end_time );
        
        wp_nonce_field( 'mw-save-event-dates', '_mw_event_nonce' );
        
        ?>
        <div class="mw-form-element">
            <?php form_label( __('Start date', 'mw_theme'), 'mw-event-start-date', array( 'id'=>'mw-event-start-date-label' ) ); ?>
            <?php form_input( 'mw_event_start_date', $start_date, array( 'id'=>'mw-event-start-date' ) ); ?>
            <?php form_label( __('Start time', 'mw_theme'), 'mw-event-start-time-hour', array( 'id'=>'mw-event-start-time-label' ) ); ?>
            <?php form_input( 'mw_event_start_time_hour', $start_time_hour, array( 'id'=>'mw-event-start-time-hour' ) ); ?>
            :
            <?php form_input( 'mw_event_start_time_minute', $start_time_minute, array( 'id'=>'mw-event-start-time-minute' ) ); ?>
            <em><?php _e('(24h format)', 'mw_theme'); ?></em>
        </div><div class="mw-form-element mw-event-end-date">
            <?php form_label( __('End date', 'mw_theme'), 'mw-event-end-date', array( 'id'=>'mw-event-end-date-label' ) ); ?>
            <?php form_input( 'mw_event_end_date', $end_date, array( 'id'=>'mw-event-end-date' ) ); ?>
            <?php form_label( __('End time', 'mw_theme'), 'mw-event-end-time-hour', array( 'id'=>'mw-event-end-time-label' ) ); ?>
            <?php form_input( 'mw_event_end_time_hour', $end_time_hour, array( 'id'=>'mw-event-end-time-hour' ) ); ?>
            :
            <?php form_input( 'mw_event_end_time_minute', $end_time_minute, array( 'id'=>'mw-event-end-time-minute' ) ); ?>
            <em><?php _e('(24h format)', 'mw_theme'); ?></em>
        </div>
        <?php
    }
    
    
    /**
     * Add columns to the Events admin page
     *
     * @access public
     * @since 1.0
     *
     * @return array
     */
    public function add_admin_columns ($columns)
    {
        $columns = array(
            'cb'                      => __('<input type=\'checkbox\' />', 'mw_theme'),
            'title'                   => __('Event Title', 'mw_theme'),
            'mw_col_event_start_date' => __('Start Date', 'mw_theme'),
            'mw_col_event_end_date'   => __('End Date', 'mw_theme'),
            'mw_col_event_cat'        => __('Event Categories', 'mw_theme'),
            'date'                    => __('Date Published', 'mw_theme'),
        );
        return $columns;
    }
    
    
    /**
     * Display data in the custom columns on the Events admin page
     *
     * @access public
     * @since 1.0
     */
    public function admin_column_data ( $column )
    {
        global $post;
        $data = get_post_custom();
        switch ($column) {
            case 'mw_col_event_start_date':
                $start_date = $data['mw_event_start_date'][0];
                echo date('Y-m-d  @ H:i', $start_date);
                break;
            case 'mw_col_event_end_date':
                $end_date = $data['mw_event_end_date'][0];
                echo date('Y-m-d  @ H:i', $end_date);
                break;
            case 'mw_col_event_cat':
                $event_terms = get_the_terms($post->ID, 'mw_event_category');
                $event_cats = array();
                if ($event_terms) {
                    foreach ($event_terms as $term) {
                        array_push($event_cats, $term->name);
                    }
                    echo implode($event_cats, ', ');
                } else {
                    _e('None', 'mw_theme');
                }
                break;
        }
    }
    
    
    /**
     * Make the custom date columns sortable
     *
     * @access public
     * @since 1.0
     *
     * @return array
     */
    public function register_sortable_columns( $columns ) {
        $columns['mw_col_event_start_date'] = 'mw_event_start_date';
        $columns['mw_col_event_end_date']   = 'mw_event_end_date';
        return $columns;
    }
    
    
    /**
     * Save custom fields with the events posts
     *
     * @access public
     * @since 1.0
     *
     * @return int
     */
    public function save_event ()
    {
        global $post;
        
        if ( !wp_verify_nonce( $_POST['_mw_event_nonce'], 'mw-save-event-dates' ))
            return $post->ID;
        if ( !current_user_can( 'edit_post', $post->ID ))
            return $post->ID;
        
        if ( !isset($_POST["mw_event_start_date"]) || !isset($_POST["mw_event_start_time_hour"]) || !isset($_POST["mw_event_start_time_minute"])
             || !isset($_POST["mw_event_end_date"]) || !isset($_POST["mw_event_end_time_hour"]) || !isset($_POST["mw_event_end_time_minute"]) )
        {
            return $post->ID;
        }
        
        // convert back to unix format & update post
        $start_date = strtotime( $_POST["mw_event_start_date"] . ' ' . $_POST["mw_event_start_time_hour"] . ':' . $_POST["mw_event_start_time_minute"] );
        $end_date   = strtotime( $_POST["mw_event_end_date"]   . ' ' . $_POST["mw_event_end_time_hour"]   . ':' . $_POST["mw_event_end_time_minute"] );
        update_post_meta( $post->ID, "mw_event_start_date", $start_date );
        update_post_meta( $post->ID, "mw_event_end_date",   $end_date );
    }
    
    
    /**
     * Customised update messages
     *
     * @access public
     * @since 1.0
     *
     * @return array
     */
    public function events_updated_messages( $messages ) {
        global $post, $post_ID;
        $messages['mw_events'] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => sprintf( __('Event updated. <a href="%s">View item</a>', 'mw_theme'), esc_url( get_permalink($post_ID) ) ),
            2 => __('Custom field updated.', 'mw_theme'),
            3 => __('Custom field deleted.', 'mw_theme'),
            4 => __('Event updated.', 'mw_theme'),
            5 => isset($_GET['revision']) ? sprintf( __('Event restored to revision from %s', 'mw_theme'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6 => sprintf( __('Event published. <a href="%s">View event</a>', 'mw_theme'), esc_url( get_permalink($post_ID) ) ),
            7 => __('Event saved.', 'mw_theme'),
            8 => sprintf( __('Event submitted. <a target="_blank" href="%s">Preview event</a>', 'mw_theme'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
            9 => sprintf( __('Event scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview event</a>', 'mw_theme'),
              date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
            10 => sprintf( __('Event draft updated. <a target="_blank" href="%s">Preview event</a>', 'mw_theme'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
        );
        return $messages;
    }
    
    
    
    //////////////////////////////////////////////
    //  ADD CUSTOM FILTERS TO THE EVENTS ADMIN
    //////////////////////////////////////////////
    
    
    /**
     * Add custom filters interface (taxonomy and event date) to the events table
     *
     * @access public
     * @since 1.0
     */
    public function add_custom_filters_interface ()
    {
        global $typenow, $wp_query;
        if ( $typenow != 'mw_events' ) return;
        
        // Add categories filter
		wp_dropdown_categories(array(
            'show_option_all' =>  __('Show all categories', 'mw_theme'),
            'taxonomy'        =>  'mw_event_category',
            'name'            =>  'mw_event_category',
            'orderby'         =>  'name',
            'selected'        =>  $wp_query->query['mw_event_category'],
            'hierarchical'    =>  true,
            'depth'           =>  5,
            'show_count'      =>  true,
            'hide_empty'      =>  false
        ));
        
        // Add event status filter
        $options = array(
            'all'    => __('Show all events', 'mw_theme'),
            'past'   => __('Show past events', 'mw_theme'),
            'future' => __('Current &amp; future events', 'mw_theme')
        );
        form_dropdown( 'mw_event_status', $options, ( isset($_GET['mw_event_status']) ? $_GET['mw_event_status'] : 'all' ) );
    }
    
    
    /**
     * Convert event category id to taxonomy term (in query_vars)
     * to enable filtering by event category
     *
     * @access public
     * @since 1.0
     */
    public function event_cat_id_to_taxonomy_term ( $query ) {
        global $pagenow;
        if ( $pagenow=='edit.php' && isset($query->query_vars['mw_event_category']) ) {
            $term = get_term_by('id', $query->query_vars['mw_event_category'] ,'mw_event_category');
            $query->query_vars['mw_event_category'] = $term->slug;
        }
    }
    
    
    /**
     * Event date filter (past/future)
     * - join posts with post meta to filter by event date
     *
     * @access public
     * @since 1.0
     *
     * @return string
     */
    public function get_custom_field_posts_join ( $join )
    {
        global $pagenow, $typenow, $wpdb;
        if ( $typenow == 'mw_events' && $pagenow == 'edit.php' && ($_GET['mw_event_status']=='past' || $_GET['mw_event_status']=='future') ) {
            return $join . " JOIN $wpdb->postmeta postmeta ON (postmeta.post_id = $wpdb->posts.ID AND postmeta.meta_key = 'mw_event_end_date') ";
        } else {
            return $join;
        }
    }
    
    
    /**
     * Event date filter (past/future)
     * - add SQL "where" clausule to select only past or future events
     *
     * @access public
     * @since 1.0
     *
     * @return string
     */
    public function get_custom_field_posts_where ( $where )
    {
        global $pagenow, $typenow;
        if ( $typenow == 'mw_events' && $pagenow == 'edit.php' && ($_GET['mw_event_status']=='past' || $_GET['mw_event_status']=='future') ) {
            $now = $this->now;
            if ( $_GET['mw_event_status']=='past' ) {
                return $where . " AND postmeta.meta_key = 'mw_event_end_date' AND postmeta.meta_value < '$now' ";
            } else {
                return $where . " AND postmeta.meta_key = 'mw_event_end_date' AND postmeta.meta_value >= '$now' ";
            }
        } else {
            return $where;
        }
    }
    
    
    
    //////////////////////////////////////////////
    //  LOAD CSS AND JS FOR EVENTS ADMIN PAGES
    //////////////////////////////////////////////
    
    
    /**
     * Load CSS styles for the Events admin page
     *
     * @access public
     * @since 1.0
     */
    public function load_admin_styles()
    {
        global $post_type;
        if( $post_type != 'mw_events' ) return;
        wp_enqueue_style('mw-events-css', MW_THEME_URL . 'events-manager/css/mw-events.css');
    }
    
    
    /**
     * Load CSS styles for the events editor page
     *
     * @access public
     * @since 1.0
     */
    public function load_post_editor_styles ()
    {
        global $post_type;
        if( $post_type != 'mw_events' ) return;
        wp_enqueue_style('ui-datepicker', MW_THEME_URL . 'events-manager/css/jquery-ui-1.8.11.custom.css');
        wp_enqueue_style('mw-events-css', MW_THEME_URL . 'events-manager/css/mw-events-post-editor.css');
    }
    
    
    /**
     * Load jQuery scripts for the events editor page
     *
     * @access public
     * @since 1.0
     */
    public function load_post_editor_scripts ()
    {
        global $post_type;
        if( $post_type != 'mw_events' ) return;
        wp_enqueue_script( 'ui-datepicker', MW_THEME_URL . 'events-manager/js/jquery.ui.datepicker.min.js', array('jquery', 'jquery-ui-core') );
        wp_enqueue_script( 'mw-events-admin', MW_THEME_URL .'events-manager/js/mw-events-admin.js', array('jquery', 'jquery-ui-core', 'ui-datepicker') );
    }
    
    
    
    //////////////////////////////////////////////
    //  GET THE EVENTS DATA
    //////////////////////////////////////////////
    
    public function get_events ()
    {
        global $wpdb;
        $now = $this->now;
        $sql_query = "
        SELECT posts.*, meta1.meta_value AS start_date, meta2.meta_value AS end_date
        FROM $wpdb->posts posts
        JOIN $wpdb->postmeta meta1 ON ( meta1.post_id = posts.ID AND meta1.meta_key = 'mw_event_start_date' )
        JOIN $wpdb->postmeta meta2 ON ( meta2.post_id = posts.ID AND meta2.meta_key = 'mw_event_end_date' )
        WHERE posts.post_type = 'mw_events'
        AND posts.post_status = 'publish'
        AND meta2.meta_value >= '$now'
        ORDER BY meta1.meta_value ASC
        ";
        $events = $wpdb->get_results($sql_query, OBJECT);
        return $events;
    }
    
}

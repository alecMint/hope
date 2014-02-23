<?php if (!defined('MW_THEME')) die('No direct script access allowed');

/** Get current color scheme */
$theme_options = get_option( 'mw_theme_options' );
if ( isset($theme_options['color_scheme']) ) {
    $current_color_scheme = $theme_options['color_scheme'];
    $def_colors = MW_Color_Schemes::get_instance()->get_scheme_colors($current_color_scheme);
} else {
    $def_colors = MW_Color_Schemes::get_instance()->get_default_scheme_colors();
}
$default_color_scheme = MW_Color_Schemes::get_instance()->get_default_scheme();
$color_schemes = MW_Color_Schemes::get_instance()->get_color_schemes();


// Supported item types:
//   heading, textblock, input, checkbox, checkbox_group, radio, select, textarea, upload, colorpicker,
//   post, posts, page, pages, category, categories, tag, tags, custom_post, custom_posts,
//   measurement, slider

$options = array(

    /** General settings */
    
    array(
        'item_id'       => 'general_settings',
        'item_type'     => 'heading',
        'item_title'    => __('General Settings', 'mw_theme')
    ),
    array(
        'item_id'       => 'logo_img',
        'item_type'     => 'upload',
        'item_title'    => __('Custom logo', 'mw_theme'),
        'item_desc'     => __('Upload your own logo image or enter image URL', 'mw_theme'),
        'default'       => ''
    ),
    array(
        'item_id'       => 'logo_pos_y',
        'item_type'     => 'input',
        'item_title'    => __('Adjust logo vertical position', 'mw_theme'),
        'item_desc'     => __('Logo is positioned absolutely above the bottom edge of the site header<br/><br/>Positive number = move up<br/>Negative number = move down', 'mw_theme'),
        'default'       => ''
    ),
    array(
        'item_id'       => 'use_site_title',
        'item_type'     => 'checkbox',
        'item_title'    => __('Use site title instead of logo image', 'mw_theme'),
        'item_desc'     => __('With this option selected the theme will display site title in place of the logo. Edit the site title in "Settings &rsaquo; General"', 'mw_theme'),
        'item_options'  => 'Yes',
        'default'       => FALSE
    ),
    array(
        'item_id'       => 'show_top_nav',
        'item_type'     => 'checkbox',
        'item_title'    => __('Show secondary navigation?', 'mw_theme'),
        'item_desc'     => __('Unchecking this box you can disable the secondary navigation displayed in the top right corner of the page', 'mw_theme'),
        'item_options'  => 'Yes',
        'default'       => TRUE
    ),
    array(
        'item_id'       => 'posts_show_author_box',
        'item_type'     => 'checkbox',
        'item_title'    => __('Show author info at the end of blog posts?', 'mw_theme'),
        'item_desc'     => __('By default this theme will show information about post author at the and of the blog post', 'mw_theme'),
        'item_options'  => 'Yes',
        'default'       => TRUE
    ),
    array(
        'item_id'       => 'sidebar_position',
        'item_type'     => 'radio',
        'item_title'    => __('Sidebar position on Posts and Pages', 'mw_theme'),
        'item_desc'     => __('Select whether the sidebar on Posts and Pages should be displayed on right or left side of the page', 'mw_theme'),
        'default'       => 'right',
        'item_options'  => array(
            'right' => __('Sidebar on the right', 'mw_theme'),
            'left'   => __('Sidebar on the left', 'mw_theme')
        )
    ),
    array(
        'item_id'       => 'footnotes_content',
        'item_type'     => 'textarea',
        'item_title'    => __('Footnotes', 'mw_theme'),
        'item_desc'     => __('Enter content (text or HTML) to display below the site content area. This can be used to show copyright information, privacy policy, etc.', 'mw_theme'),
        'default'       => '',
        'item_options'  => 7
    ),
    array(
        'item_id'       => 'tracking_code',
        'item_type'     => 'textarea',
        'item_title'    => __('Tracking code', 'mw_theme'),
        'item_desc'     => __('Paste here your Google Analytics (or other) tracking code. Please include the <code>&lt; script &gt;</code> tags. This will be added into the footer of your site', 'mw_theme'),
        'default'       => '',
        'item_options'  => 7
    ),
    array(
        'item_id'       => 'custom_css',
        'item_type'     => 'textarea',
        'item_title'    => __('Custom CSS', 'mw_theme'),
        'item_desc'     => __('Here you can quickly add some custom CSS. It will be added at the end of the &lt; head &gt; section', 'mw_theme'),
        'default'       => '',
        'item_options'  => 15
    ),
    
    
    
     /** Frontpage layout */
    
    array(
        'item_id'       => 'frontpage_layout',
        'item_type'     => 'heading',
        'item_title'    => __('Frontpage Layout', 'mw_theme')
    ),
    array(
        'item_id'       => 'frontpage_layout_info',
        'item_type'     => 'textblock',
        'item_desc'     =>
        '<img src="'. MW_THEME_URL . 'admin-assets/frontpage-layout-diagram.png" width="550" height="416" style="display:block;margin-bottom:20px;"/>'
        . __('<p>The frontpage consists of 2 widgetised panels, plus global header and footer. Use the settings below to control the layout of <strong>Panel 1</strong> and <strong>Panel 2</strong>.</p>', 'mw_theme')
    ),
    
    // Panel 1
    array(
        'item_id'       => 'panel_1',
        'item_type'     => 'heading2',
        'item_title'    => __('Panel 1', 'mw_theme')
    ),
    array(
        'item_id'       => 'frontpage_layout_info',
        'item_type'     => 'textblock',
        'item_desc'     => '<img src="'. MW_THEME_URL . 'admin-assets/frontpage-layout-diagram-panel-1.png" width="540" height="200" />'
    ),
    array(
        'item_id'       => 'show_panel_1',
        'item_type'     => 'checkbox',
        'item_title'    => __('Show Panel 1', 'mw_theme'),
        'item_options'  => 'Yes',
        'default'       => TRUE
    ),
    array(
        'item_id'       => 'panel_1_layout',
        'item_type'     => 'radio',
        'item_title'    => __('Select Panel 1 layout', 'mw_theme'),
        'item_desc'     => __('<strong>Sidebar</strong> and <strong>Featured</strong> are widgetised areas. To add widgets go to <em>Appearance &rsaquo; Widgets</em>.<br/> <strong>Slider</strong> content is managed via this options panel, just switch to the <em>Frontpage Slider</em> tab.', 'mw_theme'),
        'default'       => 'sr_s_f',
        'item_options'  => array(
            'sr_s_f' => __('Sidebar on right + Slider + Featured', 'mw_theme'),
            'sr_s'   => __('Sidebar on right + Slider', 'mw_theme'),
            'sr_f'   => __('Sidebar on right + Featured', 'mw_theme'),
            'sl_s_f' => __('Sidebar on left + Slider + Featured', 'mw_theme'),
            'sl_s'   => __('Sidebar on left + Slider', 'mw_theme'),
            'sl_f'   => __('Sidebar on left + Featured', 'mw_theme'),
            'full'   => __('Only Featured (no Sidebar, no Slider)', 'mw_theme'),
        )
    ),
    
    // Panel 2
    array(
        'item_id'       => 'panel_2',
        'item_type'     => 'heading2',
        'item_title'    => __('Panel 2', 'mw_theme')
    ),
    array(
        'item_id'       => 'frontpage_layout_info',
        'item_type'     => 'textblock',
        'item_desc'     => '<img src="'. MW_THEME_URL . 'admin-assets/frontpage-layout-diagram-panel-2.png" width="531" height="184" />'
    ),
    array(
        'item_id'       => 'show_panel_2',
        'item_type'     => 'checkbox',
        'item_title'    => __('Show Panel 2', 'mw_theme'),
        'item_options'  => 'Yes',
        'default'       => TRUE
    ),
    array(
        'item_id'       => 'panel_2_layout',
        'item_type'     => 'radio',
        'item_title'    => __('Select Panel 2 layout', 'mw_theme'),
        'item_desc'     => __('All columns in Panel 2 are widgetised areas. Add widgets in <em>Appearance &rsaquo; Widgets</em>', 'mw_theme'),
        'default'       => '3col',
        'item_options'  => array(
            '3col' => __('3 equal columns', 'mw_theme'),
            '2col' => __('2 equal columns', 'mw_theme'),
            'sr'   => __('Column + sidebar on right', 'mw_theme'),
            'sl'   => __('Column + sidebar on left', 'mw_theme'),
            'full' => __('Full width column', 'mw_theme')
        )
    ),
    
    

    /** Frontpage slider */
    
    array(
        'item_id'       => 'front_slider',
        'item_type'     => 'heading',
        'item_title'    => __('Frontpage Slider', 'mw_theme')
    ),
    
    // slide 1
    array(
        'item_id'       => 'slide_1',
        'item_type'     => 'heading2',
        'item_title'    => __('Slide 1', 'mw_theme')
    ),
    array(
        'item_id'       => 'slide_1_img',
        'item_type'     => 'upload',
        'item_title'    => __('Slide 1 - image', 'mw_theme'),
        'item_desc'     => __('Image size: 612 x 290 px', 'mw_theme')
    ),
    array(
        'item_id'       => 'slide_1_heading',
        'item_type'     => 'input',
        'item_title'    => __('Slide 1 - heading', 'mw_theme')
    ),
    array(
        'item_id'       => 'slide_1_text',
        'item_type'     => 'textarea',
        'item_title'    => __('Slide 1 - text', 'mw_theme'),
        'item_options'  => 2
    ),
    array(
        'item_id'       => 'slide_1_link',
        'item_type'     => 'input',
        'item_title'    => __('Slide 1 - link URL', 'mw_theme')
    ),
    
    // slide 2
    array(
        'item_id'       => 'slide_2',
        'item_type'     => 'heading2',
        'item_title'    => __('Slide 2', 'mw_theme')
    ),
    array(
        'item_id'       => 'slide_2_img',
        'item_type'     => 'upload',
        'item_title'    => __('Slide 2 - image', 'mw_theme'),
        'item_desc'     => __('Image size: 612 x 290 px', 'mw_theme')
    ),
    array(
        'item_id'       => 'slide_2_heading',
        'item_type'     => 'input',
        'item_title'    => __('Slide 2 - heading', 'mw_theme')
    ),
    array(
        'item_id'       => 'slide_2_text',
        'item_type'     => 'textarea',
        'item_title'    => __('Slide 2 - text', 'mw_theme'),
        'item_options'  => 2
    ),
    array(
        'item_id'       => 'slide_2_link',
        'item_type'     => 'input',
        'item_title'    => __('Slide 2 - link URL', 'mw_theme')
    ),
    
    // slide 3
    array(
        'item_id'       => 'slide_3',
        'item_type'     => 'heading2',
        'item_title'    => __('Slide 3', 'mw_theme')
    ),
    array(
        'item_id'       => 'slide_3_img',
        'item_type'     => 'upload',
        'item_title'    => __('Slide 3 - image', 'mw_theme'),
        'item_desc'     => __('Image size: 612 x 290 px', 'mw_theme')
    ),
    array(
        'item_id'       => 'slide_3_heading',
        'item_type'     => 'input',
        'item_title'    => __('Slide 3 - heading', 'mw_theme')
    ),
    array(
        'item_id'       => 'slide_3_text',
        'item_type'     => 'textarea',
        'item_title'    => __('Slide 3 - text', 'mw_theme'),
        'item_options'  => 2
    ),
    array(
        'item_id'       => 'slide_3_link',
        'item_type'     => 'input',
        'item_title'    => __('Slide 3 - link URL', 'mw_theme')
    ),
    
    // slide 4
    array(
        'item_id'       => 'slide_4',
        'item_type'     => 'heading2',
        'item_title'    => __('Slide 4', 'mw_theme')
    ),
    array(
        'item_id'       => 'slide_4_img',
        'item_type'     => 'upload',
        'item_title'    => __('Slide 4 - image', 'mw_theme'),
        'item_desc'     => __('Image size: 612 x 290 px', 'mw_theme')
    ),
    array(
        'item_id'       => 'slide_4_heading',
        'item_type'     => 'input',
        'item_title'    => __('Slide 4 - heading', 'mw_theme')
    ),
    array(
        'item_id'       => 'slide_4_text',
        'item_type'     => 'textarea',
        'item_title'    => __('Slide 4 - text', 'mw_theme'),
        'item_options'  => 2
    ),
    array(
        'item_id'       => 'slide_4_link',
        'item_type'     => 'input',
        'item_title'    => __('Slide 4 - link URL', 'mw_theme')
    ),
    
    // slide 5
    array(
        'item_id'       => 'slide_5',
        'item_type'     => 'heading2',
        'item_title'    => __('Slide 5', 'mw_theme')
    ),
    array(
        'item_id'       => 'slide_5_img',
        'item_type'     => 'upload',
        'item_title'    => __('Slide 5 - image', 'mw_theme'),
        'item_desc'     => __('Image size: 612 x 290 px', 'mw_theme')
    ),
    array(
        'item_id'       => 'slide_5_heading',
        'item_type'     => 'input',
        'item_title'    => __('Slide 5 - heading', 'mw_theme')
    ),
    array(
        'item_id'       => 'slide_5_text',
        'item_type'     => 'textarea',
        'item_title'    => __('Slide 5 - text', 'mw_theme'),
        'item_options'  => 2
    ),
    array(
        'item_id'       => 'slide_5_link',
        'item_type'     => 'input',
        'item_title'    => __('Slide 5 - link URL', 'mw_theme')
    ),

    // slide 6
    array(
        'item_id'       => 'slide_6',
        'item_type'     => 'heading2',
        'item_title'    => __('Slide 6', 'mw_theme')
    ),
    array(
        'item_id'       => 'slide_6_img',
        'item_type'     => 'upload',
        'item_title'    => __('Slide 6 - image', 'mw_theme'),
        'item_desc'     => __('Image size: 612 x 290 px', 'mw_theme')
    ),
    array(
        'item_id'       => 'slide_6_heading',
        'item_type'     => 'input',
        'item_title'    => __('Slide 6 - heading', 'mw_theme')
    ),
    array(
        'item_id'       => 'slide_6_text',
        'item_type'     => 'textarea',
        'item_title'    => __('Slide 6 - text', 'mw_theme'),
        'item_options'  => 2
    ),
    array(
        'item_id'       => 'slide_6_link',
        'item_type'     => 'input',
        'item_title'    => __('Slide 6 - link URL', 'mw_theme')
    ),
    
    
    
    /** Contact forms */
    
    
    array(
        'item_id'       => 'contact_forms',
        'item_type'     => 'heading',
        'item_title'    => __('Contact forms', 'mw_theme')
    ),
    
    // Settings
    array(
        'item_id'       => 'contact_form_settings',
        'item_type'     => 'heading2',
        'item_title'    => __('Contact form settings', 'mw_theme')
    ),
    array(
        'item_id'       => 'contact_email',
        'item_type'     => 'input',
        'item_title'    => __('Contact email address', 'mw_theme'),
        'item_desc'     => __('Enter email address where you want the contact forms sent to', 'mw_theme')
    ),
    array(
        'item_id'       => 'contact_subject_pfx',
        'item_type'     => 'input',
        'item_title'    => __('Message subject prefix', 'mw_theme'),
        'item_desc'     => __('Subject line prefix helps identifying in your inbox messages from the contact form', 'mw_theme'),
        'default'       => '[Message from contact form]'
    ),
    array(
        'item_id'       => 'contact_ajax_submit',
        'item_type'     => 'checkbox',
        'item_title'    => __('Use AJAX posting?', 'mw_theme'),
        'item_desc'     => __('Check this box if you want the contact forms posted via AJAX (without page reload)', 'mw_theme'),
        'item_options'  => 'Yes',
        'default'       => TRUE
    ),
    array(
        'item_id'       => 'contact_msg_thanks',
        'item_type'     => 'textarea',
        'item_title'    => __('"Thank you" message', 'mw_theme'),
        'item_desc'     => __('Message displayed to the user when the form is sent', 'mw_theme'),
        'item_options'  => 2,
        'default'       => __('Your message has been sent. Thank you.', 'mw_theme')
    ),
    array(
        'item_id'       => 'contact_msg_error',
        'item_type'     => 'textarea',
        'item_title'    => __('Error message', 'mw_theme'),
        'item_desc'     => __('This error message will be displayed to the user if there are any problems with form submission', 'mw_theme'),
        'item_options'  => 2,
        'default'       => __('There was an error while sending your message.', 'mw_theme')
    ),
    
    // Fields
    array(
        'item_id'       => 'contact_form_fields',
        'item_type'     => 'heading2',
        'item_title'    => __('Contact form fields', 'mw_theme')
    ),
    
    array(
        'item_id'       => 'contact_show_name',
        'item_type'     => 'checkbox',
        'item_title'    => __('Show "Name" field?', 'mw_theme'),
        'item_options'  => 'Yes',
        'default'       => TRUE
    ),
    array(
        'item_id'       => 'contact_req_name',
        'item_type'     => 'checkbox',
        'item_title'    => __('Is "Name" field required?', 'mw_theme'),
        'item_options'  => 'Yes',
        'default'       => TRUE
    ),
    array(
        'item_id'       => 'contact_label_name',
        'item_type'     => 'input',
        'item_title'    => __('"Name" field label', 'mw_theme'),
        'default'       => __('Name', 'mw_theme'),
    ),
    
    array(
        'item_id'       => 'contact_show_email',
        'item_type'     => 'checkbox',
        'item_title'    => __('Show "Email" field?', 'mw_theme'),
        'item_options'  => 'Yes',
        'default'       => TRUE
    ),
    array(
        'item_id'       => 'contact_req_email',
        'item_type'     => 'checkbox',
        'item_title'    => __('Is "Email" field required?', 'mw_theme'),
        'item_options'  => 'Yes',
        'default'       => TRUE
    ),
    array(
        'item_id'       => 'contact_label_email',
        'item_type'     => 'input',
        'item_title'    => __('"Email" field label', 'mw_theme'),
        'default'       => __('Email', 'mw_theme'),
    ),
    
    array(
        'item_id'       => 'contact_show_phone',
        'item_type'     => 'checkbox',
        'item_title'    => __('Show "Telephone" field?', 'mw_theme'),
        'item_options'  => 'Yes',
        'default'       => TRUE
    ),
    array(
        'item_id'       => 'contact_req_phone',
        'item_type'     => 'checkbox',
        'item_title'    => __('Is "Telephone" field required?', 'mw_theme'),
        'item_options'  => 'Yes',
        'default'       => TRUE
    ),
    array(
        'item_id'       => 'contact_label_phone',
        'item_type'     => 'input',
        'item_title'    => __('"Telephone" field label', 'mw_theme'),
        'default'       => __('Telephone', 'mw_theme'),
    ),
    
    array(
        'item_id'       => 'contact_show_subject',
        'item_type'     => 'checkbox',
        'item_title'    => __('Show "Subject" field?', 'mw_theme'),
        'item_options'  => 'Yes',
        'default'       => TRUE
    ),
    array(
        'item_id'       => 'contact_req_subject',
        'item_type'     => 'checkbox',
        'item_title'    => __('Is "Subject" field required?', 'mw_theme'),
        'item_options'  => 'Yes',
        'default'       => TRUE
    ),
    array(
        'item_id'       => 'contact_label_subject',
        'item_type'     => 'input',
        'item_title'    => __('"Subject" field label', 'mw_theme'),
        'default'       => __('Subject', 'mw_theme'),
    ),
    
    array(
        'item_id'       => 'contact_label_message',
        'item_type'     => 'input',
        'item_title'    => __('"Message" field label', 'mw_theme'),
        'item_desc'     => __('Message field is always displayed and always required', 'mw_theme'),
        'default'       => __('Message', 'mw_theme'),
    ),
    
    array(
        'item_id'       => 'contact_submit_btn_label',
        'item_type'     => 'input',
        'item_title'    => __('Submit button label', 'mw_theme'),
        'default'       => __('Send message', 'mw_theme'),
    ),
    
    
    
    /** Color options */
    
    
    array(
        'item_id'       => 'color_options',
        'item_type'     => 'heading',
        'item_title'    => __('Color Options', 'mw_theme')
    ),
    array(
        'item_id'       => 'color_scheme',
        'item_type'     => 'select',
        'item_title'    => __('Select color scheme', 'mw_theme'),
        'item_desc'     => __('Select a color scheme. You can modify individual color settings below.', 'mw_theme'),
        'item_options'  => implode(',', $color_schemes),
        'default'       => $default_color_scheme
    ),
    array(
        'item_id'       => 'color_scheme_info',
        'item_type'     => 'textblock',
        'item_desc'     => __('<strong>WARNING!</strong> Selecting a different color scheme will reset your color modifications below to the color scheme\'s default colors.', 'mw_theme')
    ),
    
    
    // Site background options
    array(
        'item_id'       => 'site_bg_options',
        'item_type'     => 'heading2',
        'item_title'    => __('Site background options', 'mw_theme')
    ),
    array(
        'item_id'       => 'use_custom_site_bg',
        'item_type'     => 'checkbox',
        'item_title'    => __('Use custom site background settings?', 'mw_theme'),
        'item_desc'     => __('Check this box if you want to override the theme&rsquo;s default background settings and set your own below', 'mw_theme'),
        'item_options'  => 'Yes',
        'default'       => FALSE
    ),
    array(
        'item_id'       => 'site_bg_col',
        'item_type'     => 'colorpicker',
        'item_title'    => __('Site background color', 'mw_theme'),
        'item_desc'     => sprintf( __('Pick a custom site background color (color&nbsp;scheme:&nbsp;%s)', 'mw_theme'), '<span id="desc_site_bg_col">'. $def_colors['site_bg_col'] .'</span>' ),
        'default'       => $def_colors['site_bg_col']
    ),
    array(
        'item_id'       => 'site_bg_img',
        'item_type'     => 'upload',
        'item_title'    => __('Site background image', 'mw_theme'),
        'item_desc'     => __('Upload your own background image', 'mw_theme'),
        'default'       => ''
    ),
    array(
        'item_id'       => 'site_bg_img_repeat',
        'item_type'     => 'select',
        'item_title'    => __('Site background image repeat', 'mw_theme'),
        'item_desc'     => __('Select how you want to repeat the background image', 'mw_theme'),
        'item_options'  => 'no-repeat,repeat-x,repeat-y,repeat',
        'default'       => 'no-repeat'
    ),
    array(
        'item_id'       => 'site_bg_img_pos',
        'item_type'     => 'select',
        'item_title'    => __('Site background image position', 'mw_theme'),
        'item_desc'     => __('Select how you want to position the background image', 'mw_theme'),
        'item_options'  => 'top center,top left,top right,center center,center left,center right,bottom center,bottom left,bottom right',
        'default'       => 'top center'
    ),
    
    // Content area options
    array(
        'item_id'       => 'content_bg_options',
        'item_type'     => 'heading2',
        'item_title'    => __('Content area background options', 'mw_theme')
    ),
    array(
        'item_id'       => 'use_custom_content_bg',
        'item_type'     => 'checkbox',
        'item_title'    => __('Use custom content area background settings?', 'mw_theme'),
        'item_desc'     => __('Check this box if you want to override the theme&rsquo;s default background settings and set your own below', 'mw_theme'),
        'item_options'  => 'Yes',
        'default'       => FALSE
    ),
    array(
        'item_id'       => 'content_bg_col',
        'item_type'     => 'colorpicker',
        'item_title'    => __('Content area background color', 'mw_theme'),
        'item_desc'     => sprintf( __('Pick a custom content background color (color&nbsp;scheme:&nbsp;%s)', 'mw_theme'), '<span id="desc_content_bg_col">'. $def_colors['content_bg_col'] .'</span>' ),
        'default'       => $def_colors['content_bg_col']
    ),
    array(
        'item_id'       => 'content_border_col',
        'item_type'     => 'colorpicker',
        'item_title'    => __('Content area border color', 'mw_theme'),
        'item_desc'     => sprintf( __('Pick a custom content area border color (color&nbsp;scheme:&nbsp;%s)', 'mw_theme'), '<span id="desc_content_border_col">'. $def_colors['content_border_col'] .'</span>' ),
        'default'       => $def_colors['content_border_col']
    ),
    array(
        'item_id'       => 'content_bg_img',
        'item_type'     => 'upload',
        'item_title'    => __('Content area background image', 'mw_theme'),
        'item_desc'     => __('Upload your own background image', 'mw_theme'),
        'default'       => ''
    ),
    array(
        'item_id'       => 'content_bg_img_repeat',
        'item_type'     => 'select',
        'item_title'    => __('Content area background image repeat', 'mw_theme'),
        'item_desc'     => __('Select how you want to repeat the background image', 'mw_theme'),
        'item_options'  => 'no-repeat,repeat-x,repeat-y,repeat',
        'default'       => 'no-repeat'
    ),
    array(
        'item_id'       => 'content_bg_img_pos',
        'item_type'     => 'select',
        'item_title'    => __('Content area background image position', 'mw_theme'),
        'item_desc'     => __('Select how you want to position the background image', 'mw_theme'),
        'item_options'  => 'top center,top left,top right,center center,center left,center right,bottom center,bottom left,bottom right',
        'default'       => 'top center'
    ),
    
    // Content color options
    array(
        'item_id'       => 'content_col_options',
        'item_type'     => 'heading2',
        'item_title'    => __('Content colors', 'mw_theme'),
    ),
    array(
        'item_id'       => 'use_custom_text_col',
        'item_type'     => 'checkbox',
        'item_title'    => __('Use custom content color settings?', 'mw_theme'),
        'item_desc'     => __('Check this box if you want to override the theme&rsquo;s default text color settings and set your own below', 'mw_theme'),
        'item_options'  => 'Yes',
        'default'       => FALSE
    ),
    array(
        'item_id'       => 'col_text',
        'item_type'     => 'colorpicker',
        'item_title'    => __('Body text color', 'mw_theme'),
        'item_desc'     => sprintf( __('Pick a color for all normal body text (color&nbsp;scheme:&nbsp;%s)', 'mw_theme'), '<span id="desc_col_text">'. $def_colors['col_text'] .'</span>' ),
        'default'       => $def_colors['col_text']
    ),
    array(
        'item_id'       => 'col_headings',
        'item_type'     => 'colorpicker',
        'item_title'    => __('Headings color', 'mw_theme'),
        'item_desc'     => sprintf( __('Pick a color for headings (color&nbsp;scheme:&nbsp;%s)', 'mw_theme'), '<span id="desc_col_headings">'. $def_colors['col_headings'] .'</span>' ),
        'default'       => $def_colors['col_headings']
    ),
    array(
        'item_id'       => 'col_link',
        'item_type'     => 'colorpicker',
        'item_title'    => __('Link color', 'mw_theme'),
        'item_desc'     => sprintf( __('Pick a color for the links (color&nbsp;scheme:&nbsp;%s)', 'mw_theme'), '<span id="desc_col_link">'. $def_colors['col_link'] .'</span>' ),
        'default'       => $def_colors['col_link']
    ),
    array(
        'item_id'       => 'col_link_hover',
        'item_type'     => 'colorpicker',
        'item_title'    => __('Link hover color', 'mw_theme'),
        'item_desc'     => sprintf( __('Pick a color for links hover state (color&nbsp;scheme:&nbsp;%s)', 'mw_theme'), '<span id="desc_col_link_hover">'. $def_colors['col_link_hover'] .'</span>' ),
        'default'       => $def_colors['col_link_hover']
    ),
    array(
        'item_id'       => 'col_nav_link',
        'item_type'     => 'colorpicker',
        'item_title'    => __('Top navigation link color', 'mw_theme'),
        'item_desc'     => sprintf( __('Pick a color for the navigation links (color&nbsp;scheme:&nbsp;%s)', 'mw_theme'), '<span id="desc_col_nav_link">'. $def_colors['col_nav_link'] .'</span>' ),
        'default'       => $def_colors['col_nav_link']
    ),
    array(
        'item_id'       => 'col_nav_link_hover',
        'item_type'     => 'colorpicker',
        'item_title'    => __('Top navigation link hover color', 'mw_theme'),
        'item_desc'     => sprintf( __('Pick a color for navigation links hover state (color&nbsp;scheme:&nbsp;%s)', 'mw_theme'), '<span id="desc_col_nav_link_hover">'. $def_colors['col_nav_link_hover'] .'</span>' ),
        'default'       => $def_colors['col_nav_link_hover']
    ),
    array(
        'item_id'       => 'col_subnav_link_hover',
        'item_type'     => 'colorpicker',
        'item_title'    => __('Top navigation drop-down link hover color', 'mw_theme'),
        'item_desc'     => sprintf( __('Pick a color for links hover state in the main navigation drop-down (color&nbsp;scheme:&nbsp;%s)', 'mw_theme'), '<span id="desc_col_subnav_link_hover">'. $def_colors['col_subnav_link_hover'] .'</span>' ),
        'default'       => $def_colors['col_subnav_link_hover']
    ),
    array(
        'item_id'       => 'col_btn',
        'item_type'     => 'colorpicker',
        'item_title'    => __('Button color', 'mw_theme'),
        'item_desc'     => sprintf( __('Pick a color for buttons (color&nbsp;scheme:&nbsp;%s)', 'mw_theme'), '<span id="desc_col_btn">'. $def_colors['col_btn'] .'</span>' ),
        'default'       => $def_colors['col_btn']
    ),
    
    // Boxed widget color options
    array(
        'item_id'       => 'widget_col_options',
        'item_type'     => 'heading2',
        'item_title'    => __('Widget color options', 'mw_theme'),
    ),
    array(
        'item_id'       => 'widget_col_options_info',
        'item_type'     => 'textblock',
        'item_desc'     => __('Widget color settings apply to <em>boxed</em> widgets only. You can override them for individual widgets in the wideget&rsquo;s own settings.', 'mw_theme'),
    ),
    array(
        'item_id'       => 'use_custom_widget_styles',
        'item_type'     => 'checkbox',
        'item_title'    => __('Use custom widget color settings?', 'mw_theme'),
        'item_desc'     => __('Check this box if you want to override the theme&rsquo;s default widget color settings and set your own below', 'mw_theme'),
        'item_options'  => 'Yes',
        'default'       => FALSE
    ),
    array(
        'item_id'       => 'widget_bg_col',
        'item_type'     => 'colorpicker',
        'item_title'    => __('Widget background color', 'mw_theme'),
        'item_desc'     => sprintf( __('Pick a background color (color&nbsp;scheme:&nbsp;%s)', 'mw_theme'), '<span id="desc_widget_bg_col">'. $def_colors['widget_bg_col'] .'</span>' ),
        'default'       => $def_colors['widget_bg_col']
    ),
    array(
        'item_id'       => 'widget_border_col',
        'item_type'     => 'colorpicker',
        'item_title'    => __('Widget border color', 'mw_theme'),
        'item_desc'     => sprintf( __('Pick a border color (color&nbsp;scheme:&nbsp;%s)', 'mw_theme'), '<span id="desc_widget_border_col">'. $def_colors['widget_border_col'] .'</span>' ),
        'default'       => $def_colors['widget_border_col']
    ),
    
    
);
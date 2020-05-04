<?php

/**
* For full documentation, please visit: http://docs.reduxframework.com/
* For a more extensive sample-config file, you may look at:
* https://github.com/reduxframework/redux-framework/blob/master/sample/sample-config.php
*/

if ( ! class_exists( 'Redux' ) ) {
    return;
}

// This is your option name where all the Redux data is stored.
$opt_name = "vslmd_options";
$ReduxFrameworkAssets = get_template_directory_uri() . '/vslmd/options/redux-framework/assets/';

/**
* ---> SET ARGUMENTS
* All the possible arguments for Redux.
* For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
* */

//Query Sliders

if ( class_exists( 'RevSlider' ) ) {
    
    $slider = new RevSlider();
    $arrSliders = $slider->getArrSliders();
    $revsliders = array();
    
    if ( $arrSliders ) {
        foreach ( $arrSliders as $slider ) {
            /** @var $slider RevSlider */
            $revsliders[ $slider->getAlias() ] = $slider->getTitle();
        }
    } else { $revsliders[] = __( 'No Slider Found', 'vslmd' ); }
    
} else { $revsliders[] = __( 'No Slider Found', 'vslmd' ); }

//Query Sliders End

$theme = wp_get_theme(); // For use with some settings. Not necessary.

$args = array(
    'opt_name' => $opt_name,
    'display_name' => $theme->get('Name'),
    'page_slug' => '_options',
    'page_title' => 'Theme Options',
    'update_notice' => false,
    'dev_mode' => false,
    'admin_bar' => true,
    'menu_type' => 'submenu',
    'footer_credit' => 'Visualmodo',
    'menu_title' => 'Theme Options',
    'allow_sub_menu' => true,
    'page_parent' => 'visualmodo',
    'customizer' => true,
    'default_mark' => '*',
    'google_api_key' => 'AIzaSyCqb1AAo5Nhw-3HfZyS_7A-i1QUoQyOeNQ',
    'google_update_weekly' => true,
    'async_typography' => true,
    'output' => true,
    'output_tag' => true,
    'settings_api' => true,
    'compiler' => true,
    'page_permissions' => 'manage_options',
    'save_defaults' => true,
    'show_import_export' => true,
    'transient_time' => 60 * MINUTE_IN_SECONDS,
    'network_sites' => true,
    'display_version' => $theme->get('Version'),
    'use_cdn' => true,
    'hints' => array(
        'icon'          => 'el el-cog',
        'icon_position' => 'right',
        'icon_color'    => 'lightgray',
        'icon_size'     => 'normal',
        'tip_style'     => array(
            'color'   => 'red',
            'shadow'  => true,
            'rounded' => false,
            'style'   => '',
        ),
        'tip_position' => array(
            'my' => 'top left',
            'at' => 'bottom right',
        ),
        'tip_effect' => array(
            'show' => array(
                'effect'   => 'slide',
                'duration' => '500',
                'event'    => 'mouseover',
            ),
            'hide' => array(
                'effect'   => 'slide',
                'duration' => '500',
                'event'    => 'click mouseleave',
            ),
        ),
    ),
);

// SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
$args['share_icons'][] = array(
    'url'   => 'https://www.behance.net/visualmodo',
    'title' => 'Follow us on Behance',
    'icon'  => 'el el-behance'
);
$args['share_icons'][] = array(
    'url'   => 'https://dribbble.com/visualmodo',
    'title' => 'Follow us on Dribbble',
    'icon'  => 'el el-dribbble'
);
$args['share_icons'][] = array(
    'url'   => 'https://github.com/visualmodo',
    'title' => 'Visit us on GitHub',
    'icon'  => 'el el-github'
    //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
);
$args['share_icons'][] = array(
    'url'   => 'https://www.facebook.com/visualmodo',
    'title' => 'Like us on Facebook',
    'icon'  => 'el el-facebook'
);
$args['share_icons'][] = array(
    'url'   => 'http://twitter.com/visualmodo',
    'title' => 'Follow us on Twitter',
    'icon'  => 'el el-twitter'
);
$args['share_icons'][] = array(
    'url'   => 'https://www.linkedin.com/company/visualmodo',
    'title' => 'Find us on LinkedIn',
    'icon'  => 'el el-linkedin'
);

Redux::setArgs( $opt_name, $args );

/*
* ---> END ARGUMENTS
*/

/*
* ---> START HELP TABS
*/

$tabs = array(
    array(
        'id'      => 'redux-help-tab-1',
        'title'   => __( 'Theme Information 1', 'admin_folder' ),
        'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'admin_folder' )
    ),
    array(
        'id'      => 'redux-help-tab-2',
        'title'   => __( 'Theme Information 2', 'admin_folder' ),
        'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'admin_folder' )
        )
    );
    //Redux::setHelpTab( $opt_name, $tabs );
    
    // Set the help sidebar
    $content = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'admin_folder' );
    //Redux::setHelpSidebar( $opt_name, $content );
    
    
    /*
    * <--- END HELP TABS
    */
    
    
    /*
    *
    * ---> START SECTIONS
    *
    */
    
    Redux::setSection( $opt_name, array(
        'icon'      => 'el-icon-lines',
        'title'     => __('Header', 'vslmd'),
        'desc'      => __('Control and configure the general setup of your header.', 'vslmd'),
        'fields'    => array(
            array(
                'id' => 'nav_position', 
                'type' => 'button_set',
                'title' => __('Navigation Position', 'vslmd'),
                'subtitle' => __('Choose horizontal or vertical navigation.', 'vslmd'),
                'options' => array(
                    'horizontal-nav' => 'Horizontal',
                    'vertical-nav' => 'Vertical'
                ),
                'default' => 'horizontal-nav'
            ),
            /*array(
                'id' => 'vertical_nav_structure', 
                'type' => 'button_set',
                'title' => __('Navigation Structure', 'vslmd'),
                'required'      => array('nav_position','equals','vertical-nav'),
                'options' => array(
                    '1' => 'Opened',
                    '2' => 'Closed'
                ),
                'default' => '1'
            ),*/
            array(
                'id' => 'header_layout',
                'type' => 'image_select',
                'title' => __('Header Layout', 'vslmd'),
                'required'         => array('nav_position','equals','horizontal-nav'),
                'options' => array(
                    '1' => array('title' => '', 'img' => $ReduxFrameworkAssets . 'img/header_1.png'),
                    '2' => array('title' => '', 'img' => $ReduxFrameworkAssets . 'img/header_1b.png'),
                    '3' => array('title' => '', 'img' => $ReduxFrameworkAssets . 'img/header_2.png'),
                    '4' => array('title' => '', 'img' => $ReduxFrameworkAssets . 'img/header_2b.png'),
                    '5' => array('title' => '', 'img' => $ReduxFrameworkAssets . 'img/header_3.png'),
                ),
                'default' => '2'
            ),
            array(
                'id' => 'sticky_menu',
                'type' => 'switch',
                'title' => __('Fixed Navigation', 'vslmd'), 
                'subtitle' => __('Do you want the header as sticky?', 'vslmd'),
                'required'         => array('nav_position','equals','horizontal-nav'),
                'default' => 1
            ),
            array(
                'id' => 'top_header', 
                'type' => 'button_set',
                'title' => __('Top Header', 'vslmd'),
                'subtitle' => __('Configure the top header.', 'vslmd'),
                'required'         => array('nav_position','equals','horizontal-nav'),
                'options' => array(
                    '1' => __('Off', 'vslmd'),
                    '2' => __('Only Mobile', 'vslmd'),
                    '3' => __('Only Desktop', 'vslmd'),
                    '4' => __('Desktop And Mobile', 'vslmd'),
                ),
                'default' => '3'
            ),
            array(
                'id' => 'top_header_columns',
                'type' => 'image_select',
                'title' => __('Top Header Structure', 'vslmd'), 
                'subtitle' => __('Select the columns for top header area.', 'vslmd'),
                'desc' => __('Customize the Top Header with widgets.', 'vslmd'),
                'required' => array(
                    array('nav_position', 'equals', 'horizontal-nav'),
                    array('top_header', '>=', 2),
                ),
                'options' => array(
                    '1' => array('title' => '', 'img' => $ReduxFrameworkAssets . 'img/1col.png'),
                    '2' => array('title' => '', 'img' => $ReduxFrameworkAssets . 'img/2col.png'),
                    '3' => array('title' => '', 'img' => $ReduxFrameworkAssets . 'img/3col.png'),
                    '4' => array('title' => '', 'img' => $ReduxFrameworkAssets . 'img/3cm.png'),
                    '5' => array('title' => '', 'img' => $ReduxFrameworkAssets . 'img/2cl.png'),
                    '6' => array('title' => '', 'img' => $ReduxFrameworkAssets . 'img/2cr.png'),
                ),
                'default' => '2'
            ),
            array(
                'id' => 'woocart', 
                'type' => 'button_set',
                'title' => __('WooCommerce Cart', 'vslmd'),
                'subtitle' => __('Enable or disable cart on header.', 'vslmd'),
                'required'         => array('nav_position','equals','horizontal-nav'),
                'options' => array(
                    '1' => __('Off', 'vslmd'),
                    '2' => __('Only Mobile', 'vslmd'),
                    '3' => __('Only Desktop', 'vslmd'),
                    '4' => __('Desktop And Mobile', 'vslmd'),
                ),
                'default' => '1'
            ),
            array(
                'id' => 'search_header', 
                'type' => 'button_set',
                'title' => __('Search', 'vslmd'),
                'subtitle' => __('Enable or disable search on header.', 'vslmd'),
                'required'         => array('nav_position','equals','horizontal-nav'),
                'options' => array(
                    '1' => __('Off', 'vslmd'),
                    '2' => __('Only Mobile', 'vslmd'),
                    '3' => __('Only Desktop', 'vslmd'),
                    '4' => __('Desktop And Mobile', 'vslmd'),
                ),
                'default' => '3'
            ),
            array(
                'id' => 'wishlist', 
                'type' => 'button_set',
                'title' => __('Wishlist', 'vslmd'),
                'subtitle' => __('Enable or disable wishlist on header.', 'vslmd'),
                'required'         => array('nav_position','equals','horizontal-nav'),
                'options' => array(
                    '1' => __('Off', 'vslmd'),
                    '2' => __('Only Mobile', 'vslmd'),
                    '3' => __('Only Desktop', 'vslmd'),
                    '4' => __('Desktop And Mobile', 'vslmd'),
                ),
                'default' => '1'
            ),
            array(
                'id'            => 'extra_menu',
                'type'          => 'slider',
                'title'         => __('Do You Want Extra Menus?', 'vslmd'),
                'desc'          => __('Enter a number. Min: 0, max: 50, step: 1, default value: 0', 'vslmd'),
                'default'       => 0,
                'min'           => 0,
                'step'          => 1,
                'max'           => 50,
                'display_value' => 'text'
            ),
        ),
        ) );
        Redux::setSection( $opt_name, array(
            'icon'      => 'el-icon-lines',
            'subsection' => true,
            'title'     => __('Style', 'vslmd'),
            'desc'      => __('Control and configure the general setup of your header.', 'vslmd'),
            'fields'    => array(
                array(
                    'id' => 'header_height', 
                    'type' => 'button_set',
                    'title' => __('Header Height', 'vslmd'),
                    'subtitle' => __('Choose the height of the your header.', 'vslmd'),
                    'options' => array(
                        'header-small' => 'Small',
                        'header-medium' => 'Medium',
                        'header-large' => 'Large'
                    ),
                    'default' => 'header-medium'
                ),
                array(
                    'id' => 'navigation_collapsed', 
                    'type' => 'button_set',
                    'title' => __('Navigation Collapsed', 'vslmd'),
                    'subtitle' => __('Choose when the menu will collapse.', 'vslmd'),
                    'options' => array(
                        'navbar-expand-sm' => '576px',
                        'navbar-expand-md' => '768px',
                        'navbar-expand-lg' => '992px',
                        'navbar-expand-xl' => '1200px',
                        'navbar-expand-never' => 'Collapsed'
                    ),
                    'default' => 'navbar-expand-md'
                ),
                array(
                    'id'        => 'boxed_or_stretched_header',
                    'type'      => 'image_select',
                    'title'     => __('Boxed or Stretched Style', 'vslmd'),
                    'subtitle'  => __('Choose the format that works best for you.', 'vslmd'),
                    'options'   => array(
                        'container' => array('alt' => 'Boxed','img' => $ReduxFrameworkAssets . 'img/3cm.png'),
                        'container-fluid' => array('alt' => 'Stretched', 'img' => $ReduxFrameworkAssets . 'img/1col.png')
                    ),
                    'default'   => 'container'
                ),
            ),
            ) );
            Redux::setSection( $opt_name, array(
                'icon'      => 'el-icon-lines',
                'subsection' => true,
                'title'     => __('Brand', 'vslmd'),
                'desc'      => __('Control and configure the general setup of your header.', 'vslmd'),
                'fields'    => array(
                    array(
                        'id' => 'brand_scheme', 
                        'type' => 'button_set',
                        'title' => __('Brand Scheme', 'vslmd'),
                        'subtitle' => __('Choose the structure that is right for you.', 'vslmd'),
                        'desc' => __('The options text use the Site Title on Settings > General. If any text content option is enabled, a new option will appear in the typography section specific to that text.', 'vslmd'),
                        'options' => array(
                            '1' => 'Just Text',
                            '2' => 'Image And Text',
                            '3' => 'Just An Image',
                            '4' => 'Retina Image And Text',
                            '5' => 'Just A Retina Image',
                        ),
                        'default' => '3'
                    ),
                    array(
                        'id'        => 'brand_site_title',
                        'type'      => 'text',
                        'title'     => __('Site Title', 'vslmd'),
                        'required' => array('brand_scheme','equals',array( 1,2,4 ) ),
                        'desc'      => __('If this field is left blank, the system will use the General Settings Site Title.', 'vslmd'),
                        'default' => ''
                    ),
                    array(
                        'id'        => 'brand_image',
                        'type'      => 'media', 
                        'title'     => __('Logo Default', 'vslmd'),
                        'desc'      => __('Click Browse and upload your logo, and then click Insert into Post. PNG and JPG allowed. Optimal image height is 48px.', 'vslmd'),
                        'subtitle'  => __('You can upload your logo here. A plain text logo of the blog name will be placed here if you have not uploaded any image for the logo.', 'vslmd'),
                        'required' => array(
                            array('brand_scheme', '>', 1),
                            array('brand_scheme', '<=', 3),
                        ),
                    ),
                    array(
                        'id'        => 'brand_light',
                        'type'      => 'media', 
                        'title'     => __('Logo Light', 'vslmd'),
                        'desc'      => __('Choose a logo image to display for "Light" header skin. Optimal image height is 48px.', 'vslmd'),
                        'required' => array(
                            array('brand_scheme', '>', 1),
                            array('brand_scheme', '<=', 3),
                        ),
                    ),
                    array(
                        'id'        => 'brand_dark',
                        'type'      => 'media', 
                        'title'     => __('Logo Dark', 'vslmd'),
                        'desc'      => __('Choose a logo image to display for "Dark" header skin. Optimal image height is 48px.', 'vslmd'),
                        'required' => array(
                            array('brand_scheme', '>', 1),
                            array('brand_scheme', '<=', 3),
                        ),
                    ),
                    array(
                        'id'        => 'brand_mobile',
                        'type'      => 'media', 
                        'title'     => __('Logo Mobile', 'vslmd'),
                        'desc'      => __('Choose a logo image to display for "Mobile" header type. Optimal image height is 24px.', 'vslmd'),
                        'required' => array(
                            array('brand_scheme', '>', 1),
                            array('brand_scheme', '<=', 3),
                        ),
                    ),
                    array(
                        'id'        => 'brand_retina_image',
                        'type'      => 'media', 
                        'title'     => __('Retina Logo Default', 'vslmd'),
                        'desc'      => __('Click Browse and upload your logo, and then click Insert into Post. PNG and JPG allowed. Optimal image height is 96px.', 'vslmd'),
                        'subtitle'  => __('You can upload your logo here. A plain text logo of the blog name will be placed here if you have not uploaded any image for the logo.', 'vslmd'),
                        'required'  => array('brand_scheme','>=','4'),
                    ),
                    array(
                        'id'        => 'brand_retina_light',
                        'type'      => 'media', 
                        'title'     => __('Retina Logo Light', 'vslmd'),
                        'desc'      => __('Choose a logo image to display for "Light" header skin. Optimal image height is 96px.', 'vslmd'),
                        'required'  => array('brand_scheme','>=','4'),
                    ),
                    array(
                        'id'        => 'brand_retina_dark',
                        'type'      => 'media', 
                        'title'     => __('Retina Logo Dark', 'vslmd'),
                        'desc'      => __('Choose a logo image to display for "Dark" header skin. Optimal image height is 96px.', 'vslmd'),
                        'required'  => array('brand_scheme','>=','4'),
                    ),
                    array(
                        'id'        => 'brand_retina_mobile',
                        'type'      => 'media', 
                        'title'     => __('Retina Logo Mobile', 'vslmd'),
                        'desc'      => __('Choose a logo image to display for "Mobile" header type. Optimal image height is 48px.', 'vslmd'),
                        'required'  => array('brand_scheme','>=','4'),
                    ),
                ),
                ) );
                Redux::setSection( $opt_name, array(
                    'icon'      => 'el-icon-lines',
                    'subsection' => true,
                    'title'     => __('Alert Message', 'vslmd'),
                    'desc'      => __('Control and configure the general setup of your header.', 'vslmd'),
                    'fields'    => array(
                        array(
                            'id'        => 'alert_message_switch',
                            'type'      => 'switch',
                            'title'     => __('Enable Alert Message', 'vslmd'),
                            'default'   => false,
                        ),
                        array(
                            'id'        => 'alert_message_background_color',
                            'type'      => 'color_rgba',
                            'title'     => __('Background Color', 'vslmd'),
                            'subtitle'  => __('Set color and alpha channel', 'vslmd'),
                            'required'  => array('alert_message_switch','equals','1'),
                            'output'    => array(
                                'background-color' => '.alert-message .alert'
                                )
                            ),
                            array(         
                                'id'       => 'alert_message_background_image',
                                'type'     => 'background',
                                'background-color' => false,
                                'title'    => __('Background Image', 'vslmd'),
                                'subtitle' => __('Background with image', 'vslmd'),
                                'required' => array('alert_message_switch','equals','1'),
                                'output'    => array(
                                    'background-color' => '.alert-message'
                                    )
                                ),
                                array(
                                    'id'             => 'alert_message_height',
                                    'type'           => 'spacing',
                                    'output'         => array('.alert-message-content'),
                                    'mode'           => 'padding',
                                    'units'          => array('em', 'px', '%'),
                                    'units_extended' => 'false',
                                    'title'          => __('Spacing', 'vslmd'),
                                    'subtitle'       => __('Choose The Spacing You Want.', 'vslmd'),
                                    'desc'           => __('You can enable or disable any piece of this field. Top, Right, Bottom, Left, or Units.', 'vslmd'),
                                ),
                                array(
                                    'id'       =>'alert_message_text',
                                    'type'     => 'editor',
                                    'title'    => __('Copyright Text', 'vslmd'), 
                                    'subtitle' => __('Create your custom footer text', 'vslmd'),
                                    'required' => array('alert_message_switch','equals','1'),
                                    'args'     => array(
                                        'teeny'  => false,
                                        'media_buttons'  => false,
                                        )
                                    ),
                                ),
                                ) );
                                Redux::setSection( $opt_name, array(
                                    'icon'      => 'el-icon-lines',
                                    'subsection' => true,
                                    'title'     => __('Side Navigation', 'vslmd'),
                                    'desc'      => __('Control and configure the general setup of your header.', 'vslmd'),
                                    'fields'    => array(
                                        array(
                                            'id'       => 'side_navigation',
                                            'type'     => 'button_set',
                                            'title'    => __('Side Navigation', 'vslmd'),
                                            'subtitle' => __('Create an animated, closable side navigation menu.', 'vslmd'),
                                            'required'      => array('nav_position','equals','horizontal-nav'),
                                            'options' => array(
                                                '' => 'No', 
                                                'sidenav-overlay' => 'Overlay',
                                                'sidenav-push' => 'Push', 
                                                'sidenav-fullscreen' => 'Fullscreen'
                                            ), 
                                            'default' => ''
                                        ),
                                        array(
                                            'id' => 'side_navigation_color_scheme', 
                                            'type' => 'select',
                                            'title' => __('Color Scheme', 'vslmd'),
                                            'subtitle' => __('Choose the Color Scheme.', 'vslmd'),
                                            'required'      => array('side_navigation','!=',''),
                                            'options' => array(
                                                'light' => 'For Light Background Colors',
                                                'dark' => 'For Dark Background Colors'
                                            ),
                                            'default' => 'light'
                                        ),
                                        array(
                                            'id'       => 'side_navigation_layout',
                                            'type'     => 'button_set',
                                            'title'    => __('Layout Side Navigation', 'vslmd'),
                                            'subtitle'     => __('Organize how you want the layout to appear.', 'vslmd'),
                                            'required'      => array('side_navigation','!=',''),
                                            'options' => array(
                                                '1' => 'No', 
                                                '2' => 'Background Color', 
                                                '3' => 'Background Image',
                                            ), 
                                            'default' => '1'
                                        ),
                                        array(
                                            'id'        => 'side_navigation_header_title_color_overlay',
                                            'type'      => 'color_rgba',
                                            'title'     => 'Background Color',
                                            'required' => array(
                                                array('side_navigation_layout', '<=', 3),
                                                array('side_navigation_layout', '!=', 1),
                                            ),
                                            'desc'      => 'Set Background Color and Opacity.',
                                            'output'    => array(
                                                'background-color' => '.side-navigation .side-navigation-inner, .light.side-navigation .side-navigation-inner, .dark.side-navigation .side-navigation-inner'
                                            ),
                                        ),
                                        array(         
                                            'id'       => 'side_navigation_background',
                                            'type'     => 'background',
                                            'background-color' => false,
                                            'required' => array('side_navigation_layout','equals','3'),
                                            'title'    => __('Background Image', 'vslmd'),
                                            'desc'     => __('Upload your image should be between 1920px x 1080px (or more) for best results.', 'vslmd'),
                                            'output' => array('.side-navigation'),
                                        ),
                                    ),
                                    ) );
                                    Redux::setSection( $opt_name, array(
                                        'icon'      => 'el-icon-chevron-down',
                                        'title'     => __('Footer', 'vslmd'),
                                        'desc'      => __('Control and configure of your footer area.', 'vslmd'),
                                        'fields'    => array(
                                            array(
                                                'id'       => 'footer_top',
                                                'type'     => 'button_set',
                                                'title'    => __('Breadcrumbs And Bottom To Top Button', 'vslmd'),
                                                'subtitle' => __('Enable or disable the breadcrumbs and botton to top', 'vslmd'),
                                                'options' => array(
                                                    '1' => 'No', 
                                                    '2' => 'Breadcrumbs', 
                                                    '3' => 'Bottom To Top',
                                                    '4' => 'Breadcrumbs And Bottom To Top',
                                                ), 
                                                'default' => '4'
                                            ),
                                            array(
                                                'id'        => 'footer_boxed_or_stretched',
                                                'type'      => 'image_select',
                                                'title'     => __('Boxed or Stretched Style', 'vslmd'),
                                                'subtitle'  => __('Choose the format that works best for you.', 'vslmd'),
                                                'options'   => array(
                                                    'container' => array('alt' => 'Boxed','img' => $ReduxFrameworkAssets . 'img/3cm.png'),
                                                    'container-fluid' => array('alt' => 'Stretched', 'img' => $ReduxFrameworkAssets . 'img/1col.png')
                                                ),
                                                'default'   => 'container'
                                            ),
                                            array(
                                                'id' => 'footer_widget_columns',
                                                'type' => 'image_select',
                                                'title' => __('Widget Area Columns', 'vslmd'), 
                                                'subtitle' => __('Select the columns for footer widget area.', 'vslmd'),
                                                'options' => array(
                                                    '0' => array('title' => '', 'img' => $ReduxFrameworkAssets . 'img/disable.png'),
                                                    '1' => array('title' => '', 'img' => $ReduxFrameworkAssets . 'img/1col.png'),
                                                    '2' => array('title' => '', 'img' => $ReduxFrameworkAssets . 'img/2col.png'),
                                                    '3' => array('title' => '', 'img' => $ReduxFrameworkAssets . 'img/3col.png'),
                                                    '4' => array('title' => '', 'img' => $ReduxFrameworkAssets . 'img/4col.png'),
                                                    '5' => array('title' => '', 'img' => $ReduxFrameworkAssets . 'img/2cr.png'),
                                                    '6' => array('title' => '', 'img' => $ReduxFrameworkAssets . 'img/2cl.png'),
                                                    '7' => array('title' => '', 'img' => $ReduxFrameworkAssets . 'img/3cm.png'),
                                                ),
                                                'default' => '3'
                                            ),
                                            array(
                                                'id'=>'footer_text',
                                                'type' => 'editor',
                                                'title' => __('Copyright Text', 'vslmd'), 
                                                'subtitle' => __('Create your custom footer text', 'vslmd'),
                                                'default' => 'Powered by Visualmodo.',
                                            ),
                                        ),
                                        ) );
                                        Redux::setSection( $opt_name, array(
                                            'icon'      => 'el-icon-tint',
                                            'title'     => __('Colors', 'vslmd'),
                                            'desc'      => __('Control and configure of colors and backgrounds.', 'vslmd'),
                                            'fields'    => array(
                                                array(
                                                    'id'       => 'color_switch',
                                                    'type'     => 'button_set',
                                                    'title'    => __( 'Color Mode', 'vslmd' ),
                                                    'subtitle'     => __( 'Select a color mode.', 'vslmd' ),
                                                    'options'  => array(
                                                        '1' => 'Global Color',
                                                        '2' => 'Custom Color'
                                                    ),
                                                    'default'  => '1'
                                                ),
                                                array(
                                                    'id'        => 'global_color',
                                                    'type'      => 'color',
                                                    'title'     => __('Choose Global Color', 'vslmd'),
                                                    'required' => array( 'color_switch', '=', '1' ),
                                                    'output'    => array(
                                                        'color'            => '.global-color, .global-color ul li, .global-color ul li a, a, h2.entry-title a, .widget-area aside.widget ol li a:hover, .btn-read-more, .pagination .page-item:not(.active) .page-link, .widget-area aside.widget ul li a:hover,.vslmd-linkedin i,.vslmd-googleplus i, .vslmd-facebook i, .vslmd-twitter i, .dark .top-footer .breadcrumbs-footer .breadcrumb a:hover, .dark .top-footer .breadcrumbs-footer .breadcrumb span a:hover, .light .top-footer .breadcrumbs-footer .breadcrumb a:hover, .light .top-footer .breadcrumbs-footer .breadcrumb span a:hover, .desktop-mode .header-bottom .navbar-nav .active > .nav-link, .desktop-mode .header-bottom .navbar-nav .active > .nav-link:focus, .desktop-mode .header-bottom .navbar-nav .active > .nav-link:hover, .light .navbar-nav.t_link li:hover a.dropdown-toggle, .dark .navbar-nav.t_link li:hover a.dropdown-toggle, .navbar-default .navbar-nav.t_link li a:focus, .navbar-default .navbar-nav.t_link li a:hover, .navbar-inverse .navbar-nav.t_link li a:focus, .navbar-inverse .navbar-nav.t_link li a:hover, .light .vslmd-widget-container li a, .light .vslmd-widget-container li span i, .dark .vslmd-widget-container li a, .dark .vslmd-widget-container li span i, .wrapper-footer.light .widgets-footer ol li a:hover, .wrapper-footer.light .widgets-footer ul li a:hover, .wrapper-footer.dark .widgets-footer ol li a:hover, .wrapper-footer.dark .widgets-footer ul li a:hover, .light .top-footer .breadcrumbs-footer .breadcrumb > li a:hover, .dark .top-footer .breadcrumbs-footer .breadcrumb > li a:hover, .light .bottom-footer a, .dark .bottom-footer a, .wrapper-footer.light .social-widget-icon a i:hover, .wrapper-footer.dark .social-widget-icon a i:hover',
                                                        
                                                        'background-color' => '.woocommerce .woocommerce-product-search button, .woocommerce-page .woocommerce-product-search button, .global-background-color, .btn-primary, .header-presentation .hp-background-color, .tagcloud a:hover, .btn-read-more:hover, .post-container .mejs-container, .post-container .mejs-container .mejs-controls, .post-container .mejs-embed, .mejs-embed body, .woocommerce-page input.button, .woocommerce .cart-table-vslmd .cart .button, .woocommerce .cart-table-vslmd .cart input.button,.woocommerce input.button.alt, .page-item.active .page-link, .light .navbar-nav.b_link .active > a,  .light .navbar-nav.b_link .active > a:focus, .light .navbar-nav.b_link .active > a:hover, .dark .navbar-nav.b_link .active > a, .dark .navbar-nav.b_link .active > a:focus, .dark .navbar-nav.b_link .active > a:hover, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce .widget_price_filter .ui-slider .ui-slider-range, .cart-menu div.widget_shopping_cart_content p.buttons a.button.checkout, .bbpress .bbp-search-form form input.button',
                                                        
                                                        'border-color' => '.global-border-color, .btn-primary, .btn-read-more, .btn-read-more:hover, .page-item.active .page-link, .bbpress .bbp-search-form form input.button',
                                                    ),
                                                    'subtitle'  => __('Pick a global color for the theme.', 'vslmd'),
                                                    'default'   => '#3379fc',
                                                    'transparent' => false,
                                                    'validate'  => 'color',
                                                ),
                                                array(
                                                    'id'        => 'body_background_color',
                                                    'type'      => 'color',
                                                    'title'     => __('Body Background Color', 'vslmd'),
                                                    'output'    => array(
                                                        'background-color' => 'body',
                                                    ),
                                                    'subtitle'  => __('Pick a Body background color for the theme.', 'vslmd'),
                                                    'default'   => '#FFFFFF',
                                                    'transparent' => false,
                                                    'validate'  => 'color',
                                                    'required' => array( 'color_switch', '=', '2' ),
                                                ),
                                                array(
                                                    'id'        => 'custom_background_color',
                                                    'type'      => 'color',
                                                    'title'     => __('Background Color', 'vslmd'),
                                                    'output'    => array(
                                                        'background-color' => '.woocommerce .woocommerce-product-search button, .woocommerce-page .woocommerce-product-search button, .global-background-color, .btn-primary, .header-presentation .hp-background-color, .tagcloud a:hover, .btn-read-more:hover, .post-container .mejs-container, .post-container .mejs-container .mejs-controls, .post-container .mejs-embed, .mejs-embed body, .woocommerce-page input.button, .woocommerce .cart-table-vslmd .cart .button, .woocommerce .cart-table-vslmd .cart input.button,.woocommerce input.button.alt, .page-item.active .page-link, .light .navbar-nav.b_link .active > a,  .light .navbar-nav.b_link .active > a:focus, .light .navbar-nav.b_link .active > a:hover, .dark .navbar-nav.b_link .active > a, .dark .navbar-nav.b_link .active > a:focus, .dark .navbar-nav.b_link .active > a:hover, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce .widget_price_filter .ui-slider .ui-slider-range, .cart-menu div.widget_shopping_cart_content p.buttons a.button.checkout, .bbpress .bbp-search-form form input.button',
                                                    ),
                                                    'subtitle'  => __('Pick a background color for the theme.', 'vslmd'),
                                                    'default'   => '#3379fc',
                                                    'transparent' => false,
                                                    'validate'  => 'color',
                                                    'required' => array( 'color_switch', '=', '2' ),
                                                ),
                                                array(
                                                    'id'        => 'custom_border_color',
                                                    'type'      => 'color',
                                                    'title'     => __('Border Color', 'vslmd'),
                                                    'output'    => array(
                                                        'border-color' => '.global-border-color, .btn-primary, .btn-read-more, .btn-read-more:hover, .page-item.active .page-link, .bbpress .bbp-search-form form input.button',
                                                    ),
                                                    'subtitle'  => __('Pick a border color for the theme.', 'vslmd'),
                                                    'default'   => '#3379fc',
                                                    'transparent' => false,
                                                    'validate'  => 'color',
                                                    'required' => array( 'color_switch', '=', '2' ),
                                                ),
                                                array(
                                                    'id'        => 'custom_color',
                                                    'type'      => 'color',
                                                    'title'     => __('Color', 'vslmd'),
                                                    'output'    => array(
                                                        'color' => '.global-color, a, h2.entry-title a, .widget-area aside.widget ol li a:hover, .btn-read-more, .pagination .page-item:not(.active) .page-link, .widget-area aside.widget ul li a:hover,.vslmd-linkedin i,.vslmd-googleplus i, .vslmd-facebook i, .vslmd-twitter i, .dark .top-footer .breadcrumbs-footer .breadcrumb a:hover, .dark .top-footer .breadcrumbs-footer .breadcrumb span a:hover, .light .top-footer .breadcrumbs-footer .breadcrumb a:hover, .light .top-footer .breadcrumbs-footer .breadcrumb span a:hover, .desktop-mode .header-bottom .navbar-nav .active > .nav-link, .desktop-mode .header-bottom .navbar-nav .active > .nav-link:focus, .desktop-mode .header-bottom .navbar-nav .active > .nav-link:hover, .light .navbar-nav.t_link li:hover a.dropdown-toggle, .dark .navbar-nav.t_link li:hover a.dropdown-toggle, .navbar-default .navbar-nav.t_link li a:focus, .navbar-default .navbar-nav.t_link li a:hover, .navbar-inverse .navbar-nav.t_link li a:focus, .navbar-inverse .navbar-nav.t_link li a:hover, .light .vslmd-widget-container li a, .light .vslmd-widget-container li span i, .dark .vslmd-widget-container li a, .dark .vslmd-widget-container li span i, .wrapper-footer.light .widgets-footer ol li a:hover, .wrapper-footer.light .widgets-footer ul li a:hover, .wrapper-footer.dark .widgets-footer ol li a:hover, .wrapper-footer.dark .widgets-footer ul li a:hover, .light .top-footer .breadcrumbs-footer .breadcrumb > li a:hover, .dark .top-footer .breadcrumbs-footer .breadcrumb > li a:hover, .light .bottom-footer a, .dark .bottom-footer a, .wrapper-footer.light .social-widget-icon a i:hover, .wrapper-footer.dark .social-widget-icon a i:hover',
                                                    ),
                                                    'subtitle'  => __('Pick a color for the theme.', 'vslmd'),
                                                    'default'   => '#3379fc',
                                                    'transparent' => false,
                                                    'validate'  => 'color',
                                                    'required' => array( 'color_switch', '=', '2' ),
                                                ),
                                                array(
                                                    'id'        => 'custom_page_heading_text_color',
                                                    'type'      => 'color',
                                                    'title'     => __('Page Heading Text Color', 'vslmd'),
                                                    'output'    => array(
                                                        'color' => '.global-page-heading-text-color, .header-presentation .hp-background-color .container .hp-content h1',
                                                    ),
                                                    'subtitle'  => __('Pick a page heading text color for the theme.', 'vslmd'),
                                                    'default'   => '#fff',
                                                    'transparent' => false,
                                                    'validate'  => 'color',
                                                    'required' => array( 'color_switch', '=', '2' ),
                                                ),
                                                array(
                                                    'id'        => 'custom_page_heading_subtitle_text_color',
                                                    'type'      => 'color',
                                                    'title'     => __('Page Heading Subtitle Text Color', 'vslmd'),
                                                    'output'    => array(
                                                        'color' => '.global-page-heading-subtitle-text-color, .header-presentation .hp-background-color .container .hp-content p',
                                                    ),
                                                    'subtitle'  => __('Pick a page heading subtitle text color for the theme.', 'vslmd'),
                                                    'default'   => 'rgba(255, 255, 255, 0.8)',
                                                    'transparent' => false,
                                                    'validate'  => 'color',
                                                    'required' => array( 'color_switch', '=', '2' ),
                                                ),
                                                array(
                                                    'id'        => 'custom_body_text_color',
                                                    'type'      => 'color',
                                                    'title'     => __('Body Text Color', 'vslmd'),
                                                    'output'    => array(
                                                        'color' => '.global-body-text-color, body',
                                                    ),
                                                    'subtitle'  => __('Pick a body text color for the theme.', 'vslmd'),
                                                    'default'   => '#818B92',
                                                    'transparent' => false,
                                                    'validate'  => 'color',
                                                    'required' => array( 'color_switch', '=', '2' ),
                                                ),
                                                array(
                                                    'id'        => 'custom_heading_text_color',
                                                    'type'      => 'color',
                                                    'title'     => __('Heading Text Color', 'vslmd'),
                                                    'output'    => array(
                                                        'color' => '.global-heading-text-color, h1, h2, h3, h4, h5, h6',
                                                    ),
                                                    'subtitle'  => __('Pick a heading text color for the theme.', 'vslmd'),
                                                    'default'   => '#222328',
                                                    'transparent' => false,
                                                    'validate'  => 'color',
                                                    'required' => array( 'color_switch', '=', '2' ),
                                                ),
                                            ),
                                            ) );
                                            Redux::setSection( $opt_name, array(
                                                'icon'      => 'el-icon-tint',
                                                'subsection' => true,
                                                'title'     => __('Header', 'vslmd'),
                                                'desc'      => __('Control and configure of colors and backgrounds.', 'vslmd'),
                                                'fields'    => array(
                                                    array(
                                                        'id'       => 'header_color_scheme',
                                                        'type'     => 'button_set',
                                                        'title'    => __( 'Header Color Scheme', 'vslmd' ),
                                                        'subtitle'     => __( 'Select a color mode.', 'vslmd' ),
                                                        'options'  => array(
                                                            'light navbar-light bg-white' => 'White',
                                                            'light navbar-light bg-light' => 'Light',
                                                            'dark navbar-dark bg-dark' => 'Dark',
                                                            'navbar-custom' => 'Custom'
                                                        ),
                                                        'default'  => 'light navbar-light bg-white'
                                                    ),
                                                    array(
                                                        'id' => 'header_navbar_color', 
                                                        'type' => 'select',
                                                        'title' => __('Navigation Color Scheme', 'vslmd'),
                                                        'subtitle' => __('Choose the Color.', 'vslmd'),
                                                        'options' => array(
                                                            'navbar-light' => 'Light',
                                                            'navbar-dark' => 'Dark'
                                                        ),
                                                        'default' => 'navbar-light',
                                                        'required' => array( 'header_color_scheme', '=', 'navbar-custom' ),
                                                    ),
                                                    array(
                                                        'id' => 'dropdown_menu_color', 
                                                        'type' => 'select',
                                                        'title' => __('Dropdown Navigation Color Scheme', 'vslmd'),
                                                        'subtitle' => __('Choose the Color.', 'vslmd'),
                                                        'options' => array(
                                                            'dropdown-menu-light' => 'Light',
                                                            'dropdown-menu-dark' => 'Dark'
                                                        ),
                                                        'default' => 'dropdown-menu-dark',
                                                        'required' => array( 'header_color_scheme', '=', 'navbar-custom' ),
                                                    ),
                                                    array(
                                                        'id' => 'header_navbar_background',
                                                        'type' => 'color_rgba',
                                                        'title' => __('Navigation Background Color', 'vslmd'),
                                                        'subtitle' => __('Choose the Background Color.', 'vslmd'),
                                                        'transparent' => false,
                                                        'output'    => array(
                                                            'background-color' => '.header-bottom, .header-top, .vertical-header',
                                                        ),
                                                        'default'   => array(
                                                            'color'     => '#FFFFFF',
                                                            'alpha'     => 1
                                                        ),
                                                        'required' => array( 'header_color_scheme', '=', 'navbar-custom' ),
                                                    ),
                                                    array(
                                                        'id' => 'dropdown_menu_background',
                                                        'type' => 'color_rgba',
                                                        'title' => __('Dropdown Navigation Background Color', 'vslmd'),
                                                        'subtitle' => __('Choose the Background Color.', 'vslmd'),
                                                        'transparent' => false,
                                                        'output'    => array(
                                                            'background-color' => '.navbar-nav .dropdown-menu.dropdown-menu-dark-no-bg, .navbar-nav .dropdown-menu.dropdown-menu-light-no-bg',
                                                        ),
                                                        'default'   => array(
                                                            'color'     => '#343a40',
                                                            'alpha'     => 1
                                                        ),
                                                        'required' => array( 'header_color_scheme', '=', 'navbar-custom' ),
                                                    ),
                                                    array(
                                                        'id' => 'header_navigation_color',
                                                        'type' => 'color_rgba',
                                                        'title' => __('Navigation Color', 'vslmd'),
                                                        'subtitle' => __('Set navigation text color and alpha channel', 'vslmd'),
                                                        'transparent' => false,
                                                        'output'    => array(
                                                            'color' => '.desktop-mode .navbar-nav .nav-link',
                                                        ),
                                                        'default'   => array(
                                                            'color'     => '#000000',
                                                            'alpha'     => 0.5,
                                                        ),
                                                        'required' => array( 'header_color_scheme', '=', 'navbar-custom' ),
                                                    ),
                                                    array(
                                                        'id' => 'header_navigation_color_hover',
                                                        'type' => 'color_rgba',
                                                        'title' => __('Navigation Color Hover', 'vslmd'),
                                                        'subtitle' => __('Set navigation text color and alpha channel', 'vslmd'),
                                                        'transparent' => false,
                                                        'output'    => array(
                                                            'color' => '.desktop-mode .navbar-light .navbar-nav .nav-link:focus, .desktop-mode .navbar-light .navbar-nav .nav-link:hover',
                                                        ),
                                                        'default'   => array(
                                                            'color'     => '#000000',
                                                            'alpha'     => 0.7,
                                                        ),
                                                        'required' => array( 'header_color_scheme', '=', 'navbar-custom' ),
                                                    ),
                                                    array(
                                                        'id' => 'header_dropdown_navigation_color',
                                                        'type' => 'color_rgba',
                                                        'title' => __('Navigation Dropdown Color', 'vslmd'),
                                                        'subtitle' => __('Set dropdown navigation text color and alpha channel', 'vslmd'),
                                                        'transparent' => false,
                                                        'output'    => array(
                                                            'color' => '.desktop-mode .navbar-nav .dropdown-menu .nav-link, .desktop-mode .navbar-nav .dropdown-menu.dropdown-menu-dark-no-bg .nav-link, .desktop-mode .navbar-nav .dropdown-menu.dropdown-menu-light-no-bg .nav-link',
                                                        ),
                                                        'default'   => array(
                                                            'color'     => '#FFFFFF',
                                                            'alpha'     => 0.5,
                                                        ),
                                                        'required' => array( 'header_color_scheme', '=', 'navbar-custom' ),
                                                    ),
                                                    array(
                                                        'id' => 'header_dropdown_navigation_color_hover',
                                                        'type' => 'color_rgba',
                                                        'title' => __('Navigation Dropdown Color Hover', 'vslmd'),
                                                        'subtitle' => __('Set dropdown navigation text color and alpha channel', 'vslmd'),
                                                        'transparent' => false,
                                                        'output'    => array(
                                                            'color' => '.desktop-mode .navbar-nav .dropdown-menu .nav-link:hover, .desktop-mode .navbar-nav .dropdown-menu.dropdown-menu-dark-no-bg .nav-link:hover, .desktop-mode .navbar-nav .dropdown-menu.dropdown-menu-light-no-bg .nav-link:hover,.desktop-mode .navbar-nav .dropdown-menu .nav-link:focus, .desktop-mode .navbar-nav .dropdown-menu.dropdown-menu-dark-no-bg .nav-link:focus, .desktop-mode .navbar-nav .dropdown-menu.dropdown-menu-light-no-bg .nav-link:focus',
                                                        ),
                                                        'default'   => array(
                                                            'color'     => '#FFFFFF',
                                                            'alpha'     => 0.7,
                                                        ),
                                                        'required' => array( 'header_color_scheme', '=', 'navbar-custom' ),
                                                    ),
                                                ),
                                                ) );
                                                Redux::setSection( $opt_name, array(
                                                    'icon'      => 'el-icon-tint',
                                                    'subsection' => true,
                                                    'title'     => __('Footer', 'vslmd'),
                                                    'desc'      => __('Control and configure of colors and backgrounds.', 'vslmd'),
                                                    'fields'    => array(
                                                        array(
                                                            'id'       => 'footer_color_scheme',
                                                            'type'     => 'button_set',
                                                            'title'    => __( 'Footer Color Scheme', 'vslmd' ),
                                                            'subtitle'     => __( 'Select a color mode.', 'vslmd' ),
                                                            'options'  => array(
                                                                'light footer-bg-white' => 'White',
                                                                'light footer-bg-light' => 'Light',
                                                                'dark footer-bg-dark' => 'Dark'
                                                            ),
                                                            'default'  => 'dark footer-bg-dark'
                                                        ),
                                                        array(
                                                            'id'        => 'footer_background_color',
                                                            'type'      => 'color_rgba',
                                                            'title'     => __('Background Color', 'vslmd'),
                                                            'subtitle'  => __('Set color and alpha channel', 'vslmd'),
                                                            'output'    => array(
                                                                'background-color' => '.footer-background-color'
                                                                )
                                                            ),
                                                            array(         
                                                                'id'       => 'footer_background_image',
                                                                'type'     => 'background',
                                                                'background-color' => false,
                                                                'title'    => __('Background Image', 'vslmd'),
                                                                'subtitle' => __('Background with image', 'vslmd'),
                                                                'output' => array('.footer-background-image'),
                                                            ),
                                                            
                                                        ),
                                                        ) );
                                                        Redux::setSection( $opt_name, array(
                                                            'icon'      => 'el-icon-fontsize',
                                                            'title'     => __('Typography', 'vslmd'),
                                                            'desc'      => __('Control and configure the typography of your theme.', 'vslmd'),
                                                            ) );
                                                            Redux::setSection( $opt_name, array(
                                                                'icon'      => 'el-icon-fontsize',
                                                                'subsection' => true,
                                                                'title'     => __('Navigation & Page Header', 'vslmd'),
                                                                'desc'      => __('Control and configure the typography of your theme.', 'vslmd'),
                                                                'fields'    => array(
                                                                    array(
                                                                        'id'        => 'web_font_switch',
                                                                        'type'      => 'switch',
                                                                        'title'     => __('Enable Custom Fonts?', 'vslmd'),
                                                                        'default'   => false,
                                                                    ),
                                                                    array(
                                                                        'id' => 'nav_font',
                                                                        'type' => 'typography',
                                                                        'title' => __('Navigation Font', 'vslmd'),
                                                                        'subtitle' => __('Specify the Navigation font properties.', 'vslmd'),
                                                                        'output' => array('.navbar-brand, .navbar-nav li a'),
                                                                        'google' => true,
                                                                        'font-backup' => false,
                                                                        'line-height'=>true,
                                                                        'font-style' => true,
                                                                        'text-align' => false,
                                                                        'subsets' => true,
                                                                        'font-weight' => true,
                                                                        'letter-spacing' => true,
                                                                        'subset' => true,
                                                                        'color' => false,
                                                                        'text-transform' => true,
                                                                        'preview' => true,
                                                                        'units' => 'px',
                                                                        'required'      => array('web_font_switch','equals','1'),
                                                                    ),
                                                                    array(
                                                                        'id' => 'nav_dropdown_font',
                                                                        'type' => 'typography',
                                                                        'title' => __('Navigation Dropdown Font', 'vslmd'),
                                                                        'subtitle' => __('Specify the Navigation Dropdown font properties.', 'vslmd'),
                                                                        'output' => array('.dropdown-submenu>.dropdown-menu a, .navbar-nav>li>.dropdown-menu a'),
                                                                        'google' => true,
                                                                        'font-backup' => false,
                                                                        'line-height'=>true,
                                                                        'font-style' => true,
                                                                        'text-align' => false,
                                                                        'subsets' => true,
                                                                        'font-weight' => true,
                                                                        'letter-spacing' => true,
                                                                        'subset' => true,
                                                                        'color' => false,
                                                                        'text-transform' => true,
                                                                        'preview' => true,
                                                                        'units' => 'px',
                                                                        'required'      => array('web_font_switch','equals','1'),
                                                                    ),
                                                                    array(
                                                                        'id' => 'brand_font',
                                                                        'type' => 'typography',
                                                                        'title' => __('Brand Font', 'vslmd'),
                                                                        'subtitle' => __('Specify the Brand font properties.', 'vslmd'),
                                                                        'output' => array('.navbar-brand'),
                                                                        'google' => true,
                                                                        'font-backup' => false,
                                                                        'line-height'=>false,
                                                                        'font-style' => true,
                                                                        'text-align' => false,
                                                                        'subsets' => true,
                                                                        'font-weight' => true,
                                                                        'letter-spacing' => true,
                                                                        'subset' => true,
                                                                        'color' => false,
                                                                        'text-transform' => true,
                                                                        'preview' => true,
                                                                        'units' => 'px',
                                                                        'required'      => array('web_font_switch','equals','1'),
                                                                    ),
                                                                    array(
                                                                        'id' => 'page_heading_font',
                                                                        'type' => 'typography',
                                                                        'title' => __('Page Heading Font', 'vslmd'),
                                                                        'subtitle' => __('Specify the Page Heading font properties.', 'vslmd'),
                                                                        'output' => array('.desktop-mode .header-presentation .hp-background-color .container .hp-content h1, .mobile-mode .header-presentation .hp-background-color .container .hp-content h1'),
                                                                        'google' => true,
                                                                        'font-backup' => false,
                                                                        'line-height'=>true,
                                                                        'font-style' => true,
                                                                        'text-align' => false,
                                                                        'subsets' => true,
                                                                        'font-weight' => true,
                                                                        'letter-spacing' => true,
                                                                        'subset' => true,
                                                                        'color' => false,
                                                                        'text-transform' => true,
                                                                        'text-align' => true,
                                                                        'preview' => true,
                                                                        'units' => 'px',
                                                                        'required'      => array('web_font_switch','equals','1'),
                                                                    ),
                                                                    array(
                                                                        'id' => 'page_heading_sub_font',
                                                                        'type' => 'typography',
                                                                        'title' => __('Page Heading Subtitle Font', 'vslmd'),
                                                                        'subtitle' => __('Specify the Page Heading Subtitle font properties.', 'vslmd'),
                                                                        'output' => array('.desktop-mode .header-presentation .hp-background-color .container .hp-content p, .mobile-mode .header-presentation .hp-background-color .container .hp-content p'),
                                                                        'google' => true,
                                                                        'font-backup' => false,
                                                                        'line-height'=>true,
                                                                        'font-style' => true,
                                                                        'text-align' => false,
                                                                        'subsets' => true,
                                                                        'font-weight' => true,
                                                                        'letter-spacing' => true,
                                                                        'subset' => true,
                                                                        'color' => false,
                                                                        'text-transform' => true,
                                                                        'text-align' => true,
                                                                        'preview' => true,
                                                                        'units' => 'px',
                                                                        'required'      => array('web_font_switch','equals','1'),
                                                                    ),
                                                                ),
                                                                ) );
                                                                Redux::setSection( $opt_name, array(
                                                                    'icon'      => 'el-icon-fontsize',
                                                                    'subsection' => true,
                                                                    'title'     => __('General HTML elements', 'vslmd'),
                                                                    'desc'      => __('Control and configure the typography of your theme.', 'vslmd'),
                                                                    'fields'    => array(
                                                                        array(
                                                                            'id' => 'body_font',
                                                                            'type' => 'typography',
                                                                            'title' => __('Body Font', 'vslmd'),
                                                                            'subtitle' => __('Specify the Body font properties.', 'vslmd'),
                                                                            'output' => array('body'),
                                                                            'google' => true,
                                                                            'font-backup' => false,
                                                                            'line-height'=>true,
                                                                            'font-style' => true,
                                                                            'text-align' => false,
                                                                            'subsets' => false,
                                                                            'text-transform' => true,
                                                                            'font-weight' => true,
                                                                            'letter-spacing' => true,
                                                                            'subset' => true,
                                                                            'color' => false,
                                                                            'preview' => true,
                                                                            'units' => 'px',
                                                                            'required'      => array('web_font_switch','equals','1'),
                                                                        ),
                                                                        array(
                                                                            'id' => 'h1_heading_font',
                                                                            'type' => 'typography',
                                                                            'title' => __('Heading 1', 'vslmd'),
                                                                            'subtitle' => __('Specify the H1 Text properties.', 'vslmd'),
                                                                            'output' => array('h1'),
                                                                            'google' => true,
                                                                            'font-backup' => false,
                                                                            'line-height'=>true,
                                                                            'font-style' => true,
                                                                            'text-align' => false,
                                                                            'subsets' => false,
                                                                            'text-transform' => true,
                                                                            'font-weight' => true,
                                                                            'letter-spacing' => true,
                                                                            'subset' => true,
                                                                            'color' => false,
                                                                            'preview' => true,
                                                                            'units' => 'px',
                                                                            'required'      => array('web_font_switch','equals','1'),
                                                                        ),
                                                                        array(
                                                                            'id' => 'h2_heading_font',
                                                                            'type' => 'typography',
                                                                            'title' => __('Heading 2', 'vslmd'),
                                                                            'subtitle' => __('Specify the H2 Text properties.', 'vslmd'),
                                                                            'output' => array('h2'),
                                                                            'google' => true,
                                                                            'font-backup' => false,
                                                                            'line-height'=>true,
                                                                            'font-style' => true,
                                                                            'text-align' => false,
                                                                            'subsets' => false,
                                                                            'text-transform' => true,
                                                                            'font-weight' => true,
                                                                            'letter-spacing' => true,
                                                                            'subset' => true,
                                                                            'color' => false,
                                                                            'preview' => true,
                                                                            'units' => 'px',
                                                                            'required'      => array('web_font_switch','equals','1'),
                                                                        ),
                                                                        array(
                                                                            'id' => 'h3_heading_font',
                                                                            'type' => 'typography',
                                                                            'title' => __('Heading 3', 'vslmd'),
                                                                            'subtitle' => __('Specify the H3 Text properties.', 'vslmd'),
                                                                            'output' => array('h3'),
                                                                            'google' => true,
                                                                            'font-backup' => false,
                                                                            'line-height'=>true,
                                                                            'font-style' => true,
                                                                            'text-align' => false,
                                                                            'subsets' => false,
                                                                            'text-transform' => true,
                                                                            'font-weight' => true,
                                                                            'letter-spacing' => true,
                                                                            'subset' => true,
                                                                            'color' => false,
                                                                            'preview' => true,
                                                                            'units' => 'px',
                                                                            'required'      => array('web_font_switch','equals','1'),
                                                                        ),
                                                                        array(
                                                                            'id' => 'h4_heading_font',
                                                                            'type' => 'typography',
                                                                            'title' => __('Heading 4', 'vslmd'),
                                                                            'subtitle' => __('Specify the H4 Text properties.', 'vslmd'),
                                                                            'output' => array('h4'),
                                                                            'google' => true,
                                                                            'font-backup' => false,
                                                                            'line-height'=>true,
                                                                            'font-style' => true,
                                                                            'text-align' => false,
                                                                            'subsets' => false,
                                                                            'text-transform' => true,
                                                                            'font-weight' => true,
                                                                            'letter-spacing' => true,
                                                                            'subset' => true,
                                                                            'color' => false,
                                                                            'preview' => true,
                                                                            'units' => 'px',
                                                                            'required'      => array('web_font_switch','equals','1'),
                                                                        ),
                                                                        array(
                                                                            'id' => 'h5_heading_font',
                                                                            'type' => 'typography',
                                                                            'title' => __('Heading 5', 'vslmd'),
                                                                            'subtitle' => __('Specify the H5 Text properties.', 'vslmd'),
                                                                            'output' => array('h5'),
                                                                            'google' => true,
                                                                            'font-backup' => false,
                                                                            'line-height'=>true,
                                                                            'font-style' => true,
                                                                            'text-align' => false,
                                                                            'subsets' => false,
                                                                            'text-transform' => true,
                                                                            'font-weight' => true,
                                                                            'letter-spacing' => true,
                                                                            'subset' => true,
                                                                            'color' => false,
                                                                            'preview' => true,
                                                                            'units' => 'px',
                                                                            'required'      => array('web_font_switch','equals','1'),
                                                                        ),
                                                                        array(
                                                                            'id' => 'h6_heading_font',
                                                                            'type' => 'typography',
                                                                            'title' => __('Heading 6', 'vslmd'),
                                                                            'subtitle' => __('Specify the H6 Text properties.', 'vslmd'),
                                                                            'output' => array('h6'),
                                                                            'google' => true,
                                                                            'font-backup' => false,
                                                                            'line-height'=>true,
                                                                            'font-style' => true,
                                                                            'text-align' => false,
                                                                            'subsets' => false,
                                                                            'text-transform' => true,
                                                                            'font-weight' => true,
                                                                            'letter-spacing' => true,
                                                                            'subset' => true,
                                                                            'color' => false,
                                                                            'preview' => true,
                                                                            'units' => 'px',
                                                                            'required'      => array('web_font_switch','equals','1'),
                                                                        ),
                                                                    ),
                                                                    ) );
                                                                    Redux::setSection( $opt_name, array(
                                                                        'icon'      => 'el-icon-screen',
                                                                        'title'     => __('Layout', 'vslmd'),
                                                                        'desc'      => __('Control and configure the layout.', 'vslmd'),
                                                                        'fields'    => array(
                                                                            array(
                                                                                'id'        => 'boxed_or_stretched',
                                                                                'type'      => 'image_select',
                                                                                'compiler'  => true,
                                                                                'title'     => __('Boxed or Stretched style', 'vslmd'),
                                                                                'subtitle'  => __('Choose the format that works best for you.', 'vslmd'),
                                                                                'options'   => array(
                                                                                    'boxed-layout' => array('alt' => 'Boxed','img' => $ReduxFrameworkAssets . 'img/3cm.png'),
                                                                                    'stretched-layout' => array('alt' => 'Stretched', 'img' => $ReduxFrameworkAssets . 'img/1col.png')
                                                                                ),
                                                                                'default'   => 'stretched-layout'
                                                                            ),
                                                                            array(
                                                                                'id'        => 'boxed_background_color',
                                                                                'type'      => 'color_rgba',
                                                                                'title'     => __('Background Color', 'vslmd'),
                                                                                'subtitle'  => __('Set color and alpha channel', 'vslmd'),
                                                                                'required'  => array('boxed_or_stretched','equals','boxed-layout'),
                                                                                'output'    => array('background-color' => 'body'),
                                                                            ),
                                                                            array(         
                                                                                'id'       => 'boxed_background_image',
                                                                                'type'     => 'background',
                                                                                'background-color' => false,
                                                                                'title'    => __('Background Image', 'vslmd'),
                                                                                'subtitle' => __('Background with image', 'vslmd'),
                                                                                'output'   => array('html'),
                                                                                'required' => array('boxed_or_stretched','equals','boxed-layout'),
                                                                            ),
                                                                        ),
                                                                        ) );                    
                                                                        Redux::setSection( $opt_name, array(
                                                                            'title' => __('Post Types', 'vslmd'),
                                                                            'desc' => __('Control and configure the general setup of your custom post types.', 'vslmd'),
                                                                            'icon' => 'el-icon-pencil-alt',
                                                                            'fields' => array( 
                                                                                array(
                                                                                    'id'            => 'extra-custom-post-types',
                                                                                    'type'          => 'slider',
                                                                                    'title'         => __('How many custom post types?', 'vslmd'),
                                                                                    'subtitle'      => __('', 'vslmd'),
                                                                                    'desc'          => __('Enter a number. Min: 1, max: 5, step: 1, default value: 0', 'vslmd'),
                                                                                    'default'       => 2,
                                                                                    'min'           => 0,
                                                                                    'step'          => 1,
                                                                                    'max'           => 5,
                                                                                    'display_value' => 'text'
                                                                                ),
                                                                                array(
                                                                                    'id'        => 'custom-post-type-singular-name-1',
                                                                                    'type'      => 'text',
                                                                                    'title'     => __('Singular name', 'vslmd'),
                                                                                    'required'      => array('extra-custom-post-types','>=','1'),
                                                                                    'desc'      => __('Enter a Singular Name. Ex: "Book"', 'vslmd'),
                                                                                    'default'       => 'Portfolio',
                                                                                ),
                                                                                array(
                                                                                    'id'        => 'custom-post-type-plural-name-1',
                                                                                    'type'      => 'text',
                                                                                    'title'     => __('Plural name', 'vslmd'),
                                                                                    'required'      => array('extra-custom-post-types','>=','1'),
                                                                                    'desc'      => __('Enter a Plural Name. Ex: "Books"', 'vslmd'),
                                                                                    'default'       => 'Projects',
                                                                                ),
                                                                                array(
                                                                                    'id' => 'custom-post-type-slug-1', 
                                                                                    'type' => 'text', 
                                                                                    'title' => __('Custom Slug', 'vslmd'),
                                                                                    'required'      => array('extra-custom-post-types','>=','1'),
                                                                                    'subtitle' => __('', 'vslmd'),
                                                                                    'default'       => 'portfolio',
                                                                                    'desc' => __('Please enter the Slug here.  Ex: "book"<br/><br/>
                                                                                    <b>You will still have to refresh your permalinks after saving this!</b><br/><br/>
                                                                                    This is done by going to <b>Settings -> Permalinks</b> and clicking save.', 'vslmd'),
                                                                                ),  
                                                                                array(
                                                                                    'id'        => 'custom-post-type-index-1',
                                                                                    'type'      => 'text',
                                                                                    'title'     => __('URL Index', 'vslmd'),
                                                                                    'required'      => array('extra-custom-post-types','>=','1'),
                                                                                    'desc'      => __('Enter an URL valid of the Index.', 'vslmd'),
                                                                                ),
                                                                                array(
                                                                                    'id' => 'custom-post-type-icon-1', 
                                                                                    'type' => 'text', 
                                                                                    'title' => __('Custom Post Type Icon', 'vslmd'),
                                                                                    'required'      => array('extra-custom-post-types','>=','1'),
                                                                                    'subtitle' => __('Choice the Icon you want.', 'vslmd'),
                                                                                    'desc' => __('Choice the <a target="_blank" href="https://developer.wordpress.org/resource/dashicons/">Dashicon</a> and paster here. Ex: "dashicons-admin-site"', 'vslmd'),
                                                                                    'default'       => 'dashicons-portfolio',
                                                                                ),
                                                                                array(
                                                                                    'id'=>'custom-post-type-divide-1',
                                                                                    'required'      => array('extra-custom-post-types','>=','2'),
                                                                                    'type' => 'divide'
                                                                                ), 
                                                                                array(
                                                                                    'id'        => 'custom-post-type-singular-name-2',
                                                                                    'type'      => 'text',
                                                                                    'title'     => __('Singular name', 'vslmd'),
                                                                                    'required'      => array('extra-custom-post-types','>=','2'),
                                                                                    'desc'      => __('Enter a Singular Name. Ex: "Book"', 'vslmd'),
                                                                                    'default'       => 'Team',
                                                                                ),
                                                                                array(
                                                                                    'id'        => 'custom-post-type-plural-name-2',
                                                                                    'type'      => 'text',
                                                                                    'title'     => __('Plural name', 'vslmd'),
                                                                                    'required'      => array('extra-custom-post-types','>=','2'),
                                                                                    'desc'      => __('Enter a Plural Name. Ex: "Books"', 'vslmd'),
                                                                                    'default'       => 'Team',
                                                                                ),
                                                                                array(
                                                                                    'id' => 'custom-post-type-slug-2', 
                                                                                    'type' => 'text', 
                                                                                    'title' => __('Custom Slug', 'vslmd'),
                                                                                    'required'      => array('extra-custom-post-types','>=','2'),
                                                                                    'subtitle' => __('', 'vslmd'),
                                                                                    'default'       => 'team',
                                                                                    'desc' => __('Please enter the Slug here.  Ex: "book"<br/><br/>
                                                                                    <b>You will still have to refresh your permalinks after saving this!</b><br/><br/>
                                                                                    This is done by going to <b>Settings -> Permalinks</b> and clicking save.', 'vslmd'),
                                                                                ),  
                                                                                array(
                                                                                    'id'        => 'custom-post-type-index-2',
                                                                                    'type'      => 'text',
                                                                                    'title'     => __('URL Index', 'vslmd'),
                                                                                    'required'      => array('extra-custom-post-types','>=','2'),
                                                                                    'desc'      => __('Enter an URL valid of the Index.', 'vslmd'),
                                                                                ),
                                                                                array(
                                                                                    'id' => 'custom-post-type-icon-2', 
                                                                                    'type' => 'text', 
                                                                                    'title' => __('Custom Post Type Icon', 'vslmd'),
                                                                                    'required'      => array('extra-custom-post-types','>=','2'),
                                                                                    'subtitle' => __('Choice the Icon you want.', 'vslmd'),
                                                                                    'default'       => 'dashicons-groups',
                                                                                    'desc' => __('Choice the <a target="_blank" href="https://developer.wordpress.org/resource/dashicons/">Dashicon</a> and paster here. Ex: "dashicons-admin-site"', 'vslmd'),
                                                                                ),
                                                                                array(
                                                                                    'id'=>'custom-post-type-divide-2',
                                                                                    'required'      => array('extra-custom-post-types','>=','3'),
                                                                                    'type' => 'divide'
                                                                                ),  
                                                                                array(
                                                                                    'id'        => 'custom-post-type-singular-name-3',
                                                                                    'type'      => 'text',
                                                                                    'title'     => __('Singular name', 'vslmd'),
                                                                                    'required'      => array('extra-custom-post-types','>=','3'),
                                                                                    'desc'      => __('Enter a Singular Name. Ex: "Book"', 'vslmd'),
                                                                                ),
                                                                                array(
                                                                                    'id'        => 'custom-post-type-plural-name-3',
                                                                                    'type'      => 'text',
                                                                                    'title'     => __('Plural name', 'vslmd'),
                                                                                    'required'      => array('extra-custom-post-types','>=','3'),
                                                                                    'desc'      => __('Enter a Plural Name. Ex: "Books"', 'vslmd'),
                                                                                ),
                                                                                array(
                                                                                    'id' => 'custom-post-type-slug-3', 
                                                                                    'type' => 'text', 
                                                                                    'title' => __('Custom Slug', 'vslmd'),
                                                                                    'required'      => array('extra-custom-post-types','>=','3'),
                                                                                    'subtitle' => __('', 'vslmd'),
                                                                                    'desc' => __('Please enter the Slug here.  Ex: "book"<br/><br/>
                                                                                    <b>You will still have to refresh your permalinks after saving this!</b><br/><br/>
                                                                                    This is done by going to <b>Settings -> Permalinks</b> and clicking save.', 'vslmd'),
                                                                                ),  
                                                                                array(
                                                                                    'id'        => 'custom-post-type-index-3',
                                                                                    'type'      => 'text',
                                                                                    'title'     => __('URL Index', 'vslmd'),
                                                                                    'required'      => array('extra-custom-post-types','>=','3'),
                                                                                    'desc'      => __('Enter an URL valid of the Index.', 'vslmd'),
                                                                                ),
                                                                                array(
                                                                                    'id' => 'custom-post-type-icon-3', 
                                                                                    'type' => 'text', 
                                                                                    'title' => __('Custom Post Type Icon', 'vslmd'),
                                                                                    'required'      => array('extra-custom-post-types','>=','3'),
                                                                                    'subtitle' => __('Choice the Icon you want.', 'vslmd'),
                                                                                    'desc' => __('Choice the <a target="_blank" href="https://developer.wordpress.org/resource/dashicons/">Dashicon</a> and paster here. Ex: "dashicons-admin-site"', 'vslmd'),
                                                                                ), 
                                                                                array(
                                                                                    'id'=>'custom-post-type-divide-3',
                                                                                    'required'      => array('extra-custom-post-types','>=','4'),
                                                                                    'type' => 'divide'
                                                                                ),
                                                                                array(
                                                                                    'id'        => 'custom-post-type-singular-name-4',
                                                                                    'type'      => 'text',
                                                                                    'title'     => __('Singular name', 'vslmd'),
                                                                                    'required'      => array('extra-custom-post-types','>=','4'),
                                                                                    'desc'      => __('Enter a Singular Name. Ex: "Book"', 'vslmd'),
                                                                                ),
                                                                                array(
                                                                                    'id'        => 'custom-post-type-plural-name-4',
                                                                                    'type'      => 'text',
                                                                                    'title'     => __('Plural name', 'vslmd'),
                                                                                    'required'      => array('extra-custom-post-types','>=','4'),
                                                                                    'desc'      => __('Enter a Plural Name. Ex: "Books"', 'vslmd'),
                                                                                ),
                                                                                array(
                                                                                    'id' => 'custom-post-type-slug-4', 
                                                                                    'type' => 'text', 
                                                                                    'title' => __('Custom Slug', 'vslmd'),
                                                                                    'required'      => array('extra-custom-post-types','>=','4'),
                                                                                    'subtitle' => __('', 'vslmd'),
                                                                                    'desc' => __('Please enter the Slug here.  Ex: "book"<br/><br/>
                                                                                    <b>You will still have to refresh your permalinks after saving this!</b><br/><br/>
                                                                                    This is done by going to <b>Settings -> Permalinks</b> and clicking save.', 'vslmd'),
                                                                                ),  
                                                                                array(
                                                                                    'id'        => 'custom-post-type-index-4',
                                                                                    'type'      => 'text',
                                                                                    'title'     => __('URL Index', 'vslmd'),
                                                                                    'required'      => array('extra-custom-post-types','>=','4'),
                                                                                    'desc'      => __('Enter an URL valid of the Index.', 'vslmd'),
                                                                                ),
                                                                                array(
                                                                                    'id' => 'custom-post-type-icon-4', 
                                                                                    'type' => 'text', 
                                                                                    'title' => __('Custom Post Type Icon', 'vslmd'),
                                                                                    'required'      => array('extra-custom-post-types','>=','4'),
                                                                                    'subtitle' => __('Choice the Icon you want.', 'vslmd'),
                                                                                    'desc' => __('Choice the <a target="_blank" href="https://developer.wordpress.org/resource/dashicons/">Dashicon</a> and paster here. Ex: "dashicons-admin-site"', 'vslmd'),
                                                                                ), 
                                                                                array(
                                                                                    'id'=>'custom-post-type-divide-4',
                                                                                    'required'      => array('extra-custom-post-types','>=','5'),
                                                                                    'type' => 'divide'
                                                                                ), 
                                                                                array(
                                                                                    'id'        => 'custom-post-type-singular-name-5',
                                                                                    'type'      => 'text',
                                                                                    'title'     => __('Singular name', 'vslmd'),
                                                                                    'required'      => array('extra-custom-post-types','>=','5'),
                                                                                    'desc'      => __('Enter a Singular Name. Ex: "Book"', 'vslmd'),
                                                                                ),
                                                                                array(
                                                                                    'id'        => 'custom-post-type-plural-name-5',
                                                                                    'type'      => 'text',
                                                                                    'title'     => __('Plural name', 'vslmd'),
                                                                                    'required'      => array('extra-custom-post-types','>=','5'),
                                                                                    'desc'      => __('Enter a Plural Name. Ex: "Books"', 'vslmd'),
                                                                                ),
                                                                                array(
                                                                                    'id' => 'custom-post-type-slug-5', 
                                                                                    'type' => 'text', 
                                                                                    'title' => __('Custom Slug', 'vslmd'),
                                                                                    'required'      => array('extra-custom-post-types','>=','5'),
                                                                                    'subtitle' => __('', 'vslmd'),
                                                                                    'desc' => __('Please enter the Slug here.  Ex: "book"<br/><br/>
                                                                                    <b>You will still have to refresh your permalinks after saving this!</b><br/><br/>
                                                                                    This is done by going to <b>Settings -> Permalinks</b> and clicking save.', 'vslmd'),
                                                                                ),  
                                                                                array(
                                                                                    'id'        => 'custom-post-type-index-5',
                                                                                    'type'      => 'text',
                                                                                    'title'     => __('URL Index', 'vslmd'),
                                                                                    'required'      => array('extra-custom-post-types','>=','5'),
                                                                                    'desc'      => __('Enter an URL valid of the Index.', 'vslmd'),
                                                                                ),
                                                                                array(
                                                                                    'id' => 'custom-post-type-icon-5', 
                                                                                    'type' => 'text', 
                                                                                    'title' => __('Custom Post Type Icon', 'vslmd'),
                                                                                    'required'      => array('extra-custom-post-types','>=','5'),
                                                                                    'subtitle' => __('Choice the Icon you want.', 'vslmd'),
                                                                                    'desc' => __('Choice the <a target="_blank" href="https://developer.wordpress.org/resource/dashicons/">Dashicon</a> and paster here. Ex: "dashicons-admin-site"', 'vslmd'),
                                                                                ), 
                                                                                )
                                                                                ) );
                                                                                /*
                                                                                Redux::setSection( $opt_name, array(
                                                                                    'icon'      => 'el-icon-file',
                                                                                    'subsection' => true,
                                                                                    'title'     => __('Preset Post Types', 'vslmd'),
                                                                                    'desc'      => __('Control and configure the general setup of your custom post types.', 'vslmd'),
                                                                                    'fields'    => array(
                                                                                        array(
                                                                                            'id' => 'knowledgebase_post_type',
                                                                                            'type' => 'switch',
                                                                                            'title' => __('Knowledgebases', 'vslmd'), 
                                                                                            'subtitle' => __('Do you want Knowledgebase post type?', 'vslmd'),
                                                                                            'default' => 0
                                                                                        ),
                                                                                        array(
                                                                                            'id' => 'portfolio_post_type',
                                                                                            'type' => 'switch',
                                                                                            'title' => __('Portfolio', 'vslmd'), 
                                                                                            'subtitle' => __('Do you want Portfolio post type?', 'vslmd'),
                                                                                            'default' => 0
                                                                                        ),
                                                                                        array(
                                                                                            'id' => 'team_post_type',
                                                                                            'type' => 'switch',
                                                                                            'title' => __('Team', 'vslmd'), 
                                                                                            'subtitle' => __('Do you want Team post type?', 'vslmd'),
                                                                                            'default' => 0
                                                                                        ),
                                                                                    ),
                                                                                    ) );  
                                                                                    */
                                                                                    Redux::setSection( $opt_name, array(
                                                                                        'icon'      => 'el-icon-file',
                                                                                        'subsection' => true,
                                                                                        'title'     => __('Share', 'vslmd'),
                                                                                        'desc'      => __('Control and configure the general setup of your custom post types.', 'vslmd'),
                                                                                        'fields'    => array(
                                                                                            array(
                                                                                                'id'        => 'post_type_share_switch',
                                                                                                'type'      => 'switch',
                                                                                                'title'     => __('Enable Sharing System?', 'vslmd'),
                                                                                                'default'   => false,
                                                                                            ),
                                                                                            array(
                                                                                                'id'       => 'post_type_share',
                                                                                                'type'     => 'select',
                                                                                                'multi'    => true,
                                                                                                'data'     => 'post_types',
                                                                                                'title'    => __('Sharing for Posts and Pages', 'vslmd'), 
                                                                                                'desc'     => __('Enable or Disable Sharing System for each Post Type or Pages.', 'vslmd'),
                                                                                                'required' => array('post_type_share_switch','equals','1'),
                                                                                            ),
                                                                                            array(
                                                                                                'id' => 'post_type_share_position', 
                                                                                                'type' => 'select',
                                                                                                'title' => __('Post Type Share Position', 'vslmd'),
                                                                                                'subtitle' => __('Choose the Position.', 'vslmd'),
                                                                                                'required' => array('post_type_share_switch','equals','1'),
                                                                                                'options' => array(
                                                                                                    'text-left' => 'Left',
                                                                                                    'text-center' => 'Center',
                                                                                                    'text-right' => 'Right'
                                                                                                ),
                                                                                                'default' => 'center'
                                                                                            ),
                                                                                        ),
                                                                                        ) );  
                                                                                        Redux::setSection( $opt_name, array(
                                                                                            'icon'      => 'el-icon-pencil',
                                                                                            'title'     => __('Blog', 'vslmd'),
                                                                                            'desc'      => __('Control and configure the general setup of your Blog.', 'vslmd'),
                                                                                            'fields'    => array(
                                                                                                array(
                                                                                                    'id'       => 'single_post_widget',
                                                                                                    'type'     => 'button_set',
                                                                                                    'title'    => __('Single Post Widget', 'vslmd'),
                                                                                                    'subtitle' => __('Enable or disable the widget on single post', 'vslmd'),
                                                                                                    'options' => array(
                                                                                                        '1' => 'No', 
                                                                                                        '2' => 'Left', 
                                                                                                        '3' => 'Right',
                                                                                                    ), 
                                                                                                    'default' => '3'
                                                                                                ),
                                                                                            ),
                                                                                            ) );  
                                                                                            global $woocommerce; 
                                                                                            if ($woocommerce) {
                                                                                                Redux::setSection( $opt_name, array(
                                                                                                    'icon' => 'el-icon-shopping-cart',
                                                                                                    'title' => __('WooCommerce', 'vslmd'),
                                                                                                    'desc' => __('Control and configure the general setup of your store.', 'vslmd'),
                                                                                                    'fields' => array( 
                                                                                                        array(
                                                                                                            'id' => 'shop_structure',
                                                                                                            'type' => 'image_select',
                                                                                                            'title' => __('Main Shop Structure', 'vslmd'), 
                                                                                                            'subtitle' => __('Select sidebar alignment.', 'vslmd'),
                                                                                                            'desc' => '',
                                                                                                            'options' => array(
                                                                                                                '0' => array('img' => $ReduxFrameworkAssets . 'img/1col.png'),
                                                                                                                '1' => array('img' => $ReduxFrameworkAssets . 'img/2cl.png'),
                                                                                                                '2' => array('img' => $ReduxFrameworkAssets . 'img/2cr.png')
                                                                                                            ),
                                                                                                            'default' => '0'
                                                                                                        ),
                                                                                                        array(
                                                                                                            'id' => 'product_structure',
                                                                                                            'type' => 'image_select',
                                                                                                            'title' => __('Single Product Structure', 'vslmd'), 
                                                                                                            'subtitle' => __('Select sidebar alignment.', 'vslmd'),
                                                                                                            'desc' => '',
                                                                                                            'options' => array(
                                                                                                                '0' => array('img' => $ReduxFrameworkAssets . 'img/1col.png'),
                                                                                                                '1' => array('img' => $ReduxFrameworkAssets . 'img/2cl.png'),
                                                                                                                '2' => array('img' => $ReduxFrameworkAssets . 'img/2cr.png')
                                                                                                            ),
                                                                                                            'default' => '0'
                                                                                                        ),  
                                                                                                        )
                                                                                                        ) );
                                                                                                        Redux::setSection( $opt_name, array(
                                                                                                            'icon'      => 'el-icon-shopping-cart',
                                                                                                            'subsection' => true,
                                                                                                            'title'     => __('Header Main Shop', 'vslmd'),
                                                                                                            'desc'      => __('Control and configure the general setup of your store.', 'vslmd'),
                                                                                                            'fields'    => array(
                                                                                                                array(
                                                                                                                    'id'       => 'woo_menu_overlay_switch',
                                                                                                                    'type'     => 'button_set',
                                                                                                                    'title'    => __('Overlay Navigation Menu', 'vslmd'),
                                                                                                                    'desc' => __('The menu will overlay the content on top.', 'vslmd'),
                                                                                                                    'options' => array(
                                                                                                                        'no-overlay' => 'No', 
                                                                                                                        'default-colors-overlay colors-overlay-enabled' => 'Default Colors', 
                                                                                                                        'light-colors-overlay colors-overlay-enabled' => 'Light Colors',
                                                                                                                        'dark-colors-overlay colors-overlay-enabled' => 'Dark Colors'
                                                                                                                    ), 
                                                                                                                    'default' => 'no-overlay'
                                                                                                                ),
                                                                                                                array(
                                                                                                                    'id'       => 'woo_layout_header_title',
                                                                                                                    'type'     => 'button_set',
                                                                                                                    'title'    => __('Layout Header Title', 'vslmd'),
                                                                                                                    'subtitle'     => __('Organize how you want the layout to appear.', 'vslmd'),
                                                                                                                    'options' => array(
                                                                                                                        '1' => 'No', 
                                                                                                                        '2' => 'Background Color', 
                                                                                                                        '3' => 'Background Image',
                                                                                                                        '4' => 'Slider Revolution',
                                                                                                                    ), 
                                                                                                                    'default' => '2'
                                                                                                                ),
                                                                                                                array(
                                                                                                                    'id'        => 'woo_header_title_color_overlay',
                                                                                                                    'type'      => 'color_rgba',
                                                                                                                    'title'     => 'Background Color',
                                                                                                                    'required' => array(
                                                                                                                        array('woo_layout_header_title', '<=', 3),
                                                                                                                        array('woo_layout_header_title', '!=', 1),
                                                                                                                    ),
                                                                                                                    'desc'      => 'Set Background Color and Opacity.',
                                                                                                                    'output'    => array(
                                                                                                                        'background-color' => 'body.woocommerce .header-presentation .hp-background-color'
                                                                                                                        )
                                                                                                                    ),
                                                                                                                    array(         
                                                                                                                        'id'       => 'woo_header_title_background',
                                                                                                                        'type'     => 'background',
                                                                                                                        'background-color' => false,
                                                                                                                        'required' => array('woo_layout_header_title','equals','3'),
                                                                                                                        'title'    => __('Background Image', 'vslmd'),
                                                                                                                        'desc'     => __('Upload your image should be between 1920px x 1080px (or more) for best results.', 'vslmd'),
                                                                                                                        'output'    => array('body.woocommerce .header-presentation'),
                                                                                                                    ),  
                                                                                                                    array(
                                                                                                                        'id'       => 'woo_custom_header_title_height',
                                                                                                                        'type'     => 'button_set',
                                                                                                                        'title'    => __('Header Title Height', 'vslmd'),
                                                                                                                        'desc'     => __('Choose the height you want.', 'vslmd'),
                                                                                                                        'required' => array(
                                                                                                                            array('woo_layout_header_title', '<=', 3),
                                                                                                                            array('woo_layout_header_title', '>', 1),
                                                                                                                        ),
                                                                                                                        'options' => array(
                                                                                                                            'small' => 'Small', 
                                                                                                                            'medium' => 'Medium', 
                                                                                                                            'full' => 'Full height'
                                                                                                                        ), 
                                                                                                                        'default' => 'medium'
                                                                                                                    ),
                                                                                                                    array(
                                                                                                                        'id'       => 'woo_title_editor',
                                                                                                                        'type'     => 'text',
                                                                                                                        'title'    => __('Title', 'vslmd'), 
                                                                                                                        'subtitle'     => __('Please enter the title.', 'vslmd'),
                                                                                                                        'required' => array(
                                                                                                                            array('woo_layout_header_title', '<=', 3),
                                                                                                                            array('woo_layout_header_title', '>', 1),
                                                                                                                        ),
                                                                                                                    ),
                                                                                                                    array(
                                                                                                                        'id'       => 'woo_caption_editor',
                                                                                                                        'type'     => 'text',
                                                                                                                        'title'    => __('Caption', 'vslmd'), 
                                                                                                                        'subtitle'     => __('Please enter the caption.', 'vslmd'),
                                                                                                                        'required' => array(
                                                                                                                            array('woo_layout_header_title', '<=', 3),
                                                                                                                            array('woo_layout_header_title', '>', 1),
                                                                                                                        ),
                                                                                                                    ),
                                                                                                                    array(
                                                                                                                        'id' => 'woo_slider_rev_header', 
                                                                                                                        'title' => __('Slider Revolution', 'vslmd'),
                                                                                                                        'desc' => __('Choose Slide Template', 'vslmd'),
                                                                                                                        'required' => array('woo_layout_header_title','equals','4'),
                                                                                                                        'type' => 'select',
                                                                                                                        'options'   => $revsliders,
                                                                                                                    ),
                                                                                                                ),
                                                                                                                ) );    
                                                                                                            }
                                                                                                            Redux::setSection( $opt_name, array(
                                                                                                                'icon'      => 'el-icon-user',
                                                                                                                'title'     => __('Account', 'vslmd'),
                                                                                                                'desc'      => __('Control and configure the general setup of your Register, Login and Reset pages.', 'vslmd'),
                                                                                                                'fields'    => array(
                                                                                                                    array(
                                                                                                                        'id'        => 'account_brand',
                                                                                                                        'type'      => 'media', 
                                                                                                                        'title'     => __('Upload your Logo', 'vslmd'),
                                                                                                                        'desc'      => __('The WordPress logo will be placed here if you have not uploaded any image for the logo.', 'vslmd'),
                                                                                                                        'subtitle'  => __('Standard resolution is 84px x 84px.', 'vslmd'),
                                                                                                                    ),
                                                                                                                    array(
                                                                                                                        'id'        => 'account_background_color',
                                                                                                                        'type'      => 'color_rgba',
                                                                                                                        'title'     => 'Background Color',
                                                                                                                        'desc'      => 'Set Background Color and Opacity.',
                                                                                                                    ),
                                                                                                                    array(         
                                                                                                                        'id'       => 'account_background_image',
                                                                                                                        'type'     => 'background',
                                                                                                                        'background-color' => false,
                                                                                                                        'title'    => __('Background Image', 'vslmd'),
                                                                                                                        'desc'     => __('Upload your image should be between 1920px x 1080px (or more) for best results.', 'vslmd'),
                                                                                                                    ), 
                                                                                                                    array(
                                                                                                                        'id' => 'account_color_scheme', 
                                                                                                                        'type' => 'select',
                                                                                                                        'title' => __('Text and Buttons Color Scheme', 'vslmd'),
                                                                                                                        'subtitle' => __('Choose the Color Scheme.', 'vslmd'),
                                                                                                                        'options' => array(
                                                                                                                            'light' => 'Light',
                                                                                                                            'dark' => 'Dark'
                                                                                                                        ),
                                                                                                                    ),
                                                                                                                ),
                                                                                                                ) );
                                                                                                                Redux::setSection( $opt_name, array(
                                                                                                                    'icon'      => 'el-icon-css',
                                                                                                                    'title'     => __('Custom Code', 'vslmd'),
                                                                                                                    'desc'      => __('Enter your custom codes.', 'vslmd'),
                                                                                                                    'fields'    => array(
                                                                                                                        array(
                                                                                                                            'id'        => 'custom_css',
                                                                                                                            'type'      => 'ace_editor',
                                                                                                                            'title'     => __('CSS Code', 'vslmd'),
                                                                                                                            'subtitle'  => __('Paste your CSS code here.', 'vslmd'),
                                                                                                                            'mode'      => 'css',
                                                                                                                            'theme'     => 'monokai',
                                                                                                                            'default' => ''
                                                                                                                        ),
                                                                                                                        array(
                                                                                                                            'id'        => 'custom_javascript',
                                                                                                                            'type'      => 'ace_editor',
                                                                                                                            'title'     => __('JS Code', 'vslmd'),
                                                                                                                            'subtitle'  => __('Paste your JS code here.', 'vslmd'),
                                                                                                                            'mode'      => 'javascript',
                                                                                                                            'theme'     => 'chrome',
                                                                                                                        ),
                                                                                                                        array(
                                                                                                                            'id'        => 'custom_analytics',
                                                                                                                            'type'      => 'ace_editor',
                                                                                                                            'title'     => __('Google Analytics Code', 'vslmd'),
                                                                                                                            'subtitle'  => __('Paste your code here.', 'vslmd'),
                                                                                                                            'mode'      => 'text',
                                                                                                                            'theme'     => 'chrome',
                                                                                                                            'default' => ''
                                                                                                                        ),
                                                                                                                    ),
                                                                                                                    ) ); 
                                                                                                                    Redux::setSection( $opt_name, array(
                                                                                                                        'icon'      => 'el el-error',
                                                                                                                        'title'     => __('404', 'vslmd'),
                                                                                                                        'desc'      => __('Control and configure the general setup of your 404 page.', 'vslmd'),
                                                                                                                        'fields'    => array(
                                                                                                                            array(
                                                                                                                                'id'        => '404_switch',
                                                                                                                                'type'      => 'switch',
                                                                                                                                'title'     => __('Enable Custom 404?', 'vslmd'),
                                                                                                                                'default'   => false,
                                                                                                                            ),
                                                                                                                            array(
                                                                                                                                'id'       => '404_menu_overlay_switch',
                                                                                                                                'type'     => 'button_set',
                                                                                                                                'title'    => __('Overlay Navigation Menu', 'vslmd'),
                                                                                                                                'desc' => __('The menu will overlay the content on top.', 'vslmd'),
                                                                                                                                'required'      => array('404_switch','equals','1'),
                                                                                                                                'options' => array(
                                                                                                                                    'no-overlay' => 'No', 
                                                                                                                                    'default-colors-overlay colors-overlay-enabled' => 'Default Colors', 
                                                                                                                                    'light-colors-overlay colors-overlay-enabled' => 'Light Colors',
                                                                                                                                    'dark-colors-overlay colors-overlay-enabled' => 'Dark Colors'
                                                                                                                                ), 
                                                                                                                                'default' => 'no-overlay'
                                                                                                                            ),
                                                                                                                            array(
                                                                                                                                'id'       => '404_layout_header_title',
                                                                                                                                'type'     => 'button_set',
                                                                                                                                'title'    => __('Layout Header Title', 'vslmd'),
                                                                                                                                'subtitle'     => __('Organize how you want the layout to appear.', 'vslmd'),
                                                                                                                                'required'      => array('404_switch','equals','1'),
                                                                                                                                'options' => array(
                                                                                                                                    '1' => 'No', 
                                                                                                                                    '2' => 'Background Color', 
                                                                                                                                    '3' => 'Background Image',
                                                                                                                                    '4' => 'Slider Revolution',
                                                                                                                                ), 
                                                                                                                                'default' => '2'
                                                                                                                            ),
                                                                                                                            array(
                                                                                                                                'id'        => '404_header_title_color_overlay',
                                                                                                                                'type'      => 'color_rgba',
                                                                                                                                'title'     => 'Background Color',
                                                                                                                                'required' => array(
                                                                                                                                    array('404_switch','equals','1'),
                                                                                                                                    array('404_layout_header_title', '<=', 3),
                                                                                                                                    array('404_layout_header_title', '!=', 1),
                                                                                                                                ),
                                                                                                                                'desc'      => 'Set Background Color and Opacity.',
                                                                                                                                'output'    => array(
                                                                                                                                    'background-color' => 'body.error404 .header-presentation .hp-background-color'
                                                                                                                                    )
                                                                                                                                ),
                                                                                                                                array(         
                                                                                                                                    'id'       => '404_header_title_background',
                                                                                                                                    'type'     => 'background',
                                                                                                                                    'background-color' => false,
                                                                                                                                    'required' => array(
                                                                                                                                        array('404_switch','equals','1'),
                                                                                                                                        array('404_layout_header_title','equals','3'),
                                                                                                                                    ),
                                                                                                                                    'title'    => __('Background Image', 'vslmd'),
                                                                                                                                    'desc'     => __('Upload your image should be between 1920px x 1080px (or more) for best results.', 'vslmd'),
                                                                                                                                    'output'    => array('body.error404 .header-presentation'),
                                                                                                                                    
                                                                                                                                ),  
                                                                                                                                array(
                                                                                                                                    'id'       => '404_custom_header_title_height',
                                                                                                                                    'type'     => 'button_set',
                                                                                                                                    'title'    => __('Header Title Height', 'vslmd'),
                                                                                                                                    'desc'     => __('Choose the height you want.', 'vslmd'),
                                                                                                                                    'required' => array(
                                                                                                                                        array('404_switch','equals','1'),
                                                                                                                                        array('404_layout_header_title', '<=', 3),
                                                                                                                                        array('404_layout_header_title', '>', 1),
                                                                                                                                    ),
                                                                                                                                    'options' => array(
                                                                                                                                        'small' => 'Small', 
                                                                                                                                        'medium' => 'Medium', 
                                                                                                                                        'full' => 'Full height'
                                                                                                                                    ), 
                                                                                                                                    'default' => 'medium'
                                                                                                                                ),
                                                                                                                                array(
                                                                                                                                    'id'       => '404_title_editor',
                                                                                                                                    'type'     => 'text',
                                                                                                                                    'title'    => __('Title', 'vslmd'), 
                                                                                                                                    'subtitle'     => __('Please enter the title.', 'vslmd'),
                                                                                                                                    'required' => array(
                                                                                                                                        array('404_switch','equals','1'),
                                                                                                                                        array('404_layout_header_title', '<=', 3),
                                                                                                                                        array('404_layout_header_title', '>', 1),
                                                                                                                                    ),
                                                                                                                                ),
                                                                                                                                array(
                                                                                                                                    'id'       => '404_caption_editor',
                                                                                                                                    'type'     => 'text',
                                                                                                                                    'title'    => __('Caption', 'vslmd'), 
                                                                                                                                    'subtitle'     => __('Please enter the caption.', 'vslmd'),
                                                                                                                                    'required' => array(
                                                                                                                                        array('404_switch','equals','1'),
                                                                                                                                        array('404_layout_header_title', '<=', 3),
                                                                                                                                        array('404_layout_header_title', '>', 1),
                                                                                                                                    ),
                                                                                                                                ),
                                                                                                                                array(
                                                                                                                                    'id' => '404_slider_rev_header', 
                                                                                                                                    'title' => __('Slider Revolution', 'vslmd'),
                                                                                                                                    'desc' => __('Choose Slide Template', 'vslmd'),
                                                                                                                                    'required' => array(
                                                                                                                                        array('404_switch','equals','1'),
                                                                                                                                        array('404_layout_header_title','equals','4'),
                                                                                                                                    ),
                                                                                                                                    'type' => 'select',
                                                                                                                                    'options'   => $revsliders,
                                                                                                                                ),
                                                                                                                                array(
                                                                                                                                    'id'       => '404_content',
                                                                                                                                    'type'     => 'select',
                                                                                                                                    'title'    => __('Select Page Content', 'vslmd'), 
                                                                                                                                    'subtitle' => __('Select a page content for 404 page.', 'vslmd'),
                                                                                                                                    'required'      => array('404_switch','equals','1'),
                                                                                                                                    'data'  => 'page',
                                                                                                                                ),
                                                                                                                            ),
                                                                                                                            ) ); 
                                                                                                                            
                                                                                                                            /*
                                                                                                                            * <--- END SECTIONS
                                                                                                                            */
                                                                                                                            
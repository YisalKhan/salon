<?php
/**
 * Declaring widgets
 *
 * @package cornerstone
 */
function vslmd_widgets_init() {

    $options = get_option('vslmd_options'); 
    $top_header_columns = $options['top_header_columns'];
    $nav_position = (!empty($options['nav_position'])) ? $options['nav_position'] : 'horizontal-nav';
    $side_navigation = (empty($options['side_navigation'])) ? '' : $options['side_navigation'];

	register_sidebar( array(
		'name'          => __( 'Sidebar', 'vslmd' ),
		'id'            => 'sidebar-1',
		'description'   => 'Sidebar widget area',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

    register_sidebar( array(
        'name'          => __( 'Before Footer', 'vslmd' ),
        'id'            => 'before-footer',
        'description'   => 'Widget area for footer',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '<h4 class="widget-footer-title">',
        'after_title'   => '</h4>',
    ) );
	
    register_sidebar( array(
        'name'          => __( 'First Footer', 'vslmd' ),
        'id'            => 'first-footer',
        'description'   => 'Widget area for footer',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '<h4 class="widget-footer-title">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Second Footer', 'vslmd' ),
        'id'            => 'second-footer',
        'description'   => 'Widget area for footer',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '<h4 class="widget-footer-title">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Third Footer', 'vslmd' ),
        'id'            => 'third-footer',
        'description'   => 'Widget area for footer',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '<h4 class="widget-footer-title">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Fourth Footer', 'vslmd' ),
        'id'            => 'fourth-footer',
        'description'   => 'Widget area for footer',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '<h4 class="widget-footer-title">',
        'after_title'   => '</h4>',
    ) );
	
	register_sidebar( array(
        'name'          => __( 'After Footer', 'vslmd' ),
        'id'            => 'after-footer',
        'description'   => 'Widget area for footer',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '<h4 class="widget-footer-title">',
        'after_title'   => '</h4>',
    ) );
	
	register_sidebar( array(
        'name'          => __( 'Copyright Footer', 'vslmd' ),
        'id'            => 'copyright-footer',
        'description'   => 'Widget area for footer',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '<h4 class="widget-footer-title">',
        'after_title'   => '</h4>',
    ) );
	
	register_sidebar( array(
        'name'          => __( 'First Extra Widget', 'vslmd' ),
        'id'            => 'first-extra-widget',
        'description'   => 'Extra widget area for pages',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	
	register_sidebar( array(
        'name'          => __( 'Second Extra Widget', 'vslmd' ),
        'id'            => 'second-extra-widget',
        'description'   => 'Extra widget area for pages',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	
	register_sidebar( array(
        'name'          => __( 'Third Extra Widget', 'vslmd' ),
        'id'            => 'third-extra-widget',
        'description'   => 'Extra widget area for pages',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	
	register_sidebar( array(
        'name'          => __( 'Fourth Extra Widget', 'vslmd' ),
        'id'            => 'fourth-extra-widget',
        'description'   => 'Extra widget area for pages',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	
    // Widget Header Start

    if( $nav_position == 'horizontal-nav' ){

        if( $top_header_columns != '0' ){

            if( $top_header_columns == '1' ) {

                register_sidebar( array(
                    'name'          => __( 'Top Header Central', 'vslmd' ),
                    'id'            => 'top-header-central',
                    'description'   => 'Top header widget',
                    'before_widget' => '<div class="top-header-central">',
                    'after_widget'  => '</div>',
                    'before_title'  => '',
                    'after_title'   => '',
                ) );

            } elseif( $top_header_columns == '2' || $top_header_columns == '5' || $top_header_columns == '6' ) {

                register_sidebar( array(
                    'name'          => __( 'Top Header Left', 'vslmd' ),
                    'id'            => 'top-header-left',
                    'description'   => 'Top header widget',
                    'before_widget' => '<div class="top-header-left">',
                    'after_widget'  => '</div>',
                    'before_title'  => '',
                    'after_title'   => '',
                ) );

                register_sidebar( array(
                    'name'          => __( 'Top Header Right', 'vslmd' ),
                    'id'            => 'top-header-right',
                    'description'   => 'Top header widget',
                    'before_widget' => '<div class="top-header-right">',
                    'after_widget'  => '</div>',
                    'before_title'  => '',
                    'after_title'   => '',
                ) );

            } elseif( $top_header_columns == '3' || $top_header_columns == '4' ) {

                register_sidebar( array(
                    'name'          => __( 'Top Header Left', 'vslmd' ),
                    'id'            => 'top-header-left',
                    'description'   => 'Top header widget',
                    'before_widget' => '<div class="top-header-left">',
                    'after_widget'  => '</div>',
                    'before_title'  => '',
                    'after_title'   => '',
                ) );

                register_sidebar( array(
                    'name'          => __( 'Top Header Central', 'vslmd' ),
                    'id'            => 'top-header-central',
                    'description'   => 'Top header widget',
                    'before_widget' => '<div class="top-header-central">',
                    'after_widget'  => '</div>',
                    'before_title'  => '',
                    'after_title'   => '',
                ) );

                register_sidebar( array(
                    'name'          => __( 'Top Header Right', 'vslmd' ),
                    'id'            => 'top-header-right',
                    'description'   => 'Top header widget',
                    'before_widget' => '<div class="top-header-right">',
                    'after_widget'  => '</div>',
                    'before_title'  => '',
                    'after_title'   => '',
                ) );

            }
        }

    } else {

        register_sidebar( array(
                'name'          => __( 'Vertical Header', 'vslmd' ),
                'id'            => 'vertical-header',
                'description'   => 'Vertical header widget',
                'before_widget' => '<div class="vertical-header-widget">',
                'after_widget'  => '</div>',
                'before_title'  => '',
                'after_title'   => '',
            ) );

    } //Widget header end
	
	// WooCommerce
	
	global $woocommerce; 
	if ($woocommerce) {
	
	register_sidebar( array(
        'name'          => __( 'WooCommerce', 'vslmd' ),
        'id'            => 'woocommerce-widget',
        'description'   => 'Widget area for WooCommerce',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	
	} //WooCommerce end

    // bbPress
    
    if ( class_exists( 'bbPress' ) ) {
    
    register_sidebar( array(
        'name'          => __( 'bbPress', 'vslmd' ),
        'id'            => 'bbpress-widget',
        'description'   => 'Widget area for bbPress',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
    
    } //bbPress end

    // Side Navigation

    if ($side_navigation) {
    
    register_sidebar( array(
        'name'          => __( 'Side Navigation', 'vslmd' ),
        'id'            => 'side-navigation',
        'description'   => 'Widget area for Side Navigation',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
    
    } //Side Navigation end





}
add_action( 'widgets_init', 'vslmd_widgets_init' );
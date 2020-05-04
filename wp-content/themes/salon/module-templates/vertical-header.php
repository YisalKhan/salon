<?php
/**
 * The vertical header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package cornerstone
 */

$options = get_option('vslmd_options');

//Variables > Theme Options
$brand_site_title = $options['brand_site_title'] ?: get_bloginfo( 'name' );
$header_color_scheme = $options['header_color_scheme'] ?: 'light navbar-light';
$header_navbar_color = $options['header_navbar_color'] ?: '';
$brand_scheme = $options['brand_scheme'] ?: '3';
$link_color_style = $options['link_color_style'] ?: 't_link';
$header_height = $options['header_height'] ?: 'header-medium';
$navigation_collapsed = $options['navigation_collapsed'] ?: 'navbar-expand-md';

/* Brand */

$brand_retina = '';

if ($brand_scheme != '1') {

    if ($brand_scheme == '2' || $brand_scheme == '3') {

        if(!empty($options['brand_image']['url'])){ $brand_image = $options['brand_image']['url']; }
        if(!empty($options['brand_light']['url'])){ $brand_light = $options['brand_light']['url']; }
        if(!empty($options['brand_dark']['url'])){ $brand_dark = $options['brand_dark']['url']; }
        if(!empty($options['brand_mobile']['url'])){ $brand_mobile = $options['brand_mobile']['url']; }

    }

    elseif ($brand_scheme == '4' || $brand_scheme == '5') {

        $brand_retina = 'brand-retina';

        if(!empty($options['brand_retina_image']['url'])){ $brand_image = $options['brand_retina_image']['url']; }
        if(!empty($options['brand_retina_light']['url'])){ $brand_light = $options['brand_retina_light']['url']; }
        if(!empty($options['brand_retina_dark']['url'])){ $brand_dark = $options['brand_retina_dark']['url']; }
        if(!empty($options['brand_retina_mobile']['url'])){ $brand_mobile = $options['brand_retina_mobile']['url']; }

    }

}

/* Vertical Navigation */

if (strpos($navigation_collapsed, 'sm') !== false) {
    $vertical_navigation_remove = 'd-none d-sm-block';
} elseif (strpos($navigation_collapsed, 'md') !== false) {
    $vertical_navigation_remove = 'd-none d-md-block';
} elseif (strpos($navigation_collapsed, 'lg') !== false) {
    $vertical_navigation_remove = 'd-none d-lg-block';
} elseif (strpos($navigation_collapsed, 'xl') !== false) {
    $vertical_navigation_remove = 'd-none d-xl-block';
}

/* Header Top Color Scheme */

if (strpos($header_color_scheme, 'bg-white') !== false ) {
    $top_header_scheme = 'light bg-white';
}elseif (strpos($header_color_scheme, 'light') !== false) {
    $top_header_scheme = 'light bg-light';
} elseif (strpos($header_color_scheme, 'dark') !== false) {
    $top_header_scheme = 'dark bg-dark';
} elseif ($header_navbar_color == 'navbar-light') {
    $top_header_scheme = 'light';
} elseif ($header_navbar_color == 'navbar-dark') {
    $top_header_scheme = 'dark';
}

if ( is_singular() ) {

    //Variables > Page Options
    $change_menu = redux_post_meta( "vslmd_options", $post->ID, "change_menu" );

}

/* Change Page Menu */

if(empty($change_menu)) {
    $change_menu = 'primary';
}


?> 

<!-- ******************* The Vertical Navbar Area ******************* -->
<aside class="vertical-header wrapper-fluid wrapper-navbar hidden-xs <?php echo $header_color_scheme .' '. $header_navbar_color; ?>" id="wrapper-navbar">

    <nav class="site-navigation">

        <div class="navbar <?php echo $header_height; ?>">

            <div class="navbar-header">

                <!-- Your site title as branding in the menu -->
                <a class="navbar-brand mb-0 <?php echo $brand_retina; ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">

                    <?php if($brand_scheme == '1' || empty($brand_image)){ 
                        echo $brand_site_title; 
                    } elseif($brand_scheme == '2' || $brand_scheme == '4'){ ?>
                        <?php if(!empty($brand_image)){ ?> <img class="brand-default" src='<?php echo $brand_image; ?>'> <?php } ?>
                        <?php if(!empty($brand_light)){ ?> <img class="brand-light" src='<?php echo $brand_light; ?>'> <?php } ?>
                        <?php if(!empty($brand_dark)){ ?> <img class="brand-dark" src='<?php echo $brand_dark; ?>'> <?php }
                        echo $brand_site_title; 
                    } else { ?>
                        <?php if(!empty($brand_image)){ ?> <img class="brand-default" src='<?php echo $brand_image; ?>'> <?php } ?>
                        <?php if(!empty($brand_light)){ ?> <img class="brand-light" src='<?php echo $brand_light; ?>'> <?php } ?>
                        <?php if(!empty($brand_dark)){ ?> <img class="brand-dark" src='<?php echo $brand_dark; ?>'> <?php }
                    } ?>

                </a>

            </div>

            <!-- The WordPress Menu goes here -->
            <?php wp_nav_menu(
                array(
                    'theme_location'  => 'primary',
                    'theme_location' => $change_menu,
                    'container' => 'nav',
                    'container_class' => 'vertical-header-menu-container',
                    'menu_class'      => $vertical_navigation_remove.' navbar-nav vertical-header-menu',
                    'fallback_cb'     => '',
                    'menu_id'         => 'main-menu',
                    'walker'          => new WP_Bootstrap_Navwalker(),
                )
            ); ?>

        </div><!-- .navbar -->

    </nav><!-- .site-navigation -->

    <?php if ( is_active_sidebar( 'vertical-header' ) ) { ?>
        <div class="widget-area vertical-header-widget-area">
            <?php dynamic_sidebar( 'vertical-header' ); ?>
        </div>
    <?php } ?>

</aside><!-- .wrapper-navbar end -->






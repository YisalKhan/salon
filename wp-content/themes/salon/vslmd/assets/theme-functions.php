<?php
/**
 * vslmd functions and definitions
 *
 * @package cornerstone
 */
/*-----------------------------------------------------------------------------------*/
/*  VSLMD Start
/*-----------------------------------------------------------------------------------*/

$theme = wp_get_theme();
if ( ! defined( 'VSLMD_VERSION' ) ) define( 'VSLMD_VERSION', $theme->get('Version') );
if ( ! defined( 'VSLMD_NAME' ) ) define( 'VSLMD_NAME', $theme->get('Name') );

/*-----------------------------------------------------------------------------------*/
/*  Exclusive Admin Functions
/*-----------------------------------------------------------------------------------*/

if (is_admin()) {

    /**
    * Updater.
    */
    require get_template_directory() . '/vslmd/assets/theme-updater.php';

    /**
    * One Click Demo Import.
    */
    require get_template_directory() . '/vslmd/assets/demo-import.php';

    /**
    * Plugin Activation.
    */
    require get_template_directory() . '/vslmd/plugin-activation/init.php';

    if(!function_exists('vslmd_enqueue_nav_menu_script')) {

        function vslmd_enqueue_nav_menu_script($hook) {
            if($hook == 'nav-menus.php') {
                wp_enqueue_script('vslmd-nav-menu', get_template_directory_uri().'/vslmd/assets/menu/admin-nav-menu.js');
                wp_enqueue_style('vslmd-nav-menu', get_template_directory_uri().'/vslmd/assets/menu/admin-nav-menu.css');
            }
        }

        add_action('admin_enqueue_scripts', 'vslmd_enqueue_nav_menu_script');
    }

    /**
    * Visualmodo Dashboard - Backend
    */

    add_action( 'admin_menu', 'vslmd_admin_menu' );

    function vslmd_admin_menu() {

        $page = add_menu_page( 'Visualmodo', 'Visualmodo', 'manage_options', 'visualmodo', 'visualmodo_dashboard', 'dashicons-admin-settings', 2  );

        add_action( 'admin_print_styles-' . $page, 'vslmd_welcome_enqueue_script' );
    }

    function visualmodo_dashboard(){
        get_template_part('module-templates/visualmodo-dashboard');
    }

    function vslmd_welcome_enqueue_script() {
        wp_enqueue_style('vslmd-dashboard-css', get_template_directory_uri().'/css/visualmodo-dashboard.css');
        wp_enqueue_style('vslmd-welcome-bootstrap-js', get_template_directory_uri().'/js/bootstrap.min.js');
    }

    /**
    * Disable Plugin Notifications
    */

    add_action('admin_head', 'remove_ultimate_addon');
    function remove_ultimate_addon() {
      echo '<style>
        #update-nag, .update-nag,
        #vc_license-activation-notice,
      .rs-update-notice-wrap {
        display: none !important;
    }
    </style>';
}

}

/**
* Load Redux Framework functions.
*/
require get_template_directory() . '/vslmd/options/admin-init.php';

/**
* Register Post Types.
*/
require get_template_directory() . '/vslmd/post-types/post-types.php';

/**
* Post Like System.
*/
require get_template_directory() . '/vslmd/assets/like-system.php';

/**
* WooCommerce.
*/
global $woocommerce; 
if ($woocommerce) {
    require get_template_directory() . '/vslmd/assets/woocommerce.php';
}

/**
* Custom Login.
*/
function vslmd_custom_login() {
    echo '<link rel="stylesheet" href="' . get_template_directory_uri() . '/css/account.css" /><link rel="stylesheet" href="' . get_template_directory_uri() . '/css/dynamic-account.css.php" />';
}
add_action('login_head', 'vslmd_custom_login');

/**
* Custom Widgets.
*/

require get_template_directory() . '/vslmd/widgets/contact-info-widget.php';
require get_template_directory() . '/vslmd/widgets/empty-space-widget.php';
require get_template_directory() . '/vslmd/widgets/flickr-widget.php';
require get_template_directory() . '/vslmd/widgets/instagram-widget.php';
require get_template_directory() . '/vslmd/widgets/menu-widget.php';
require get_template_directory() . '/vslmd/widgets/social-widget.php';
require get_template_directory() . '/vslmd/widgets/tweets-widget.php';

/**
* Menu.
*/
    require get_template_directory() . '/vslmd/assets/menu/extra-properties-menu.php';

/*-----------------------------------------------------------------------------------*/
/*  Extra Menus
/*-----------------------------------------------------------------------------------*/

$extra_menu = (empty($options['extra_menu'])) ? '0' : $options['extra_menu'];

if($extra_menu != '0') {

    for($i = 1; $i <= $extra_menu; $i++) {
        register_nav_menus(array('extra_menu_'. $i .'' => __('Extra Menu '. $i .'', 'vslmd') ));
    }
}

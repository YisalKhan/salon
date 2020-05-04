<?php

/*
  Plugin Name: Salon Booking Wordpress Plugin - Free Version
  Description: Let your customers book you services through your website. Perfect for hairdressing salons, barber shops and beauty centers.
  Version: 3.37.3
  Plugin URI: http://salonbookingsystem.com/
  Author: Salon Booking System
  Author URI: http://salonbookingsystem.com/
  Text Domain: salon-booking-system
  Domain Path: /languages
 */

define('SLN_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('SLN_PLUGIN_DIR', untrailingslashit(dirname(__FILE__)));
define('SLN_PLUGIN_URL', untrailingslashit(plugins_url('', __FILE__)));
define('SLN_VERSION', '3.37.3');
define('SLN_STORE_URL', 'https://salonbookingsystem.com');
define('SLN_AUTHOR', 'Salon Booking');
define('SLN_ITEM_SLUG', 'salon-booking-wordpress-plugin');
define('SLN_ITEM_NAME', 'Salon booking wordpress plugin');



function sln_autoload($className) {
    if (strpos($className, 'SLN_') === 0) {
        $filename = SLN_PLUGIN_DIR . "/src/" . str_replace("_", "/", $className) . '.php';
        if (file_exists($filename)) {
            require_once($filename);
            return;
        }
    }elseif(strpos($className, 'Salon')=== 0) {
        $filename = SLN_PLUGIN_DIR . "/src/" . str_replace("\\", "/", $className) . '.php';
        if (file_exists($filename)) {
            require_once($filename);
            return;
        }
    }

    $discountAppPrefixes = array(
        'SLB_Discount_',
        'SLN_',
    );
    foreach($discountAppPrefixes as $prefix) {
        if (strpos($className, $prefix) === 0) {
            $classWithoutPrefix = str_replace("_", "/", substr($className, strlen($prefix)));
            $filename           = SLN_PLUGIN_DIR . "/src/" . substr($prefix, 0, -1) . "/{$classWithoutPrefix}.php";
            if (file_exists($filename)) {
                require_once($filename);
                return;
            }
        }
    }

    if(strpos($className, 'SLB_API') === 0) {
        $filename = SLN_PLUGIN_DIR . "/src/" . str_replace("\\", "/", $className) . '.php';
        if (file_exists($filename)) {
            require_once($filename);
            return;
        }
    }

    if(strpos($className, 'SLB_Customization') === 0) {
        $filename = SLN_PLUGIN_DIR . "/src/" . str_replace("\\", "/", $className) . '.php';
        if (file_exists($filename)) {
            require_once($filename);
            return;
        }
    }
}

function my_update_notice() {
    $info = __('-', 'salon-booking-system');
    echo '<span class="spam">' . strip_tags($info, '<br><a><b><i><span>') . '</span>';
}

if (is_admin())
    add_action('in_plugin_update_message-' . plugin_basename(__FILE__), 'my_update_notice');
add_action('plugins_loaded',function(){
    load_plugin_textdomain('salon-booking-system', false, dirname(SLN_PLUGIN_BASENAME).'/languages');
});

spl_autoload_register('sln_autoload');
$sln_plugin = SLN_Plugin::getInstance();
do_action('sln.init', $sln_plugin);

add_action('init', function () {
    if ( ! session_id()
	&& ! strstr( $_SERVER['REQUEST_URI'], '/wp-admin/site-health.php' )
	&& ! ( isset( $_POST['action'] ) &&  $_POST['action'] === 'health-check-loopback-requests' )
    ) {
	session_start();
    }
});

add_action('init', function () {
    //TODO[feature-gcalendar]: move this require in the right place
    require_once SLN_PLUGIN_DIR . "/src/SLN/Third/GoogleScope.php";
    $sln_googlescope = new SLN_GoogleScope();
    $GLOBALS['sln_googlescope'] = $sln_googlescope;
    $sln_googlescope->set_settings_by_plugin(SLN_Plugin::getInstance());
    $sln_googlescope->wp_init();
    SLN_Third_GoogleCalendarImport::launch($GLOBALS['sln_googlescope']);
});

$sln_api = \SLB_API\Plugin::get_instance();

$sln_customization = \SLB_Customization\Plugin::get_instance();

add_filter( 'body_class', function( $classes ) {
    return array_merge( $classes, array( 'sln-salon-page' ) );
} );

ob_start();



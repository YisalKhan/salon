<?php
/**
 * Check and setup theme's default settings
 *
 * @package cornerstone
 *
 */

if ( ! function_exists( 'setup_theme_default_settings' ) ) :
	function setup_theme_default_settings() {

		// check if settings are set, if not set defaults.
		// Caution: DO NOT check existence using === always check with == .
		// Latest blog posts style.
		$vslmd_posts_index_style = get_theme_mod( 'vslmd_posts_index_style' );
		if ( '' == $vslmd_posts_index_style ) {
			set_theme_mod( 'vslmd_posts_index_style', 'default' );
		}

		// Sidebar position.
		$vslmd_sidebar_position = get_theme_mod( 'vslmd_sidebar_position' );
		if ( '' == $vslmd_sidebar_position ) {
			set_theme_mod( 'vslmd_sidebar_position', 'right' );
		}

		// Container width.
		$vslmd_container_type = get_theme_mod( 'vslmd_container_type' );
		if ( '' == $vslmd_container_type ) {
			set_theme_mod( 'vslmd_container_type', 'container' );
		}
	}
endif;

<?php
/**
 * VSLMD enqueue scripts
 *
 * @package cornerstone
 */

// Async load
function vslmd_async_scripts($url)
{
    if ( strpos( $url, '#asyncload') === false )
        return $url;
    else if ( is_admin() )
        return str_replace( '#asyncload', '', $url );
    else
	return str_replace( '#asyncload', '', $url )."' async='async"; 
    }
add_filter( 'clean_url', 'vslmd_async_scripts', 11, 1 );


if ( ! function_exists( 'vslmd_scripts' ) ) {
	/**
	 * Load theme's JavaScript sources.
	 */
	function vslmd_scripts() {
		// Get the theme data.
		$the_theme = wp_get_theme();
		wp_enqueue_style( 'vslmd-styles', get_template_directory_uri() . '/css/theme.min.css#asyncload', array(), $the_theme->get( 'Version' ), false ); 
		
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'popper-scripts', get_template_directory_uri() . '/js/popper.min.js', array(), true);
		wp_enqueue_script( 'vslmd-scripts', get_template_directory_uri() . '/js/theme.min.js', array(), $the_theme->get( 'Version' ), true );
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		wp_localize_script( 'vslmd-scripts', 'simpleLikes', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'like' => __( 'Like', 'vslmd' ),
			'unlike' => __( 'Unlike', 'vslmd' )
		) );
	}
} // endif function_exists( 'vslmd_scripts' ).

add_action( 'wp_enqueue_scripts', 'vslmd_scripts' );

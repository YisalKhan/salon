<?php
/**
 * Add WooCommerce support
 *
 * @package cornerstone
 */
add_action( 'after_setup_theme', 'woocommerce_support' );
if ( ! function_exists( 'woocommerce_support' ) ) {
	/**
	 * Declares WooCommerce theme support.
	 */
	function woocommerce_support() {
		add_theme_support( 'woocommerce' );
		
		// Add New Woocommerce 3.0.0 Product Gallery support
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-zoom' );

		// Gallery slider needs Flexslider - https://woocommerce.com/flexslider/
		//add_theme_support( 'wc-product-gallery-slider' );
	}
}

// Archive cart Ajax

add_filter( 'woocommerce_add_to_cart_fragments', 'cart_count_fragments', 10, 1 );
 
function cart_count_fragments( $fragments ) {
    
    $fragments['div.cart-content-count'] = '<div class="cart-content-count">' . WC()->cart->get_cart_contents_count() . '</div>';
    
    return $fragments;
    
}
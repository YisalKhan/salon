<?php 

/*-----------------------------------------------------------------------------------*/
/*	Woocommerce Functions
/*-----------------------------------------------------------------------------------*/

// Main Shop

add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 3; // 3 products per row
	}
}

// Change Paypal Logo Cards JPG for PNG

function replacePayPalIcon($iconUrl) {
    return 'https://www.paypalobjects.com/webstatic/mktg/Logo/AM_mc_vs_ms_ae_UK.png';
}
 
add_filter('woocommerce_paypal_icon', 'replacePayPalIcon');

// Checkout

add_action( 'woocommerce_checkout_before_customer_details', 'woocommerce_checkout_before_customer_details');
add_action( 'woocommerce_checkout_after_order_review', 'woocommerce_checkout_after_order_review');

function woocommerce_checkout_before_customer_details() {
    echo '<div class="row">';
}

function woocommerce_checkout_after_order_review() {
    echo '</div>';
}

add_action( 'woocommerce_checkout_before_order_review', 'woocommerce_before_order_total', 10);
add_action( 'woocommerce_checkout_after_order_review', 'woocommerce_after_order_total', 10);

function woocommerce_before_order_total() {
    echo '<div class="col-md-4 checkout-order-total-vslmd">';
	echo '<h3 class="order_review_heading">' . __( 'Your order', 'vslmd' ) . '</h3>';
}

function woocommerce_after_order_total() {
    echo '</div>';
}

add_action( 'woocommerce_checkout_before_customer_details', 'woocommerce_before_details', 10);
add_action( 'woocommerce_checkout_after_customer_details', 'woocommerce_after_details', 10);

function woocommerce_before_details() {
    echo '<div class="col-md-8 checkout-details-vslmd">';
}

function woocommerce_after_details() {
    echo '</div>';
}

// Cart

add_action( 'woocommerce_before_cart', 'woocommerce_before_cart');
add_action( 'woocommerce_after_cart', 'woocommerce_after_cart');

function woocommerce_before_cart() {
    echo '<div class="woocommerce-cart-vslmd row">';
}

function woocommerce_after_cart() {
    echo '</div>';
}

// Cart Table

add_action( 'woocommerce_before_cart_table', 'woocommerce_before_extern_cart');
add_action( 'woocommerce_after_cart_table', 'woocommerce_after_extern_cart');

function woocommerce_before_extern_cart() {
    echo '<div class="art-table-vslmd">';
}

function woocommerce_after_extern_cart() {
    echo '</div>';
}

// Cart Collaterals
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' , 10 );
add_action( 'woocommerce_cart_collaterals', 'woocommerce_before_collaterals', 5);
add_action( 'woocommerce_cart_collaterals', 'woocommerce_after_collaterals', 10);

function woocommerce_before_collaterals() {
    echo '<div class="cart-collaterals-vslmd">';
}

function woocommerce_after_collaterals() {
    echo '</div>';
}
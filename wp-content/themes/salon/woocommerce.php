<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package cornerstone
 */
$options = get_option('vslmd_options');

if( is_shop() || is_product_category() || is_product_tag() ) {
	$woo_sidebar = (!empty($options['shop_structure'])) ? $options['shop_structure'] : '0';
} elseif( is_product() ) {
	$woo_sidebar = (!empty($options['product_structure'])) ? $options['product_structure'] : '0';	
}

get_header(); ?>

<div class="wrapper" id="woocommerce-wrapper">
    
    <div class="container">

        <div class="row">
            
           <?php if( $woo_sidebar == '1' && is_active_sidebar( 'woocommerce-widget' ) ) { ?> <!-- Sidebar Left -->
           
           <div class="col-md-3">
            <?php dynamic_sidebar('woocommerce-widget'); ?>
        </div>
        
        <?php } ?>
        
        <div id="primary" class="<?php if ( $woo_sidebar != '0' ) : ?>col-md-8<?php else : ?>col-md-12<?php endif; ?> content-area">
            
            <main id="main" class="site-main" role="main">

                <!-- The WooCommerce loop -->
                <?php woocommerce_content(); ?>

            </main><!-- #main -->
            
        </div><!-- #primary -->
        
        <?php if( $woo_sidebar == '2' && is_active_sidebar( 'woocommerce-widget' ) ) { ?> <!-- Sidebar Right -->
        
        <div class="col-md-4 woocommerce-widget">
            <?php dynamic_sidebar('woocommerce-widget'); ?>
        </div>
        
        <?php } ?>

    </div>
    
</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>

<?php
/**
 * Navigation Extra Elements.
 *
 * Search, Cart, Side Navigation...
 *
 * @package cornerstone
 */
global $yith_wcwl, $woocommerce;

$options = get_option('vslmd_options');
$search_header = $options['search_header'] ?: '3';
$woocart = $options['woocart'] ?: '1';
$side_navigation = $options['side_navigation'] ?: '';
$wishlist = $options['wishlist'] ?: '1';
$header_color_scheme = $options['header_color_scheme'] ?: 'light navbar-light';
$dropdown_menu_color = $options['dropdown_menu_color'] ?: 'dropdown-menu-dark';
$navigation_collapsed = $options['navigation_collapsed'] ?: 'navbar-expand-md';

/* Color Scheme */

if (strpos($header_color_scheme, 'bg-white') !== false ) {
    $dropdown_menu_color = 'dropdown-menu-white';
}elseif (strpos($header_color_scheme, 'light') !== false) {
    $dropdown_menu_color = 'dropdown-menu-light';
} elseif (strpos($header_color_scheme, 'dark') !== false) {
    $dropdown_menu_color = 'dropdown-menu-dark';
} elseif ($dropdown_menu_color == 'dropdown-menu-dark') {
    $dropdown_menu_color = 'dropdown-menu-dark-no-bg';
} else {
    $dropdown_menu_color = 'dropdown-menu-light-no-bg';
}

//Woocommerce Cart Show Control

if ($woocart == '2') {
    if ($navigation_collapsed == 'navbar-expand-sm') {  
        $show_woo_cart = 'd-sm-none d-md-none d-lg-none d-xl-none';
    } elseif ($navigation_collapsed == 'navbar-expand-md') {  
        $show_woo_cart = 'd-md-none d-lg-none d-xl-none';
    } elseif ($navigation_collapsed == 'navbar-expand-lg') {  
        $show_woo_cart = 'd-lg-none d-xl-none';
    } elseif ($navigation_collapsed == 'navbar-expand-xl') {  
        $show_woo_cart = 'd-xl-none';
    } elseif ($navigation_collapsed == 'navbar-expand-never') {  
        $show_woo_cart = 'd-block';
    }
} elseif ($woocart == '3') {
    if ($navigation_collapsed == 'navbar-expand-sm') {  
        $show_woo_cart = 'd-none d-sm-block d-md-block d-lg-block d-xl-block';
    } elseif ($navigation_collapsed == 'navbar-expand-md') {  
        $show_woo_cart = 'd-none d-sm-none d-md-block d-lg-block d-xl-block';
    } elseif ($navigation_collapsed == 'navbar-expand-lg') {  
        $show_woo_cart = 'd-none d-sm-none d-md-none d-lg-block d-xl-block';
    } elseif ($navigation_collapsed == 'navbar-expand-xl') {  
        $show_woo_cart = 'd-none d-sm-none d-md-none d-lg-none d-xl-block';
    } elseif ($navigation_collapsed == 'navbar-expand-never') {  
        $show_woo_cart = 'd-none';
    }
} else {  
    $show_woo_cart = 'd-block';
}

//Search Cart Show Control

if ($search_header == '2') {
    if ($navigation_collapsed == 'navbar-expand-sm') {  
        $show_search_header = 'd-sm-none d-md-none d-lg-none d-xl-none';
    } elseif ($navigation_collapsed == 'navbar-expand-md') {  
        $show_search_header = 'd-md-none d-lg-none d-xl-none';
    } elseif ($navigation_collapsed == 'navbar-expand-lg') {  
        $show_search_header = 'd-lg-none d-xl-none';
    } elseif ($navigation_collapsed == 'navbar-expand-xl') {  
        $show_search_header = 'd-xl-none';
    } elseif ($navigation_collapsed == 'navbar-expand-never') {  
        $show_search_header = 'd-block';
    }
} elseif ($search_header == '3') {
    if ($navigation_collapsed == 'navbar-expand-sm') {  
        $show_search_header = 'd-none d-sm-block d-md-block d-lg-block d-xl-block';
    } elseif ($navigation_collapsed == 'navbar-expand-md') {  
        $show_search_header = 'd-none d-sm-none d-md-block d-lg-block d-xl-block';
    } elseif ($navigation_collapsed == 'navbar-expand-lg') {  
        $show_search_header = 'd-none d-sm-none d-md-none d-lg-block d-xl-block';
    } elseif ($navigation_collapsed == 'navbar-expand-xl') {  
        $show_search_header = 'd-none d-sm-none d-md-none d-lg-none d-xl-block';
    } elseif ($navigation_collapsed == 'navbar-expand-never') {  
        $show_search_header = 'd-none';
    }
} else {  
    $show_search_header = 'd-block';
}

//Wishlist Show Control

if ($wishlist == '2') {
    if ($navigation_collapsed == 'navbar-expand-sm') {  
        $show_wishlist = 'd-sm-none d-md-none d-lg-none d-xl-none';
    } elseif ($navigation_collapsed == 'navbar-expand-md') {  
        $show_wishlist = 'd-md-none d-lg-none d-xl-none';
    } elseif ($navigation_collapsed == 'navbar-expand-lg') {  
        $show_wishlist = 'd-lg-none d-xl-none';
    } elseif ($navigation_collapsed == 'navbar-expand-xl') {  
        $show_wishlist = 'd-xl-none';
    } elseif ($navigation_collapsed == 'navbar-expand-never') {  
        $show_wishlist = 'd-block';
    }
} elseif ($wishlist == '3') {
    if ($navigation_collapsed == 'navbar-expand-sm') {  
        $show_wishlist = 'd-none d-sm-block d-md-block d-lg-block d-xl-block';
    } elseif ($navigation_collapsed == 'navbar-expand-md') {  
        $show_wishlist = 'd-none d-sm-none d-md-block d-lg-block d-xl-block';
    } elseif ($navigation_collapsed == 'navbar-expand-lg') {  
        $show_wishlist = 'd-none d-sm-none d-md-none d-lg-block d-xl-block';
    } elseif ($navigation_collapsed == 'navbar-expand-xl') {  
        $show_wishlist = 'd-none d-sm-none d-md-none d-lg-none d-xl-block';
    } elseif ($navigation_collapsed == 'navbar-expand-never') {  
        $show_wishlist = 'd-none';
    }
} else {  
    $show_wishlist = 'd-block';
}

//Side Menu Show Control

if ($side_navigation != '') {
    if ($navigation_collapsed == 'navbar-expand-sm') {  
        $show_side_menu = 'd-none d-sm-block d-md-block d-lg-block d-xl-block';
    } elseif ($navigation_collapsed == 'navbar-expand-md') {  
        $show_side_menu = 'd-none d-sm-none d-md-block d-lg-block d-xl-block';
    } elseif ($navigation_collapsed == 'navbar-expand-lg') {  
        $show_side_menu = 'd-none d-sm-none d-md-none d-lg-block d-xl-block';
    } elseif ($navigation_collapsed == 'navbar-expand-xl') {  
        $show_side_menu = 'd-none d-sm-none d-md-none d-lg-none d-xl-block';
    } elseif ($navigation_collapsed == 'navbar-expand-never') {  
        $show_side_menu = 'd-none';
    }
} else {
    $show_side_menu = '';
}

?>

<div class="extra-elements-nav">

    <ul id="extra-elements-menu" class="navbar-nav justify-content-end">


        <!-- Wishlist -->

        <?php if (class_exists('YITH_WCWL')) { ?>
        <?php if ( $wishlist >= '2') { ?>
        <li class="nav-item wishlist-button nav-wishlist <?php echo $show_wishlist; ?>">
            <a class="nav-link" href="<?php echo esc_url($yith_wcwl->get_wishlist_url()); ?>">
                <span><i class="fa fa-heart-o"></i></span>
                <span class="wishlist_items_number"><?php echo yith_wcwl_count_products(); ?></span>
            </a>
        </li>                           
        <?php } ?>
        <?php } ?>

        <!-- Cart Menu -->

        <?php if($woocommerce && $woocart >= '2') { ?>
        
        <li class="nav-item dropdown nav-cart <?php echo $show_woo_cart; ?>">
            <a data-toggle="dropdown" class="dropdown-toggle nav-link" href="#">
                <span><i class="fa fa-shopping-cart" aria-hidden="true"></i></span>
                <span class="cart-content-count"><?php echo $woocommerce->cart->cart_contents_count; ?></span>
            </a>
            <ul role="menu" class="dropdown-menu dropdown-menu-right extra-md-menu <?php echo $dropdown_menu_color; ?>">
                <li class="cart-menu">
                    <div class="widget_shopping_cart_content"></div>    
                </li>
            </ul>
        </li>

        <?php } ?>

        <!-- Search Middle Screen -->

        <?php if($search_header >= '2') { ?>

        <li class="nav-item dropdown nav-search <?php echo $show_search_header; ?>">
            <a data-toggle="dropdown" class="dropdown-toggle nav-link" href="#"><i class="fa fa-search" aria-hidden="true"></i></a>
            <ul role="menu" class="dropdown-menu dropdown-menu-right extra-md-menu <?php echo $dropdown_menu_color; ?>">
                <li>
                    <form method="get" id="searchform" action="<?php echo home_url( '/' ); ?>" role="search">
                        <div class="input-group">
                            <input type="text" class="field form-control" name="s" id="s" placeholder="<?php _e( 'Search &hellip;', 'vslmd' ); ?>" />
                            <span class="input-group-btn">
                                <input type="submit" class="submit btn btn-primary" name="submit" id="searchsubmit" value="<?php _e( 'Search', 'vslmd' ); ?>" />
                            </span>
                        </div>
                    </form>
                </li>
            </ul>
        </li>

        <?php } ?>

        <!-- Side Navigation -->

        <?php if( $side_navigation != '' && is_active_sidebar( 'side-navigation' ) ) { ?>

        <li class="nav-item nav-side-navigation <?php echo $show_side_menu; ?>">
            <a id="open-side-navigation" class="nav-link" href="#"><i class="fa fa-bars" aria-hidden="true"></i></a>
        </li>

        <?php } ?>

    </ul>

</div>
<?php
/**
 * Welcome Visualmodo backend Page.
 *
 * @package cornerstone
 */
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">

            <div class="wrap vslmd-page-welcome about-wrap">
              <h1><?php echo sprintf( __( 'Welcome to ', 'vslmd' ) . VSLMD_NAME .' '. VSLMD_VERSION ) ?></h1>

              <div class="about-text">
                  <?php _e( 'WordPress Theme by Visualmodo a clean, modern, minimal, smart and multi-purpose WordPress template. It has a wide range of incredible features and amazing tools for every site style creation, everything you need to create an awesome WordPress site in an incredibly easy way saving time doing it. Build any site design without coding!', 'vslmd' ) ?>
              </div>

              <div class="wp-badge vslmd-welcome-page-logo">
                <?php echo sprintf( __( 'Version %s', 'vslmd' ), VSLMD_VERSION ) ?>
              </div>

                <p class="vslmd-page-actions">
                    <a href="<?php echo esc_attr( admin_url( 'admin.php?page=_options' ) ) ?>"
                  class="button button-primary vslmd-welcome-button vslmd-welcome-button-settings"><span class="dashicons dashicons-admin-generic"></span><?php _e( 'Settings', 'vslmd' ) ?></a>

                    <a href="<?php echo esc_attr( 'https://visualmodo.com/documentation/' ) ?>"
                      class="button button-primary vslmd-welcome-button vslmd-welcome-button-documentation" target="_blank"><span class="dashicons dashicons-warning"></span><?php _e( 'Documentation', 'vslmd' ) ?></a>

                    <a href="<?php echo esc_attr( 'https://visualmodo.com/account/submit-ticket/' ) ?>"
                          class="button button-primary vslmd-welcome-button vslmd-welcome-button-support" target="_blank"><span class="dashicons dashicons-sos"></span><?php _e( 'Support', 'vslmd' ) ?></a>

                          <a href="https://twitter.com/share" class="twitter-share-button"
                          data-via="visualmodo"
                          data-text="Premium WordPress Themes."
                          data-url="http://visualmodo.com" data-size="large">Tweet</a>
                          <script>! function ( d, s, id ) {
                            var js, fjs = d.getElementsByTagName( s )[ 0 ], p = /^http:/.test( d.location ) ? 'http' : 'https';
                            if ( ! d.getElementById( id ) ) {
                              js = d.createElement( s );
                              js.id = id;
                              js.src = p + '://platform.twitter.com/widgets.js';
                              fjs.parentNode.insertBefore( js, fjs );
                          }
                      }( document, 'script', 'twitter-wjs' );</script>
                </p>
            </div>
        </div>
    </div>

    <?php
        if( isset( $_GET[ 'tab' ] ) ) {
            $active_tab = $_GET[ 'tab' ];
        } // end if
    ?>
     
    <h2 class="nav-tab-wrapper">
        <a href="?page=visualmodo&tab=wordpress_themes" class="nav-tab <?php echo $active_tab == 'wordpress_themes' ? 'nav-tab-active' : ''; ?>">Premium WordPress Themes</a>
        <a href="?page=visualmodo&tab=membership" class="nav-tab <?php echo $active_tab == 'membership' ? 'nav-tab-active' : ''; ?>">Join Our Club</a>
    </h2>
</div>

<?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'wordpress_themes'; ?>

<?php if( $active_tab == 'wordpress_themes' ) { ?>

<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h1 class="products-title">WordPress Themes</h1>
            <h2 class="products-caption">Each Visualmodo WordPress theme is equipped with an accompanying set of beautiful, practical, and creative functions.</h2>
        </div>
        <div class="col-md-6">
            <a class="vslmd-product-url" href="https://visualmodo.com/theme/rare-wordpress-theme/" target="_blank">
                <figure class="vslmd-product">
                    <img src="https://visualmodo.com/wp-content/uploads/2017/03/rare-theme-thumbnail.jpg">
                <figcaption>
                <span class="product-title">Rare</span>
                <div class="product-description-container">
                    <h3 class="product-description">The World Is A Canvas For Your Imagination</h3>
                </div>
                <div class="product-meta">
                    <a class="product-preview-url" href="http://wordpressthemes.visualmodo.com/?theme=Rare" target="_blank"><span class="product-preview">Preview Theme</span></a>
                    <a class="product-download-url" href="https://visualmodo.com/theme/rare-wordpress-theme/" target="_blank"><span class="product-download">Download Theme</span></a>
                </div>
                </figcaption>
                </figure>
            </a>
        </div>

        <div class="col-md-6">
            <a class="vslmd-product-url" href="https://visualmodo.com/theme/marvel-vertical-menu-wordpress-theme/" target="_blank">
                <figure class="vslmd-product">
                    <img src="https://visualmodo.com/wp-content/uploads/2017/05/seller-theme-thumbnail-alt.jpg">
                <figcaption>
                <span class="product-title">Seller</span>
                <div class="product-description-container">
                    <h3 class="product-description">The Shop Solution For Your WordPress Website</h3>
                </div>
                <div class="product-meta">
                    <a class="product-preview-url" href="http://wordpressthemes.visualmodo.com/?theme=Seller" target="_blank"><span class="product-preview">Preview Theme</span></a>
                    <a class="product-download-url" href="https://visualmodo.com/theme/seller-ecommerce-wordpress-theme/" target="_blank"><span class="product-download">Download Theme</span></a>
                </div>
                </figcaption>
                </figure>
            </a>
        </div>

        <div class="col-md-6">
            <a class="vslmd-product-url" href="https://visualmodo.com/theme/marvel-vertical-menu-wordpress-theme/" target="_blank">
                <figure class="vslmd-product">
                    <img src="https://visualmodo.com/wp-content/uploads/2017/03/marvel-theme-thumbnail.jpg">
                <figcaption>
                <span class="product-title">Marvel</span>
                <div class="product-description-container">
                    <h3 class="product-description">Easy And Creative Navigation Style</h3>
                </div>
                <div class="product-meta">
                    <a class="product-preview-url" href="http://wordpressthemes.visualmodo.com/?theme=Marvel" target="_blank"><span class="product-preview">Preview Theme</span></a>
                    <a class="product-download-url" href="https://visualmodo.com/theme/marvel-vertical-menu-wordpress-theme/" target="_blank"><span class="product-download">Download Theme</span></a>
                </div>
                </figcaption>
                </figure>
            </a>
        </div>

        <div class="col-md-6">
            <a class="vslmd-product-url" href="https://visualmodo.com/theme/edge-wordpress-theme/" target="_blank">
                <figure class="vslmd-product">
                    <img src="https://visualmodo.com/wp-content/uploads/2017/03/edge-theme-thumbnail.jpg">
                <figcaption>
                <span class="product-title">Edge</span>
                <div class="product-description-container">
                    <h3 class="product-description">A Multi Purpose Clean Visual Expression</h3>
                </div>
                <div class="product-meta">
                    <a class="product-preview-url" href="http://wordpressthemes.visualmodo.com/?theme=Edge" target="_blank"><span class="product-preview">Preview Theme</span></a>
                    <a class="product-download-url" href="https://visualmodo.com/theme/edge-wordpress-theme/" target="_blank"><span class="product-download">Download Theme</span></a>
                </div>
                </figcaption>
                </figure>
            </a>
        </div>

        <div class="col-md-6">
            <a class="vslmd-product-url" href="https://visualmodo.com/theme/fitness-wordpress-theme/" target="_blank">
                <figure class="vslmd-product">
                    <img src="https://visualmodo.com/wp-content/uploads/2017/03/fitness-theme-thumbnail.jpg">
                <figcaption>
                <span class="product-title">Fitness</span>
                <div class="product-description-container">
                    <h3 class="product-description">Health And Fitness Classic Clean Template</h3>
                </div>
                <div class="product-meta">
                    <a class="product-preview-url" href="http://wordpressthemes.visualmodo.com/?theme=Fitness" target="_blank"><span class="product-preview">Preview Theme</span></a>
                    <a class="product-download-url" href="https://visualmodo.com/theme/fitness-wordpress-theme/" target="_blank"><span class="product-download">Download Theme</span></a>
                </div>
                </figcaption>
                </figure>
            </a>
        </div>

        <div class="col-md-6">
            <a class="vslmd-product-url" href="https://visualmodo.com/theme/gym-wordpress-theme/" target="_blank">
                <figure class="vslmd-product">
                    <img src="https://visualmodo.com/wp-content/uploads/2017/03/gym-theme-thumbnail.jpg">
                <figcaption>
                <span class="product-title">Gym</span>
                <div class="product-description-container">
                    <h3 class="product-description">The Strongest Template For Sports &amp; Fitness Websites</h3>
                </div>
                <div class="product-meta">
                    <a class="product-preview-url" href="http://wordpressthemes.visualmodo.com/?theme=Gym" target="_blank"><span class="product-preview">Preview Theme</span></a>
                    <a class="product-download-url" href="https://visualmodo.com/theme/gym-wordpress-theme/" target="_blank"><span class="product-download">Download Theme</span></a>
                </div>
                </figcaption>
                </figure>
            </a>
        </div>

        <div class="col-md-6">
            <a class="vslmd-product-url" href="https://visualmodo.com/theme/zenith-wordpress-theme/" target="_blank">
                <figure class="vslmd-product">
                    <img src="https://visualmodo.com/wp-content/uploads/2017/03/zenith-theme-thumbnail.jpg">
                <figcaption>
                <span class="product-title">Zenith</span>
                <div class="product-description-container">
                    <h3 class="product-description">Portfolio &amp; Agency Minimal Template</h3>
                </div>
                <div class="product-meta">
                    <a class="product-preview-url" href="http://wordpressthemes.visualmodo.com/?theme=Zenith" target="_blank"><span class="product-preview">Preview Theme</span></a>
                    <a class="product-download-url" href="https://visualmodo.com/theme/zenith-wordpress-theme/" target="_blank"><span class="product-download">Download Theme</span></a>
                </div>
                </figcaption>
                </figure>
            </a>
        </div>

        <div class="col-md-6">
            <a class="vslmd-product-url" href="https://visualmodo.com/theme/food-wordpress-theme/" target="_blank">
                <figure class="vslmd-product">
                    <img src="https://visualmodo.com/wp-content/uploads/2017/03/food-theme-thumbnail.jpg">
                <figcaption>
                <span class="product-title">Food</span>
                <div class="product-description-container">
                    <h3 class="product-description">A Delicious Restaurant &amp; Candy Shop Template</h3>
                </div>
                <div class="product-meta">
                    <a class="product-preview-url" href="http://wordpressthemes.visualmodo.com/?theme=Food" target="_blank"><span class="product-preview">Preview Theme</span></a>
                    <a class="product-download-url" href="https://visualmodo.com/theme/food-wordpress-theme/" target="_blank"><span class="product-download">Download Theme</span></a>
                </div>
                </figcaption>
                </figure>
            </a>
        </div>

        <div class="col-md-6">
            <a class="vslmd-product-url" href="https://visualmodo.com/theme/peak-wordpress-theme/" target="_blank">
                <figure class="vslmd-product">
                    <img src="https://visualmodo.com/wp-content/uploads/2017/03/peak-theme-thumbnail.jpg">
                <figcaption>
                <span class="product-title">Peak</span>
                <div class="product-description-container">
                    <h3 class="product-description">Clean, Modern, Stylish and Minimal Multi Purpose</h3>
                </div>
                <div class="product-meta">
                    <a class="product-preview-url" href="http://wordpressthemes.visualmodo.com/?theme=Peak" target="_blank"><span class="product-preview">Preview Theme</span></a>
                    <a class="product-download-url" href="https://visualmodo.com/theme/peak-wordpress-theme/" target="_blank"><span class="product-download">Download Theme</span></a>
                </div>
                </figcaption>
                </figure>
            </a>
        </div>

        <div class="col-md-6">
            <a class="vslmd-product-url" href="https://visualmodo.com/theme/spark-wordpress-theme/" target="_blank">
                <figure class="vslmd-product">
                    <img src="https://visualmodo.com/wp-content/uploads/2017/03/spark-theme-thumbnail.jpg">
                <figcaption>
                <span class="product-title">Spark</span>
                <div class="product-description-container">
                    <h3 class="product-description">User Friendly Royal Multi Purpose Mobile Ready</h3>
                </div>
                <div class="product-meta">
                    <a class="product-preview-url" href="http://wordpressthemes.visualmodo.com/?theme=Spark" target="_blank"><span class="product-preview">Preview Theme</span></a>
                    <a class="product-download-url" href="https://visualmodo.com/theme/spark-wordpress-theme/" target="_blank"><span class="product-download">Download Theme</span></a>
                </div>
                </figcaption>
                </figure>
            </a>
        </div>

        <div class="col-md-6">
            <a class="vslmd-product-url" href="https://visualmodo.com/theme/sport-wordpress-theme/" target="_blank">
                <figure class="vslmd-product">
                    <img src="https://visualmodo.com/wp-content/uploads/2017/03/sport-theme-thumbnail.jpg">
                <figcaption>
                <span class="product-title">Sport</span>
                <div class="product-description-container">
                    <h3 class="product-description">Sport Needs Performance And Design</h3>
                </div>
                <div class="product-meta">
                    <a class="product-preview-url" href="http://wordpressthemes.visualmodo.com/?theme=Sport" target="_blank"><span class="product-preview">Preview Theme</span></a>
                    <a class="product-download-url" href="https://visualmodo.com/theme/sport-wordpress-theme/" target="_blank"><span class="product-download">Download Theme</span></a>
                </div>
                </figcaption>
                </figure>
            </a>
        </div>

        <div class="col-md-6">
            <a class="vslmd-product-url" href="https://visualmodo.com/theme/stream-wordpress-theme/" target="_blank">
                <figure class="vslmd-product">
                    <img src="https://visualmodo.com/wp-content/uploads/2017/03/stream-theme-thumbnail.jpg">
                <figcaption>
                <span class="product-title">Stream</span>
                <div class="product-description-container">
                    <h3 class="product-description">All You Need To One-Page Website Style</h3>
                </div>
                <div class="product-meta">
                    <a class="product-preview-url" href="http://wordpressthemes.visualmodo.com/?theme=Stream" target="_blank"><span class="product-preview">Preview Theme</span></a>
                    <a class="product-download-url" href="https://visualmodo.com/theme/stream-wordpress-theme/" target="_blank"><span class="product-download">Download Theme</span></a>
                </div>
                </figcaption>
                </figure>
            </a>
        </div>

        <div class="col-md-6">
            <a class="vslmd-product-url" href="https://visualmodo.com/theme/ink-wordpress-theme/" target="_blank">
                <figure class="vslmd-product">
                    <img src="https://visualmodo.com/wp-content/uploads/2017/03/ink-theme-thumbnail.jpg">
                <figcaption>
                <span class="product-title">Ink</span>
                <div class="product-description-container">
                    <h3 class="product-description">Clean, Modern, Stylish and Minimal Blog Template</h3>
                </div>
                <div class="product-meta">
                    <a class="product-preview-url" href="http://wordpressthemes.visualmodo.com/?theme=Ink" target="_blank"><span class="product-preview">Preview Theme</span></a>
                    <a class="product-download-url" href="https://visualmodo.com/theme/ink-wordpress-theme/" target="_blank"><span class="product-download">Download Theme</span></a>
                </div>
                </figcaption>
                </figure>
            </a>
        </div>

        <div class="col-md-6">
            <a class="vslmd-product-url" href="https://visualmodo.com/theme/beyond-wordpress-theme/" target="_blank">
                <figure class="vslmd-product">
                    <img src="https://visualmodo.com/wp-content/uploads/2017/03/beyond-theme-thumbnail.jpg">
                <figcaption>
                <span class="product-title">Beyond</span>
                <div class="product-description-container">
                    <h3 class="product-description">Every Brand Needs a Strong Visual Expression</h3>
                </div>
                <div class="product-meta">
                    <a class="product-preview-url" href="http://wordpressthemes.visualmodo.com/?theme=Beyond" target="_blank"><span class="product-preview">Preview Theme</span></a>
                    <a class="product-download-url" href="https://visualmodo.com/theme/beyond-wordpress-theme/" target="_blank"><span class="product-download">Download Theme</span></a>
                </div>
                </figcaption>
                </figure>
            </a>
        </div>

        <div class="col-md-6">
            <a class="vslmd-product-url" href="https://visualmodo.com/theme/wedding-wordpress-theme/" target="_blank">
                <figure class="vslmd-product">
                    <img src="https://visualmodo.com/wp-content/uploads/2017/03/wedding-theme-thumbnail.jpg">
                <figcaption>
                <span class="product-title">Wedding</span>
                <div class="product-description-container">
                    <h3 class="product-description">The Wedding Day Is Amazing</h3>
                </div>
                <div class="product-meta">
                    <a class="product-preview-url" href="http://wordpressthemes.visualmodo.com/?theme=Wedding" target="_blank"><span class="product-preview">Preview Theme</span></a>
                    <a class="product-download-url" href="https://visualmodo.com/theme/wedding-wordpress-theme/" target="_blank"><span class="product-download">Download Theme</span></a>
                </div>
                </figcaption>
                </figure>
            </a>
        </div>

    </div>
</div>

<?php } else { ?>

<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h1 class="products-title">One Subscription. Unlimited Downloads</h1>
            <h2 class="products-caption">Inspiring and ready-to-use WordPress themes and plugins. Unlimited theme downloads for a single yearly fee.</h2>
        </div>
        <div class="col-md-6">
            <div class="vslmd-pricing">
                <div class="vslmd-pricing-ribbon"><div class="vslmd-pricing-ribbon-inside"><p>POPULAR</p></div></div>
                <h3 class="vslmd-pricing-title">1 YEAR<br>MEMBERSHIP</h3>
                <span class="vslmd-pricing-value">99</span>
                <p class="vslmd-pricing-plan"><strong>USD /</strong> ONCE</p>
                <h4 class="vslmd-pricing-sub-heading">12 months access to all<br>themes, updates &amp; support</h4>
                <ul class="vslmd-pricing-features">
                    <li><span class="dashicons dashicons-yes"></span>Access To All Themes</li>
                    <li><span class="dashicons dashicons-yes"></span>Access To All Plugins</li>
                    <li><span class="dashicons dashicons-yes"></span>Unlimited Domain Usage</li>
                    <li><span class="dashicons dashicons-yes"></span>1 Year of Theme Updates</li>  
                    <li><span class="dashicons dashicons-yes"></span>1 Year of Premium Support</li>
                    <li><span class="dashicons dashicons-yes"></span>Advanced Documentation</li>
                    <li><span class="dashicons dashicons-yes"></span>Complete Access To All Plugins</li>
                    <li><span class="dashicons dashicons-yes"></span>Access all new themes</li>
                    <li><span class="dashicons dashicons-yes"></span><del>One Time Fee</del></li>                    
                </ul>
                <a class="vslmd-pricing-button" href="https://visualmodo.com/checkout/?add-to-cart=5405" target="_blank">Sign Up Today!</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="vslmd-pricing">
                <div class="vslmd-pricing-ribbon"><div class="vslmd-pricing-ribbon-inside"><p>BEST DEAL</p></div></div>
                <h3 class="vslmd-pricing-title">LIFETIME<br>MEMBERSHIP</h3>
                <span class="vslmd-pricing-value">259</span>
                <p class="vslmd-pricing-plan"><strong>USD /</strong> ONCE</p>
                <h4 class="vslmd-pricing-sub-heading">Forever access to all<br>themes, updates &amp; support</h4>
                <ul class="vslmd-pricing-features">
                    <li><span class="dashicons dashicons-yes"></span>Lifetime Access To All Themes</li>
                    <li><span class="dashicons dashicons-yes"></span>Lifetime Access To All Plugins</li>
                    <li><span class="dashicons dashicons-yes"></span>Unlimited Domain Usage</li>
                    <li><span class="dashicons dashicons-yes"></span>Lifetime Updates</li>  
                    <li><span class="dashicons dashicons-yes"></span>Lifetime Premium Support</li>
                    <li><span class="dashicons dashicons-yes"></span>Advanced Documentation</li>
                    <li><span class="dashicons dashicons-yes"></span>Complete Access To All Plugins</li>
                    <li><span class="dashicons dashicons-yes"></span>Access all new themes</li>
                    <li><span class="dashicons dashicons-yes"></span>One Time Fee</li>                    
                </ul>
                <a class="vslmd-pricing-button" href="https://visualmodo.com/checkout/?add-to-cart=5462" target="_blank">Go Unlimited!</a>
            </div>
        </div>
    </div>
</div>

<?php } ?>
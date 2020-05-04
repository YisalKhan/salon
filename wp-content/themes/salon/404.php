<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package cornerstone
 */

$options = get_option('vslmd_options');
$page_404 = $options['404_switch'];
$content_404 = $options['404_content'];
get_header(); 

if($page_404 != '1') {?>
<div class="wrapper" id="404-wrapper">
    
    <div  id="content" class="container">

        <div class="row">
        
            <div id="primary" class="content-area">

                <main id="main" class="site-main" role="main">

                    <section class="error-404 not-found">
                        
                        <header class="page-header">

                            <h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'vslmd' ); ?></h1>
                        </header><!-- .page-header -->

                        <div class="page-content">

                            <p><?php _e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'vslmd' ); ?></p>

                            <?php get_search_form(); ?>

                            <?php the_widget( 'WP_Widget_Recent_Posts' ); ?>

                            <?php if ( vslmd_categorized_blog() ) : // Only show the widget if site has multiple categories. ?>

                                <div class="widget widget_categories">

                                    <h2 class="widget-title"><?php _e( 'Most Used Categories', 'vslmd' ); ?></h2>

                                    <ul>
                                    <?php
                                        wp_list_categories( array(
                                            'orderby'    => 'count',
                                            'order'      => 'DESC',
                                            'show_count' => 1,
                                            'title_li'   => '',
                                            'number'     => 10,
                                        ) );
                                    ?>
                                    </ul>

                                </div><!-- .widget -->
                            
                            <?php endif; ?>

                            <?php
                                /* translators: %1$s: smiley */
                                $archive_content = '<p>' . sprintf( __( 'Try looking in the monthly archives. %1$s', 'vslmd' ), convert_smilies( ':)' ) ) . '</p>';
                                the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$archive_content" );
                            ?>

                            <?php the_widget( 'WP_Widget_Tag_Cloud' ); ?>

                        </div><!-- .page-content -->
                        
                    </section><!-- .error-404 -->

                </main><!-- #main -->
                
            </div><!-- #primary -->

        </div> <!-- .row -->
        
    </div><!-- Container end -->
    
</div><!-- Wrapper end -->
<?php } else if( $page_404 == '1' && !empty($content_404) ) { 

$page_id = $content_404;
$page_data = get_page( $page_id ); 
$content = apply_filters('the_content', $page_data->post_content);?>

<div class="wrapper" id="page-wrapper">
    
    <div  id="content" class="container">
        
       <div id="primary" class="col-md-12 content-area">

            <main id="main" class="site-main" role="main">

                    <?php echo $content; ?>

            </main><!-- #main -->
           
        </div><!-- #primary -->
        
    </div><!-- Container end -->
    
</div><!-- Wrapper end -->

<?php } get_footer(); ?>
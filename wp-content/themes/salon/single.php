<?php
/**
 * The template for displaying all single posts.
 *
 * @package cornerstone
 */

$options = get_option('vslmd_options');
global $woocommerce; 

//Variables > Theme Options
$single_post_widget = $options['single_post_widget'] ?: '3';

get_header(); ?>
<div class="wrapper" id="single-wrapper">
    
    <div  id="content" class="container">

        <div class="row">

            <?php if ($single_post_widget == '2') { get_template_part('sidebar'); } ?>
        
            <div id="primary" class="<?php if ( $single_post_widget =='1' ) : ?>col-md-12<?php else : ?>col-md-8<?php endif; ?> content-area">
                
                <main id="main" class="site-main" role="main">

                    <?php while ( have_posts() ) : the_post(); ?>

                        <?php get_template_part( 'loop-templates/content', 'single' ); ?>

                        <?php vslmd_post_nav(); ?>

                        <?php
                        // If comments are open or we have at least one comment, load up the comment template
                        if ( comments_open() || get_comments_number() ) :
                            comments_template();
                        endif;
                        ?>
                        
                    <?php endwhile; // end of the loop. ?>

                </main><!-- #main -->
                
            </div><!-- #primary -->

        <?php if ($single_post_widget == '3') { get_template_part('sidebar'); } ?>

        </div><!-- .row -->
        
    </div><!-- Container end -->
    
</div><!-- Wrapper end -->

<?php get_footer(); ?>
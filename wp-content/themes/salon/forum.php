<?php
/**
 * The template for displaying all forums.
 *
 * This is the template that displays all forums by default.
 * Please note that this is the WordPress construct of forums
 * and that other 'forums' on your WordPress site will use a
 * different template.
 *
 * @package cornerstone
 */

get_header(); ?>

<div class="wrapper" id="forum-wrapper">

    <div  id="content" class="container">

        <div class="row">

           <div id="primary" class="<?php if ( is_active_sidebar( 'bbpress-widget' ) && function_exists( 'is_bbpress' ) ) : ?>col-md-8<?php else : ?>col-md-12<?php endif; ?> content-area">

                <main id="main" class="site-main" role="main">

                    <?php while ( have_posts() ) : the_post(); ?>

                        <?php get_template_part( 'loop-templates/content', 'page' ); ?>

                    <?php endwhile; // end of the loop. ?>

                </main><!-- #main -->

            </div><!-- #primary -->

            <?php if ( is_active_sidebar( 'bbpress-widget' ) && function_exists( 'is_bbpress' ) ) { ?>
  
                <div class="col-md-4 widget-area" id="secondary" role="complementary">
                <?php dynamic_sidebar( 'bbpress-widget' ); ?>
                </div><!-- .widget-area -->
              
            <?php } ?>

        </div><!-- .row -->

    </div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>
<?php
/**
 * The template for displaying the author pages.
 *
 * Learn more: https://codex.wordpress.org/Author_Templates
 *
 * @package cornerstone
 */

get_header(); ?>

<div class="wrapper" id="author-wrapper">

	<div  id="content" class="container">

		<div class="row">

			<div id="primary" class="<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>col-md-8<?php else : ?>col-md-12<?php endif; ?> content-area">

				<main id="main" class="site-main" role="main">

					<?php if ( have_posts() ) : ?>

						<header class="page-header author-header">

							<?php
							$curauth = ( isset( $_GET['author_name'] ) ) ? get_user_by( 'slug',
								$author_name ) : get_userdata( intval( $author ) );
								?>

								<h1 class="mb-4"><?php esc_html_e( 'About:', 'vslmd' ); ?><?php echo esc_html( $curauth->nickname ); ?></h1>

								<?php if ( ! empty( $curauth->ID ) ) : ?>
									<?php echo get_avatar( $curauth->ID ); ?>
								<?php endif; ?>

								<div class="wrapper">
									<?php if ( ! empty( $curauth->user_url ) ) : ?>
										<span><?php esc_html_e( 'Website:', 'vslmd' ); ?></span>
										<span>
											<a href="<?php echo esc_url( $curauth->user_url ); ?>"><?php echo esc_html( $curauth->user_url ); ?></a>
										</span>
									<?php endif; ?>

									<?php if ( ! empty( $curauth->user_description ) ) : ?>
										<span><?php esc_html_e( 'Profile', 'vslmd' ); ?></span>
										<span><?php echo esc_html( $curauth->user_description ); ?></span>
									<?php endif; ?>
								</div>

								<h2><?php esc_html_e( 'Posts by', 'vslmd' ); ?> <?php echo esc_html( $curauth->nickname ); ?>
								:</h2>

							</header><!-- .page-header -->

							<?php /* Start the Loop */ ?>
							<?php while ( have_posts() ) : the_post(); ?>

								<?php
                                /* Include the Post-Format-specific template for the content.
                                 * If you want to override this in a child theme, then include a file
                                 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                                 */
                                get_template_part( 'loop-templates/content', get_post_format() );
                                ?>

                            <?php endwhile; ?>

                            <?php vslmd_pagination(); ?>

                        <?php else : ?>

                        	<?php get_template_part( 'loop-templates/content', 'none' ); ?>

                        <?php endif; ?>

                    </main><!-- #main -->

                </div><!-- #primary -->

                <?php get_sidebar(); ?>

            </div> <!-- .row -->

        </div><!-- Container end -->

    </div><!-- Wrapper end -->

    <?php get_footer(); ?>

<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package cornerstone
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

     <?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?> 
    
	<div class="entry-content">

		<?php the_content(); ?>

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'vslmd' ),
				'after'  => '</div>',
			) );
		?>

	</div><!-- .entry-content -->

</article><!-- #post-## -->

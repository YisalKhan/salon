<?php
/**
 * @package cornerstone
 */
?>
<?php if(get_post_format() == '' || get_post_format() == 'standard') { ?> 
<article id="post-<?php the_ID(); ?>" <?php post_class('card mb-4'); ?>>

	<a href="<?php the_permalink(); ?>">
		<?php echo get_the_post_thumbnail( $post->ID, 'full card-img-top' ); ?> 
	</a>
	<div class="card-body">

		<?php the_title( sprintf( '<h2 class="card-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' == get_post_type() ) : ?>

			<div class="card-text mb-2"><?php vslmd_posted_on(); ?></div><!-- .card-text -->

		<?php endif; ?>

		<?php
		the_excerpt();
		?>

		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'vslmd' ),
			'after'  => '</div>',
		) );
		?>

	</div><!-- .card-body -->

</article><!-- #post-## -->
<?php } ?> 
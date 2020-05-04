<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package cornerstone
 */

//Variables > Theme Options

$options = get_option('vslmd_options');
$footer_color_scheme = $options['footer_color_scheme'] ?: 'dark footer-bg-dark';
$footer_widget_columns = $options['footer_widget_columns'];
$footer_boxed_or_stretched = $options['footer_boxed_or_stretched'] ?: 'container';
$footer_top = $options['footer_top'] ?: '4';
$footer_text = $options['footer_text'];

//Variables > Page Options

if ( is_singular() ) {
	$layout_structure = redux_post_meta( "vslmd_options", $post->ID, "layout_structure" ) ?: '4'; 
} else {
	$layout_structure = '4';
}

?>

<?php if($layout_structure == '2' || $layout_structure == '4') { ?>

<div class="footer-background-image">

	<div class="wrapper wrapper-footer footer-background-color <?php echo $footer_color_scheme; ?>">

		<div class="<?php echo $footer_boxed_or_stretched; ?>">

			<div class="row">

				<div class="col">

					<footer id="colophon" class="site-footer" role="contentinfo">

						<?php if ( $footer_top != '1' ) { ?>

						<?php if ( !is_front_page() && $footer_top == '2' ) { ?>
						<div class="top-footer">
							<div class="<?php echo $footer_boxed_or_stretched; ?>">
								<div class="row align-items-center">
									<div class="breadcrumbs-footer col">
										<?php if (function_exists('vslmd_breadcrumbs')) vslmd_breadcrumbs(); ?>
									</div>
								</div>
							</div>
						</div>
						<?php } elseif ( $footer_top == '3' ) { ?>
						<div class="top-footer">
							<div class="bottom-to-top text-center">
								<span><i class="fa fa-angle-up"></i></span>
							</div>
						</div>
						<?php } elseif ( $footer_top == '4' ) { ?>

						<?php if ( is_front_page() ) { ?>
						<div class="top-footer">
							<div class="bottom-to-top text-center">
								<span><i class="fa fa-angle-up"></i></span>
							</div>
						</div>

						<?php } else { ?>
						<div class="top-footer">
							<div class="<?php echo $footer_boxed_or_stretched; ?>">
								<div class="row align-items-center">
									<div class="breadcrumbs-footer col-11">
										<?php if (function_exists('vslmd_breadcrumbs')) vslmd_breadcrumbs(); ?>
									</div>
									<div class="bottom-to-top col-1 text-right">
										<span><i class="fa fa-angle-up"></i></span>
									</div>
								</div>
							</div>
						</div>

						<?php } ?>

						<?php } ?>

						<?php } ?>

						<?php if ( $footer_widget_columns != '0' ) { ?>

						<div class="widgets-footer">
							<div class="<?php echo $footer_boxed_or_stretched; ?>">
								<div class="row">
									<?php
									get_template_part('widget-templates/before-footer');
									get_template_part('widget-templates/footer');
									get_template_part('widget-templates/after-footer'); 
									?>

								</div>
							</div>
						</div>

						<?php } ?>

						<div class="site-info bottom-footer">
							<div class="<?php echo $footer_boxed_or_stretched; ?>">
								<div class="row align-items-center">
									<div class="col-md-6 copyright-footer-item">
										<?php if(empty($footer_text)) { ?>
										<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'vslmd' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'vslmd' ), 'WordPress' ); ?></a>
										<span class="sep"> | </span>
										<?php printf( __( 'Theme: %1$s by %2$s.', 'vslmd' ), wp_get_theme(), '<a href="https://visualmodo.com/" rel="designer">visualmodo.com</a>' ); ?>
										<?php } else { ?>
										<span><?php echo $footer_text ?></span>
										<?php } ?>
									</div>
									<?php get_template_part('widget-templates/copyright-footer'); ?>
								</div>
							</div>
						</div><!-- .site-info -->

					</footer><!-- #colophon -->

				</div><!-- col end -->

			</div><!-- row end -->

		</div><!-- container end -->

	</div><!-- background color end -->

</div><!-- wrapper end -->

<?php } ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>
<?php
/**
* Welcome Page View
*
* @since 1.0.0
* @package salon-booking-system
*/
if ( ! defined( 'WPINC' ) ) {
die;
}
?>
<div class="wrapper">
    <!--header start-->
    <!--header end-->
    <div class="main-content">
	<!--main-content start-->
	<div class="container">
	    <!--welcome start-->
	    <div class="welcome">
		<div class="welcome-head animated fadeInDown"  style="visibility: visible;animation-delay:0.2s;">
		    <h1><?php echo sprintf(__( 'Welcome to <span>%s</span>', 'salon-booking-system' ), apply_filters('wpo_welcome_page_header_plugin_title', __( 'Salon Booking System', 'salon-booking-system' ))); ?></h1>
		    <p><?php _e( 'Follow these simple steps to complete the setup and start collecting your reservations right away.', 'salon-booking-system' ); ?></p>
		</div>
		<div class="welcome-in animated fadeInLeft" style="visibility: visible;animation-delay:0.4s;">
		    <ul>
			<li>
			    <div class="create clearfix">
				<div class="create-lt active flt_lt">
				    <span>1</span>
				</div>
				<div class="create-rt flt_rt">
				    <h6><?php _e( 'We’ve just created three new pages called:', 'salon-booking-system' ); ?></h6>
				    <ul>
					<li>
					    <a href="<?php echo get_permalink($settings->getPayPageId()) ?>">
						<?php _e( 'Booking', 'salon-booking-system' ); ?>
					    </a>
					</li>
					<li>
					    <a href="<?php echo get_permalink($settings->getBookingmyaccountPageId()) ?>">
						<?php _e( 'Booking my account', 'salon-booking-system' ); ?>
					    </a>
					</li>
					<li>
					    <a href="<?php echo get_permalink($settings->getThankyouPageId()) ?>">
						<?php _e( 'Thank you page', 'salon-booking-system' ); ?>
					    </a>
					</li>
				    </ul>
				</div>
			    </div>
			</li>
			<li >
			    <div class="create clearfix">
				<div class="create-lt flt_lt">
				    <span>2</span>
				</div>
				<div class="create-rt flt_rt">
				    <p><?php _e( 'Go to the <strong>Settings > General section </strong>
					and provide all the basic information
					of your Salon.', 'salon-booking-system' ); ?></p>
				</div>
			    </div>
			</li>
			<li >
			    <div class="create clearfix">
				<div class="create-lt flt_lt">
				    <span>3</span>
				</div>
				<div class="create-rt flt_rt">
				    <p><?php _e( 'Go to the <strong>Settings > Booking rules</strong>
					section and setup your Salon availability
					rules.', 'salon-booking-system' ); ?></p>
				</div>
			    </div>
			</li>
			<li >
			    <div class="create clearfix">
				<div class="create-lt flt_lt">
				    <span>4</span>
				</div>
				<div class="create-rt flt_rt">
				    <p><?php _e( 'Go to the <strong>Services</strong> section <br>
					to create and setup your Salon <br> services.', 'salon-booking-system' ); ?></p>
				</div>
			    </div>
			</li>
			<li >
			    <div class="create clearfix">
				<div class="create-lt flt_lt">
				    <span>5</span>
				</div>
				<div class="create-rt flt_rt">
				    <p><?php _e( 'Go to the <strong>Assistants</strong> section
					to create and setup your Salon <br>
					assistants.', 'salon-booking-system' ); ?></p>
				</div>
			    </div>
			</li>
		    </ul>
		</div>
		<div class="btn-right flt_rt  animated fadeInRight" style="visibility: visible;animation-delay:0.6s;">
		    <a href="admin.php?page=salon-settings&tab=general" class="btn"><?php _e( 'Let’s get started!', 'salon-booking-system' ); ?></a>
		</div>
		<div class="clear"></div>
	    </div>
	    <!--welcome end-->
	</div>
	<div class="document animated fadeInUp" style="visibility: visible;animation-delay:0.8s;">
	    <div class="document-main">
		<div class="document-in clearfix">
		    <div class="document-lt flt_lt">
			<p><?php _e( 'Need more help on the
			    plugin setup and usage?', 'salon-booking-system' ); ?></p>
		    </div>
		    <div class="document-rt flt_rt">
			<a href="https://salonbookingsystem.helpscoutdocs.com/" class="btn" target="blank"><?php _e( 'Documentation', 'salon-booking-system' ); ?></a>
		    </div>

                </div>
		<div class="review">
		    <span>
			<?php _e( 'Are you happy?', 'salon-booking-system' ); ?>
			<a href="https://wordpress.org/support/plugin/salon-booking-system/reviews/#new-post" target="blank">
			    <?php _e( 'Leave a positive review!', 'salon-booking-system' ); ?>
			</a>
		    </span>
		</div>
	    </div>
	</div>
	<!--main-content end-->
    </div>
</div>
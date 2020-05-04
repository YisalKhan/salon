<?php
if ( ! defined( 'WPINC' ) ) {
die;
}
?>
<html>
    <head>
	<title>
	    <?php _e('Salon Booking System - Booking Cancellation', 'salon-booking-system'); ?>
	</title>
	<link rel='stylesheet' href='<?php echo is_rtl() ? SLN_PLUGIN_URL . '/css/cancel-booking-rtl.css' : SLN_PLUGIN_URL . '/css/cancel-booking.css' ?>' type='text/css' media='all' />
    </head>
    <body>
	<div class="sln-cancel-booking-block">
	    <div class="sln-cancel-booking-block__header">
		<?php _e('Salon Booking System', 'salon-booking-system'); ?>
	    </div>
	    <div class="sln-cancel-booking-block__text">
		<?php _e("Booking Cancellation", 'salon-booking-system'); ?>
	    </div>
	    <div class="sln-cancel-booking-block__body">
		<div class="sln-cancel-booking-block__body__booking">
		    #<?php echo $booking->getId(); ?> <?php echo $booking->getTitle(); ?>
		</div>
		<div class="sln-cancel-booking-block__body__action">
		    <?php if ($booking->hasStatus(SLN_Enum_BookingStatus::CANCELED)): ?>
			<div class="sln-cancel-booking-block__body__action__booking-cancelled">
			    <?php _e('Booking is cancelled', 'salon-booking-system'); ?>
			</div>
			<script>
			    setTimeout(function () {
				window.location.href = '<?php echo $booking_url; ?>';
			    }, 1000);
			</script>
		    <?php elseif (!$cancellation_enabled): ?>
			<div class="sln-cancel-booking-block__body__action__cancellation-disabled">
			    <?php _e('Cancellation is disabled', 'salon-booking-system'); ?>
			</div>
		    <?php elseif ($out_of_time): ?>
			<div class="sln-cancel-booking-block__body__action__out_of_time">
			    <?php _e('Out of time', 'salon-booking-system'); ?>
			</div>
		    <?php else: ?>
			<div class="sln-cancel-booking-block__body__action__form-block">
			    <form action="<?php echo $booking->getCancelUrl(); ?>" method="post" class="sln-cancel-booking-block__body__action__form-block__form">
				<input type="hidden" name="cancel_booking" value="1">
				<div>
				    <button class="sln-cancel-booking-block__body__action__form-block__form__cancel-button">
					<?php _e('Cancel Booking', 'salon-booking-system'); ?>
				    </button>
				</div>
			    </form>
			    <div class="sln-cancel-booking-block__body__action__form-block__note">
				<?php _e('You can cancel the booking until:', 'salon-booking-system'); ?>
				<span class="sln-cancel-booking-block__body__action__form-block__note__date">
				    <?php echo $cancel_until ?>
				</span>
			    </div>
			</div>
		    <?php endif; ?>
		</div>
	    </div>
	</div>
    </body>
</html>
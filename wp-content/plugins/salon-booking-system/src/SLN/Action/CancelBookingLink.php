<?php

class SLN_Action_CancelBookingLink {

    public function __construct(SLN_Plugin $plugin) {
	$this->plugin = $plugin;
    }

    public static function getUrl() {
	return add_query_arg('sln-api', 'cancel_booking', home_url('/'));
    }

    public function isCancelBookingPage() {
	return isset($_GET['sln-api']) && $_GET['sln-api'] == 'cancel_booking';
    }

    public function execute() {

	if ( ! $this->isCancelBookingPage() ) {
	    return;
	}

	$booking_id = $_GET['booking_id'];
	$booking    = null;

	try {
	    $booking = $this->plugin->createBooking($booking_id);
	} catch (Exception $ex) {

	}

	if (!$booking) {
	    return;
	}

	do_action('sln_before_cancel_booking_link', $booking);

	$settings = $this->plugin->getSettings();

	$cancellationEnabled = $settings->get('cancellation_enabled');
	$outOfTime	     = ($booking->getStartsAt()->getTimestamp() - time()) < $settings->get('hours_before_cancellation') * 3600;

	$startTimestamp = $booking->getStartsAt();
	$cancelUntil =  $startTimestamp->setTimeStamp( $startTimestamp->getTimestamp() - $settings->get('hours_before_cancellation') * 3600);

	if ($cancellationEnabled && !$outOfTime && isset($_POST['cancel_booking'])) {

	    $booking->setStatus(SLN_Enum_BookingStatus::CANCELED);

	    $booking = $this->plugin->createBooking($booking_id);

	    $args = compact('booking');

	    $args['forAdmin'] = true;

	    $args['to'] = $this->plugin->getSettings()->getSalonEmail();

	    $this->plugin->sendMail('mail/status_canceled', $args);
	}

	echo $this->plugin->loadView('cancel_booking', array(
	    'cancellation_enabled'  => $cancellationEnabled,
	    'out_of_time'	    => $outOfTime,
	    'booking'		    => $booking,
	    'cancel_until'	    => $this->plugin->format()->datetime($cancelUntil->format('Y-m-d H:i')),
	    'booking_url'	    => get_permalink($settings->getPayPageId()),
	));

	header('HTTP/1.1 200 OK');

	exit();
    }

}
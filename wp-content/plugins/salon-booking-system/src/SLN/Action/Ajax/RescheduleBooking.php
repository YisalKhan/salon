<?php

class SLN_Action_Ajax_RescheduleBooking extends SLN_Action_Ajax_Abstract
{
    public function execute(){

	if ( !is_user_logged_in() ) {
	    return array( 'redirect' => wp_login_url());
	}

	$id = $_POST['_sln_booking_id'];

	$date = SLN_Func::filter(sanitize_text_field( wp_unslash( $_POST['_sln_booking_date']  ) ), 'date');
        $time = SLN_Func::filter(sanitize_text_field( wp_unslash( $_POST['_sln_booking_time']  ) ), 'time');

	update_post_meta($id, '_'.SLN_Plugin::POST_TYPE_BOOKING.'_date', $date);
	update_post_meta($id, '_'.SLN_Plugin::POST_TYPE_BOOKING.'_time', $time);

	synch_a_booking($id, get_post($id));

	$plugin = SLN_Plugin::getInstance();

	$booking = $plugin->createBooking($id);

	$format	 = $plugin->format();

	(new SLN_Service_Messages($plugin))->sendRescheduledMail($booking);

	return array(
	    'booking_date' => $format->date($booking->getStartsAt()),
	    'booking_time' => $format->time($booking->getStartsAt()),
	);
    }
}

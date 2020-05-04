<?php

class SLN_Action_Ajax_RescheduleBookingCheckDate extends SLN_Action_Ajax_Abstract
{
    public function execute(){

	$handler = new SLN_Action_Ajax_CheckDateAlt($this->plugin);

	$date = sanitize_text_field(wp_unslash($_POST['_sln_booking_date']));
	$time = sanitize_text_field(wp_unslash($_POST['_sln_booking_time']));

	$services = isset($_POST['_sln_booking']['services']) ? $_POST['_sln_booking']['services'] : array();

	$handler->setDate($date);
	$handler->setTime($time);

	$bookingID = $_POST['_sln_booking_id'];

	$booking = SLN_Plugin::getInstance()->createBooking($bookingID);

	$handler->setBooking($booking);

	$bb = $this->plugin->getBookingBuilder();

	$bb->clear();

	$bb->setDate($date);
	$bb->setTime($time);

	$bb->setServicesAndAttendants($services);

	$bb->save();

	$handler->checkDateTime();

	$errors = $handler->getErrors();

        if ($errors) {
            $ret = compact('errors');
        } else {
            $ret = array('success' => 1);
        }

	$ret['intervals'] = $handler->getIntervalsArray();

        return $ret;
    }

    public function getIntervals($date, $time, array $services = array()) {

	$handler = new SLN_Action_Ajax_CheckDateAlt($this->plugin);

	$handler->setDate($date);
	$handler->setTime($time);

	$bb = $this->plugin->getBookingBuilder();

	$bb->clear();

	$bb->setDate($date);
	$bb->setTime($time);

	$bb->setServicesAndAttendants($services);

	$bb->save();

	return $handler->getIntervalsArray();
    }

}
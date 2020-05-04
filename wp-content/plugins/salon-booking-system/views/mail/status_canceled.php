<?php
/**
 * @var SLN_Plugin                $plugin
 * @var SLN_Wrapper_Booking       $booking
 */
if(!isset($data['to'])){    // algolplus fix
    $data['to'] = $booking->getEmail();
}
if ($plugin->getSettings()->get('attendant_email')
    && ($attendants = $booking->getAttendants(true))

) {
	foreach ($attendants as $attendant) {
		if (($email = $attendant->getEmail())){
			if(!is_array($data['to'])) $data['to'] = array($data['to'], $email);
			else $data['to'][] = $email;
		}
	}

}

$data['subject'] = __('Booking Canceled','salon-booking-system')
    . ' ' . $plugin->format()->date($booking->getDate())
    . ' - ' . $plugin->format()->time($booking->getTime());

$contentTemplate = '_status_canceled_content';

include dirname(__FILE__).'/template.php';
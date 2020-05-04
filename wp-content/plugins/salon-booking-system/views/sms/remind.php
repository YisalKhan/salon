<?php
/**
 * @var SLN_Plugin          $plugin
 * @var SLN_Wrapper_Booking $booking
 */

$template = $plugin->getSettings()->get('sms_notification_message');

if ($template) {

    $message = str_replace(
	array(
	    '[NAME]',
	    '[SALON NAME]',
	    '[DATE]',
	    '[TIME]',
	    '[PRICE]',
	    '[BOOKING ID]',
	),
	array(
	    $booking->getDisplayName(),
	    $plugin->getSettings()->getSalonName(),
	    $plugin->format()->date($booking->getDate()),
	    $plugin->format()->time($booking->getTime()),
	    $booking->getAmount(),
	    $booking->getId(),
	),
	$template
    );

    if (strlen($message) > 160) {
	$more_string = __('...more details in the email confirmation', 'salon-booking-system');
	$message     = substr($message, 0, ( 159 - strlen($more_string))) . $more_string;
    }

    echo $message;

    return;
}

$message =
__('Hi','salon-booking-system') .' ' . $booking->getFirstname() . ' ' . $booking->getLastname()

. ' ' . __('don\'t forget your reservation at','salon-booking-system').' '. $plugin->getSettings()->getSalonName()
. ' ' . __('on','salon-booking-system').' '. $plugin->format()->date($booking->getDate()) 
. ' ' . __('at','salon-booking-system').' '. $plugin->format()->time($booking->getTime())
. ' ' . __('| Booking ID ','salon-booking-system') .$booking->getId()
. ' ' . __('| Timing: ','salon-booking-system') .' ';
foreach($booking->getBookingServices()->getItems() as $bookingService){
	$message .=  $bookingService->getStartsAt()->format( 'H:i' )
	.' '.  ($bookingService->getAttendant() ? $bookingService->getAttendant(
                        )->getTitle() : $bookingService->getService()->getTitle()).' ';
}
$message .= __('Price','salon-booking-system') .': '. $booking->getAmount();
if(strlen($message)>160){
	$more_string = __('...more details in the email confirmation','salon-booking-system');
	$message = substr($message, 0, ( 159 - strlen($more_string))).$more_string;
}
echo $message;
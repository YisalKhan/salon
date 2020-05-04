<?php
/**
 * @var SLN_Plugin          $plugin
 * @var SLN_Wrapper_Booking $booking
 */

$default_template = SLN_Admin_SettingTabs_GeneralTab::getDefaultSmsNotificationMessage();
$template	  = $plugin->getSettings()->get('sms_notification_message') ? $plugin->getSettings()->get('sms_notification_message') : $default_template;

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
    $message	 = substr($message, 0, ( 159 - strlen($more_string))) . $more_string;
}

echo $message;
<?php

require_once SLN_PLUGIN_DIR . '/src/SLN/Third/calendar/vendor/autoload.php';

use Spatie\CalendarLinks\Link;

class SLN_Helper_CalendarLink
{
    public static function getGoogleLink($booking) {
	return self::generateLink($booking)->google();
    }

    public static function getICallLink($booking, $data) {

	$filename = sprintf('Mycal_%s.ics', $booking->getId());
	$filepath = WP_CONTENT_DIR . '/uploads/' . $filename;

	$content = str_replace(array('data:text/calendar;charset=utf8,', '%0d%0a'), array('', "\r\n"), self::generateLink($booking)->ics());

	file_put_contents($filepath, $content);

	$data['attachments'][] = $filepath;

	$fileurl = home_url('/wp-content/uploads/' . $filename);

	return $fileurl;
    }

    public static function getOutlookLink($booking) {
	return self::generateLink($booking)->webOutlook();
    }

    protected static function generateLink($booking) {

	$plugin   = SLN_Plugin::getInstance();
	$settings = $plugin->getSettings();

	$from = $booking->getStartsAt();
	$to   = $booking->getEndsAt();

	$title    = $settings->getSalonName() . " - " . $plugin->format()->datetime($booking->getStartsAt());
	$location = $settings->get('gen_address');

	$desc = "";
        $desc .= __('Salon name', 'salon-booking-system') . ": " . $settings->getSalonName() . " \n\n";
        $desc .= __('Salon address', 'salon-booking-system') . ": " . $settings->get('gen_address') . " \n\n";
        $desc .= __('Salon phone', 'salon-booking-system') . ": " . $settings->get('gen_phone') . " \n\n";
        $desc .= __('Salon email', 'salon-booking-system') . ": " . $settings->getSalonEmail() . " \n";
        //Services
        $desc .= "\n" . __('Booked services', 'salon-booking-system') . ":";
        foreach ($booking->getBookingServices()->getItems() as $bookingService) {
            $desc .= "\n";
            $desc .= $bookingService->getService()->getName() . ': ' .
                     $plugin->format()->time($bookingService->getStartsAt()) . ' ➝ ' .
                     $plugin->format()->time($bookingService->getEndsAt());
            if($bookingService->getAttendant()){
                $desc .= ' - ' . $bookingService->getAttendant()->getName();
            }
        }
        $notes = $booking->getNote();
        $desc .= "\n\n" . __('Booking notes', 'salon-booking-system') . ":\n" . (empty($notes) ? __("None", 'salon-booking-system') : $notes);
        $desc .= "\n\n" . __('Booking status', 'salon-booking-system') . ": " . SLN_Enum_BookingStatus::getLabel($booking->getStatus());

	$link = Link::create($title, $from, $to)
		    ->description($desc)
		    ->address($location);

	return $link;
    }
 
}
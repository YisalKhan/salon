<?php
/**
 * @var SLN_Plugin                        $plugin
 * @var string                            $formAction
 * @var string                            $submitName
 * @var SLN_Shortcode_Salon_AttendantStep $step
 * @var SLN_Wrapper_Attendant[]           $attendants
 */

$ah = $plugin->getAvailabilityHelper();
$ah->setDate($plugin->getBookingBuilder()->getDateTime());
$bookingServices = SLN_Wrapper_Booking_Services::build($bb->getAttendantsIds(), $bb->getDateTime());

//$duration      = new SLN_DateTime('1970-01-01 '.$bb->getDuration());
$hasAttendants = false;
$style         = $step->getShortcode()->getStyleShortcode();
$size          = SLN_Enum_ShortcodeStyle::getSize($style);


$services = $bb->getServices();
foreach ($services as $k => $service) {
    if (!$service->isAttendantsEnabled()) {
        unset($services[$k]);
    }
}

$isChooseAttendantForMeDisabled = $plugin->getSettings()->isChooseAttendantForMeDisabled();

$tmp = '';
$i = 0;
foreach ($attendants as $attendant) {
    if ($attendant->hasServices($services)) {
        $errors = SLN_Shortcode_Salon_AttendantHelper::validateItem($bookingServices->getItems(), $ah, $attendant);

	if (!$i && $isChooseAttendantForMeDisabled) {
	    $tmp .= SLN_Shortcode_Salon_AttendantHelper::renderItem($size, $errors, $attendant, null, true);
	} else {
	    $tmp .= SLN_Shortcode_Salon_AttendantHelper::renderItem($size, $errors, $attendant);
	}

        $hasAttendants = true;
	$i++;
    }
}
if ($tmp && !$isChooseAttendantForMeDisabled) {
    $tmp = SLN_Shortcode_Salon_AttendantHelper::renderItem($size).$tmp;
}

?>
<div class="sln-attendant-list">
    <?php if ($tmp) : ?>
        <div class="row"><?php echo $tmp ?></div>
    <?php else: ?>
        <div class="alert alert-warning">
            <p><?php echo __(
                    'No assistants available for the selected time/slot - please choose another one',
                    'salon-booking-system'
                ) ?></p>
        </div>
    <?php endif ?>
</div>


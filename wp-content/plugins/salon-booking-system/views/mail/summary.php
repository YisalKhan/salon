<?php
/**
 * @var SLN_Plugin          $plugin
 * @var SLN_Wrapper_Booking $booking
 */
if(empty($data['to'])){
    $data['to']      = $booking->getEmail();
}

if(isset($remind) && $remind) {
    $data['subject'] = str_replace(
        array(
            '[DATE]',
            '[TIME]',
            '[SALON NAME]'
        ),
        array(
            $plugin->format()->date($booking->getDate()),
            $plugin->format()->time($booking->getTime()),
            $plugin->getSettings()->get('gen_name') ?
                $plugin->getSettings()->get('gen_name') : get_bloginfo('name')
        ),
        $plugin->getSettings()->get('email_subject')
    );
    $manageBookingsLink = true;
} elseif(isset($updated) && $updated) {
    $data['subject'] = str_replace(
        '[SALON NAME]',
        $plugin->getSettings()->get('gen_name') ?
            $plugin->getSettings()->get('gen_name') : get_bloginfo('name'),
        __('Your reservation at [SALON NAME] has been modified', 'salon-booking-system')
    );
    $manageBookingsLink = true;
} elseif(isset($rescheduled) && $rescheduled) {
    $current_user = wp_get_current_user();
    $data['subject'] = sprintf(
        __('Booking #%s has been re-scheduled by %s', 'salon-booking-system'),
        $booking->getId(),
        implode(' ', array_filter(array($current_user->user_firstname, $current_user->user_lastname)))
    );
    $manageBookingsLink = true;
} else {
    $data['subject'] = __('New booking ','salon-booking-system')
                       .' '. $plugin->format()->date($booking->getDate())
                       . ' - ' . $plugin->format()->time($booking->getTime());

    $data['subject'] = apply_filters('sln.new_booking.notifications.email.subject', $data['subject'], $booking);

    $manageBookingsLink = true;
}
$forAdmin = false;

$contentTemplate = '_summary_content';

include dirname(__FILE__) . '/template.php';

<?php
/**
 * @var SLN_Plugin                $plugin
 * @var SLN_Wrapper_Booking       $booking
 */
$data['to'] = $booking->getEmail();
$data['subject'] = sprintf(__('Payment for booking #%s has been confirmed','salon-booking-system'), $booking->getId());

$contentTemplate = '_payment_confirmed_content';

include dirname(__FILE__).'/template.php';
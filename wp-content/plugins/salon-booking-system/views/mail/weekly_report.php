<?php   // algolplus
/**
 * @var SLN_Plugin           $plugin
 * @var SLN_Wrapper_Customer $customer
 * @var array $stats
 */

$data['to']      = $plugin->getSettings()->getSalonEmail();
$data['subject'] = __('Salon Booking weekly report', 'salon-booking-system');

include dirname(__FILE__).'/weekly_report/template.php';

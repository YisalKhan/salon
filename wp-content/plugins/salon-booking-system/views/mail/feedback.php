<?php   // algolplus
/**
 * @var SLN_Plugin           $plugin
 * @var SLN_Wrapper_Customer $customer
 */
$customer = $booking->getCustomer();
$data['to']      = $customer->get('user_email');
$data['subject'] = $plugin->getSettings()->getSalonName();
$manageBookingsLink = true;

$contentTemplate = '_feedback_content';

include dirname(__FILE__).'/template.php';
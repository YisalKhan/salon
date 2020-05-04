<?php   // algolplus
/**
 * @var SLN_Plugin           $plugin
 * @var SLN_Wrapper_Customer $customer
 */

$data['to']      = $customer->get('user_email');
$data['subject'] = $plugin->getSettings()->getSalonName();
$manageBookingsLink = true;

$contentTemplate = '_follow_up_content';

include dirname(__FILE__).'/template.php';
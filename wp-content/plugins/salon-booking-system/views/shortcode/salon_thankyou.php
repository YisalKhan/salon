<?php
/**
 * @var SLN_Plugin $plugin
 * @var string     $formAction
 * @var string     $submitName
 * @var SLN_Shortcode_Salon_ThankyouStep $step
 */
$confirmation = $plugin->getSettings()->get('confirmation');
$pendingPayment = $plugin->getSettings()->isPayEnabled() && in_array($plugin->getBookingBuilder()->getLastBooking()->getStatus(), array(SLN_Enum_BookingStatus::PENDING_PAYMENT));
$payLater = $plugin->getSettings()->get('pay_cash');
$currentStep = $step->getShortcode()->getCurrentStep();
$ajaxData = "sln_step_page=$currentStep&submit_$currentStep=1";

$ajaxData = apply_filters('sln.booking.thankyou-step.get-ajax-data', $ajaxData);

$ajaxEnabled = $plugin->getSettings()->isAjaxEnabled();

$paymentMethod = ((!$confirmation || $pendingPayment) && $plugin->getSettings()->isPayEnabled()) ?
SLN_Enum_PaymentMethodProvider::getService($plugin->getSettings()->getPaymentMethod(), $plugin)
: false;

if ($plugin->getBookingBuilder()->getLastBooking()->getAmount() == 0) {
	$pendingPayment = $payLater = $paymentMethod = false;
}

$style = $step->getShortcode()->getStyleShortcode();
$size = SLN_Enum_ShortcodeStyle::getSize($style);

?>
<div id="salon-step-thankyou" class="sln-thankyou">
<?php include '_salon_thankyou_'.$size.'.php'; ?>
</div>

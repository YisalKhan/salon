<?php

class SLN_Shortcode_Salon_ThankyouStep extends SLN_Shortcode_Salon_Step
{
    private $op;

    public function setOp($op)
    {
        $this->op = $op;
    }

    protected function dispatchForm()
    {
        $plugin = $this->getPlugin();
        $settings = $plugin->getSettings();
        $bb = $plugin->getBookingBuilder();
        if (isset($_GET['sln_booking_id']) && intval($_GET['sln_booking_id'])) {
            $bb->clear(intval($_GET['sln_booking_id']));
        }
        $booking = $bb->getLastBooking();
        $paymentMethod = $settings->isPayEnabled() ?SLN_Enum_PaymentMethodProvider::getService($settings->getPaymentMethod(), $plugin) : false;
        $mode = sanitize_text_field(wp_unslash($_GET['mode']));
        $mode = isset($mode) ? $mode : null;
        if ($mode == 'confirm') {
            $this->goToThankyou();
        } elseif ($mode == 'later') {
            if($booking->getStatus() == SLN_Enum_BookingStatus::PENDING_PAYMENT) {
		if ( $booking->getAmount() > 0.0 ) {
		    $booking->setStatus(SLN_Enum_BookingStatus::PAY_LATER);
		} else {
		    $booking->setStatus(SLN_Enum_BookingStatus::CONFIRMED);
		}
	    }
            $this->goToThankyou();
        } elseif (isset($_GET['op']) || $mode) {
            if ($error = $paymentMethod->dispatchThankyou($this, $booking)) {
                $this->addError($error);
            } else {
                $this->goToThankyou();
            }
        }

        return false;
    }

    public function goToThankyou()
    {
        $id = $this->getPlugin()->getSettings()->getThankyouPageId();
        if ($id) {
            $this->redirect(get_permalink($id));
        }else{
            $this->redirect(home_url());
        }
    }

    public function getViewData()
    {
        $ret = parent::getViewData();
        $formAction = $ret['formAction'];

	$laterUrl = add_query_arg(
	    array(
		'mode'			    => 'later',
		'submit_'.$this->getStep()  => 1
	    ),
	    $formAction
	);

	$laterUrl = apply_filters('sln.booking.thankyou-step.get-later-url', $laterUrl);

	$confirmUrl = add_query_arg(
	    array(
		'mode'			    => 'confirm',
		'submit_'.$this->getStep()  => 1
	    ),
	    $formAction
	);

	$confirmUrl = apply_filters('sln.booking.thankyou-step.get-confirm-url', $confirmUrl);

        $data = array(
            'formAction' => $formAction,
            'booking' => $this->getPlugin()->getBookingBuilder()->getLastBooking(),
            'confirmUrl' => $confirmUrl,
            'laterUrl' => $laterUrl,
        );
        if($this->getPlugin()->getSettings()->isPayEnabled()){

	    $payUrl = add_query_arg(
		array(
		    'mode'			=> $this->getPlugin()->getSettings()->getPaymentMethod(),
		    'submit_'.$this->getStep()  => 1,
		),
		$formAction
	    );

	    $payUrl = apply_filters('sln.booking.thankyou-step.get-pay-url', $payUrl);

            $data['payUrl'] = $payUrl;
            $data['payOp']  = $this->op;
        }

        return array_merge( $ret,$data );
    }

    public function redirect($url)
    {
        if ($this->isAjax()) {
            throw new SLN_Action_Ajax_RedirectException($url);
        } else {
            wp_redirect($url);die();
        }
    }

    public function isAjax()
    {
        return defined('DOING_AJAX') && DOING_AJAX;
    }
}

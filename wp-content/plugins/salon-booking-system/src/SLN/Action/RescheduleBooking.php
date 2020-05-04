<?php

class SLN_Action_RescheduleBooking
{
    const SESSION_ERROR_KEY   = '_reschedule_button_errors';

    const FORM_STEP_DATE      = 'date';
    const FORM_STEP_SERVICES  = 'services';
    const FORM_STEP_SECONDARY = 'secondary';
    const FORM_STEP_ATTENDANT = 'attendant';

    /** @var SLN_Plugin */
    private $plugin;

    /** @var SLN_Shortcode_Salon */
    private $booking_form_handler;

    public function __construct(SLN_Plugin $plugin)
    {
	$this->plugin		    = $plugin;
	$this->booking_form_handler = new SLN_Shortcode_Salon($plugin, null);
    }

    public function execute() {

	if ( ! isset($_GET['sln_reschedule_booking']) ) {
	    return;
	}

	try {

	    $booking = $this->plugin->createBooking($_GET['booking_id']);
	    $bb	     = $this->plugin->getBookingBuilder();

	    $nextStep = $this->init_booking_form($booking, $bb);

	    wp_redirect(add_query_arg(
		array('sln_step_page' => $nextStep),
		get_permalink($this->plugin->getSettings()->getPayPageId() )
	    ));

	    exit();

	} catch (\Exception $ex) {

	}
    }

    protected function init_booking_form($booking, $bb) {

        $bb->clear();

	do_action('sln.reshedule-booking.pre-set-booking-form', $booking, $bb);

	$bb->save();

	$bookingServices = $this->get_services_attendants($booking);

	if ( ( $step = $this->set_booking_form_primary_services($bookingServices) ) ) {
	    return $step;
	}

	if ( ( $step = $this->set_booking_form_secondary_services($bookingServices) ) ) {
	    return $step;
	}

	if ( ( $step = $this->set_booking_form_attendants($bookingServices) ) ) {
	    return $step;
	}

	return static::FORM_STEP_DATE;
    }

    protected function get_services_attendants($booking) {

	$result	  = array();
	$services = $booking->getServicesMeta();

	foreach ($services as $s) {
	    $result[$s['service']] = $s['attendant'] ? $s['attendant'] : 0;
	}

	return $result;
    }

    protected function set_booking_form_primary_services($bookingServices) {

	$currentStep = static::FORM_STEP_SERVICES;

	$primaryServicesStepHandler = $this->booking_form_handler->getStepObject($currentStep);

	$_POST['submit_' . $currentStep] = 1;

	$_REQUEST['sln'] = array(
	    'services' => $bookingServices,
	);

	if ( ! $primaryServicesStepHandler->isValid() ) {

	    $this->addError(__("Can't set booking services. Please, select others.", 'salon-booking-system'));

	    return $currentStep;
	}

	if ( ! $this->plugin->getBookingBuilder()->getAttendantsIds() ) {

	    $this->addError(__('Not available set some booking services. Please, select others.', 'salon-booking-system'));

	    return $currentStep;
	}

	return null;
    }

    protected function set_booking_form_secondary_services($bookingServices) {

	$currentStep = static::FORM_STEP_SECONDARY;

	if ( ! in_array($currentStep, $this->booking_form_handler->getSteps()) ) {

	    if( array_diff(array_keys($bookingServices), array_keys($this->plugin->getBookingBuilder()->getAttendantsIds())) ) {

		$this->addError(__('Not available set some secondary booking services. Please, select others.', 'salon-booking-system'));

		return static::FORM_STEP_SERVICE;
	    }

	    return null;
	}

	$secondaryServicesStepHandler = $this->booking_form_handler->getStepObject($currentStep);

	$_POST['submit_' . $currentStep] = 1;

	$_POST['sln'] = array(
	    'services' => $bookingServices,
	);

	if ( ! $secondaryServicesStepHandler->isValid() ) {

	    $this->addError(__("Can't set secondary booking services. Please, select others.", 'salon-booking-system'));

	    return $currentStep;
	}

	if ( array_diff(array_keys($bookingServices), array_keys($this->plugin->getBookingBuilder()->getAttendantsIds())) ) {

	    $this->addError(__('Not available set some secondary booking services. Please, select others.', 'salon-booking-system'));

	    return $currentStep;
	}

	return null;
    }

    protected function set_booking_form_attendants($bookingServices) {

	$currentStep = static::FORM_STEP_ATTENDANT;
	$nextStep    = $currentStep;

	$attendantStepHandler = $this->booking_form_handler->getStepObject($currentStep);

	if ( ! in_array($currentStep, $this->booking_form_handler->getSteps()) ) {

	    if( ! $this->plugin->getSettings()->isAttendantsEnabled() ) {
		return null;
	    }

	    $nextStep = static::FORM_STEP_SERVICES;
	}

	$_POST['submit_' . $currentStep] = 1;

	$isMultipleAttSelection = $this->plugin->getSettings()->isMultipleAttendantsEnabled();

	if( $isMultipleAttSelection ) {
	    $_POST['sln'] = array(
		'attendants' => $bookingServices,
	    );
        } else {
	    $_POST['sln'] = array(
		'attendant' => current($bookingServices),
	    );
	}

	if ( ! $attendantStepHandler->isValid() ) {

	    $this->addError(__("Can't set attendants. Please, select others.", 'salon-booking-system'));

	    return $nextStep;
	}

	$services = $this->plugin->getBookingBuilder()->getAttendantsIds();

	foreach ($bookingServices as $sid => $attid) {
	    if ( ! isset($services[$sid]) || $services[$sid] != $attid ) {

		$this->addError(__('Not available set some attendants. Please, select others.', 'salon-booking-system'));

		return $nextStep;
	    }
	}

	return null;
    }

    public static function getErrors() {
	return isset( $_SESSION[static::SESSION_ERROR_KEY] ) ? $_SESSION[static::SESSION_ERROR_KEY] : array();
    }

    protected function addError($message) {

	if ( ! isset( $_SESSION[static::SESSION_ERROR_KEY] ) ) {
	    $_SESSION[static::SESSION_ERROR_KEY] = array();
	}

	$_SESSION[static::SESSION_ERROR_KEY][] = $message;
    }

    public static function clearErrors() {

	if ( isset( $_SESSION[static::SESSION_ERROR_KEY] ) ) {
	    unset( $_SESSION[static::SESSION_ERROR_KEY] );
	}
    }

}
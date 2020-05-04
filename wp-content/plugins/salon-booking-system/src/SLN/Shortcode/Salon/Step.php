<?php

abstract class SLN_Shortcode_Salon_Step
{
    private $plugin;
    private $attrs;
    private $step;
    private $shortcode;
    private $errors = array();
    private $additional_errors = array();

    function __construct(SLN_Plugin $plugin, SLN_Shortcode_Salon $shortcode, $step)
    {
        $this->plugin    = $plugin;
        $this->shortcode = $shortcode;
        $this->step      = $step;
    }

    public function isValid()
    {
        return (isset($_POST['submit_' . $this->getStep()]) || isset($_GET['submit_' . $this->getStep(
                )])) && $this->dispatchForm();
    }

    public function render()
    {
        return $this->getPlugin()->loadView('shortcode/salon_' . $this->getStep(), $this->getViewData());
    }

    protected function getViewData()
    {
        $step = $this->getStep();

	$rescheduledErrors = SLN_Action_RescheduleBooking::getErrors();

	SLN_Action_RescheduleBooking::clearErrors();

        return array(
            'formAction'        => add_query_arg(array('sln_step_page' => $this->shortcode->getCurrentStep())),
            'backUrl'           => add_query_arg(array('sln_step_page' => $this->shortcode->getPrevStep())),
            'submitName'        => 'submit_' . $step,
            'step'              => $this,
            'errors'            => $this->errors,
            'additional_errors' => array_merge($this->additional_errors, $rescheduledErrors),
            'settings'          => $this->plugin->getSettings(),
        );
    }

    protected function getStep()
    {
        return $this->step;
    }

    /** @return SLN_Plugin */
    protected function getPlugin()
    {
        return $this->plugin;
    }

    public function getShortcode()
    {
        return $this->shortcode;
    }

    abstract protected function dispatchForm();

    public function addError($err)
    {
        $this->errors[] = $err;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function hasErrors() {
        return !empty($this->errors);
    }

    public function addAdditionalError($err) {
        $this->additional_errors[] = $err;
    }

    public function getAddtitionalErrors() {
        return $this->additional_errors;
    }

    public function setAttendantsAuto() {

        if( ! $this->getPlugin()->getSettings()->isAttendantsEnabled() ) {
            return true;
        }

	$attendantsNeeds = false;

        $bb = $this->getPlugin()->getBookingBuilder();

        $booking_attendants = $bb->getAttendantsIds();
        foreach ($bb->getServices() as $service) {
            $sId = $service->getId();
            if ($service->isAttendantsEnabled() && (!isset($booking_attendants[$sId]) || empty($booking_attendants[$sId]))) {
                $attendantsNeeds = true;
            }
        }

	if ( ! $attendantsNeeds ) {
	    return true;
	}

        if ($this->getPlugin()->getSettings()->isMultipleAttendantsEnabled()) {

            $ids = array();

            foreach ($bb->getServicesIds() as $sId) {
                $ids[$sId] = '';
            }

            $_POST['sln']['attendants'] = $ids;
        } else {
            $_POST['sln']['attendant'] = '';
        }

        $_POST['submit_attendant'] = 'next';
        $_POST['attendant_auto'] = true;

        $attendantStep = new SLN_Shortcode_Salon_AttendantStep($this->plugin, $this->getShortcode(), 'attendant');

        if ($attendantStep->isValid()) {
            return true;
        }

        foreach ($attendantStep->getErrors() as $error) {
            $this->addAdditionalError($error);
        }

        return false;
    }

}

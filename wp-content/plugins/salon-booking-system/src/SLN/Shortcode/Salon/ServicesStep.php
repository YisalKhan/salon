<?php

class SLN_Shortcode_Salon_ServicesStep extends SLN_Shortcode_Salon_Step
{
    private $services;

    protected function dispatchForm()
    {
        $bb = $this->getPlugin()->getBookingBuilder();
        $values = isset($_REQUEST['sln']) && isset($_REQUEST['sln']['services']) && is_array($_REQUEST['sln']['services'])  ? $_REQUEST['sln']['services'] : array();
        foreach ($this->getServices() as $service) {
            if (isset($values) && isset($values[$service->getId()])) {
                $bb->addService($service);
            } else {
                $bb->removeService($service);
            }
        }
        $bb->save();
        if(isset($_GET['sln'])) {
            return false;
        } elseif (empty($values)) {
            $this->addError(__('You must choose at least one service', 'salon-booking-system'));

            return false;
        }

        return true;
    }

    /**
     * @return SLN_Wrapper_Service[]
     */
    public function getServices()
    {
        if (!isset($this->services)) {
            /** @var SLN_Repository_ServiceRepository $repo */
            $repo = $this->getPlugin()->getRepository(SLN_Plugin::POST_TYPE_SERVICE);

	    $services = $repo->getAllPrimary();

	    $services = array_filter($services, function ($service) {
		return !$service->isHideOnFrontend();
	    });

            $this->services = $repo->sortByExecAndTitleDESC($services);
            $this->services = apply_filters('sln.shortcode.salon.ServicesStep.getServices', $this->services);
        }

        return $this->services;
    }

}

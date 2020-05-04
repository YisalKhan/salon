<?php

namespace SLB_API\Controller;

use SLN_Plugin;
use WP_REST_Server;
use SLN_Action_Ajax_CheckServices;
use SLN_Action_Ajax_CheckServicesAlt;

class AvailabilityServices_Controller extends REST_Controller
{
    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'availability/services';

    public function register_routes() {

        register_rest_route( $this->namespace, '/' . $this->rest_base . '/primary', array(
            'args' => apply_filters('sln_api_availability_services_register_routes_get_args', array(
                'date'     => array(
                    'description'       => __('Date.', 'salon-booking-system'),
                    'type'              => 'string',
                    'format'            => 'YYYY-MM-DD',
                    'required'          => true,
                    'validate_callback' => array($this, 'rest_validate_request_arg'),
                ),
                'time'     => array(
                    'description'       => __('Time.', 'salon-booking-system'),
                    'type'              => 'string',
                    'format'            => 'HH:ii',
                    'required'          => true,
                    'validate_callback' => array($this, 'rest_validate_request_arg'),
                ),
                'primary_services' => array(
                    'description' => __('Selected primary services.', 'salon-booking-system'),
                    'type'        => 'array',
                    'required'    => true,
                    'default'     => array(),
                    'items'       => array(
                        'type' => 'integer',
                    ),
                ),
                'secondary_services' => array(
                    'description' => __('Selected secondary services.', 'salon-booking-system'),
                    'type'        => 'array',
                    'required'    => true,
                    'default'     => array(),
                    'items'       => array(
                        'type' => 'integer',
                    ),
                ),
            )),
            array(
                'methods'  => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_primary_services'),
            ),
        ) );

        register_rest_route( $this->namespace, '/' . $this->rest_base . '/secondary', array(
            'args' => apply_filters('sln_api_availability_services_register_routes_get_args', array(
                'date'     => array(
                    'description'       => __('Date.', 'salon-booking-system'),
                    'type'              => 'string',
                    'format'            => 'YYYY-MM-DD',
                    'required'          => true,
                    'validate_callback' => array($this, 'rest_validate_request_arg'),
                ),
                'time'     => array(
                    'description'       => __('Time.', 'salon-booking-system'),
                    'type'              => 'string',
                    'format'            => 'HH:ii',
                    'required'          => true,
                    'validate_callback' => array($this, 'rest_validate_request_arg'),
                ),
                'primary_services' => array(
                    'description' => __('Selected primary services.', 'salon-booking-system'),
                    'type'        => 'array',
                    'required'    => true,
                    'default'     => array(),
                    'items'       => array(
                        'type' => 'integer',
                    ),
                ),
                'secondary_services' => array(
                    'description' => __('Selected secondary services.', 'salon-booking-system'),
                    'type'        => 'array',
                    'required'    => true,
                    'default'     => array(),
                    'items'       => array(
                        'type' => 'integer',
                    ),
                ),
            )),
            array(
                'methods'  => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_secondary_services'),
            ),
        ) );

    }

    public function get_primary_services( $request )
    {
        $plugin = SLN_Plugin::getInstance();

        $primary = array();

        foreach ($request->get_param('primary_services') as $sId) {
            $primary[$sId] = 0;
        }

        $secondary  = array();

        foreach ($request->get_param('secondary_services') as $sId) {
            $secondary[$sId] = 0;
        }

        $plugin->getBookingBuilder()->setServicesAndAttendants($secondary);

        if ($plugin->getSettings()->isFormStepsAltOrder()) {
            $handler = new SLN_Action_Ajax_CheckServicesAlt($plugin);
        } else {
            $handler = new SLN_Action_Ajax_CheckServices($plugin);
        }

        $handler->setDate($request->get_param('date'));
        $handler->setTime($request->get_param('time'));

	$bb = $plugin->getBookingBuilder();

	do_action('sln_api_availability_services_before_check', $bb, $request);

	$bb->setDate($request->get_param('date'));
	$bb->setTime($request->get_param('time'));

        $handler->setBookingBuilder($bb);
        $handler->setAvailabilityHelper($plugin->getAvailabilityHelper());

        $services = $handler->initPrimaryServices($primary);

	$services = apply_filters('sln_api_availability_services_get_result_services', $services, $request);

        $result = array();

        foreach ($services as $serviceID => $s) {

            $service = SLN_Plugin::getInstance()->createService($serviceID);

            $result[] = array(
                'service_id'   => $service->getId(),
                'service_name' => $service->getName(),
                'available'    => SLN_Action_Ajax_CheckServices::STATUS_ERROR === $s['status'] ? false : true,
                'selected'     => SLN_Action_Ajax_CheckServices::STATUS_ERROR === $s['status'] ? false : ($s['status'] ? true : false),
                'error'        => $s['error'],
            );
        }

        return $this->success_response(array(
            'services' => $result,
        ));
    }

    public function get_secondary_services( $request )
    {
        $plugin = SLN_Plugin::getInstance();

        $primary = array();

        foreach ($request->get_param('primary_services') as $sId) {
            $primary[$sId] = 0;
        }

        $secondary  = array();

        foreach ($request->get_param('secondary_services') as $sId) {
            $secondary[$sId] = 0;
        }

        $plugin->getBookingBuilder()->setServicesAndAttendants($primary);

        if ($plugin->getSettings()->isFormStepsAltOrder()) {
            $handler = new SLN_Action_Ajax_CheckServicesAlt($plugin);
        } else {
            $handler = new SLN_Action_Ajax_CheckServices($plugin);
        }

        $handler->setDate($request->get_param('date'));
        $handler->setTime($request->get_param('time'));

	$bb = $plugin->getBookingBuilder();

	do_action('sln_api_availability_services_before_check', $bb, $request);

	$bb->setDate($request->get_param('date'));
	$bb->setTime($request->get_param('time'));

        $handler->setBookingBuilder($bb);
        $handler->setAvailabilityHelper($plugin->getAvailabilityHelper());

        $services = $handler->initSecondaryServices($secondary);

	$services = apply_filters('sln_api_availability_services_get_result_services', $services, $request);

        $result = array();

        foreach ($services as $serviceID => $s) {

            $service = SLN_Plugin::getInstance()->createService($serviceID);

            $result[] = array(
                'service_id'   => $service->getId(),
                'service_name' => $service->getName(),
                'available'    => SLN_Action_Ajax_CheckServices::STATUS_ERROR === $s['status'] ? false : true,
                'selected'     => SLN_Action_Ajax_CheckServices::STATUS_ERROR === $s['status'] ? false : ($s['status'] ? true : false),
                'error'        => $s['error'],
            );
        }

        return $this->success_response(array(
            'services' => $result,
        ));
    }

}
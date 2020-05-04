<?php

namespace SLB_API\Controller;

use SLN_Plugin;
use WP_REST_Server;
use SLN_Action_Ajax_CheckDate;
use SLN_Action_Ajax_CheckDateAlt;

class AvailabilityIntervals_Controller extends REST_Controller
{
    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'availability/intervals';

    public function register_routes() {

        register_rest_route( $this->namespace, '/' . $this->rest_base, array(
            'args' => apply_filters('sln_api_availability_intervals_register_routes_get_intervals_args', array(
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
                'services' => array(
                    'description'       => __('Booking services.', 'salon-booking-system'),
                    'type'              => 'array',
                    'required'          => true,
                    'default'           => array(),
                    'validate_callback' => array($this, 'rest_validate_request_arg'),
                    'items'             => array(
                        'type'       => 'object',
                        'required'   => array('service_id'),
                        'properties' => array(
                            'service_id' =>  array(
                                'type' => 'integer',
                            ),
                            'assistant_id' =>  array(
                                'type' => 'integer',
                            ),
                        ),
                    ),
                ),
            )),
            array(
                'methods'  => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'get_intervals'),
            ),
        ) );
    }

    public function get_intervals( $request )
    {
        $plugin = SLN_Plugin::getInstance();

        if ($plugin->getSettings()->isFormStepsAltOrder()) {

            $services = array();

            foreach ($request->get_param('services') as $s) {

                if (!isset($s['service_id'])) {
                    continue;
                }

                $services[$s['service_id']] = isset($s['assistant_id']) ? $s['assistant_id'] : 0;
            }

            $plugin->getBookingBuilder()->setServicesAndAttendants($services);

            $handler = new SLN_Action_Ajax_CheckDateAlt($plugin);
        } else {
            $handler = new SLN_Action_Ajax_CheckDate($plugin);
        }

	do_action('sln_api_availability_intervals_get_availability_intervals_before_check', $request);

        $handler->setDate($request->get_param('date'));
        $handler->setTime($request->get_param('time'));

        $handler->checkDateTime();

	$ret = array(
	    'success'	=> empty($handler->getErrors()) ? 1 : 0,
	    'errors'	=> $handler->getErrors(),
	    'intervals' => $handler->getIntervalsArray(),
	);

        return $this->success_response($ret);
    }


}
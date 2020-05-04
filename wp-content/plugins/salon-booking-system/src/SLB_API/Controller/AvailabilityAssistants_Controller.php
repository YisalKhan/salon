<?php

namespace SLB_API\Controller;

use SLN_Func;
use SLN_Plugin;
use SLN_DateTime;
use WP_REST_Server;
use SLN_Wrapper_Booking_Services;

class AvailabilityAssistants_Controller extends REST_Controller
{
    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'availability/assistants';

    public function register_routes() {

        register_rest_route( $this->namespace, '/' . $this->rest_base , array(
            'args' => apply_filters('sln_api_availability_assistants_register_routes_get_args', array(
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
                    'description' => __('Selected services.', 'salon-booking-system'),
                    'type'        => 'array',
                    'required'    => true,
                    'default'     => array(),
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
                'callback' => array($this, 'get_assistants'),
            ),
        ) );

    }

    public function get_assistants( $request )
    {
        $services = array();

        foreach ($request->get_param('services') as $s) {

            if (!isset($s['service_id'])) {
                continue;
            }

            $services[$s['service_id']] = isset($s['assistant_id']) ? $s['assistant_id'] : 0;
        }

	do_action('sln_api_availability_assistants_before_check', $request);

        $ret = $this->validate(
            $services,
            $this->get_date_time(
                $request->get_param('date'),
                $request->get_param('time')
            ),
            SLN_Plugin::getInstance()->getAvailabilityHelper(),
	    $request
        );

        return $this->success_response($ret);
    }

    public function validate($services, $date, $ah, $request)
    {
        $rservices  = array();
        $isValid    = true;

        $ah->setDate($date);

        $bookingServices = SLN_Wrapper_Booking_Services::build($services, $date);

        foreach ($bookingServices->getItems() as $bookingService) {

            $service   = $bookingService->getService();
            $serviceId = $service->getId();

            $rservice  = array(
                'service_id'   => $serviceId,
                'service_name' => $service->getName(),
                'assistants'   => array(),
            );

            if ( ! $service->isAttendantsEnabled() ) {
                $rservices[] = $rservice;
                continue;
            }

            $availAttsForService = $ah->getAvailableAttsIdsForBookingService($bookingService);

            $tmpAssistants       = array();
            $selectedAttendantId = $services[$serviceId];

            foreach ($availAttsForService as $attId) {

                $attendant = SLN_Plugin::getInstance()->createAttendant($attId);

                $tmpAssistants[$attId] = array(
                    'assistant_id'   => $attendant->getId(),
                    'assistant_name' => $attendant->getName(),
                    'available'      => true,
                    'selected'       => $attId === $selectedAttendantId,
                    'error'          => '',
                );
            }

            if ( $selectedAttendantId && ! in_array($selectedAttendantId, $availAttsForService) ) {

                $attendant = SLN_Plugin::getInstance()->createAttendant($selectedAttendantId);

                $tmpAssistants[$selectedAttendantId] = array(
                    'assistant_id'   => $attendant->getId(),
                    'assistant_name' => $attendant->getName(),
                    'available'      => false,
                    'selected'       => false,
                    'error'          => sprintf(
                        __("Attendant %s isn't available for %s service at %s", 'salon-booking-system'),
                        $attendant->getName(),
                        $service->getName(),
                        $ah->getDayBookings()->getTime(
                            $bookingService->getStartsAt()->format('H'),
                            $bookingService->getStartsAt()->format('i')
                        )
                    ),
                );

                $isValid = false;
            }

            $rservice['assistants'] = array_values($tmpAssistants);
            $rservices[]            = $rservice;
        }

	$rservices = apply_filters('sln_api_availability_assistants_get_result_services', $rservices, $request);

        return array(
            'is_valid' => $isValid,
            'services' => $rservices,
        );
    }

    protected function get_date_time($date, $time)
    {
        $ret  = new SLN_DateTime(
            SLN_Func::filter($date, 'date').' '.SLN_Func::filter($time, 'time'.':00')
        );

        return $ret;
    }

}
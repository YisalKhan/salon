<?php

namespace SLB_API\Controller;

use SLN_Func;
use SLN_Plugin;
use WP_REST_Server;
use SLN_Enum_DaysOfWeek;
use SLN_Action_Ajax_CheckDate;
use SLN_Action_Ajax_CheckServices;
use SLN_Action_Ajax_CheckAttendants;

class AvailabilityBooking_Controller extends REST_Controller
{
    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'availability/booking';

    public function register_routes() {

        register_rest_route( $this->namespace, '/' . $this->rest_base . '/date', array(
            'args' => apply_filters('sln_api_availability_booking_register_routes_get_availability_date_time_args', array(
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
            )),
            array(
                'methods'  => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_availability_date_time'),
            ),
        ) );

        register_rest_route( $this->namespace, '/' . $this->rest_base . '/services', array(
            'args' => apply_filters('sln_api_availability_booking_register_routes_get_availability_services_args', array(
                'booking_id'     => array(
                    'description'       => __('Booking id.', 'salon-booking-system'),
                    'type'              => 'integer',
                    'default'           => 0,
                    'validate_callback' => array($this, 'rest_validate_request_arg'),
                ),
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
                'is_all_services'	=> array(
                    'description'       => __('Is all services.', 'salon-booking-system'),
                    'type'              => 'boolean',
                    'default'           => false,
                    'validate_callback' => array($this, 'rest_validate_request_arg'),
                ),
                'services' => array(
                    'description'       => __('Booking services.', 'salon-booking-system'),
                    'type'              => 'array',
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
                'callback' => array($this, 'get_availability_services'),
            ),
        ) );

	register_rest_route( $this->namespace, '/' . $this->rest_base . '/assistants', array(
            'args' => apply_filters('sln_api_availability_booking_register_routes_get_availability_assistants_args',  array(
                'booking_id'     => array(
                    'description'       => __('Booking id.', 'salon-booking-system'),
                    'type'              => 'integer',
                    'default'           => 0,
                    'validate_callback' => array($this, 'rest_validate_request_arg'),
                ),
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
                'selected_service_id'	=> array(
                    'description'       => __('Selected service id.', 'salon-booking-system'),
                    'type'              => 'integer',
                    'required'          => true,
                    'validate_callback' => array($this, 'rest_validate_request_arg'),
                ),
                'services' => array(
                    'description'       => __('Booking services.', 'salon-booking-system'),
                    'type'              => 'array',
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
                'callback' => array($this, 'get_availability_assistants'),
            ),
        ) );
    }

    public function get_availability_date_time( $request )
    {
        $plugin = SLN_Plugin::getInstance();

	$handler = new SLN_Action_Ajax_CheckDate($plugin);

	do_action('sln_api_availability_booking_get_availability_date_time_before_check', $request);

        $handler->setDate($request->get_param('date'));
        $handler->setTime($request->get_param('time'));

        $handler->checkDateTime();

        $ret = array(
	    'success'	=> empty($handler->getErrors()) ? 1 : 0,
	    'errors'	=> array_map('strip_tags', $handler->getErrors()),
	    'intervals' => $handler->getIntervalsArray(),
	);

        return $this->success_response($ret);
    }

    public function get_availability_services( $request )
    {
        $plugin = SLN_Plugin::getInstance();

	$handler = new SLN_Action_Ajax_CheckServices($plugin);

	$handler->setDate($request->get_param('date'));
        $handler->setTime($request->get_param('time'));

	$bb = $plugin->getBookingBuilder();

	do_action('sln_api_availability_booking_get_availability_services_before_check', $bb, $request);

	$bb->setDate($request->get_param('date'));
	$bb->setTime($request->get_param('time'));

        $handler->setBookingBuilder($bb);
        $handler->setAvailabilityHelper($plugin->getAvailabilityHelper());

	$services     = array();
	$booking_data = array();

	foreach ($request->get_param('services') as $s) {

	    if ( ! isset( $s['service_id'] ) ) {
		continue;
	    }

	    $services[] = $s['service_id'];

	    if ( ! isset( $booking_data['attendants'] ) ) {
		$booking_data['attendants'] = array();
	    }

	    if ( ! isset( $booking_data['price'] ) ) {
		$booking_data['price'] = array();
	    }

	    if ( ! isset( $booking_data['duration'] ) ) {
		$booking_data['duration'] = array();
	    }

	    if ( ! isset( $booking_data['break_duration'] ) ) {
		$booking_data['break_duration'] = array();
	    }

	    $service = $plugin->createService($s['service_id']);

	    $booking_data['attendants'][$s['service_id']]	= isset($s['assistant_id']) ? $s['assistant_id'] : 0;
	    $booking_data['price'][$s['service_id']]		= $service->getPrice();
	    $booking_data['duration'][$s['service_id']]		= SLN_Func::getMinutesFromDuration($service->getDuration());
	    $booking_data['break_duration'][$s['service_id']]	= SLN_Func::getMinutesFromDuration($service->getBreakDuration());
	}

	$count = count($services);

	if ( $request->get_param('is_all_services') ) {

	    $services_repo = $plugin->getRepository(SLN_Plugin::POST_TYPE_SERVICE)->getIds();
            $services	   = array();

	    foreach ($services_repo as $service) {
                $services[intval($service)] = $service;
            }
	}

	$with_booking_limit = !$request->get_param('is_all_services');

	$ret = $handler->initAllServicesForAdmin($request->get_param('booking_id'), $services, $booking_data, $with_booking_limit, $count);

	$ret = apply_filters('sln_api_availability_booking_get_availability_services_ret', $ret, $bb);

        $result = array();

        foreach ($ret as $serviceID => $s) {

	    $service	= $plugin->createService($serviceID);
	    $available	= SLN_Action_Ajax_CheckServices::STATUS_ERROR === $s['status'] ? false : true;

            $result[] = $this->prepare_service_response_for_collection($service, $available);
        }

        return $this->success_response(array(
            'services' => $result,
        ));
    }

    protected function prepare_service_response_for_collection($service, $available)
    {
        $availabilities = array();

        foreach ($service->getAvailabilityItems()->toArray() as $availability) {

            $data = $availability->getData();

            if (!$data) {
                continue;
            }

            $avDays = array();

            foreach (SLN_Enum_DaysOfWeek::toArray() as $dayKey => $dayLabel) {
                $avDays[$dayKey] = isset($data['days'][$dayKey]) ? 1 : 0;
            }

            $availabilities[] = array(
                'days'      => $avDays,
                'from'      => $data['from'],
                'to'        => $data['to'],
                'always'    => $data['always'],
                'from_date' => $data['from_date'],
                'to_date'   => $data['to_date'],
            );
        }

        $categories = get_the_terms($service->getId(), SLN_Plugin::TAXONOMY_SERVICE_CATEGORY);

        if (is_wp_error($categories)) {
            throw new \Exception(__( 'Get categories error.', 'salon-booking-system' ));
        }

        $categories_ids = array();

        if (is_array($categories)) {
            foreach ($categories as $category) {
                $categories_ids[] = $category->term_id;
            }
        }

        $parent_services = $service->getMeta('secondary_parent_services');
        $parent_services = $parent_services ? $parent_services : array();

        return array(
            'id'                        => $service->getId(),
            'name'                      => $service->getName(),
            'available'                 => $available,
            'price'                     => $service->getPrice(),
            'currency'                  => SLN_Plugin::getInstance()->getSettings()->getCurrencySymbol(),
            'unit'                      => $service->getUnitPerHour(),
            'duration'                  => $service->getDuration()->format('H:i'),
            'exclusive'                 => $service->isExclusive() ? 1 : 0,
            'secondary'                 => $service->isSecondary() ? 1 : 0,
            'secondary_display_mode'    => $service->getMeta('secondary_display_mode'),
            'secondary_parent_services' => $parent_services,
            'execution_order'           => $service->getExecOrder(),
            'break'                     => $service->getBreakDuration()->format('H:i'),
            'empty_assistants'          => $service->isAttendantsEnabled() ? 0 : 1,
            'description'               => $service->getContent(),
            'categories'                => $categories_ids,
            'availabilities'            => $availabilities,
            'image_url'                 => (string) wp_get_attachment_url(get_post_thumbnail_id($service->getId())),
        );
    }

    public function get_availability_assistants( $request )
    {
        $plugin = SLN_Plugin::getInstance();

	$handler = new SLN_Action_Ajax_CheckAttendants($plugin);

        $handler->setDate($request->get_param('date'));
        $handler->setTime($request->get_param('time'));

	$bb = $plugin->getBookingBuilder();

	do_action('sln_api_availability_booking_get_availability_assistants_before_check', $bb, $request);

	$bb->setDate($request->get_param('date'));
	$bb->setTime($request->get_param('time'));

        $handler->setBookingBuilder($bb);
        $handler->setAvailabilityHelper($plugin->getAvailabilityHelper());

	$services     = array();
	$booking_data = array();

	foreach ($request->get_param('services') as $s) {

	    if ( ! isset( $s['service_id'] ) ) {
		continue;
	    }

	    $services[] = $s['service_id'];

	    if ( ! isset( $booking_data['attendants'] ) ) {
		$booking_data['attendants'] = array();
	    }

	    if ( ! isset( $booking_data['price'] ) ) {
		$booking_data['price'] = array();
	    }

	    if ( ! isset( $booking_data['duration'] ) ) {
		$booking_data['duration'] = array();
	    }

	    if ( ! isset( $booking_data['break_duration'] ) ) {
		$booking_data['break_duration'] = array();
	    }

	    $service = $plugin->createService($s['service_id']);

	    $booking_data['attendants'][$s['service_id']]	= isset($s['assistant_id']) ? $s['assistant_id'] : 0;
	    $booking_data['price'][$s['service_id']]		= $service->getPrice();
	    $booking_data['duration'][$s['service_id']]		= SLN_Func::getMinutesFromDuration($service->getDuration());
	    $booking_data['break_duration'][$s['service_id']]	= SLN_Func::getMinutesFromDuration($service->getBreakDuration());
	}

	$ret = $handler->initAllAttentansForAdmin($request->get_param('booking_id'), $services, $booking_data, $request->get_param('selected_service_id'));
	$ret = apply_filters('sln_api_availability_booking_get_availability_assistants_ret', $ret, $bb);

	$result = array();

        foreach ($ret as $assistantID => $a) {

	    $assistant = $plugin->createAttendant($assistantID);
	    $available = SLN_Action_Ajax_CheckServices::STATUS_ERROR === $a['status'] ? false : true;

            $result[] = $this->prepare_assistant_response_for_collection($assistant, $available);
        }

        return $this->success_response(array(
            'assistants' => $result,
        ));
    }

    protected function prepare_assistant_response_for_collection($attendant, $available)
    {
        $availabilities = array();

        foreach ($attendant->getAvailabilityItems()->toArray() as $availability) {

            $data = $availability->getData();

            if (!$data) {
                continue;
            }

            $avDays = array();

            foreach (SLN_Enum_DaysOfWeek::toArray() as $dayKey => $dayLabel) {
                $avDays[$dayKey] = isset($data['days'][$dayKey]) ? 1 : 0;
            }

            $availabilities[] = array(
                'days'      => $avDays,
                'from'      => $data['from'],
                'to'        => $data['to'],
                'always'    => $data['always'],
                'from_date' => $data['from_date'],
                'to_date'   => $data['to_date'],
            );
        }

        $holidays = array();

        foreach ($attendant->getHolidayItems()->toArray() as $holiday) {

            $data = $holiday->getData();

            if (!$data) {
                continue;
            }

            $holidays[] = array(
                'from_date' => $data['from_date'],
                'to_date'   => $data['to_date'],
                'from_time' => $data['from_time'],
                'to_time'   => $data['to_time'],
            );
        }

        return array(
            'id'             => $attendant->getId(),
            'name'           => $attendant->getName(),
            'available'	     => $available,
            'services'       => $attendant->getServicesIds(),
            'email'          => $attendant->getEmail(),
            'phone'          => $attendant->getPhone(),
            'description'    => $attendant->getContent(),
            'availabilities' => $availabilities,
            'holidays'       => $holidays,
            'image_url'      => (string) wp_get_attachment_url(get_post_thumbnail_id($attendant->getId())),
        );
    }

}
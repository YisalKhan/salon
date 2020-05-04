<?php

namespace SLB_API\Controller;

use WP_REST_Server;
use WP_Error;
use SLN_Plugin;
use WP_Query;
use WP_User;
use SLN_Enum_BookingStatus;
use SLN_Wrapper_Booking_Builder;

class Bookings_Controller extends REST_Controller
{
    const POST_TYPE = SLN_Plugin::POST_TYPE_BOOKING;

    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'bookings';
    protected $booking_id;
    protected $customer_id;
    protected $request;

    public function register_routes() {

        register_rest_route( $this->namespace, '/' . $this->rest_base, array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array( $this, 'get_items' ),
                'permission_callback' => array( $this, 'get_items_permissions_check' ),
                'args'		      => apply_filters('sln_api_bookings_register_routes_get_items_args', array(
                    'search' => array(
                        'description' => __( 'Search string.', 'salon-booking-system' ),
                        'type'        => 'string',
                        'default'     => '',
                    ),
                    'services' => array(
                        'description' => __( 'Services ids.', 'salon-booking-system' ),
                        'type'        => 'array',
                        'items'       => array(
                            'type' => 'integer',
                        ),
                    ),
                    'customers' => array(
                        'description' => __( 'Customers ids.', 'salon-booking-system' ),
                        'type'        => 'array',
                        'items'       => array(
                            'type' => 'integer',
                        ),
                    ),
                    'start_date' => array(
                        'description'       => __('Start date.', 'salon-booking-system'),
                        'type'              => 'string',
                        'format'            => 'YYYY-MM-DD',
                        'validate_callback' => array($this, 'rest_validate_request_arg'),
                    ),
                    'end_date' => array(
                        'description'       => __('End date.', 'salon-booking-system'),
                        'type'              => 'string',
                        'format'            => 'YYYY-MM-DD',
                        'validate_callback' => array($this, 'rest_validate_request_arg'),
                    ),
                    'order'      => array(
                        'description' => __('Order.', 'salon-booking-system'),
                        'type'        => 'string',
                        'enum'        => array('asc', 'desc'),
                        'default'     => 'asc',
                    ),
                    'orderby'      => array(
                        'description' => __('Order by.', 'salon-booking-system'),
                        'type'        => 'string',
                        'enum'        => array('id', 'date_time'),
                        'default'     => 'id',
                    ),
                    'per_page'      => array(
                        'description' => __('Per page.', 'salon-booking-system'),
                        'type'        => 'integer',
                        'default'     => 10,
                    ),
                    'page'      => array(
                        'description' => __('Page.', 'salon-booking-system'),
                        'type'        => 'integer',
                        'default'     => 1,
                    ),
                    'offset'      => array(
                        'description' => __('Offset.', 'salon-booking-system'),
                        'type'        => 'integer',
                    ),
                )),
            ),
            array(
                'methods'   => WP_REST_Server::CREATABLE,
                'callback'  => array( $this, 'create_item' ),
                'args'	    => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
            ),
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );

        register_rest_route( $this->namespace, '/' . $this->rest_base . '/upcoming', array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array( $this, 'get_upcoming_items' ),
                'permission_callback' => array( $this, 'get_items_permissions_check' ),
                'args' => array(
                    'hours' => array(
                        'description'       => __('Hours.', 'salon-booking-system'),
                        'type'              => 'integer',
                        'validate_callback' => array($this, 'rest_validate_request_arg'),
			'required'          => true,
                    ),
                ),
            ),
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );

        register_rest_route( $this->namespace, '/' . $this->rest_base . '/stats', array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array( $this, 'get_stats' ),
                'permission_callback' => array( $this, 'get_items_permissions_check' ),
                'args' => apply_filters('sln_api_bookings_register_routes_get_stats_args', array(
                    'start_date' => array(
                        'description'       => __('Start date.', 'salon-booking-system'),
                        'type'              => 'string',
                        'format'            => 'YYYY-MM-DD',
                        'required'          => true,
                        'validate_callback' => array($this, 'rest_validate_request_arg'),
                        'default'           => current_time('Y-01-01'),
                    ),
                    'end_date' => array(
                        'description'       => __('End date.', 'salon-booking-system'),
                        'type'              => 'string',
                        'format'            => 'YYYY-MM-DD',
                        'required'          => true,
                        'validate_callback' => array($this, 'rest_validate_request_arg'),
                        'default'           => current_time('Y-12-31'),
                    ),
                    'group_by' => array(
                        'description' => __( 'Group by.', 'salon-booking-system' ),
                        'type'        => 'string',
                        'enum'        => array('day', 'month', 'year'),
                        'required'    => true,
                        'default'     => 'month',
                    ),
                )),
            ),
        ) );

        register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)', array(
            'args' => array(
                'id' => array(
                    'description' => __( 'Unique identifier for the resource.', 'salon-booking-system' ),
                    'type'        => 'integer',
                    'required'    => true,
                ),
            ),
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array( $this, 'get_item' ),
                'permission_callback' => array( $this, 'get_item_permissions_check' ),
                'args'                => array(
                    'context' => $this->get_context_param( array( 'default' => 'view' ) ),
                ),
            ),
            array(
                'methods'             => WP_REST_Server::EDITABLE,
                'callback'            => array( $this, 'update_item' ),
                'permission_callback' => array( $this, 'update_item_permissions_check' ),
                'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
            ),
            array(
                'methods'             => WP_REST_Server::DELETABLE,
                'callback'            => array( $this, 'delete_item' ),
                'permission_callback' => array( $this, 'delete_item_permissions_check' ),
            ),
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );
    }

    public function get_stats( $request )
    {
        global $wpdb;

        $formats = array(
            'day'   => '%e',
            'month' => '%c',
            'year'  => '%Y',
        );

        $periods = array(
            'day'   => array(
                'interval' => '1D',
                'format'   => 'j',
            ),
            'month'   => array(
                'interval' => '1M',
                'format'   => 'n',
            ),
            'year'   => array(
                'interval' => '1Y',
                'format'   => 'Y',
            ),
        );

        $p = $periods[$request->get_param('group_by')];

        $datePeriod = new \DatePeriod(
            new \DateTime($request->get_param('start_date')),
            new \DateInterval('P'.$p['interval']),
            (new \DateTime($request->get_param('end_date')))->modify('+1 day')
        );

        $stats = array();

        foreach ($datePeriod as $date) {
            $stats[$date->format($p['format'])] = array(
                'unit_type'      => $request->get_param('group_by'),
                'unit_value'     => $date->format($p['format']),
                'bookings_count' => 0,
            );
        }

        $format = $formats[$request->get_param('group_by')];

	$sql_joins = "INNER JOIN {$wpdb->prefix}postmeta pm ON p.id = pm.post_id
		    AND pm.meta_key = '_sln_booking_date'
		    AND DATE(pm.meta_value) >= '".(new \SLN_DateTime($request->get_param('start_date')))->format('Y-m-d')."'
		    AND DATE(pm.meta_value) <= '".(new \SLN_DateTime($request->get_param('end_date')))->format('Y-m-d')."'";


	$sql_joins = apply_filters('sln_api_bookings_get_stats_sql_joins', $sql_joins, $request);

        $results = $wpdb->get_results("
            SELECT
                COUNT(DISTINCT p.ID) as bookings_count,
                DATE_FORMAT(pm.meta_value, '".$format."') as unit_value
            FROM {$wpdb->prefix}posts p
            {$sql_joins}
            WHERE
                p.post_type = '".self::POST_TYPE."'
	    AND
		p.post_status <> 'trash'
            GROUP BY
                DATE_FORMAT(pm.meta_value, '".$format."')",
            OBJECT
        );

        foreach ($results as $result) {
            $stats[$result->unit_value] = array(
                'unit_type'      => $request->get_param('group_by'),
                'unit_value'     => $result->unit_value,
                'bookings_count' => (int)$result->bookings_count,
            );
        }

        return $this->success_response(array('items' => array_values($stats)));
    }

    public function get_items( $request )
    {
        $prepared_args          = array();
        $prepared_args['order'] = $request->get_param('order');

        $prepared_args['posts_per_page'] = $request->get_param('per_page');

        $request['orderby'] = $request->get_param('orderby');
        $request['page']    = $request->get_param('page');

        if ( ! empty( $request['offset'] ) ) {
            $prepared_args['offset'] = $request['offset'];
        } else {
            $prepared_args['offset'] = ( $request['page'] - 1 ) * $prepared_args['posts_per_page'];
        }

        $orderby_possibles = array(
            'id'        => array('orderby' => 'ID'),
            'date_time' => array(
		'meta_query' => array(
		    'booking_date' => array(
			'key'	  => '_sln_booking_date',
			'type'    => 'DATE',
			'compare' => 'EXISTS',
		    ),
		    'booking_time' => array(
			'key'	  => '_sln_booking_time',
			'type'    => 'TIME',
			'compare' => 'EXISTS',
		    ),
		),
		'orderby' => 'booking_date booking_time',
            ),
        );

        $prepared_args = array_merge($prepared_args, $orderby_possibles[ $request['orderby'] ]);
        $prepared_args['post_type'] = self::POST_TYPE;

        if ($request->get_param('start_date')) {

            if ( ! isset( $prepared_args['meta_query'] ) ) {
                $prepared_args['meta_query'] = array();
            }

	    $_start_date = $request->get_param('start_date');

	    $_meta = array();

            $_meta[] = array(
                'key'     => '_sln_booking_date',
                'value'   => $_start_date,
                'compare' => '>=',
                'type'    => 'DATE',
            );

	    $prepared_args['meta_query'][] = $_meta;
        }

        if ($request->get_param('end_date')) {

            if ( ! isset( $prepared_args['meta_query'] ) ) {
                $prepared_args['meta_query'] = array();
            }

	    $_end_date = $request->get_param('end_date');

	    $_meta = array();

            $_meta[] = array(
                'key'     => '_sln_booking_date',
                'value'   => $_end_date,
                'compare' => '<=',
                'type'    => 'DATE',
            );

	    $prepared_args['meta_query'][] = $_meta;
        }

        if ($request->get_param('customers')) {
            $prepared_args['author__in'] = $request->get_param('customers');
        }

        if ($request->get_param('services')) {

            if ( ! isset( $prepared_args['meta_query'] ) ) {
                $prepared_args['meta_query'] = array();
            }

            $prepared_args['meta_query'][] = array(
                'key'     => '_sln_booking_services',
                'value'   => implode('|', array_map(function ($v) {
                    return sprintf('\"service\"\;\i\:%s\;', $v);
                }, $request->get_param('services'))),
                'compare' => 'REGEXP',
            );
        }

        $s = $request->get_param('search');

        if ($s !== '') {

            if ( ! isset( $prepared_args['meta_query'] ) ) {
                $prepared_args['meta_query'] = array();
            }

            $prepared_args['meta_query'][] = array(
                'relation' => 'OR',
                array(
                    'key'     => '_sln_booking_firstname',
                    'value'   => $s,
                    'compare' => 'LIKE',
                ),
                array(
                    'key'     => '_sln_booking_lastname',
                    'value'   => $s,
                    'compare' => 'LIKE',
                ),
                array(
                    'key'     => '_sln_booking_email',
                    'value'   => $s,
                    'compare' => 'LIKE',
                ),
                array(
                    'key'     => '_sln_booking_phone',
                    'value'   => $s,
                    'compare' => 'LIKE',
                ),
            );
        }

        $bookings = array();

	$prepared_args = apply_filters('sln_api_bookings_get_items_prepared_args', $prepared_args, $request);

        $query = new WP_Query( $prepared_args );

        try {
            foreach ( $query->posts as $booking ) {
                $data        = $this->prepare_item_for_response( $booking, $request );
                $bookings[]  = $this->prepare_response_for_collection( $data );
            }
        } catch (\Exception $ex) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, resource list error ('.$ex->getMessage().').', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        $response = $this->success_response(array('items' => $bookings));

        // Store pagination values for headers then unset for count query.
        $per_page = (int) $prepared_args['posts_per_page'];
        $page     = ceil( ( ( (int) $prepared_args['offset'] ) / $per_page ) + 1 );

	$prepared_args['fields'] = 'ID';

        $total = $query->found_posts;

        if ( $total < 1 ) {
            // Out-of-bounds, run the query again without LIMIT for total count.
            unset( $prepared_args['posts_per_page'] );
            unset( $prepared_args['offset'] );
            $count_query = new WP_Query( $prepared_args );
            $total = $count_query->found_posts;
        }

        $response->header( 'X-WP-Total', (int) $total );

        $max_pages = ceil( $total / $per_page );

        $response->header( 'X-WP-TotalPages', (int) $max_pages );

        $base = add_query_arg( $request->get_query_params(), rest_url( sprintf( '/%s/%s', $this->namespace, $this->rest_base ) ) );

        if ( $page > 1 ) {
            $prev_page = $page - 1;
            if ( $prev_page > $max_pages ) {
                $prev_page = $max_pages;
            }
            $prev_link = add_query_arg( 'page', $prev_page, $base );
            $response->link_header( 'prev', $prev_link );
        }

        if ( $max_pages > $page ) {
            $next_page = $page + 1;
            $next_link = add_query_arg( 'page', $next_page, $base );
            $response->link_header( 'next', $next_link );
        }

        return $response;
    }

    public function get_upcoming_items( $request )
    {
	$current_datetime = \SLN_TimeFunc::currentDateTime();

	$from_date = $current_datetime->format('Y-m-d');
	$from_time = $current_datetime->format('H:i:s');

	$current_datetime = $current_datetime->add(new \DateInterval('PT'.((int)($request['hours'] * 3600)).'S'));

	$to_date = $current_datetime->format('Y-m-d');
	$to_time = $current_datetime->format('H:i:s');

        $prepared_args = array(
	    'orderby'	    => 'ID',
	    'order'	    => 'asc',
	    'post_type'	    => self::POST_TYPE,
	    'post_status'   => array(
		SLN_Enum_BookingStatus::PAID,
		SLN_Enum_BookingStatus::PAY_LATER,
		SLN_Enum_BookingStatus::CONFIRMED,
	    ),
	);

	$prepared_args1 = array_merge($prepared_args, array(
	    'meta_query' => array(
		array(
		    array(
			'key'     => '_sln_booking_date',
			'value'   => $from_date,
			'compare' => '=',
			'type'    => 'DATE',
		    ),
		    array(
			'key'     => '_sln_booking_time',
			'value'   => $from_time,
			'compare' => '>=',
			'type'    => 'TIME',
		    ),
		),
	    ),
	));

	$prepared_args2 = array_merge($prepared_args, array(
	    'meta_query' => array(
		array(
		    array(
			'key'     => '_sln_booking_date',
			'value'   => $from_date,
			'compare' => '>',
			'type'    => 'DATE',
		    ),
		    array(
			'key'     => '_sln_booking_date',
			'value'   => $to_date,
			'compare' => '<',
			'type'    => 'DATE',
		    ),
		),
	    ),
	));

	$prepared_args3 = array_merge($prepared_args, array(
	    'meta_query' => array(
		array(
		    array(
			'key'     => '_sln_booking_date',
			'value'   => $to_date,
			'compare' => '=',
			'type'    => 'DATE',
		    ),
		    array(
			'key'     => '_sln_booking_time',
			'value'   => $to_time,
			'compare' => '<=',
			'type'    => 'TIME',
		    ),
		),
	    ),
	));

        $bookings = array();

        $query1 = new WP_Query( $prepared_args1 );
        $query2 = new WP_Query( $prepared_args2 );
        $query3 = new WP_Query( $prepared_args3 );

        try {

	    $posts = array_merge($query1->posts, $query2->posts, $query3->posts);

            foreach ( $posts as $booking ) {
                $data        = $this->prepare_item_for_response( $booking, $request );
                $bookings[]  = $this->prepare_response_for_collection( $data );
            }
        } catch (\Exception $ex) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, resource list error ('.$ex->getMessage().').', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        $response = $this->success_response(array('items' => $bookings));

        return $response;
    }

    public function prepare_item_for_response( $booking, $request )
    {
        return SLN_Plugin::getInstance()->createBooking($booking);
    }

    public function prepare_response_for_collection($booking)
    {
        $tmp_services = $booking->getBookingServices();
        $tmp_services = $tmp_services ? $tmp_services->getItems() : array();
        $services     = array();

        foreach ($tmp_services as $service) {
            $services[] = array(
                'start_at'       => $service->getStartsAt()->format('H:i'),
                'end_at'         => $service->getEndsAt()->format('H:i'),
                'service_id'     => $service->getService() ? $service->getService()->getId() : null,
                'service_name'   => $service->getService() ? $service->getService()->getName() : null,
                'service_price'  => $service->getService() ? $service->getService()->getPrice() : null,
                'assistant_id'   => $service->getAttendant() ? $service->getAttendant()->getId() : null,
                'assistant_name' => $service->getAttendant() ? $service->getAttendant()->getName() : null,
            );
        }

        $response = array(
            'id'                  => $booking->getId(),
            'created'             => $booking->getPostDate()->getTimestamp(),
            'date'                => $booking->getDate()->format('Y-m-d'),
            'time'                => $booking->getTime()->format('H:i'),
            'status'              => $booking->getStatus(),
            'customer_id'         => $booking->getCustomer() ? $booking->getCustomer()->getId() : $booking->getCustomer(),
            'customer_first_name' => $booking->getFirstname(),
            'customer_last_name'  => $booking->getLastname(),
            'customer_email'      => $booking->getEmail(),
            'customer_phone'      => $booking->getPhone(),
            'customer_address'    => $booking->getAddress(),
            'services'            => $services,
            'discounts'           => $booking->getMeta('discounts') ? $booking->getMeta('discounts') : array(),
            'duration'            => $booking->getDuration()->format('H:i'),
            'amount'              => $booking->getAmount(),
            'deposit'             => $booking->getDeposit(),
            'currency'            => SLN_Plugin::getInstance()->getSettings()->getCurrencySymbol(),
            'transaction_id'      => $booking->getTransactionId(),
            'note'                => $booking->getNote(),
        );

	return apply_filters('sln_api_bookings_prepare_response_for_collection', $response, $booking);
    }

    public function create_item( $request )
    {
        if ($request->get_param('id')) {

            $query = new WP_Query(array(
                'post_type' => self::POST_TYPE,
                'p'         => $request->get_param('id'),
            ));

            if ( $query->posts ) {
                return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, resource already exists.', 'salon-booking-system' ), array( 'status' => 409 ) );
            }
        }

        try {

	    $customer_id = $request->get_param('customer_id');

	    if ( ! $customer_id ) {
		$customer_id = $this->create_new_customer($request);
	    }

            $customer_data  = $this->get_customer_data_by_id($customer_id);

            $cloned_request = clone $request;
            $cloned_request->set_default_params(array_merge($cloned_request->get_default_params(), $customer_data));

            $data = $this->create_item_post($cloned_request, $customer_id);

        } catch (\Exception $ex) {
            return new WP_Error( 'salon_rest_cannot_create', $ex->getMessage(), array( 'status' => $ex->getCode() ? $ex->getCode() : 404 ) );
        }

        $response = $this->success_response(array(
	    'id'	  => $data['id'],
	    'customer_id' => $data['customer_id']
	));

        $response->set_status(201);

        return $response;
    }

    protected function get_customer_data_by_id($customer_id)
    {
        $user = new WP_User($customer_id);

        if( ! $user->ID ) {
	    throw new \Exception(__( "Customer doesn't exists.", 'salon-booking-system' ), 500);
        }

        return array(
            'customer_first_name' => $user->user_firstname,
            'customer_last_name'  => $user->user_lastname,
            'customer_email'      => $user->user_email,
            'customer_phone'      => get_user_meta($user->ID, '_sln_phone', true),
            'customer_address'    => get_user_meta($user->ID, '_sln_address', true),
        );
    }

    protected function create_new_customer($request)
    {
	$email = $request->get_param('customer_email');

	if ( ! $email ) {
	    throw new \Exception(__( 'Customer email empty.', 'salon-booking-system' ));
	}

	$user = get_user_by('email', $email);

	if ( $user ) {
	    return $user->ID;
	}

        $id = wp_create_user($email, wp_generate_password(), $email);

	if ( is_wp_error($id) ) {
	    throw new \Exception(__( 'Create new customer error.', 'salon-booking-system' ));
	}

        $id = wp_update_user(array(
            'ID'         => $id,
            'user_email' => $email,
            'first_name' => $request->get_param('customer_first_name'),
            'last_name'  => $request->get_param('customer_last_name'),
            'role'       => SLN_Plugin::USER_ROLE_CUSTOMER,
        ));

        if ( is_wp_error($id) ) {
            throw new \Exception(__( 'Save new customer error.', 'salon-booking-system' ));
        }

        $meta = array(
            '_sln_phone'    => $request->get_param('customer_phone'),
            '_sln_address'  => $request->get_param('customer_address'),
        );

        foreach ($meta as $key => $value) {
            update_user_meta($id, $key, $value);
        }

        return $id;
    }

    public function get_item( $request )
    {
        $query = new WP_Query(array(
            'post_type' => self::POST_TYPE,
            'p'         => $request->get_param('id'),
        ));

        if ( ! $query->posts ) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, resource not found.', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        try {
            $booking = $this->prepare_item_for_response(current($query->posts), $request);
            $booking = $this->prepare_response_for_collection($booking);
        } catch (\Exception $ex) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, get resource error ('.$ex->getMessage().').', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        return $this->success_response(array('items' => array($booking)));
    }

    public function update_item( $request )
    {
	$booking_id = $request->get_url_params()['id'];

        $query = new WP_Query(array(
            'post_type' => self::POST_TYPE,
            'p'         => $booking_id,
        ));

        if ( ! $query->posts ) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, resource not found.', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        try {
            $booking = $this->prepare_item_for_response(current($query->posts), $request);
            $booking = $this->prepare_response_for_collection($booking);
        } catch (\Exception $ex) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, get resource error ('.$ex->getMessage().').', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        try {

            $cloned_request = clone $request;

	    $cloned_request->set_default_params($booking);

	    $customer_id = $request->get_param('customer_id');

	    if ( ! $customer_id  && $request->get_param('customer_email') ) {
		$customer_id = $this->create_new_customer($request);
	    }

            if ( $customer_id && $booking['customer_id'] != $customer_id ) {
                $customer_data  = $this->get_customer_data_by_id($customer_id);
                $cloned_request->set_default_params(array_merge($cloned_request->get_default_params(), $customer_data));
            }

	    if ( ! $customer_id ) {
		$customer_id = $booking['customer_id'];
	    }

            $data = $this->update_item_post($cloned_request, $booking_id, $customer_id);

        } catch (\Exception $ex) {
            return new WP_Error( 'salon_rest_cannot_update', $ex->getMessage(), array( 'status' => 404 ) );
        }

	return $this->success_response(array(
	    'id'	  => $data['id'],
	    'customer_id' => $data['customer_id']
	));
    }

    public function delete_item( $request )
    {
        $query = new WP_Query(array(
            'post_type' => self::POST_TYPE,
            'p'         => $request->get_param('id'),
        ));

        if ( ! $query->posts ) {
            return new WP_Error( 'salon_rest_cannot_delete', __( 'Sorry, resource not found.', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        wp_delete_post($request->get_param('id'), false);

        return $this->success_response();
    }

    protected function create_item_post($request, $customer_id)
    {
	$bb = new SLN_Wrapper_Booking_Builder(SLN_Plugin::getInstance());

        $bb->setDate($request->get_param('date'));
        $bb->setTime($request->get_param('time'));

        $bb->set('firstname', $request->get_param('customer_first_name'));
        $bb->set('lastname', $request->get_param('customer_last_name'));
        $bb->set('email', $request->get_param('customer_email'));
        $bb->set('phone', $request->get_param('customer_phone'));
        $bb->set('address', $request->get_param('customer_address'));
        $bb->set('discounts', $request->get_param('discounts'));
        $bb->set('note', $request->get_param('note'));

        $services = array();

        foreach (array_filter($request->get_param('services')) as $service) {
            $services[] = array(
                'attendant' => isset($service['assistant_id']) ? $service['assistant_id'] : '',
                'service'   => isset($service['service_id']) ? $service['service_id'] : '',
            );
        }

        $bb->set('services', $services);

	do_action('sln_api_bookings_create_item_post_before_valid', $bb, $request);

	$this->request	   = $request;
	$this->customer_id = $customer_id;

	add_filter( 'sln.booking_builder.getCreateStatus', array( $this, 'get_booking_create_status' ));

	add_filter( 'sln.booking_builder.create.getPostArgs', array( $this, 'get_booking_create_get_post_args' ));

        $bb->create();

	remove_filter( 'sln.booking_builder.getCreateStatus', array( $this, 'get_booking_create_status' ));

	remove_filter( 'sln.booking_builder.create.getPostArgs', array( $this, 'get_booking_create_get_post_args' ));

	$booking = $bb->getLastBooking();

        return array(
	    'id'	  => $booking->getId(),
	    'customer_id' => $booking->getUserId(),
	);
    }

    protected function update_item_post($request, $id, $customer_id)
    {
        $bb = new SLN_Wrapper_Booking_Builder(SLN_Plugin::getInstance());

        $bb->setDate($request->get_param('date'));
        $bb->setTime($request->get_param('time'));

        $services = array();

        foreach (array_filter($request->get_param('services')) as $service) {
            $services[] = array(
                'attendant' => isset($service['assistant_id']) ? $service['assistant_id'] : '',
                'service'   => isset($service['service_id']) ? $service['service_id'] : '',
            );
        }

        $bb->set('services', $services);

	do_action('sln_api_bookings_update_item_post_before_valid', $bb, $request);

        $this->booking_id = $id;

        add_filter( 'sln.repository.booking.processCriteria', array( $this, 'process_bookings_criteria' ));

        remove_filter( 'sln.repository.booking.processCriteria', array( $this, 'process_bookings_criteria' ));

        $name      = $request->get_param('customer_first_name').' '.$request->get_param('customer_last_name');
        $datetime  = SLN_Plugin::getInstance()->format()->datetime($bb->getDateTime());

	$args = array(
            'ID'          => $id,
            'post_title'  => $name.' - '.$datetime,
            'post_type'   => self::POST_TYPE,
            'post_author' => $customer_id,
            'meta_input'  => array(
                '_sln_booking_date'      => $request->get_param('date'),
                '_sln_booking_time'      => $request->get_param('time'),
                '_sln_booking_firstname' => $request->get_param('customer_first_name'),
                '_sln_booking_lastname'  => $request->get_param('customer_last_name'),
                '_sln_booking_email'     => $request->get_param('customer_email'),
                '_sln_booking_phone'     => $request->get_param('customer_phone'),
                '_sln_booking_address'   => $request->get_param('customer_address'),
                '_sln_booking_services'  => $bb->getBookingServices()->toArrayRecursive(),
                '_sln_booking_discounts' => $request->get_param('discounts'),
                '_sln_booking_note'      => $request->get_param('note'),
            ),
        );

        $id = wp_update_post($args);

        if ( is_wp_error($id) ) {
            throw new \Exception(__( 'Save post error.', 'salon-booking-system' ));
        }

        $booking = $this->prepare_item_for_response($id, $request);

        $booking->evalBookingServices();
        $booking->evalTotal();
        $booking->evalDuration();
        $booking->setStatus($request->get_param('status'));

	do_action('sln_api_bookings_update_item_post', $id, $bb);

        return array(
	    'id'	  => $booking->getId(),
	    'customer_id' => $booking->getUserId(),
	);
    }


    public function process_bookings_criteria($criteria)
    {
        if ( ! isset($criteria['@wp_query']) ) {
            $criteria['@wp_query'] = array();
        }

        $criteria['@wp_query']['post__not_in'] = array($this->booking_id);

        return $criteria;
    }

    public function get_booking_create_status($status)
    {
	return $this->request->get_param('status') ? $this->request->get_param('status') : $status;
    }

    public function get_booking_create_get_post_args($args)
    {
	$args['post_author'] = $this->customer_id;

	return $args;
    }

    public function get_item_schema()
    {
        $schema = array(
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'title'      => 'service',
            'type'       => 'object',
            'properties' => array(
                'id' => array(
                    'description' => __( 'Unique identifier for the resource.', 'salon-booking-system' ),
                    'type'        => 'integer',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'readonly'    => true,
                    ),
                ),
                'created' => array(
                    'description' => __( 'Created timestamp for the resource.', 'salon-booking-system' ),
                    'type'        => 'integer',
                    'context'     => array( 'view' ),
                    'arg_options' => array(
                        'readonly' => true,
                    ),
                ),
                'date' => array(
                    'description' => __( 'The date for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'format'      => 'YYYY-MM-DD',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'required'          => true,
                        'validate_callback' => array($this, 'rest_validate_request_arg'),
                        'default'           => current_time('Y-m-d'),
                    ),
                ),
                'time' => array(
                    'description' => __( 'The time for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'format'      => 'HH:ii',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'required'          => true,
                        'validate_callback' => array($this, 'rest_validate_request_arg'),
                        'default'           => current_time('H:i'),
                    ),
                ),
                'status' => array(
                    'description' => __( 'The status for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'enum'        => array(
                        'sln-b-pendingpayment', 'sln-b-pending', 'sln-b-paid', 'sln-b-paylater', 'sln-b-canceled', 'sln-b-confirmed', 'sln-b-error'
                    ),
                    'arg_options' => array(
                        'required'  => true,
                    ),
                ),
                'customer_id' => array(
                    'description' => __( 'The customer id for the resource.', 'salon-booking-system' ),
                    'type'        => 'integer',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'default' => 0,
                    ),
                ),
                'customer_first_name' => array(
                    'description' => __( 'The customer first name for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'sanitize_callback' => 'sanitize_text_field',
                        'default'           => '',
                    ),
                ),
                'customer_last_name' => array(
                    'description' => __( 'The customer last name for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'sanitize_callback' => 'sanitize_text_field',
                        'default'           => '',
                    ),
                ),
                'customer_email' => array(
                    'description' => __( 'The customer email for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'format'      => 'email',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'sanitize_callback' => 'sanitize_text_field',
                        'default'           => '',
                    ),
                ),
                'customer_phone' => array(
                    'description' => __( 'The customer phone for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'sanitize_callback' => 'sanitize_text_field',
                        'default'           => '',
                    ),
                ),
                'customer_address' => array(
                    'description' => __( 'The customer address for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'sanitize_callback' => 'sanitize_text_field',
                        'default'           => '',
                    ),
                ),
                'services' => array(
                    'description' => __( 'The services for the resource.', 'salon-booking-system' ),
                    'type'        => 'array',
                    'context'     => array( 'view', 'edit' ),
                    'items'  => array(
                        'description' => __( 'The service item.', 'salon-booking-system' ),
                        'type'        => 'object',
                        'context'     => array( 'view', 'edit' ),
                        'required'    => array( 'service_id', 'assistant_id' ),
                        'properties'  => array(
                            'start_at' => array(
                                'description' => __( 'The start at.', 'salon-booking-system' ),
                                'type'        => 'string',
                                'format'      => 'HH:ii',
                                'context'     => array( 'view' ),
                                'arg_options' => array(
                                    'readonly' => true,
                                    'default'  => '',
                                ),
                            ),
                            'end_at' => array(
                                'description' => __( 'The end at.', 'salon-booking-system' ),
                                'type'        => 'string',
                                'format'      => 'HH:ii',
                                'context'     => array( 'view' ),
                                'arg_options' => array(
                                    'readonly' => true,
                                    'default'  => '',
                                ),
                            ),
                            'service_id' => array(
                                'description' => __( 'The service id.', 'salon-booking-system' ),
                                'type'        => 'integer',
                                'context'     => array( 'view', 'edit' ),
                                'arg_options' => array(
                                    'required' => true,
                                ),
                            ),
                            'service_name' => array(
                                'description' => __( 'The service name.', 'salon-booking-system' ),
                                'type'        => 'string',
                                'context'     => array( 'view' ),
                            ),
                            'service_price' => array(
                                'description' => __( 'The service price.', 'salon-booking-system' ),
                                'type'        => 'number',
                                'context'     => array( 'view' ),
                            ),
                            'assistant_id' => array(
                                'description' => __( 'The assistant id.', 'salon-booking-system' ),
                                'type'        => 'integer',
                                'context'     => array( 'view', 'edit' ),
                                'arg_options' => array(
                                    'required' => true,
                                ),
                            ),
                            'assistant_name' => array(
                                'description' => __( 'The assistant name.', 'salon-booking-system' ),
                                'type'        => 'string',
                                'context'     => array( 'view' ),
                            ),
                        ),
                    ),
                    'arg_options' => array(
                        'default'           => array(),
                        'validate_callback' => array($this, 'rest_validate_request_arg'),
                    ),
                ),
                'discounts' => array(
                    'description' => __( 'The discounts ids for the resource.', 'salon-booking-system' ),
                    'type'        => 'array',
                    'items'       => array(
                        'type' => 'integer',
                    ),
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'default' => array(),
                    ),
                ),
                'duration' => array(
                    'description' => __( 'The duration for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'format'      => 'HH:ii',
                    'context'     => array( 'view' ),
                    'arg_options' => array(
                        'readonly'          => true,
                        'validate_callback' => array($this, 'rest_validate_request_arg'),
                    ),
                ),
                'amount' => array(
                    'description' => __( 'The amount for the resource.', 'salon-booking-system' ),
                    'type'        => 'number',
                    'context'     => array( 'view' ),
                    'arg_options' => array(
                        'readonly' => true,
                    ),
                ),
                'deposit' => array(
                    'description' => __( 'The deposit for the resource.', 'salon-booking-system' ),
                    'type'        => 'number',
                    'context'     => array( 'view' ),
                    'arg_options' => array(
                        'readonly' => true,
                    ),
                ),
                'currency' => array(
                    'description' => __( 'The currency symbol the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'context'     => array( 'view' ),
                    'arg_options' => array(
                        'readonly' => true,
                    ),
                ),
                'transaction_id' => array(
                    'description' => __( 'The transaction id for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'context'     => array( 'view' ),
                    'arg_options' => array(
                        'readonly' => true,
                    ),
                ),
                'note' => array(
                    'description' => __( 'The description for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'context'     => array( 'view' ),
                    'arg_options' => array(
                        'sanitize_callback' => 'sanitize_text_field',
                        'default'           => '',
                    ),
                ),
            )
        );

        return apply_filters('sln_api_bookings_get_item_schema', $schema);
    }

}
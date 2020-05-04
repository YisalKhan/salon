<?php

namespace SLB_API\Controller;

use WP_REST_Server;
use WP_Error;
use SLN_Plugin;
use SLN_Enum_DaysOfWeek;
use WP_Query;

class Services_Controller extends REST_Controller
{
    const POST_TYPE = SLN_Plugin::POST_TYPE_SERVICE;

    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'services';

    public function register_routes() {

        register_rest_route( $this->namespace, '/' . $this->rest_base, array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array( $this, 'get_items' ),
                'permission_callback' => array( $this, 'get_items_permissions_check' ),
		'args'		      => apply_filters('sln_api_services_register_routes_get_items_args', array(
                    'type' => array(
                        'description' => __( 'Type of services (all, primary or secondary).', 'salon-booking-system' ),
                        'type'        => 'string',
                        'enum'        => array('all', 'primary', 'secondary'),
                        'required'    => true,
                        'default'     => 'all',
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
                        'enum'        => array('id', 'name'),
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
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array( $this, 'create_item' ),
                'permission_callback' => array( $this, 'create_item_permissions_check' ),
                'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
            ),
            'schema' => array( $this, 'get_public_item_schema' ),
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

    public function get_items( $request )
    {
        $prepared_args          = array();
        $prepared_args['order'] = isset($request['order']) && in_array(strtolower($request['order']), array('asc', 'desc')) ? $request['order'] : 'asc';

        $prepared_args['posts_per_page'] = is_null($request['per_page']) ? 10 : $request['per_page'];

        $request['orderby'] = is_null($request['orderby']) ? 'id' : $request['orderby'];
        $request['page']    = is_null($request['page']) ? 1 : $request['page'];

        if ( ! empty( $request['offset'] ) ) {
            $prepared_args['offset'] = $request['offset'];
        } else {
            $prepared_args['offset'] = ( $request['page'] - 1 ) * $prepared_args['posts_per_page'];
        }

        $orderby_possibles = array(
            'id'   => 'ID',
            'name' => 'title',
        );

        $prepared_args['orderby']     = $orderby_possibles[ $request['orderby'] ];
        $prepared_args['post_type']   = self::POST_TYPE;
	$prepared_args['post_status'] = 'publish';

        $services = array();

        switch ($request->get_param('type')) {
            case 'primary':
                $prepared_args['meta_query'] = array(
                    'relation' => 'OR',
                    array(
                        'key'     => '_sln_service_secondary',
                        'compare' => 'NOT EXISTS',
                        'value'   => '0',
                    ),
                    array(
                        'key'     => '_sln_service_secondary',
                        'value'   => '0',
                        'compare' => '=',
                    ),
                );
                break;
            case 'secondary':
                $prepared_args['meta_query'] = array(
                    array(
                        'key'     => '_sln_service_secondary',
                        'value'   => '1',
                        'compare' => '=',
                    )
                );
                break;
        }

	$prepared_args = apply_filters('sln_api_services_get_items_prepared_args', $prepared_args, $request);

        $query = new WP_Query( $prepared_args );

        try {
            foreach ( $query->posts as $service ) {
                $data       = $this->prepare_item_for_response( $service, $request );
                $services[] = $this->prepare_response_for_collection( $data );
            }
        } catch (\Exception $ex) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, resource list error ('.$ex->getMessage().').', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        $response = $this->success_response(array('items' => $services));

        // Store pagination values for headers then unset for count query.
        $per_page = (int) $prepared_args['posts_per_page'];
        $page     = ceil( ( ( (int) $prepared_args['offset'] ) / $per_page ) + 1 );

	$prepared_args['fields'] = 'ID';

        $total_assistants = $query->found_posts;

        if ( $total_assistants < 1 ) {
            // Out-of-bounds, run the query again without LIMIT for total count.
            unset( $prepared_args['posts_per_page'] );
            unset( $prepared_args['offset'] );
            $count_query = new WP_Query( $prepared_args );
            $total_assistants = $count_query->found_posts;
        }

        $response->header( 'X-WP-Total', (int) $total_assistants );

        $max_pages = ceil( $total_assistants / $per_page );

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

    public function prepare_item_for_response( $service, $request )
    {
        return SLN_Plugin::getInstance()->createService($service);
    }

    public function prepare_response_for_collection($service)
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

        $response = array(
            'id'                        => $service->getId(),
            'name'                      => $service->getName(),
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

	return apply_filters('sln_api_services_prepare_response_for_collection', $response, $service);
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
            $id = $this->save_item_post($request);
        } catch (\Exception $ex) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, error on create ('.$ex->getMessage().').', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        $response = $this->success_response(array('id' => $id));

        $response->set_status(201);

        return $response;
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
            $service = $this->prepare_item_for_response(current($query->posts), $request);
            $service = $this->prepare_response_for_collection($service);
        } catch (\Exception $ex) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, get resource error ('.$ex->getMessage().').', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        return $this->success_response(array('items' => array($service)));
    }

    public function update_item( $request )
    {
        $query = new WP_Query(array(
            'post_type' => self::POST_TYPE,
            'p'         => $request->get_param('id'),
        ));

        if ( ! $query->posts ) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, resource not found.', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        $service = $this->prepare_item_for_response(current($query->posts), $request);
        $service = $this->prepare_response_for_collection($service);

        try {
            $cloned_request = clone $request;
            $cloned_request->set_default_params($service);
            $this->save_item_post($cloned_request, $request->get_param('id'));
        } catch (\Exception $ex) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, error on update ('.$ex->getMessage().').', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        return $this->success_response();
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

    protected function save_item_post($request, $id = 0)
    {
        $availabilities     = array();
        $tmp_availabilities = array_filter($request->get_param('availabilities'));

        foreach ($tmp_availabilities as $availability) {

            $avDays = array();

            foreach (SLN_Enum_DaysOfWeek::toArray() as $dayKey => $dayLabel) {
                if (!empty($availability['days'][$dayKey])) {
                    $avDays[$dayKey] = 1;
                }
            }

            $availabilities[] = array(
                'days'      => $avDays,
                'from'      => array(
                    isset($availability['from'][0]) ? $availability['from'][0] : '',
                    isset($availability['from'][1]) ? $availability['from'][1] : '',
                ),
                'to'        => array(
                    isset($availability['to'][0]) ? $availability['to'][0] : '',
                    isset($availability['to'][1]) ? $availability['to'][1] : '',
                ),
                'always'    => isset($availability['always']) && $availability['always'] ? 1 : 0,
                'from_date' => isset($availability['from_date']) ? $availability['from_date'] : '',
                'to_date'   => isset($availability['to_date']) ? $availability['to_date'] : '',
            );
        }

        $id = wp_insert_post(array(
            'ID'          => $id,
            'post_title'  => $request->get_param('name'),
            'post_excerpt'=> $request->get_param('description'),
            'post_type'   => self::POST_TYPE,
            'post_status' => 'publish',
            'meta_input'   => array(
                '_sln_service_price'                     => $request->get_param('price'),
                '_sln_service_unit'                      => $request->get_param('unit'),
                '_sln_service_duration'                  => $request->get_param('duration'),
                '_sln_service_exclusive'                 => $request->get_param('exclusive'),
                '_sln_service_secondary'                 => $request->get_param('secondary'),
                '_sln_service_secondary_display_mode'    => $request->get_param('secondary_display_mode'),
                '_sln_service_secondary_parent_services' => $request->get_param('secondary_parent_services'),
                '_sln_service_exec_order'                => $request->get_param('execution_order'),
                '_sln_service_break_duration'            => $request->get_param('break'),
                '_sln_service_attendants'                => $request->get_param('empty_assistants'),
                '_sln_service_availabilities'            => $availabilities,
            ),
            'tax_input' => array(
                SLN_Plugin::TAXONOMY_SERVICE_CATEGORY => $request->get_param('categories'),
            ),
        ));

        if ( is_wp_error($id) ) {
            throw new \Exception(__( 'Save post error.', 'salon-booking-system' ));
        }

        $this->save_item_image($request->get_param('image_url'), $id);

	do_action('sln_api_services_save_item_post', $id, $request);

        return $id;
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
                'name' => array(
                    'description' => __( 'The name for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'sanitize_callback' => 'sanitize_text_field',
                        'required'          => true,
                    ),
                ),
                'price' => array(
                    'description' => __( 'The price for the resource.', 'salon-booking-system' ),
                    'type'        => 'number',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'default' => 0,
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
                'unit' => array(
                    'description' => __( 'The unit for the resource.', 'salon-booking-system' ),
                    'type'        => 'integer',
                    'context'     => array( 'view', 'edit' ),
                    'enum'        => range(1, 20),
                    'arg_options' => array(
                        'default' => 1,
                    ),
                ),
                'duration' => array(
                    'description' => __( 'The duration for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'default' => '01:00',
                    ),
                ),
                'exclusive' => array(
                    'description' => __( 'The exclusive for the resource.', 'salon-booking-system' ),
                    'type'        => 'integer',
                    'context'     => array( 'view', 'edit' ),
                    'enum'        => array(0, 1),
                    'arg_options' => array(
                        'default' => 0,
                    ),
                ),
                'secondary' => array(
                    'description' => __( 'The secondary for the resource.', 'salon-booking-system' ),
                    'type'        => 'integer',
                    'context'     => array( 'view', 'edit' ),
                    'enum'        => array(0, 1),
                    'arg_options' => array(
                        'default' => 0,
                    ),
                ),
                'secondary_display_mode' => array(
                    'description' => __( 'The secondary display mode for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'enum'        => array('always', 'category', 'service'),
                    'arg_options' => array(
                        'default' => 'always',
                    ),
                ),
                'secondary_parent_services' => array(
                    'description' => __( 'The parent services ids for the resource.', 'salon-booking-system' ),
                    'type'        => 'array',
                    'items'       => array(
                        'type' => 'integer',
                    ),
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'default' => array(),
                    ),
                ),
                'execution_order' => array(
                    'description' => __( 'The order execution for the resource.', 'salon-booking-system' ),
                    'type'        => 'integer',
                    'context'     => array( 'view', 'edit' ),
                    'enum'        => range(1, 10),
                    'arg_options' => array(
                        'default' => 1,
                    ),
                ),
                'break' => array(
                    'description' => __( 'The break between services for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'enum'        => array('00:00', '01:00', '02:00', '03:00'),
                    'arg_options' => array(
                        'default' => '00:00',
                    ),
                ),
                'empty_assistants' => array(
                    'description' => __( 'The no assistants for the resource.', 'salon-booking-system' ),
                    'type'        => 'integer',
                    'context'     => array( 'view', 'edit' ),
                    'enum'        => array(0, 1),
                    'arg_options' => array(
                        'default' => 0,
                    ),
                ),
                'description' => array(
                    'description' => __( 'The description for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'sanitize_callback' => 'sanitize_text_field',
                        'default'           => '',
                    ),
                ),
                'categories' => array(
                    'description' => __( 'The services categories ids for the resource.', 'salon-booking-system' ),
                    'type'        => 'array',
                    'items'       => array(
                        'type' => 'integer',
                    ),
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'default' => array(),
                    ),
                ),
                'availabilities' => array(
                    'description' => __( 'The availabilities for the resource.', 'salon-booking-system' ),
                    'type'        => 'array',
                    'context'     => array( 'view', 'edit' ),
                    'items'  => array(
                        'description' => __( 'The availability item.', 'salon-booking-system' ),
                        'type'        => 'object',
                        'context'     => array( 'view', 'edit' ),
                        'properties'  => array(
                            'days' => array(
                                'description' => __( 'The days.', 'salon-booking-system' ),
                                'type'        => 'object',
                                'properties'  => array(
                                    0 => array(
                                        'description' => __( 'The sunday.', 'salon-booking-system' ),
                                        'type'        => 'integer',
                                        'enum'        => array(0, 1),
                                    ),
                                    1 => array(
                                        'description' => __( 'The monday.', 'salon-booking-system' ),
                                        'type'        => 'integer',
                                        'enum'        => array(0, 1),
                                    ),
                                    2 => array(
                                        'description' => __( 'The tuesday.', 'salon-booking-system' ),
                                        'type'        => 'integer',
                                        'enum'        => array(0, 1),
                                    ),
                                    3 => array(
                                        'description' => __( 'The wednesday.', 'salon-booking-system' ),
                                        'type'        => 'integer',
                                        'enum'        => array(0, 1),
                                    ),
                                    4 => array(
                                        'description' => __( 'The thursday.', 'salon-booking-system' ),
                                        'type'        => 'integer',
                                        'enum'        => array(0, 1),
                                    ),
                                    5 => array(
                                        'description' => __( 'The friday.', 'salon-booking-system' ),
                                        'type'        => 'integer',
                                        'enum'        => array(0, 1),
                                    ),
                                    6 => array(
                                        'description' => __( 'The saturday.', 'salon-booking-system' ),
                                        'type'        => 'integer',
                                        'enum'        => array(0, 1),
                                    ),
                                ),
                                'context'     => array( 'view', 'edit' ),
                                'arg_options' => array(
                                    'required' => true,
                                ),
                            ),
                            'from' => array(
                                'description' => __( 'The from time.', 'salon-booking-system' ),
                                'type'        => 'object',
                                'properties'  => array(
                                    0 => array(
                                        'type'   => 'string',
                                        'format' => 'HH:ii',
                                    ),
                                    1 => array(
                                        'type'   => 'string',
                                        'format' => 'HH:ii',
                                    ),
                                ),
                                'context'     => array( 'view', 'edit' ),
                                'arg_options' => array(
                                    'required' => true,
                                ),
                            ),
                            'to' => array(
                                'description' => __( 'The to time.', 'salon-booking-system' ),
                                'type'        => 'object',
                                'properties'  => array(
                                    0 => array(
                                        'type'   => 'string',
                                        'format' => 'HH:ii',
                                    ),
                                    1 => array(
                                        'type'   => 'string',
                                        'format' => 'HH:ii',
                                    ),
                                ),
                                'context'     => array( 'view', 'edit' ),
                                'arg_options' => array(
                                    'required' => true,
                                ),
                            ),
                            'always' => array(
                                'description' => __( 'The always.', 'salon-booking-system' ),
                                'type'        => 'integer',
                                'enum'        => array(0, 1),
                                'context'     => array( 'view', 'edit' ),
                            ),
                            'from_date' => array(
                                'description' => __( 'The from date.', 'salon-booking-system' ),
                                'type'        => 'string',
                                'format'      => 'YYYY-MM-DD',
                                'context'     => array( 'view', 'edit' ),
                            ),
                            'to_date' => array(
                                'description' => __( 'The to date.', 'salon-booking-system' ),
                                'type'        => 'string',
                                'format'      => 'YYYY-MM-DD',
                                'context'     => array( 'view', 'edit' ),
                            ),
                        ),
                    ),
                    'arg_options' => array(
                        'default'           => array(),
                        'validate_callback' => array($this, 'rest_validate_request_arg'),
                    ),
                ),
                'image_url' => array(
                    'description' => __( 'The image url for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                ),
            ),
        );

        return apply_filters('sln_api_services_get_item_schema', $schema);
    }

}
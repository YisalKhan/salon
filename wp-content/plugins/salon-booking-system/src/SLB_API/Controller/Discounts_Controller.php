<?php

namespace SLB_API\Controller;

use WP_REST_Server;
use SLN_Plugin;
use WP_Error;
use WP_Query;
use SLB_Discount_Plugin;
use SLB_Discount_Wrapper_Discount;

class Discounts_Controller extends REST_Controller
{
    const POST_TYPE = SLB_Discount_Plugin::POST_TYPE_DISCOUNT;

    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'discounts';

    public function register_routes() {

        register_rest_route( $this->namespace, '/' . $this->rest_base, array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array( $this, 'get_items' ),
                'permission_callback' => array( $this, 'get_items_permissions_check' ),
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

        $prepared_args['orderby']   = $orderby_possibles[ $request['orderby'] ];
        $prepared_args['post_type'] = self::POST_TYPE;

        $discounts = array();

        $query = new WP_Query( $prepared_args );

        try {
            foreach ( $query->posts as $discount ) {
                $data        = $this->prepare_item_for_response( $discount, $request );
                $discounts[] = $this->prepare_response_for_collection( $data );
            }
        } catch (\Exception $ex) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, resource list error ('.$ex->getMessage().').', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        $response = $this->success_response(array('items' => $discounts));

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

    public function prepare_item_for_response( $discount, $request )
    {
        return new SLB_Discount_Wrapper_Discount($discount);
    }

    public function prepare_response_for_collection($discount)
    {
        return array(
            'id'             => $discount->getId(),
            'name'           => $discount->getName(),
            'amount'         => $discount->getAmount(),
            'amount_type'    => $discount->getAmountType(),
            'currency'       => SLN_Plugin::getInstance()->getSettings()->getCurrencySymbol(),
            'total_limit'    => $discount->getTotalUsagesLimit(),
            'valid_from'     => $discount->getStartsAt()->format('Y-m-d'),
            'valid_to'       => $discount->getEndsAt()->format('Y-m-d'),
            'per_user_limit' => $discount->getUsagesLimit(),
            'services'       => $discount->getServicesIds(),
            'discount_type'  => $discount->getDiscountType(),
            'discount_code'  => $discount->getCouponCode(),
            'discount_rules' => $discount->getDiscountRules(),
        );
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
            $discount = $this->prepare_item_for_response(current($query->posts), $request);
            $discount = $this->prepare_response_for_collection($discount);
        } catch (\Exception $ex) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, get resource error ('.$ex->getMessage().').', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        return $this->success_response(array('items' => array($discount)));
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

        try {
            $discount = $this->prepare_item_for_response(current($query->posts), $request);
            $discount = $this->prepare_response_for_collection($discount);
        } catch (\Exception $ex) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, get resource error ('.$ex->getMessage().').', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        try {
            $cloned_request = clone $request;
            $cloned_request->set_default_params($discount);
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
        $tmp_discount_rules = array_filter($request->get_param('discount_rules'));
        $discount_rules     = array();

        foreach ($tmp_discount_rules as $i => $rule) {

            if ( ! isset( $rule['mode'] ) ) {
                throw new \Exception(__( sprintf('Missing parameter: discount_rules[%s][mode].', $i), 'salon-booking-system' ));
            }

            $discount_rules[] = array(
                'mode'            => $rule['mode'],
                'bookings_number' => $rule['mode'] === 'bookings' && isset($rule['bookings_number']) ? $rule['bookings_number'] : '',
                'amount_number'   => $rule['mode'] === 'amount' && isset($rule['amount_number']) ? $rule['amount_number'] : '',
                'daterange_from'  => $rule['mode'] === 'daterange' && isset($rule['daterange_from']) ? $rule['daterange_from'] : '',
                'daterange_to'    => $rule['mode'] === 'daterange' && isset($rule['daterange_to']) ? $rule['daterange_to'] : '',
                'weekdays'        => $rule['mode'] === 'weekdays' ? (isset($rule['weekdays']) ? $rule['weekdays'] : array()) : '',
            );
        }

        $id = wp_insert_post(array(
            'ID'          => $id,
            'post_title'  => $request->get_param('name'),
            'post_type'   => self::POST_TYPE,
            'post_status' => 'publish',
            'meta_input'  => array(
                '_sln_discount_amount'             => $request->get_param('amount'),
                '_sln_discount_amount_type'        => $request->get_param('amount_type'),
                '_sln_discount_usages_limit_total' => $request->get_param('total_limit'),
                '_sln_discount_from'               => $request->get_param('valid_from'),
                '_sln_discount_to'                 => $request->get_param('valid_to'),
                '_sln_discount_usages_limit'       => $request->get_param('per_user_limit'),
                '_sln_discount_services'           => $request->get_param('services'),
                '_sln_discount_type'               => $request->get_param('discount_type'),
                '_sln_discount_code'               => $request->get_param('discount_code'),
                '_sln_discount_rules'              => $discount_rules,
            ),
        ));

        if ( is_wp_error($id) ) {
            throw new \Exception(__( 'Save post error.', 'salon-booking-system' ));
        }

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
                'amount' => array(
                    'description' => __( 'The amount for the resource.', 'salon-booking-system' ),
                    'type'        => 'number',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'default' => 0,
                    ),
                ),
                'amount_type' => array(
                    'description' => __( 'The amount type for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'enum'        => array('fixed', 'percentage'),
                    'arg_options' => array(
                        'default' => 'fixed',
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
                'total_limit' => array(
                    'description' => __( 'The usage limit for the resource.', 'salon-booking-system' ),
                    'type'        => 'integer',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'default' => 0,
                    ),
                ),
                'valid_from' => array(
                    'description' => __( 'The valid from date for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'format'      => 'YYYY-MM-DD',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'validate_callback' => array($this, 'rest_validate_request_arg'),
                        'default'           => current_time('Y-m-d'),
                    ),
                ),
                'valid_to' => array(
                    'description' => __( 'The valid to date for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'format'      => 'YYYY-MM-DD',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'validate_callback' => array($this, 'rest_validate_request_arg'),
                        'default'           => current_time('Y-m-d'),
                    ),
                ),
                'per_user_limit' => array(
                    'description' => __( 'The user usage limit for the resource.', 'salon-booking-system' ),
                    'type'        => 'integer',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'default' => 0,
                    ),
                ),
                'services' => array(
                    'description' => __( 'The target services ids for the resource.', 'salon-booking-system' ),
                    'type'        => 'array',
                    'items'       => array(
                        'type' => 'integer',
                    ),
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'default' => array(),
                    ),
                ),
                'discount_type' => array(
                    'description' => __( 'The type (auto or rules) for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'enum'        => array('sln-d-coupon', 'sln-d-auto'),
                    'arg_options' => array(
                        'default' => 'sln-d-auto',
                    ),
                ),
                'discount_code' => array(
                    'description' => __( 'The coupon code for the resource.', 'salon-booking-system' ),
                    'type'        => 'integer',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'default' => SLB_Discount_Wrapper_Discount::generateCouponCode(),
                    ),
                ),
                'discount_rules' => array(
                    'description' => __( 'The discount rules for the resource.', 'salon-booking-system' ),
                    'type'        => 'array',
                    'context'     => array( 'view', 'edit' ),
                    'items'  => array(
                        'description' => __( 'The discount rule.', 'salon-booking-system' ),
                        'type'        => 'object',
                        'context'     => array( 'view', 'edit' ),
                        'required'    => array( 'mode' ),
                        'properties'  => array(
                            'mode' => array(
                                'description' => __( 'The mode of rule.', 'salon-booking-system' ),
                                'type'        => 'string',
                                'context'     => array( 'view', 'edit' ),
                                'enum'        => array( 'bookings', 'amount', 'daterange', 'weekdays' ),
                                'arg_options' => array(
                                    'required' => true,
                                    'default'  => 'bookings',
                                ),
                            ),
                            'bookings_number' => array(
                                'description' => __( 'The booking number.', 'salon-booking-system' ),
                                'type'        => 'integer',
                                'context'     => array( 'view', 'edit' ),
                                'arg_options' => array(
                                    'default' => null,
                                ),
                            ),
                            'amount_number' => array(
                                'description' => __( 'The booking number.', 'salon-booking-system' ),
                                'type'        => 'number',
                                'context'     => array( 'view', 'edit' ),
                                'arg_options' => array(
                                    'default' => null,
                                ),
                            ),
                            'daterange_from' => array(
                                'description' => __( 'The from date for the resource.', 'salon-booking-system' ),
                                'type'        => 'string',
                                'format'      => 'date-time',
                                'context'     => array( 'view', 'edit' ),
                                'arg_options' => array(
                                    'default' => '',
                                ),
                            ),
                            'daterange_to' => array(
                                'description' => __( 'The from date for the resource.', 'salon-booking-system' ),
                                'type'        => 'string',
                                'format'      => 'date-time',
                                'context'     => array( 'view', 'edit' ),
                                'arg_options' => array(
                                    'default' => '',
                                ),
                            ),
                            'weekdays' => array(
                                'description' => __( 'The days of week for the resource.', 'salon-booking-system' ),
                                'type'        => 'array',
                                'context'     => array( 'view', 'edit' ),
                                'items'       => array(
                                    'description' => __( 'The number day of week for the resource.', 'salon-booking-system' ),
                                    'type'        => 'integer',
                                    'enum'        => range(0, 6),
                                ),
                                'arg_options' => array(
                                    'default' => array(),
                                ),
                            ),
                        ),
                    ),
                    'arg_options' => array(
                        'default' => array(),
                    ),
                ),
            )
        );

        return $schema;
    }

}
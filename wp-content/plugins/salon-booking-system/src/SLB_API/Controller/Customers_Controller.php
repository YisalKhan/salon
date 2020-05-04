<?php

namespace SLB_API\Controller;

use WP_REST_Server;
use WP_Error;
use SLN_Plugin;
use WP_User_Query;
use SLN_Wrapper_Customer;
use WP_Query;

class Customers_Controller extends REST_Controller
{
    const ROLE = SLN_Plugin::USER_ROLE_CUSTOMER;

    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'customers';

    public function register_routes() {

        register_rest_route( $this->namespace, '/' . $this->rest_base, array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array( $this, 'get_items' ),
                'args' => array(
                    'search' => array(
                        'description' => __( 'Search string.', 'salon-booking-system' ),
                        'type'        => 'string',
                        'default'     => '',
                    ),
                ),
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

    public function permissions_check($capability, $object_id = 0)
    {
        $capabilities = array(
            'create' => 'add_users',
            'edit'   => 'edit_users',
            'delete' => 'delete_users',
        );

	return current_user_can( isset($capabilities[$capability]) ? $capabilities[$capability] : '', $object_id );
    }

    public function create_item_permissions_check( $request ) {

        if ( ! $this->permissions_check( 'create' ) ) {
            return new WP_Error( 'salon_rest_cannot_create', __( 'Sorry, you cannot create resource.', 'salon-booking-system' ), array( 'status' => rest_authorization_required_code() ) );
        }

        return true;
    }

    public function update_item_permissions_check( $request ) {

        if ( ! $this->permissions_check( 'edit' ) ) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, you cannot update resource.', 'salon-booking-system' ), array( 'status' => rest_authorization_required_code() ) );
        }

        return true;
    }

    public function delete_item_permissions_check( $request ) {

        if ( ! $this->permissions_check( 'delete' )) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, you cannot delete resource.', 'salon-booking-system' ), array( 'status' => rest_authorization_required_code() ) );
        }

        return true;
    }

    public function get_items( $request )
    {
        $prepared_args          = array();
        $prepared_args['order'] = isset($request['order']) && in_array(strtolower($request['order']), array('asc', 'desc')) ? $request['order'] : 'asc';

        $prepared_args['number'] = is_null($request['per_page']) ? 10 : $request['per_page'];

        $request['orderby'] = is_null($request['orderby']) ? 'id' : $request['orderby'];
        $request['page']    = is_null($request['page']) ? 1 : $request['page'];

        if ( ! empty( $request['offset'] ) ) {
            $prepared_args['offset'] = $request['offset'];
        } else {
            $prepared_args['offset'] = ( $request['page'] - 1 ) * $prepared_args['number'];
        }

        $orderby_possibles = array(
            'id'           => 'ID',
            'display_name' => 'display_name',
        );

        $prepared_args['orderby'] = $orderby_possibles[ $request['orderby'] ];
        $prepared_args['role']    = array(self::ROLE);

        $s = $request->get_param('search');

        if ($s !== '') {

            $include = array();

            $search_params_main_fields = array_merge($prepared_args, array(
                'search'         => '*' . $s . '*',
                'search_columns' => array('user_nicename', 'user_email'),
                'fields'         => 'ID',
            ));

            $include = array_merge($include, (new WP_User_Query($search_params_main_fields))->results);

            $search_params_meta_fields = $prepared_args;

            if ( ! isset( $search_params_meta_fields['meta_query'] ) ) {
                $search_params_meta_fields['meta_query'] = array();
            }

            $search_params_meta_fields['fields'] = 'ID';

            $search_params_meta_fields['meta_query'][] = array(
                'relation' => 'OR',
                array(
                    'key'     => 'first_name',
                    'value'   => $s,
                    'compare' => 'LIKE',
                ),
                array(
                    'key'     => 'last_name',
                    'value'   => $s,
                    'compare' => 'LIKE',
                ),
                array(
                    'key'     => '_sln_phone',
                    'value'   => $s,
                    'compare' => 'LIKE',
                ),
            );

            $include = array_merge($include, (new WP_User_Query($search_params_meta_fields))->results);

            $prepared_args['include'] = $include ? $include : array(-1);
        }

        $customers = array();

        $query = new WP_User_Query( $prepared_args );

        try {
            foreach ( $query->results as $customer ) {
                $data        = $this->prepare_item_for_response( $customer, $request );
                $customers[] = $this->prepare_response_for_collection( $data );
            }
        } catch (\Exception $ex) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, resource list error ('.$ex->getMessage().').', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        $response = $this->success_response(array('items' => $customers));

        // Store pagination values for headers then unset for count query.
        $per_page = (int) $prepared_args['number'];
        $page     = ceil( ( ( (int) $prepared_args['offset'] ) / $per_page ) + 1 );

	$prepared_args['fields'] = 'ID';

        $total = $query->get_total();

        if ( $total < 1 ) {
            // Out-of-bounds, run the query again without LIMIT for total count.
            unset( $prepared_args['number'] );
            unset( $prepared_args['offset'] );
            $count_query = new WP_User_Query( $prepared_args );
            $total = $count_query->get_total();
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

    public function prepare_item_for_response( $customer, $request )
    {
        return new SLN_Wrapper_Customer($customer);
    }

    public function prepare_response_for_collection($customer)
    {
        $query = new WP_Query(array(
            'author'    => $customer->getId(),
            'post_type' => SLN_Plugin::POST_TYPE_BOOKING,
            'fields'    => 'ids',
        ));

        if (is_wp_error($query)) {
            throw new \Exception(__( 'Get bookings ids error.', 'salon-booking-system' ));
        }

        $bookings = $query->posts;

        return array(
            'id'         => $customer->getId(),
            'first_name' => $customer->get('first_name'),
            'last_name'  => $customer->get('last_name'),
            'email'      => $customer->get('user_email'),
            'phone'      => $customer->getMeta('phone'),
            'address'    => $customer->getMeta('address'),
            'note'       => $customer->getMeta('personal_note'),
            'bookings'   => $bookings,
        );
    }

    public function create_item( $request )
    {
        if ($request->get_param('id')) {

            $query = new WP_User_Query(array(
                'role'           => array(self::ROLE),
                'search'         => $request->get_param('id'),
                'search_columns' => array('ID'),
            ));

            if ( $query->results ) {
                return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, resource already exists.', 'salon-booking-system' ), array( 'status' => 409 ) );
            }
        }

        try {
            $id = $this->save_item_user($request);
        } catch (\Exception $ex) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, error on create ('.$ex->getMessage().').', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        $response = $this->success_response(array('id' => $id));

        $response->set_status(201);

        return $response;
    }

    public function get_item( $request )
    {
        $query = new WP_User_Query(array(
            'role'           => array(self::ROLE),
            'search'         => $request->get_param('id'),
            'search_columns' => array('ID'),
        ));

        if ( ! $query->results ) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, resource not found.', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        try {
            $customer = $this->prepare_item_for_response(current($query->results), $request);
            $customer = $this->prepare_response_for_collection($customer);
        } catch (\Exception $ex) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, get resource error ('.$ex->getMessage().').', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        return $this->success_response(array('items' => array($customer)));
    }

    public function update_item( $request )
    {
        $query = new WP_User_Query(array(
            'role'           => array(self::ROLE),
            'search'         => $request->get_param('id'),
            'search_columns' => array('ID'),
        ));

        if ( ! $query->results ) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, resource not found.', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        try {
            $customer = $this->prepare_item_for_response(current($query->results), $request);
            $customer = $this->prepare_response_for_collection($customer);
        } catch (\Exception $ex) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, get resource error ('.$ex->getMessage().').', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        try {
            $cloned_request = clone $request;
            $cloned_request->set_default_params($customer);
            $this->save_item_user($cloned_request, $request->get_param('id'));
        } catch (\Exception $ex) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, error on update ('.$ex->getMessage().').', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        return $this->success_response();
    }

    public function delete_item( $request )
    {
        $query = new WP_User_Query(array(
            'role'           => array(self::ROLE),
            'search'         => $request->get_param('id'),
            'search_columns' => array('ID'),
        ));

        if ( ! $query->results ) {
            return new WP_Error( 'salon_rest_cannot_delete', __( 'Sorry, resource not found.', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        wp_delete_user($request->get_param('id'));

        return $this->success_response();
    }

    protected function save_item_user($request, $id = 0)
    {
        if (!$id) {

            $id = wp_create_user($request->get_param('email'), wp_generate_password(), $request->get_param('email'));

            if ( is_wp_error($id) ) {
                throw new \Exception(__( 'Save customer error.', 'salon-booking-system' ));
            }
        }

        $id = wp_update_user(array(
            'ID'         => $id,
            'user_email' => $request->get_param('email'),
            'first_name' => $request->get_param('first_name'),
            'last_name'  => $request->get_param('last_name'),
            'role'       => SLN_Plugin::USER_ROLE_CUSTOMER,
        ));

        if ( is_wp_error($id) ) {
            throw new \Exception(__( 'Save customer error.', 'salon-booking-system' ));
        }

        $meta = array(
            '_sln_phone'         => $request->get_param('phone'),
            '_sln_address'       => $request->get_param('address'),
            '_sln_personal_note' => $request->get_param('note'),
        );

        foreach ($meta as $key => $value) {
            update_user_meta($id, $key, $value);
        }

        return $id;
    }

    public function get_item_schema()
    {
        $schema = array(
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'title'      => 'customer',
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
                'first_name' => array(
                    'description' => __( 'The first name for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'validate_callback' => array($this, 'rest_validate_not_empty_string'),
                        'sanitize_callback' => 'sanitize_text_field',
                        'required'          => true,
                    ),
                ),
                'last_name' => array(
                    'description' => __( 'The last name for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'validate_callback' => array($this, 'rest_validate_not_empty_string'),
                        'sanitize_callback' => 'sanitize_text_field',
                        'required'          => true,
                    ),
                ),
                'email' => array(
                    'description' => __( 'The email for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'format'      => 'email',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'required' => true,
                    ),
                ),
                'phone' => array(
                    'description' => __( 'The phone for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'format'      => 'phone',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'default' => '',
                    ),
                ),
                'address' => array(
                    'description' => __( 'The address for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'sanitize_callback' => 'sanitize_text_field',
                        'default'           => '',
                    ),
                ),
                'note' => array(
                    'description' => __( 'The note for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'sanitize_callback' => 'sanitize_text_field',
                        'default'           => '',
                    ),
                ),
                'bookings' => array(
                    'description' => __( 'The bookings ids for the resource.', 'salon-booking-system' ),
                    'type'        => 'array',
                    'items'       => array(
                        'type' => 'integer',
                    ),
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'readonly'=> true,
                    ),
                ),
            ),
        );

        return $schema;
    }

}
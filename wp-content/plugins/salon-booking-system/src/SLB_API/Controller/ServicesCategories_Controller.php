<?php

namespace SLB_API\Controller;

use WP_REST_Server;
use WP_Error;
use SLN_Plugin;
use WP_Term_Query;

class ServicesCategories_Controller extends REST_Controller
{
    const TAXONOMY_SLUG = SLN_Plugin::TAXONOMY_SERVICE_CATEGORY;

    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'services/categories';

    public function register_routes() {

        register_rest_route( $this->namespace, '/' . $this->rest_base, array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array( $this, 'get_items' ),
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
        $object       = get_taxonomy(static::TAXONOMY_SLUG);
        $capabilities = is_null($object) ? array() : (array)$object->cap;

        return current_user_can( isset($capabilities[$capability]) ? $capabilities[$capability] : '', $object_id );
    }

    public function create_item_permissions_check( $request ) {

        if ( ! $this->permissions_check( 'manage_terms' ) ) {
            return new WP_Error( 'salon_rest_cannot_create', __( 'Sorry, you cannot create resource.', 'salon-booking-system' ), array( 'status' => rest_authorization_required_code() ) );
        }

        return true;
    }

    public function update_item_permissions_check( $request ) {

        if ( ! $this->permissions_check( 'edit_terms' ) ) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, you cannot update resource.', 'salon-booking-system' ), array( 'status' => rest_authorization_required_code() ) );
        }

        return true;
    }

    public function delete_item_permissions_check( $request ) {

        if ( ! $this->permissions_check( 'delete_terms' )) {
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
            'id'   => 'id',
            'name' => 'name',
        );

        $prepared_args['orderby']    = $orderby_possibles[ $request['orderby'] ];
        $prepared_args['taxonomy']   = self::TAXONOMY_SLUG;
        $prepared_args['hide_empty'] = false;

        $categories = array();

        $query = new WP_Term_Query( $prepared_args );

        try {
            foreach ( $query->terms as $category ) {
                $categories[] = $this->prepare_response_for_collection( $category );
            }
        } catch (\Exception $ex) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, resource list error ('.$ex->getMessage().').', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        $response = $this->success_response(array('items' => $categories));

        // Store pagination values for headers then unset for count query.
        $per_page = (int) $prepared_args['number'];
        $page     = ceil( ( ( (int) $prepared_args['offset'] ) / $per_page ) + 1 );

	$prepared_args['fields'] = 'ids';

        // Out-of-bounds, run the query again without LIMIT for total count.
        unset( $prepared_args['number'] );
        unset( $prepared_args['offset'] );

        $total = wp_count_terms(self::TAXONOMY_SLUG, $prepared_args );

        if ( ! $total ) {
            $total = 0;
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

    public function prepare_response_for_collection($category)
    {
        return array(
            'id'          => $category->term_id,
            'name'        => $category->name,
            'slug'        => $category->slug,
            'description' => $category->description,
        );
    }

    public function create_item( $request )
    {
        if ($request->get_param('id')) {

            $category = get_term( $request->get_param('id'), self::TAXONOMY_SLUG );

            if ( $category ) {
                return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, resource already exists.', 'salon-booking-system' ), array( 'status' => 409 ) );
            }
        }

        try {
            $id = $this->save_item_term($request);
        } catch (\Exception $ex) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, error on create ('.$ex->getMessage().').', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        $response = $this->success_response(array('id' => $id));

        $response->set_status(201);

        return $response;
    }

    public function get_item( $request )
    {
        $category = get_term( $request->get_param('id'), self::TAXONOMY_SLUG );

        if ( ! $category ) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, resource not found.', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        $category = $this->prepare_response_for_collection($category);

        return $this->success_response(array('items' => array($category)));
    }

    public function update_item( $request )
    {
        $category = get_term( $request->get_param('id'), self::TAXONOMY_SLUG );

        if ( ! $category ) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, resource not found.', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        $category = $this->prepare_response_for_collection($category);

        try {
            $cloned_request = clone $request;
            $cloned_request->set_default_params($category);
            $this->save_item_term($cloned_request, $request->get_param('id'));
        } catch (\Exception $ex) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, error on update ('.$ex->getMessage().').', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        return $this->success_response();
    }

    public function delete_item( $request )
    {
        $category = get_term( $request->get_param('id'), self::TAXONOMY_SLUG );

        if ( ! $category ) {
            return new WP_Error( 'salon_rest_cannot_delete', __( 'Sorry, resource not found.', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

        wp_delete_term($request->get_param('id'), self::TAXONOMY_SLUG);

        return $this->success_response();
    }

    protected function save_item_term($request, $id = 0)
    {
        if ($id) {
            $result = wp_update_term($id, self::TAXONOMY_SLUG, array(
                'name'        => $request->get_param('name'),
                'slug'        => $request->get_param('slug'),
                'description' => $request->get_param('description'),
            ));
        } else {
            $result = wp_insert_term($request->get_param('name'), self::TAXONOMY_SLUG, array(
                'slug'        => $request->get_param('slug'),
                'description' => $request->get_param('description'),
            ));
        }

        if ( is_wp_error($result) ) {
            throw new \Exception(__( 'Save category error.', 'salon-booking-system' ));
        }

        return $result['term_id'];
    }

    public function get_item_schema()
    {
        $schema = array(
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'title'      => 'service_category',
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
                        'validate_callback' => array($this, 'rest_validate_not_empty_string'),
                        'sanitize_callback' => 'sanitize_text_field',
                        'required'          => true,
                    ),
                ),
                'slug' => array(
                    'description' => __( 'The name for the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'arg_options' => array(
                        'validate_callback' => array($this, 'rest_validate_not_empty_string'),
                        'sanitize_callback' => 'sanitize_text_field',
                        'required'          => true,
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
            ),
        );

        return $schema;
    }

}
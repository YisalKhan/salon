<?php

namespace SLB_API\Controller;

use WP_Error;
use WP_REST_Server;

class Users_Controller extends REST_Controller
{
    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'users';

    public function register_routes() {

        register_rest_route( $this->namespace, '/' . $this->rest_base, array(
            array(
                'methods'   => WP_REST_Server::EDITABLE,
                'callback'  => array( $this, 'update_item' ),
                'args'	    => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
            ),
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );
    }

    public function update_item( $request )
    {
	$user_id = get_current_user_id();

        try {
            $this->save_item_user($request, $user_id);
        } catch (\Exception $ex) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, error on update ('.$ex->getMessage().').', 'salon-booking-system' ), array( 'status' => 404 ) );
        }

	return $this->success_response(array(
	    'id' => $user_id,
	));
    }

    protected function save_item_user($request, $user_id = 0)
    {
	$meta = array();

	if ( $request->get_param('onesignal_player_id') !== null ) {
	    $meta['_sln_onesignal_player_id'] = $request->get_param('onesignal_player_id');
	}

	foreach ($meta as $key => $value) {
	    update_user_meta($user_id, $key, $value);
	}

	return $user_id;
    }

    public function get_item_schema()
    {
        $schema = array(
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'title'      => 'user',
            'type'       => 'object',
            'properties' => array(
                'onesignal_player_id' => array(
                    'description' => __( 'The notification push id the resource.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'context'     => array( 'edit' ),
                    'arg_options' => array(
                        'sanitize_callback' => 'sanitize_text_field',
                        'default'           => null,
                    ),
                ),
            ),
        );

        return $schema;
    }

}
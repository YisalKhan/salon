<?php

namespace SLB_API\Controller;

use WP_User;
use WP_Error;
use WP_REST_Server;
use SLB_API\Helper\TokenHelper;
use SLB_API\Helper\RequestHelper;

class Auth_Controller extends REST_Controller
{
    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = '';

    public function register_routes() {

        register_rest_route( $this->namespace, '/login', array(
            'args' => array(
                'name' => array(
                    'description' => __( 'User login.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'required'    => true,
                ),
                'password' => array(
                    'description' => __( 'User password.', 'salon-booking-system' ),
                    'type'        => 'string',
                    'required'    => true,
                ),
            ),
            array(
                'methods'   => WP_REST_Server::READABLE,
                'callback'  => array( $this, 'login' ),
            ),
        ) );

        register_rest_route( $this->namespace, '/logout', array(
            array(
                'methods'   => WP_REST_Server::CREATABLE,
                'callback'  => array( $this, 'logout' ),
            ),
        ) );
    }

    public function login( $request )
    {
        $username = $request->get_param('name');
        $userpass = $request->get_param('password');

        $user = get_user_by('login', $username);

        if (!$user instanceof WP_User) {
            return new WP_Error( 'salon_rest_user_not_found', __( 'Wrong user name.', 'salon-booking-system' ), array( 'status' => 404));
        }

        if (!wp_check_password($userpass, $user->user_pass, $user->ID)) {
            return new WP_Error( 'salon_rest_user_not_found', __( 'Wrong user password.', 'salon-booking-system' ), array( 'status' => 404));
        }

        $token = (new TokenHelper())->createUserAccessToken($user->ID);

        $response = rest_ensure_response(array(
            'status'       => 'OK',
            'access_token' => $token
        ));

        $response->set_status(201);

        return $response;
    }

    public function logout()
    {
        $access_token = (new RequestHelper())->getAccessToken();

        (new TokenHelper())->deleteUserAccessToken($access_token);

        $response = rest_ensure_response(array(
            'status' => 'OK',
        ));

        $response->set_status(201);

	return $response;
    }


}
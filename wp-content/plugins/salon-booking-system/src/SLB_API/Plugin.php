<?php

namespace SLB_API;

use SLB_API\Helper\TokenHelper;
use SLB_API\Helper\RequestHelper;
use SLB_API\Listener\NotificationListener;

use WP_Error;

class Plugin {

    private static $instance;

    const BASE_API = 'salon/api/v1';

    /**
     * @var SLN_Plugin
     */
    private $plugin;

    public static function get_instance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __construct()
    {
	add_action('wp_loaded', array( $this, 'init' ));

        if ( ! class_exists( '\WP_REST_Server' ) ) {
            return;
        }

        // Init REST API routes.
        add_action( 'rest_api_init', array( $this, 'rest_api_init' ));
    }

    public function init()
    {
	new NotificationListener();
    }

    public function rest_api_init()
    {
        add_filter('rest_pre_dispatch', array($this, 'handle_rest_user'), 10, 3);
        $this->register_rest_routes();
    }

    public function register_rest_routes()
    {
        $controllers = array(
            '\\SLB_API\\Controller\\Auth_Controller',
            '\\SLB_API\\Controller\\Assistants_Controller',
            '\\SLB_API\\Controller\\Services_Controller',
            '\\SLB_API\\Controller\\ServicesCategories_Controller',
            '\\SLB_API\\Controller\\Customers_Controller',
            '\\SLB_API\\Controller\\Discounts_Controller',
            '\\SLB_API\\Controller\\Bookings_Controller',
            '\\SLB_API\\Controller\\AvailabilityIntervals_Controller',
            '\\SLB_API\\Controller\\AvailabilityServices_Controller',
            '\\SLB_API\\Controller\\AvailabilityAssistants_Controller',
            '\\SLB_API\\Controller\\Users_Controller',
            '\\SLB_API\\Controller\\AvailabilityBooking_Controller',
        );

        foreach ( $controllers as $controller ) {
            $controller = new $controller();
            $controller->register_routes();
        }
    }

    public function handle_rest_user($dispatch_result, $server, $request)
    {
        if (stristr($request->get_route(), self::BASE_API) === false) {
            return $dispatch_result;
        }

        if (stristr($request->get_route(), '/login') !== false) {
            return $dispatch_result;
        }

        $token_helper   = new TokenHelper();
        $request_helper = new RequestHelper();

        $access_token = $request_helper->getAccessToken();

        if (empty($access_token) || !$token_helper->isValidUserAccessToken($access_token)) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, you access token incorrect.', 'salon-booking-system' ), array( 'status' => rest_authorization_required_code() ));
        }

        wp_set_current_user($token_helper->getUserIdByAccessToken($access_token));
    }

}
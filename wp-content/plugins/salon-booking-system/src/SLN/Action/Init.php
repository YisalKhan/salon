<?php

class SLN_Action_Init
{
    private $plugin;

    public function __construct(SLN_Plugin $plugin)
    {
        $this->plugin = $plugin;
        add_action('init',function(){
            $this->initEnum();
            if (is_admin()) {
                $this->initAdmin();
            } else {
                $this->initFrontend();
            }
        });
        $this->init();
    }

    function initEnum(){
        SLN_Enum_BookingStatus::init();
        SLN_Enum_CheckoutFields::init();
        SLN_Enum_DateFormat::init();
        SLN_Enum_DaysOfWeek::init();
        SLN_Enum_PaymentDepositType::init();
        if(class_exists('SLN_Enum_PaymentMethodProvider')){
            SLN_Enum_PaymentMethodProvider::addService('paypal', 'PayPal', 'SLN_PaymentMethod_Paypal');
            if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
                SLN_Enum_PaymentMethodProvider::addService('stripe', 'Stripe', 'SLN_PaymentMethod_Stripe');
            }
        }
        SLN_Enum_SmsProvider::init();
        SLN_Enum_TimeFormat::init();
    }

    private function init()
    {
        $p = $this->plugin;
        if(!defined("SLN_VERSION_CODECANYON") && defined("SLN_VERSION_PAY") && SLN_VERSION_PAY ) { $this->initLicense(); }

        if(!defined("SLN_VERSION_PAY")){
            $freemius = new SLN_Action_InitFreemius;
            $freemius->load();
        }

        new SLN_TaxonomyType_ServiceCategory(
            $p,
            SLN_Plugin::TAXONOMY_SERVICE_CATEGORY,
            array(SLN_Plugin::POST_TYPE_SERVICE)
        );
        $this->initSchedules();

        add_action('template_redirect', array($this, 'template_redirect'));
        new SLN_Privacy();
        new SLN_Action_InitScripts($this->plugin, is_admin());
        $this->initPolylangSupport();
        SLB_Discount_Plugin::getInstance();

        add_action('init', array($this, 'hook_action_init'));
        if (!SLN_Action_Install::isInstalled()) {
            add_action('init', function(){
                SLN_Action_Install::execute();
            });
        }

	if (is_admin()) {
	    new SLN_Welcome($p);
	}

	if(defined("SLN_VERSION_CODECANYON")){
            new SLN_Action_InitEnvatoAutomaticPluginUpdate();
        }
    }


    private function initAdmin()
    {
        $p = $this->plugin;
        new SLN_Metabox_Service($p, SLN_Plugin::POST_TYPE_SERVICE);
        new SLN_Metabox_Attendant($p, SLN_Plugin::POST_TYPE_ATTENDANT);
        new SLN_Metabox_Booking($p, SLN_Plugin::POST_TYPE_BOOKING);
        new SLN_Metabox_BookingActions($p, SLN_Plugin::POST_TYPE_BOOKING);

        new SLN_Admin_Calendar($p);
        new SLN_Admin_Tools($p);
        new SLN_Admin_Customers($p);
        new SLN_Admin_Reports($p);
        new SLN_Admin_Settings($p);

        add_action('admin_init', array($this, 'hook_admin_init'));
        add_action('admin_notices', array($this, 'hook_admin_notices'));
        $this->initAjax();
        new SLN_Action_InitComments($p);

	if (!current_user_can('delete_permanently_sln_booking')) {
	    $this->disablePermanentlyDeleteBookings();
	}
    }

    private function initFrontend()
    {
	add_action('parse_request', array(new SLN_Action_RescheduleBooking($this->plugin), 'execute'));
	add_action('parse_request', array(new SLN_Action_CancelBookingLink($this->plugin), 'execute'));
    }

    private function initAjax()
    {
        $callback = array($this->plugin, 'ajax');
        //http://codex.wordpress.org/AJAX_in_Plugins
        add_action('wp_ajax_salon', $callback);
        add_action('wp_ajax_nopriv_salon', $callback);
        add_action('wp_ajax_saloncalendar', $callback);
    }

    private function initSchedules() {
        add_filter('cron_schedules', array($this, 'cron_schedules'));

        if (!wp_get_schedule('sln_email_weekly_report')) {
            SLN_TimeFunc::startRealTimezone();
            if (((int)current_time('w')) === (SLN_Enum_DaysOfWeek::MONDAY) &&
                SLN_Func::getMinutesFromDuration(current_time('H:i')) < 8*60) {

                $time  = time();
                $time -= $time % (24*60*60);
            }
            else {
                $time  = SLN_TimeFunc::strtotime("next Monday");
            }

            $time += 8 * 60 * 60; // Monday 8:00
            wp_schedule_event($time, 'weekly', 'sln_email_weekly_report');
            unset($time);
            SLN_TimeFunc::endRealTimezone();
        }

        add_action('sln_sms_reminder', 'sln_sms_reminder');
        add_action('sln_email_reminder', 'sln_email_reminder');
        add_action('sln_sms_followup', 'sln_sms_followup');
        add_action('sln_email_followup', 'sln_email_followup');
        add_action('sln_email_feedback', 'sln_email_feedback');
        add_action('sln_cancel_bookings', 'sln_cancel_bookings');
        add_action('sln_email_weekly_report', 'sln_email_weekly_report');
    }

    public function hook_action_init()
    {
        $p = $this->plugin;
        SLN_Shortcode_Salon::init($p);
        SLN_Shortcode_SalonMyAccount::init($p);
        SLN_Shortcode_SalonMyAccount_Details::init($p);
        SLN_Shortcode_SalonCalendar::init($p);
        SLN_Shortcode_SalonAssistant::init($p);
        SLN_Shortcode_SalonServices::init($p);

        SLN_Enum_AvailabilityModeProvider::init();
        $this->plugin->addRepository(
            new SLN_Repository_BookingRepository(
                $this->plugin,
                new SLN_PostType_Booking($p, SLN_Plugin::POST_TYPE_BOOKING)
            )
        );

        $this->plugin->addRepository(
            new SLN_Repository_ServiceRepository(
                $this->plugin,
                new SLN_PostType_Service($p, SLN_Plugin::POST_TYPE_SERVICE)
            )
        );
        $this->plugin->addRepository(
            new SLN_Repository_AttendantRepository(
                $this->plugin,
                new SLN_PostType_Attendant($p, SLN_Plugin::POST_TYPE_ATTENDANT)
            )
        );
    }

    public function hook_admin_init()
    {
        new SLN_Action_Update($this->plugin);
    }

    public function hook_admin_notices()
    {
        if (current_user_can('install_plugins')) {
            $s = $this->plugin->getSettings();
            if (isset($_GET['sln-dismiss']) && $_GET['sln-dismiss'] == 'dismiss_admin_notices') {
                $s->setNoticesDisabled(true)->save();
            }
            if (!$s->getNoticesDisabled()) {
                $dismissUrl = add_query_arg(array('sln-dismiss' => 'dismiss_admin_notices'));
                echo $this->plugin->loadView('admin_notices', compact('dismissUrl'));
            }
        }
    }

    public function initPolylangSupport()
    {
        add_filter('pll_get_post_types', array($this, 'hook_pll_get_post_types'));
    }

    public function hook_pll_get_post_types($types)
    {
        unset ($types['sln_booking']);
        //decomment this to have "single language services and attendant
        //unset($types['sln_service']);
        //unset($types['sln_attendant']);

        return $types;
    }

    public function template_redirect() {
        $customerHash = isset($_GET['sln_customer_login']) ? sanitize_text_field(wp_unslash( $_GET['sln_customer_login'] )) : '';
        $feedback_id = isset($_GET['feedback_id']) ? sanitize_text_field(wp_unslash($_GET['feedback_id'])) : '';
        if (!empty($customerHash)) {
            $userid = SLN_Wrapper_Customer::getCustomerIdByHash($customerHash);
            if ($userid) {
                $user = get_user_by('id', (int) $userid);
                if ($user) {
                    $customer = new SLN_Wrapper_Customer($user);
                    if (!$customer->isEmpty()) {
                        wp_set_auth_cookie($user->ID, false);
                        do_action('wp_login', $user->user_login, $user);

                        // Create redirect URL without autologin code
                        $id = $this->plugin->getSettings()->getBookingmyaccountPageId();
                        if ($id) {
                            $url = get_permalink($id);
                            if(!empty($feedback_id)) {
                                $url .= '?feedback_id='. $feedback_id;
                            }
                        }else{
                            $url = home_url();
                        }
                        wp_redirect($url);
                        exit;
                    }
                }
            }
        }
    }

    public function cron_schedules($schedules) {
        $schedules['weekly'] = array(
            'interval' => 60 * 60 * 24 * 7,
            'display' => __('Weekly', 'salon-booking-system')
        );

        return $schedules;
    }

    private function initLicense()
    {
        global $sln_license;
        /** @var SLN_Update_Manager $sln_license */
        $sln_license = new SLN_Update_Manager(
            array(
                'slug'     => SLN_ITEM_SLUG,
                'basename' => SLN_PLUGIN_BASENAME,
                'name'     => SLN_ITEM_NAME,
                'version'  => SLN_VERSION,
                'author'   => SLN_AUTHOR,
                'store'    => SLN_STORE_URL,
            )
        );
    }

    public function disablePermanentlyDeleteBookings() {

	add_filter( 'pre_delete_post', function ($check, $post, $force_delete) {
	    if ($post->post_type === SLN_Plugin::POST_TYPE_BOOKING) {
		return false;
	    }
	    return $check;
	}, 10, 3);

	if (isset($_GET['post_type']) && $_GET['post_type'] === SLN_Plugin::POST_TYPE_BOOKING) {
	    add_action( 'admin_enqueue_scripts', function () {
		wp_enqueue_style('admin-disable-delete-permanently', SLN_PLUGIN_URL.'/css/admin-disable-delete-permanently.css', array(), SLN_Action_InitScripts::ASSETS_VERSION, 'all');
	    });
	}
    }
}

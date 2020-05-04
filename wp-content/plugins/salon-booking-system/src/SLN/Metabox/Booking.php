<?php

class SLN_Metabox_Booking extends SLN_Metabox_Abstract
{
    /** @var  SLN_Wrapper_Booking */
    private $booking;
    /** @var string */
    private $prevStatus;

    protected $fields = array(
            'amount' => 'float',
            'deposit' => 'float',
            'firstname' => '',
            'lastname' => '',
            'email' => '',
            'phone' => '',
            'address' => '',
            'date' => 'date',
            'time' => 'time',
            'services' => 'nofilter',
            'note' => '',
            'admin_note' => '',
            '_sln_calendar_event_id' => '',
        );

    public function add_meta_boxes()
    {
        $pt = $this->getPostType();
        add_meta_box(
            $pt.'-details',
            __('Booking details', 'salon-booking-system'),
            array($this, 'details_meta_box'),
            $pt,
            'normal',
            'high'
        );
    }


    protected function init()
    {
        parent::init();
        add_action('load-post.php', array($this, 'hookLoadPost'));

	if (!isset($_GET['mode']) || $_GET['mode'] !== 'sln_editor') {
	    add_action('in_admin_header', array($this, 'in_admin_header'));
	}
    }

    public function hookLoadPost()
    {
        if (
            (isset($_GET['post_type']) && $_GET['post_type'] == $this->getPostType())
            || (isset($_POST['post_type']) && $_POST['post_type'] == $this->getPostType())
        ) {
            $this->getPlugin()->messages()->setDisabled(true);
            if (isset($_GET['post'])) {
                $this->booking = $this->getPlugin()->createFromPost(intval($_GET['post']));
                $this->prevStatus = $this->booking->getStatus();
            }
            if (isset($_POST['post_ID'])) {
                $this->booking = $this->getPlugin()->createFromPost(intval($_POST['post_ID']));
                $this->prevStatus = $this->booking->getStatus();
            }

            return;
        }
    }

    public function details_meta_box($object, $box)
    {

        echo $this->getPlugin()->loadView(
            'metabox/booking',
            array(
                'metabox' => $this,
                'settings' => $this->getPlugin()->getSettings(),
                'booking' => $this->getPlugin()->createBooking($object),
                'postType' => $this->getPostType(),
                'helper' => new SLN_Metabox_Helper(),
                'mode' => isset($_GET['mode']) ? sanitize_text_field(wp_unslash($_GET['mode'])) : '',
                'date' => isset($_GET['date']) ? new SLN_DateTime(sanitize_text_field(wp_unslash($_GET['date'])),SLN_TimeFunc::getWpTimezone()) : null,
                'time' => isset($_GET['time']) ? new SLN_DateTime(sanitize_text_field(wp_unslash($_GET['time'])),SLN_TimeFunc::getWpTimezone()) : null,
            )
        );
        do_action($this->getPostType().'_details_meta_box', $object, $box);
    }

    protected function getFieldList()
    {
        $additional = array();

        foreach (SLN_Enum_CheckoutFields::toArray('not-customer') as $key => $label) {
            $additional[$key] = '';
        }

        return apply_filters('sln.metabox.booking.getFieldList', array_merge($this->fields, $additional));
    }

    private $disabledSavePost = false;

    public function save_post($post_id, $post)
    {
	if (
            get_post_field('post_type', $post_id) !== SLN_Plugin::POST_TYPE_BOOKING
            || $this->disabledSavePost
        ) {
            return;
        }

	if (preg_match('/post\-new\.php/i', $_SERVER['REQUEST_URI'])) {

	    $postnew = array(
		'ID'	      => $post_id,
		'post_author' => 0,
	    );

	    $this->disabledSavePost = true;

	    wp_update_post($postnew);

	    $this->disabledSavePost = false;
	}

        if (
            get_post_field('post_type', $post_id) !== SLN_Plugin::POST_TYPE_BOOKING
            || $this->disabledSavePost
            || !isset($_POST['_sln_booking_status'])
        ) {
            return;
        }

        $_POST['_sln_booking_services'] = $this->processServicesSubmission($_POST['_sln_booking']);
        parent::save_post($post_id, $post);
        $this->validate($_POST);
        if (isset($_SESSION['_sln_booking_user_errors'])) {
            return;
        }

        /** @var SLN_Wrapper_Booking $booking */
        $booking = $this->getPlugin()->createFromPost($post_id);
        do_action('sln.metabox.booking.pre_eval',$booking,$post_id);
        $booking->evalBookingServices();
        $booking->evalDuration();
        $this->disabledSavePost = true;
        $booking->setStatus($booking->getStatus());
        $this->disabledSavePost = false;
        $s = $booking->getStatus();
        $new = sanitize_text_field(wp_unslash($_POST['_sln_booking_status']));
        if (strpos($new, 'sln-b-') !== 0) {
            $new = SLN_ENUM_BookingStatus::PENDING_PAYMENT;
        }
        $postnew = array(
            'ID' => $post_id,
	    'post_author' => (int)$_REQUEST['post_author'], //save guest customer id 0
            'post_status' => $new,
        );
        $createUser = isset($_POST['_sln_booking_createuser'])  ? boolval($_POST['_sln_booking_createuser']) : false;
        if ($createUser) {
            $userid = $this->registration($_POST);
            if ($userid instanceof WP_Error) {
                return;
            }
            $postnew = array_merge(
                $postnew,
                array(
                    'ID' => $post_id,
                    'post_author' => $userid,
                )
            );
        }else{
            $mods = false;
            $user_id = (int) $_REQUEST['post_author'];
            $userdata = get_userdata( $user_id );
            foreach (['email'=>'user_email','firstname'=>'first_name','lastname'=>'last_name'] as $field => $wp_field) {
                $value = sanitize_text_field($_POST['_sln_booking_'.$field]);
                if($field === 'email' ){
                    if ( !empty($value) && !filter_var(
                            $value,
                            FILTER_VALIDATE_EMAIL
                        )
                    ) {
                        $this->addError(__('e-mail is not valid', 'salon-booking-system'));
                        return;
                    }
                }
                $original_value = $userdata->$wp_field;
                if( $value !== $original_value){
                    if($field === 'email' && array_intersect(['administrator'],$userdata->roles)){
                        continue;
                    }
                    $userdata->$wp_field = $value;
                    $mods = true;
                }
            }
            if($mods){
                wp_update_user($userdata);
            }
        }

        if (!empty($postnew)) {
            $this->disabledSavePost = true;
            $booking->setStatus($new);
            wp_update_post($postnew);
            $this->disabledSavePost = false;
        }

        if(($customer = $booking->getCustomer())){
            $fields = array_merge(array('phone', 'address'), array_keys( SLN_Enum_CheckoutFields::toArray('customer')));
            if($fields){
                foreach ($fields as $field) {

                    if(isset($_POST['_sln_'.$field])) $customer->setMeta($field,$_POST['_sln_'.$field]);
                }
            }

        }
        $this->addCustomerRole($booking);
        $booking->reload();
        if ($this->prevStatus != $booking->getStatus()) {
            $m = $this->getPlugin()->messages();
            $m->setDisabled(false);
            $m->sendByStatus($booking, $booking->getStatus());
        }
        $this->getPlugin()
            ->getBookingCache()
            ->processBooking($booking, false);
    }

    private function addCustomerRole($booking)
    {
        $user = new WP_User($booking->getUserId());
        $isNotAdmin = array_search('administrator', $user->roles) === false;
        $isNotSubscriber = array_search('subscriber', $user->roles) !== false;
        if ($isNotAdmin && $isNotSubscriber) {
            wp_update_user(
                array(
                    'ID' => $booking->getUserId(),
                    'role' => SLN_Plugin::USER_ROLE_CUSTOMER,
                )
            );
        }
    }

    private function processServicesSubmission($data)
    {
        $services = array();
        $services_ids = array_map('intval',$data['service']);
        if($services_ids)
        foreach ($services_ids as $serviceId) {
            $duration      = SLN_Func::convertToHoursMins($data['duration'][$serviceId]);
            $breakDuration = SLN_Func::convertToHoursMins($data['break_duration'][$serviceId]);

            $attendant = isset($data['attendants']) ? $data['attendants'][$serviceId] : (isset($data['attendant']) ? $data['attendant'] : null);
            $services[$serviceId] = array(
                'service' => $serviceId,
                'attendant' => $attendant,
                'price' => $data['price'][$serviceId],
                'duration' => $duration,
                'break_duration' => $breakDuration,
            );
        }
        return $services;
    }

    protected function registration($data)
    {
        $errors = wp_create_user($data['_sln_booking_email'], wp_generate_password(), $data['_sln_booking_email']);
        if (!is_wp_error($errors)) {
            wp_update_user(
                array(
                    'ID' => $errors,
                    'first_name' => $data['_sln_booking_firstname'],
                    'last_name' => $data['_sln_booking_lastname'],
                    'role' => SLN_Plugin::USER_ROLE_CUSTOMER,
                )
            );
            add_user_meta($errors, '_sln_phone', $data['_sln_booking_phone']);
            add_user_meta($errors, '_sln_address', $data['_sln_booking_address']);

            wp_new_user_notification($errors, null, 'both'); //, $values['password']);
        } else {
            $this->addError($errors->get_error_message());
        }

        return $errors;
    }

    private function validate($values)
    {
        if (SLN_Enum_CheckoutFields::isRequired('firstname') && empty($values['_sln_booking_firstname'])) {
            $this->addError(__('First name can\'t be empty', 'salon-booking-system'));
        }
        if (SLN_Enum_CheckoutFields::isRequired('lastname') && empty($values['_sln_booking_lastname'])) {
            $this->addError(__('Last name can\'t be empty', 'salon-booking-system'));
        }
        if (isset($_POST['_sln_booking_createuser']) && boolval($_POST['_sln_booking_createuser'])) {
            if (SLN_Enum_CheckoutFields::isRequired('email') && empty($values['_sln_booking_email'])) {
                $this->addError(__('e-mail can\'t be empty', 'salon-booking-system'));
            }
        }

        if ( !empty($values['_sln_booking_email']) && !filter_var(
                $values['_sln_booking_email'],
                FILTER_VALIDATE_EMAIL
            )
        ) {
            $this->addError(__('e-mail is not valid', 'salon-booking-system'));
        }
    }

    protected function addError($message)
    {
        $_SESSION['_sln_booking_user_errors'][] = $message;
    }


    protected function enqueueAssets()
    {
        parent::enqueueAssets();
        wp_enqueue_script(
            'salon-customBookingUser',
            SLN_PLUGIN_URL.'/js/admin/customBookingUser.js',
            array('jquery'),
            SLN_Action_InitScripts::ASSETS_VERSION,
            true
        );

	wp_localize_script(
            'salon-customBookingUser',
            'salonCustomBookingUser',
            array(
		'resend_notification_params' => apply_filters('sln_booking_resend_notification_params', array()),
		'resend_payment_params'	     => apply_filters('sln_booking_resend_payment_params', array()),
	    )
        );
    }

}


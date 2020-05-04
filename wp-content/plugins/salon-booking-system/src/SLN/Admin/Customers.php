<?php

class SLN_Admin_Customers extends SLN_Admin_AbstractPage {

	const PAGE = 'salon-customers';
    const PRIORITY = 10;

    public function __construct(SLN_Plugin $plugin)
    {
        parent::__construct($plugin);
	add_action('in_admin_header', array($this, 'in_admin_header'));
    }

    public function admin_menu()
    {
        $this->classicAdminMenu(__('Salon Customers', 'salon-booking-system'), __('Customers', 'salon-booking-system'));
        if ( ! isset($_REQUEST['id'])) {
            add_filter(
                'manage_'.get_plugin_page_hookname('salon-customers', 'salon').'_columns',
                array($this, 'users_columns')
            );
        }
    }

	public function show() {
		if (isset($_REQUEST['id'])) {
			$this->show_customer_page(intval($_REQUEST['id']));
		}
		else {
			$this->show_customers();
		}
	}

	public function show_customer_page($user_id) {

            $customer = new SLN_Wrapper_Customer(new WP_User($user_id));

            if (!empty($user_id) && $customer->isEmpty()) {
                wp_redirect(get_edit_user_link($user_id));
                exit;
            }

            if (isset($_POST['save'])) {
                $error = $this->save_customer($user_id);
            }

            $customer = new SLN_Wrapper_Customer(new WP_User($user_id));

            echo $this->plugin->loadView(
                'admin/_customer',
                array(
                    'customer' => $customer,
                    'new_link' => self::get_edit_customer_link(0),
                    'error'    => isset($error) ? $error : null,
                )
            );
        }

    private function check_email($email){
        if (email_exists($email)) {
                $error = new WP_Error();
                $error->add('email_exists', __('<strong>ERROR</strong>: This email is already registered, please choose another one.', 'salon-booking-system'));
                return $error;
        }
        return false;
    }

	private function save_customer($user_id) {
		$customer = [];
        $email = isset($_POST['sln_customer']['user_email']) ? sanitize_email( wp_unslash($_POST['sln_customer']['user_email']) ) : false;
        if(!$email){
            $error = new WP_Error();
            $error->add('missing_email', __('<strong>ERROR</strong>: This email is empty.', 'salon-booking-system'));
            return $error;
        }
        if (empty($_POST['id'])) {
            if(($error = $this->check_email($email))) return $error;
            $user_id = wp_create_user($email, wp_generate_password(), $email);
            $customer['user_email'] = $email;
        }
        else {
            $user_id = intval($_POST['id']);
            $user_info = get_userdata($user_id);
            $old_mail = $user_info->user_email;
            if($email !== $old_mail && !array_intersect(['administrator'],$user_info->roles) ){
                if(($error = $this->check_email($email))) return $error;
                $customer['user_email'] = $email;
            }
        }


        $customer['ID'] = $user_id;
        $customer['role'] = SLN_Plugin::USER_ROLE_CUSTOMER;
        if(isset($_POST['sln_customer']['first_name'])) $customer['first_name'] = sanitize_text_field(wp_unslash( $_POST['sln_customer']['first_name'] ));
        if(isset($_POST['sln_customer']['last_name'])) $customer['last_name'] = sanitize_text_field(wp_unslash( $_POST['sln_customer']['last_name'] ));
        wp_update_user($customer);

        foreach (array_map('sanitize_text_field',$_POST['sln_customer_meta']) as $k => $value) {
            update_user_meta($user_id, $k, $value);
        }

        wp_redirect(self::get_edit_customer_link($user_id));
        exit;
    }

	public function show_customers() {
		if (empty($_REQUEST)) {
			$referer = '<input type="hidden" name="wp_http_referer" value="'. esc_attr(esc_url(wp_unslash($_SERVER['REQUEST_URI']))) . '" />';
		} elseif (isset($_REQUEST['wp_http_referer'])) {
			$redirect = remove_query_arg(array('wp_http_referer', 'updated', 'delete_count'), esc_url(wp_unslash($_REQUEST['wp_http_referer'])));
			$referer = '<input type="hidden" name="wp_http_referer" value="' . esc_attr($redirect) . '" />';
		} else {
			$redirect = 'admin.php';
			$referer = '';
		}

		$table = new SLN_Admin_Customers_List();

		switch ($table->current_action()) {
                    case 'delete':
                        if (!is_multisite() && !empty($_REQUEST['users']) && is_array($_REQUEST['users'])) {
                            $users =  array_map('intval',$_REQUEST['users']);
                            foreach($users as $userId) {
                                wp_delete_user( $userId );
                            }

                            wp_redirect(admin_url('admin.php?page='.self::PAGE));
                            exit;

                        }
                        break;
		}

		$table->prepare_items();

		echo $this->plugin->loadView(
                    'admin/customers',
                    array(
                        'new_link' => self::get_edit_customer_link(0),
                        'table'    => $table,
                    )
		);
	}

	public function users_columns($users_columns) {
		$table = new SLN_Admin_Customers_List();

		return $table->get_columns();
	}



	public static function get_edit_customer_link($user_id) {

		return get_admin_url() . "admin.php?page=salon-customers&id=$user_id";
	}
}
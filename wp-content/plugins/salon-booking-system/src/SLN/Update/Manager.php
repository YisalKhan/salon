<?php

class SLN_Update_Manager
{
    private $data;
    private $processor;

    public function __construct($data)
    {
        $this->data = $data;
        add_action('admin_init', array($this, 'hook_admin_init'), 0);
        add_action('admin_menu', array($this, 'hook_admin_menu'));
        add_action('init', array($this, 'hook_init'));
        add_action('sln_update_check', array($this,'checkLicense'));
    }

    public function hook_init(){
        if(!wp_next_scheduled( 'sln_update_check' )){
            wp_schedule_event( time(), 'daily', 'sln_update_check' );
        }
    }
    public function hook_admin_menu()
    {
        $this->page = new SLN_Update_Page($this);
    }


    public function hook_admin_init()
    {
        global $pagenow;
        if ('plugins.php' == $pagenow || 'plugin-install.php' == $pagenow) {
            $this->processor = new SLN_Update_Processor($this);
        }
    }

    public function get($k)
    {
        if ($k == 'license_key') {
            return get_option($this->data['slug'].'_license_key');
        }
        if ($k == 'license_status') {
            return get_option($this->data['slug'].'_license_status');
        }
        if ($k == 'license_data') {
            return get_option($this->data['slug'].'_license_data');
        }

        return $this->data[$k];
    }

    /**
     * @param $license
     * @return null|WP_Error
     */
    public function activateLicense($key)
    {
        update_option($this->get('slug').'_license_key', $key);
        $response = $this->doCall('activate_license');
        if (is_wp_error($response)) {
            update_option($this->get('slug').'_license_status', $response->get_error_message());
        } else {
            update_option($this->get('slug').'_license_status', $response->license, true);
            update_option($this->get('slug').'_license_data', $response, true);
        }

        return $response;
    }

    /**
     * @return null|WP_Error
     * @throws Exception
     */
    public function deactivateLicense()
    {
        $response = $this->doCall('deactivate_license');
        if (is_wp_error($response)) {
            return $response;
        } elseif ($response->license == 'deactivated') {
            delete_option($this->get('slug').'_license_key');
            delete_option($this->get('slug').'_license_status');
            delete_option($this->get('slug').'_license_data');
        } else {
            update_option($this->get('slug').'_license_status', $response->license, true);
            update_option($this->get('slug').'_license_data', $response, true);
        }
    }

    public function getVersion(){
        $response = $this->doCall('get_version');

        if (is_wp_error($response)) {
            return $response;
        }

        if ($response && isset($response->sections)) {
            $response->sections = maybe_unserialize($response->sections);
        } else {
            $response = false;
        }

        return $response;
    }

    public function checkLicense(){
        $response = $this->doCall('check_license');

        if (is_wp_error($response)) {
            update_option($this->get('slug').'_license_status', $response->get_error_message());
        }

        if ($response->license == 'expired' ) {
            update_option($this->get('slug').'_license_status', $response->license, true);
            update_option($this->get('slug').'_license_data', $response, true);
        }

        return $response;
    }

    /**
     * @param $action
     * @param $license
     * @return string|WP_Error
     */
    public function doCall($action)
    {
        $license  = $this->get('license_key');
        $request  = array(
            'edd_action' => urlencode($action),
            'license'    => urlencode($license),
            'item_name'  => urlencode($this->get('name')),
            'url'        => urlencode(home_url()),
        );
        $response = wp_remote_get(
            add_query_arg($request, $this->get('store')),
            array('timeout' => 15, 'sslverify' => false)
        );
//        var_dump(array($this->get('store'), $request, $response));

        if (is_wp_error($response)) {
            return $response;
        } else {
            $license_data = json_decode(wp_remote_retrieve_body($response));

            return $license_data;
        }
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->get('license_status') == 'valid';
    }
}
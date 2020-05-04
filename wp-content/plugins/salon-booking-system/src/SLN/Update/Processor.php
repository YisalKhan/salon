<?php

// uncomment this line for testing
//set_site_transient('update_plugins', null);

class SLN_Update_Processor
{
    /** @var SLN_Update_Manager */
    private $updater;
    private $name = '';

    function __construct(SLN_Update_Manager $updater)
    {
        $this->updater = $updater;
        $this->name    = $updater->get('basename');
        $this->init();
    }

    public function init()
    {
        add_filter('pre_set_site_transient_update_plugins', array($this, 'check_update'));
        add_filter('plugins_api', array($this, 'hook_plugins_api'), 10, 3);
        if (current_user_can('update_plugins') && is_multisite()) {
            add_action('after_plugin_row_'.$this->name, array($this, 'show_update_notification'), 10, 2);
        }
    }

    /**
     * Check for Updates at the defined API endpoint and modify the update array.
     *
     * This function dives into the update API just when WordPress creates its update array,
     * then adds a custom API call and injects the custom plugin data retrieved from the API.
     * It is reassembled from parts of the native WordPress plugin update code.
     * See wp-includes/update.php line 121 for the original wp_update_plugins() function.
     *
     * @uses api_request()
     *
     * @param array $_transient_data Update array build by WordPress.
     * @return array Modified update array with custom plugin data.
     */
    function check_update($_transient_data)
    {
        global $pagenow;

        if (!is_object($_transient_data)) {
            $_transient_data = new stdClass;
        }

        if ('plugins.php' == $pagenow && is_multisite()) {
            return $_transient_data;
        }
        if (empty($_transient_data->response) || empty($_transient_data->response[$this->name])) {

            $version_info = $this->updater->getVersion();

            if (false !== $version_info && is_object($version_info) && isset($version_info->new_version)) {

                $this->did_check = true;

                if (version_compare($this->updater->get('version'), $version_info->new_version, '<')) {

                    $_transient_data->response[$this->name] = $version_info;

                }

                $_transient_data->last_checked         = time();
                $_transient_data->checked[$this->name] = $this->updater->get('version');

            }

        }

        return $_transient_data;
    }

    /**
     * show update nofication row -- needed for multisite subsites, because WP won't tell you otherwise!
     *
     * @param string $file
     * @param array  $plugin
     */
    public function show_update_notification($file)
    {
        if ($this->name != $file) {
            return;
        }

        // Remove our filter on the site transient
        remove_filter('pre_set_site_transient_update_plugins', array($this, 'check_update'), 10);

        $update_cache = get_site_transient('update_plugins');
        if (!is_object(
                $update_cache
            ) || empty($update_cache->response) || empty($update_cache->response[$this->name])
        ) {

            $cache_key    = md5('edd_plugin_'.sanitize_key($this->name).'_version_info');
            $version_info = get_transient($cache_key);
            if (false === $version_info) {

                $version_info = $this->updater->getVersion();

                set_transient($cache_key, $version_info, 3600);
            }


            if (!is_object($version_info)) {
                return;
            }

            if (version_compare($this->updater->get('version'), $version_info->new_version, '<')) {
                $update_cache->response[$this->name] = $version_info;
            }

            $update_cache->last_checked         = time();
            $update_cache->checked[$this->name] = $this->updater->get('version');

            set_site_transient('update_plugins', $update_cache);

        } else {

            $version_info = $update_cache->response[$this->name];

        }

        // Restore our filter
        add_filter('pre_set_site_transient_update_plugins', array($this, 'check_update'));
        if (!empty($update_cache->response[$this->name]) && version_compare(
                $this->updater->get('version'),
                $version_info->new_version,
                '<'
            )
        ) {

            // build a plugin list row, with update notification
            $wp_list_table = _get_list_table('WP_Plugins_List_Table');
            echo '<tr class="plugin-update-tr"><td colspan="'.$wp_list_table->get_column_count(
                ).'" class="plugin-update colspanchange"><div class="update-message">';

            $changelog_link = self_admin_url(
                'index.php?edd_sl_action=view_plugin_changelog&plugin='.$this->name.'&slug='.$this->updater->get(
                    'slug'
                ).'&TB_iframe=true&width=772&height=911'
            );

            if (empty($version_info->download_link)) {
                printf(
                    __(
                        'There is a new version of %1$s available. <a target="_blank" class="thickbox" href="%2$s">View version %3$s details</a>.',
                        'slnshsms'
                    ),
                    esc_html($version_info->name),
                    esc_url($changelog_link),
                    esc_html($version_info->new_version)
                );
            } else {
                printf(
                    __(
                        'There is a new version of %1$s available. <a target="_blank" class="thickbox" href="%2$s">View version %3$s details</a> or <a href="%4$s">update now</a>.',
                        'slnshsms'
                    ),
                    esc_html($version_info->name),
                    esc_url($changelog_link),
                    esc_html($version_info->new_version),
                    esc_url(
                        wp_nonce_url(
                            self_admin_url('update.php?action=upgrade-plugin&plugin=').$this->name,
                            'upgrade-plugin_'.$this->name
                        )
                    )
                );
            }

            echo '</div></td></tr>';
        }
    }


    /**
     * Updates information on the "View version x.x details" page with custom data.
     *
     * @uses api_request()
     * @see https://developer.wordpress.org/reference/hooks/plugins_api/
     * @param mixed  $result
     * @param string $action
     * @param object $args
     * @return object $_data
     */
    function hook_plugins_api($result, $action = '', $args = null)
    {
        if ($action != 'plugin_information') {
            return $result;
        }

        if (!isset($args->slug) || ($args->slug != $this->updater->get('slug'))) {
            return $result;
        }
        $api_response = $this->updater->getVersion();

        if (false !== $api_response) {
            $result = $api_response;
        }

        return $result; 
    }

}

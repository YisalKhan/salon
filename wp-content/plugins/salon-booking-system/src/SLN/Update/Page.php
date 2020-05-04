<?php

class SLN_Update_Page
{
    /** @var SLN_Update_Manager */
    private $updater;
    private $pageSlug;
    private $pageName;

    public function __construct(SLN_Update_Manager $updater)
    {
        $this->updater  = $updater;
        $this->pageName = $this->updater->get('name').' License';
        $this->pageSlug = $this->updater->get('slug').'-license';
        add_plugins_page($this->pageName, $this->pageName, 'manage_options', $this->pageSlug, array($this, 'render'));
        add_action('admin_notices', array($this, 'hook_admin_notices'));
    }

    public function hook_admin_notices()
    {
        if (!$this->updater->isValid() && (empty($_GET['page']) || $_GET['page'] != $this->pageSlug)) {
            $licenseUrl = admin_url('/plugins.php?page='.$this->pageSlug);
            ?>
            <div id="sln-setting-error" class="updated error">
                <h3><?php echo $this->updater->get('name').__(' needs a valid license', 'salon-booking-system') ?></h3>
                <p><a href="<?php echo $licenseUrl ?>"><?php _e('<p>Please insert your license key', 'salon-booking-system'); ?></a>
                </p>
            </div>
            <?php
        }
    }

    public function render()
    {

        if (isset($_POST['submit']) && isset($_POST['license_key'])) {
            $response = $this->updater->activateLicense($_POST['license_key']);
            if (is_wp_error($response)) {
                ?>
                <div id="sln-setting-error" class="updated error">
                    <p><?php echo 'ERROR: '.$response->get_error_code().' - '.$response->get_error_message() ?></p>
                </div>
                <?php
            } else {
                ?>
                <div id="sln-setting-error" class="updated success">
                    <p><?php echo __('License updated with success', 'salon-booking-system') ?></p>
                </div>
                <?php
            }
        }
        if (isset($_POST['license_deactivate'])) {
            $response = $this->updater->deactivateLicense();
            if (is_wp_error($response)) {
                ?>
                <div id="sln-setting-error" class="updated error">
                    <p><?php echo $response->get_error_code().' - '.$response->get_error_message() ?></p>
                </div>
                <?php
            } else {

                ?>
                <div id="sln-setting-error" class="updated success">
                    <p><?php echo __('License deactivated with success', 'salon-booking-system') ?></p>
                </div>
                <?php
            }
        }
        $license = $this->updater->get('license_key');
        $status  = $this->updater->get('license_status');
        $data    = $this->updater->get('license_data');
        ?>
        <div class="wrap">
        <h2><?php echo $this->pageName ?></h2>
        <form method="post" action="?page=<?php echo $this->pageSlug ?>">
            <table class="form-table">
                <tbody>
                <tr valign="top">
                    <th scope="row" valign="top">
                        <?php _e('License Key', 'salon-booking-system'); ?>
                    </th>
                    <td>
                        <input id="license_key" name="license_key" type="text" class="regular-text"
                               required="required"
                               value="<?php esc_attr_e($license); ?>"/>
                        <?php if (empty($license)): ?>
                            <label class="description" for="license_key"><?php _e(
                                    'Enter your license key',
                                    'salon-booking-system'
                                ); ?></label>
                        <?php endif ?>
                    </td>
                </tr>
                <?php if ($license) { ?>
                    <tr valign="top">
                        <th scope="row" valign="top">
                            <?php _e('License State', 'salon-booking-system'); ?>
                        </th>
                        <td>
                            <?php if ($status == 'valid') { ?>
                                <span style="color:green;"><?php _e('active', 'salon-booking-system'); ?></span>
                                <?php wp_nonce_field('nonce', 'nonce'); ?>&nbsp;
                                <input type="submit" class="button-secondary" name="license_deactivate"
                                       value="<?php _e('Deactivate License', 'salon-booking-system'); ?>"/>
                            <?php } elseif ($status == 'invalid') { ?>
                                <span style="color:red;"><?php _e('invalid', 'salon-booking-system'); ?></span>
                                <?php
                            } else { ?>
                                <span style="color:orange;">
                                    <?php _e('error', 'salon-booking-system'); ?>
                                    <?php echo ' '.$status ?>
                                </span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" valign="top">
                            <?php _e('Payment id', 'salon-booking-system'); ?>
                        </th>
                        <td>
                            <?php echo $data->payment_id ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" valign="top">
                            <?php _e('Customer name', 'salon-booking-system'); ?>
                        </th>
                        <td>
                            <?php echo $data->customer_name ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" valign="top">
                            <?php _e('Customer email', 'salon-booking-system'); ?>
                        </th>
                        <td>
                            <?php echo $data->customer_email ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" valign="top">
                            <?php _e('Expires', 'salon-booking-system'); ?>
                        </th>
                        <td>
                            <?php echo $data->expires ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <?php if ($status != 'valid') { ?>
                <?php submit_button(); ?>
            <?php } ?>
        </form>
        <?php
    }
}
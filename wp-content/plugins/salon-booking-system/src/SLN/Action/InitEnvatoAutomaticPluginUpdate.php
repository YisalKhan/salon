<?php

class SLN_Action_InitEnvatoAutomaticPluginUpdate
{
    protected $data;

    public function __construct() {

	$this->data = array(
	    'name' => SLN_ITEM_NAME,
	    'slug' => SLN_ITEM_SLUG,
	);

	add_action( 'plugins_loaded', array($this, 'init') );

	$this->pageName = $this->data['name'].' Codecanyon Automatic Update';
        $this->pageSlug = $this->data['slug'].'-codecanyon-automatic-update';

	add_action('admin_menu', array($this, 'hook_admin_menu'));
    }

    public function init() {

	include SLN_PLUGIN_DIR . '/envato-automatic-plugin-update/envato-plugin-update.php';

	$codecanyon_product_id = $this->get_codecanyon_product_id();

	if (!$codecanyon_product_id) {
	    return;
	}

	PresetoPluginUpdateEnvato::instance()->add_item(array(
	    'id'	=> $codecanyon_product_id,
	    'basename'	=> SLN_PLUGIN_BASENAME,
	));
    }

    public function hook_admin_menu() {
        add_plugins_page($this->pageName, $this->pageName, 'manage_options', $this->pageSlug, array($this, 'render'));
    }

    public function render() {

        if (isset($_POST['submit']) && isset($_POST['codecanyon_product_id'])) {

	    $this->save_codecanyon_product_id($_POST['codecanyon_product_id']);

	    ?>

	    <div id="sln-setting-error" class="updated success">
		<p><?php echo __('Codecanyon Product ID saved', 'salon-booking-system') ?></p>
	    </div>

	    <?php
        }

        $codecanyon_product_id = $this->get_codecanyon_product_id();

	?>

	<div class="wrap">
	<h2><?php echo $this->pageName ?></h2>
        <form method="post" action="?page=<?php echo $this->pageSlug ?>">
            <table class="form-table">
                <tbody>
		    <tr valign="top">
			<th scope="row" valign="top">
			    <?php _e('Codecanyon Product ID', 'salon-booking-system'); ?>
			</th>
			<td>
			    <input id="codecanyon_product_id" name="codecanyon_product_id" type="text" class="regular-text"
				   required="required"
				   value="<?php esc_attr_e($codecanyon_product_id); ?>"/>
			    <?php if (empty($codecanyon_product_id)): ?>
				<label class="description" for="codecanyon_product_id"><?php _e(
					'Enter your Codecanyon Product ID',
					'salon-booking-system'
				    ); ?></label>
			    <?php endif ?>
			</td>
		    </tr>
                </tbody>
            </table>
	    <?php submit_button(); ?>
        </form>
        <?php
    }

    protected function get_codecanyon_product_id() {
	return get_option($this->data['slug'].'_codecanyon_product_id');
    }

    protected function save_codecanyon_product_id($codecanyon_product_id) {
	update_option($this->data['slug'].'_codecanyon_product_id', $codecanyon_product_id);
    }

}
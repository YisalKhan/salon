<?php

class SLN_Welcome
{
    const STORE_SHOW_PAGE_KEY = '_sln_welcome_show_page';
    const MENU_SLUG	      = 'salon';

    protected $plugin;

    public function __construct(SLN_Plugin $plugin) {

	$this->plugin = $plugin;

	register_activation_hook(SLN_PLUGIN_BASENAME, array($this, 'plugin_activate'));

	if ( get_transient( self::STORE_SHOW_PAGE_KEY ) ) {

	    add_action('admin_init', array($this, 'redirect_to_welcome_page'));

	    // Enqueue the styles.
	    add_action( 'admin_enqueue_scripts', array($this, 'styles') );

	    add_action( 'admin_menu', array($this, 'welcome_page'), 50 );
	}
    }

    public function plugin_activate() {

	if (get_transient( self::STORE_SHOW_PAGE_KEY ) === false) {
	    // Transient max age is 60 seconds.
	    set_transient( self::STORE_SHOW_PAGE_KEY, 1 );
	}
    }

    public function welcome_page() {

	global $submenu;

	$menu_slug = self::MENU_SLUG;
	$hookname  = get_plugin_page_hookname( $menu_slug, '' );

	// Remove it with its actions.
	remove_all_actions( $hookname );

	if ( isset( $submenu[ $menu_slug ] ) ) {
	    $submenu_ref               = &$submenu;
	    $submenu_ref[ $menu_slug ] = array();
	}

	remove_all_actions( 'admin_notices' );
	remove_all_actions( 'network_admin_notices' );
	remove_all_actions( 'all_admin_notices' );
	remove_all_actions( 'user_admin_notices' );

	// Override menu action.
	add_action( $hookname, array($this, 'welcome_page_content') );
    }

    /**
     * Welcome page content.
     *
     * @since 1.0.0
     */
    public function welcome_page_content() {

	set_transient( self::STORE_SHOW_PAGE_KEY, 0 );

	$settings = $this->plugin->getSettings();

	echo $this->plugin->loadView('welcome', array('settings' => $settings));
    }

    /**
     * Enqueue Styles.
     *
     * @since 1.0.0
     */
    public function styles( $hook ) {

	$sln_welcome_sub_menu = get_plugin_page_hookname( self::MENU_SLUG, '' );

	// Add style to the welcome page only.
	if ( $hook != $sln_welcome_sub_menu ) {
	    return;
	}

	// Welcome page styles.
	wp_enqueue_style( 'sln_welcome_page_style', SLN_PLUGIN_URL . '/css/welcome.css', array(), SLN_Action_InitScripts::ASSETS_VERSION, 'all' );
	//Rtl support
	wp_style_add_data( 'sln_welcome_page_style', 'rtl', 'replace' );

	wp_enqueue_style( 'sln_welcome_page_animated_style', SLN_PLUGIN_URL . '/css/animate.css', array(), SLN_Action_InitScripts::ASSETS_VERSION, 'all' );
	wp_style_add_data( 'sln_welcome_page_animated_style', 'rtl', 'replace' );

	wp_enqueue_script(
            'sln_welcome_page_js',
            SLN_PLUGIN_URL.'/js/admin/welcome.js',
            array('jquery'),
            SLN_Action_InitScripts::ASSETS_VERSION,
            true
        );
    }

    public function redirect_to_welcome_page() {

	if (isset($_GET['page']) && $_GET['page'] == self::MENU_SLUG) {
	    return;
	}

	wp_safe_redirect( add_query_arg( array( 'page' => self::MENU_SLUG ), admin_url( 'admin.php' ) ) );

	exit();
    }

}

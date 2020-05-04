<?php

class SLN_Action_InitFreemius
{
	private $args = array(
        'id'                  => '3257',
        'slug'                => 'salon-booking-system',
        'type'                => 'plugin',
        'public_key'          => 'pk_e750486b23f30cab3c1e3005a4dd7',
        'is_premium'          => false,
        'has_addons'          => false,
        'has_paid_plans'      => false,
        'menu'                => array(
            'slug'           => 'salon',
            'account'        => false,
            'contact'        => false,
            'support'        => false,
        ),
    );

	function load(){
		$this->maybe_init();
		do_action( 'sbs_fs_loaded' );
	}

	function maybe_init(){
		global $sbs_fs;
		if ( ! isset( $sbs_fs ) ) {
			require_once SLN_PLUGIN_DIR . '/freemius/start.php';
			$sbs_fs = fs_dynamic_init( $this->args);
		}
		return $sbs_fs;
	}
}
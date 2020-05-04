<?php

namespace SLB_API\Listener\Events;

use SLN_Plugin;
use WP_User_Query;
use SLB_API\Third\OnesignalAPI;

class BookingEventsListener
{
    public function __construct()
    {
	add_action('sln.booking_builder.create.booking_created', array($this, 'event_created'), 10, 1);
    }

    public function event_created( $booking ) {

	$plugin   = SLN_Plugin::getInstance();
	$settings = $plugin->getSettings();

	if ( ! $settings->get('onesignal_new') || ! $booking ) {
	    return;
	}

	$query = new WP_User_Query(array(
	    'meta_query' => array(
		array(
		    'key'     => '_sln_onesignal_player_id',
		    'value'   => '',
		    'compare' => '!=',
		),
	    )
        ));

	$player_ids = array();

	foreach ($query->results as $user) {
	    $player_ids[] = $user->get('_sln_onesignal_player_id');
	}

	if ( ! $player_ids ) {
	    return;
	}

	$app_id  = $settings->get('onesignal_app_id');
	$message = $plugin->loadView('onesignal/notify', compact('booking'));

	try {
	    OnesignalAPI::notify($app_id, $player_ids, $message);
	} catch (\Exception $ex) {

	}
    }

}
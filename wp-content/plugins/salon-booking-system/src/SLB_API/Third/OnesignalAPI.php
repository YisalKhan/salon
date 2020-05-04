<?php

namespace SLB_API\Third;

class OnesignalAPI
{
    const URL = 'https://onesignal.com/api/v1/notifications';

    public static function notify($app_id, array $player_ids, $message)
    {
	$response = wp_remote_post(self::URL, array(
	    'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
	    'body'    => json_encode(array(
		'app_id'	     => $app_id,
		'contents'	     => array("en" => $message),
		'include_player_ids' => $player_ids,
	    )),
	));

	if ( is_wp_error($response) ) {
	    throw new \Exception('Request error');
	}

	$result = json_decode(wp_remote_retrieve_body($response), true);

	if ( ! $result || isset( $result['errors'] ) ) {
	    throw new \Exception('Api error');
	}

    }

}
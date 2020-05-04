<?php

class SLN_Action_Ajax_SearchBookings extends SLN_Action_Ajax_Abstract
{
	function execute(){
		$search = sanitize_text_field( $_POST['search'] );
		$day = sanitize_text_field( $_POST['day'] );
		try {
			$timestamp = new SLN_DateTime($day);
		} catch (Exception $e) {
			return [];
		}
		$repo = $this->plugin->getRepository(SLN_Plugin::POST_TYPE_BOOKING);
		return $repo->getForDaySearch($search,$timestamp);
	}
}
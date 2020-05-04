<?php

namespace SLB_API\Helper;

class TokenHelper {

	private $userApiTokenMetaKey = '_sln_customer_api_token';
	private $userApiTokenSalt    = 'e65729d07d49bb08fa0bcab2b39706fbac306531';

	public function getUserAccessToken($userId) {
		$accessToken = get_user_meta($userId, $this->userApiTokenMetaKey, true);

		if (empty($accessToken)) {
			$accessToken = $this->createUserAccessToken($userId);
		}

		return $accessToken;
	}

	public function isValidUserAccessToken($accessToken) {
		$userId = $this->getUserIdByAccessToken($accessToken);

		return !empty($userId);
	}

	public function deleteUserAccessToken($accessToken) {
		$userId = $this->getUserIdByAccessToken($accessToken);

		delete_user_meta($userId, $this->userApiTokenMetaKey);
	}

	public function createUserAccessToken($userId) {
		do {
			$accessToken = sha1($this->userApiTokenSalt.'-'.$userId.'-'.time());
		} while($this->getUserIdByAccessToken($accessToken));

		$this->saveUserAccessToken($userId, $accessToken);

		return $accessToken;
	}

	public function getUserIdByAccessToken($accessToken) {
		global $wpdb;

		$userId = $wpdb->get_var(
		    $wpdb->prepare(
			"SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key=%s AND meta_value=%s",
			$this->userApiTokenMetaKey,
			$accessToken
		    )
		);

		return $userId;
	}

	private function saveUserAccessToken($userId, $accessToken) {
		update_user_meta($userId, $this->userApiTokenMetaKey, $accessToken);
	}
}
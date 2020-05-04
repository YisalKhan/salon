<?php

class SLN_Action_Ajax_FacebookLogin extends SLN_Action_Ajax_Abstract
{
	protected $errors = array();

	public function execute()
	{
	    try {

		$accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : null;
		$userID	     = SLN_Helper_FacebookLogin::getUserIDByAccessToken($accessToken);

		//login
		$user = get_user_by('id', (int)$userID);
		wp_set_auth_cookie($user->ID, false);
		do_action('wp_login', $user->user_login, $user);

	    } catch (\Exception $ex) {
		$this->addError($ex->getMessage());
	    }

	    if ( ($errors = $this->getErrors()) ) {
		$ret = compact('errors');
	    } else {
		$ret = array('success' => 1);
	    }

	    return $ret;
	}

	protected function addError($err)
	{
		$this->errors[] = $err;
	}

	public function getErrors()
	{
		return $this->errors;
	}
}

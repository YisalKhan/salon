<?php

class SLN_Action_Ajax_UpdateProfile extends SLN_Action_Ajax_Abstract
{
    public function execute(){
    	
    	if (!is_user_logged_in()) {
			return array( 'redirect' => wp_login_url());
		}
		
    	
    	$updater = new SLN_Shortcode_SalonMyAccount_ProfileUpdater($this->plugin);
    	
            $result = $updater->dispatchForm();
            return $result;
    	
    }
}

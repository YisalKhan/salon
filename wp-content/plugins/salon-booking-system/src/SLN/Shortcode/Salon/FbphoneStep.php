<?php 
class SLN_Shortcode_Salon_FbphoneStep extends SLN_Shortcode_Salon_Step{

	protected function dispatchForm(){
		$user_id = get_current_user_id();
		$old_phone = get_user_meta( $user_id, '_sln_phone', true ) ?: ( isset($_SESSION['sln_detail_step']) && !empty($_SESSION['sln_detail_step']['phone']) ? $_SESSION['sln_detail_step']['phone'] : false );
		
		if(!$old_phone && (SLN_Enum_CheckoutFields::isRequired('phone') || $this->getPlugin()->getSettings()->get('sms_enabled')  )){
			if(  isset($_REQUEST['fb_access_token'])) return false;
			$phone = isset($_REQUEST['login_phone']) ? sanitize_text_field(wp_unslash($_REQUEST['login_phone'])) : '';
			if( empty($phone)  ){
          	  $this->addError(__('Mobile phone can\'t be empty', 'salon-booking-system'));
          	  return false;
        	} 
			if(!empty($phone)){
				$updated = update_user_meta( $user_id, '_sln_phone', $phone );

				$bb = $this->getPlugin()->getBookingBuilder();
				$filter = '';
				$bb->set('phone', SLN_Func::filter($phone, $filter));
				$bb->save();
			}
			if( (!$updated )){
			
				return false;
			}else{
				$_SESSION['sln_sms_dontcheck'] = true;
			}
		}
		return true;




		
		 
			
			
			
			
			
		
	}
	function isValid(){
		return $this->dispatchForm();
}
}
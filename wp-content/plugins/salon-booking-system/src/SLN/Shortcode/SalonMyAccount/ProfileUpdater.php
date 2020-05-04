<?php 
class SLN_Shortcode_SalonMyAccount_ProfileUpdater{

	function __construct(SLN_Plugin $plugin){
		$this->plugin    = $plugin;
	}

    public function dispatchForm(){
        if( !isset( $_POST['slnUpdateProfileNonceField'] ) || !wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['slnUpdateProfileNonceField'])), 'slnUpdateProfileNonce') )             
            {
                $this->addError(__('Wrong Nonce.', 'salon-booking-system'));
                return array( "status" => "error", "errors"=> $this->getErrors());
            }
        
        return $this->process();
    }

	private function process(){
        
		$values = $this->bindValues($_POST['sln']);
		$this->validate($values);
        if ($this->hasErrors()) {
            return array( "status" => "error", "errors"=> $this->getErrors());
        }
        $this->updateUser($values);
        $this->updateUserMeta($values);
        return array( "status" => "success");
	}
    private function updateUserMeta($values=array()){
        $user_meta_fields = array_merge(array('phone', 'address'), array_keys( SLN_Enum_CheckoutFields::toArray('customer')));
        foreach($user_meta_fields as $k){
            if(isset($values[$k])){
               update_user_meta(get_current_user_id(), '_sln_'.$k, $values[$k]);
            }
        }
    }

    private function updateUser($values=array()){
        if(array_intersect( array_keys($values),array('firstname','lastname','email') )){
             $current_user = wp_get_current_user();
             $userdata = array(
                    'ID' => get_current_user_id(), 
                    'first_name' => $values['firstname'], 
                    'last_name' => $values['lastname'],                    
             );
             if(!array_intersect(['administrator'],$current_user->roles)){
                $userdata = array_merge($userdata,[
                    'user_email' => $values['email'],
                    'nickname' => $values['email'],
                    'user_nicename' => $values['email'],
                    'display_name'=> $values['email'],
                ]);
             }
             if(!empty($values['password'])){
                $userdata['user_pass'] = $values['password'];
             }
             $updated = wp_update_user( $userdata );
             if ( is_wp_error( $updated ) ) {
                $this->addError(__('Something goes wrong', 'salon-booking-system'));
             }
            return $updated;
        }
    }

    private function isUserObjectField($field){
        return in_array($field, array('firstname','lastname','email'));
    }

	private function validate($values){
        $fields = array_merge(SLN_Enum_CheckoutFields::toArray('customer'),SLN_Enum_CheckoutFields::toArray('defaults'));
        foreach ($fields as $field => $label) {
            if (SLN_Enum_CheckoutFields::isRequiredNotHidden($field) && empty($values[$field]) ){
                $this->addError(__( $label.' can\'t be empty', 'salon-booking-system'));
                
            }
            if (!empty($values['email']) && $field === 'email' && !filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
                   $this->addError(__('e-mail is not valid', 'salon-booking-system'));
            }
        }
        if ($this->hasErrors()) {
            return false;
        }
        $current_user = wp_get_current_user();
        if ($values['email'] !== $current_user->user_email && email_exists($values['email'])) {
            $this->addError(__('E-mail exists', 'salon-booking-system'));
            return false;

        }
        if ($values['password'] != $values['password_confirm']) {

            $this->addError(__('Passwords are different', 'salon-booking-system'));
            return false;

        }
    }

	protected function bindValues($values)
    {
        $fields = array_keys(SLN_Enum_CheckoutFields::toArrayFullLabelsOnly());
        foreach ($fields as $field ) {
            $data[$field] = isset($values[$field]) ? sanitize_text_field($values[$field]) : '';
        }

        return $data;
    }

    protected function getPlugin()
    {
        return $this->plugin;
    }

    public function addError($err)
    {
        $this->errors[] = $err;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function hasErrors() {
        return !empty($this->errors);
    }
}
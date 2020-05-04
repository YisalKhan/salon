<?php

class SLN_Enum_CheckoutFields
{
    private static $fields = [];
    private static $defaults_fields;
    private static $checkoutFieldsSettings;
    private static $requiredByDefault = ['firstname','email'];
    private static $inited = false;

    public static function init()
    {
        $default_fields = array(
            'firstname' => [ 'label' => __('First name', 'salon-booking-system'), 'required' => true ],
            'lastname'  => __('Last name', 'salon-booking-system'),
            'email'     => [ 'label' => __('E-mail', 'salon-booking-system'), 'required' => true ],
            'phone'     => __('Mobile phone', 'salon-booking-system'),
            'address'   => __('Address', 'salon-booking-system'),
        );

        self::$defaults_fields = array_keys($default_fields);

        self::$checkoutFieldsSettings = SLN_Plugin::getInstance()->getSettings()->get('checkout_fields') ?: [];

        $additional_fields = apply_filters('sln.checkout.additional_fields',array());

        $fields = array_merge($default_fields,$additional_fields);

        foreach ($fields as $field => $opts ) {
            self::$fields[$field] = self::createField($opts);
        }
        self::$inited = true;
    }

    public static function toArray($context = 'all', $labels_only = true )
    {

        if($context == 'defaults'){
            $fields = self::getDefaultFields();
        }elseif($context == 'additional'){
            $fields = self::getAdditionalFields();
        }elseif($context == 'customer'){
            $fields = self::getCustomerFields();
        }
        elseif($context == 'customer-not-hidden'){
            $fields = self::getCustomerFieldsNotBookingHidden();
        }
        elseif($context == 'not-customer'){
            $fields = self::getNotCustomerFields();
        }elseif($context == 'required'){
            $fields = self::getRequiredFields();
        }elseif($context == 'booking-not-hidden'){
            $fields = self::getNotBookingHiddenFields();
        }elseif($context == 'customer-hidden'){
            $fields = self::getCustomerFieldsBookingHidden();
        }else{
            $fields = self::getFields();
        }

        $fields = array_map(function($field) use($labels_only){
            return $labels_only ? $field->get('label') : $field->getSettings();
        }, $fields );

        return $fields;
    }

    public static function toArrayFullLabelsOnly(){

	static $retToArrayFullLabelsOnly;

	if(null === $retToArrayFullLabelsOnly){
            $retToArrayFullLabelsOnly = self::toArrayFull();
        }

        return $retToArrayFullLabelsOnly;
    }

    public static function toArrayFullSettings(){

	static $retToArrayFullSettings;

	if(null === $retToArrayFullSettings){
            $retToArrayFullSettings = self::toArrayFull(false);
        }

        return $retToArrayFullSettings;
    }

    public static function toArrayFull($labels_only = true ){

	$ret = self::getFields();

	foreach([
	    'password' => __('Password', 'salon-booking-system'),
	    'password_confirm' => __('Confirm your password', 'salon-booking-system'),
	] as $field => $opts ) {
	    $ret[$field] = self::createField($opts);
	}

	$ret = array_map(function($field)use($labels_only){
	    return $labels_only ? $field->get('label') : $field->getSettings();
	}, $ret );

        return $ret;
    }

    public static function getLabel($key){
        if(($field = self::getField($key))){
            return $field->get('label');
        }
        return false;
    }

    public static function isHidden($key){
        if(($field = self::getField($key))){
            if (self::isRequiredByDefault($key)) {
                return false;
            }
            return self::getSettingsProp($key,'hide');
        }
        return false;
    }

    public static function isRequired($key){
        if(($field = self::getField($key))){
            if (self::isRequiredByDefault($key)) {
                return true;
            }
            return self::getSettingsProp($key,'require');
        }
        return false;
    }

    public static function getSettingLabel($key){
        if(($field = self::getField($key))){
            return $field->get('label').(self::isRequiredByDefault($key) ? ' '.__('(not editable)', 'salon-booking-system') : '');
        }
        return false;
    }

    public static function isRequiredNotHidden($key){
        return self::isRequired($key) && !self::isHidden($key);
    }

public static function isHiddenOrNotRequired($key){
   //return !self::isRequired($key, $checkoutFields) || self::isHidden($key, $checkoutFields);
   return !self::isRequired($key, self::$defaults_fields) || self::isHidden($key, self::$defaults_fields);
}

    public static function isRequiredByDefault($key){
        return in_array($key,self::$requiredByDefault);
    }

    protected static function createField($opts){
        return new SLN_CheckoutField($opts);
    }

    protected static function getField($key){
        $fields = self::getFields();
        return array_key_exists($key,$fields) ? $fields[$key] : false;
    }

    protected static function getFields(){
        if(!self::$inited){
            throw new Exception("SLN_Enum_CheckoutFields used before it's inited");
        }
        return self::$fields;
    }

    protected static function getSettingsProp($field,$prop){
        return (array_key_exists($field, self::$checkoutFieldsSettings) && is_array(self::$checkoutFieldsSettings[$field])
             && array_key_exists($prop, self::$checkoutFieldsSettings[$field])) ? self::$checkoutFieldsSettings[$field][$prop] : false ;
    }

    protected static function getDefaultFields(){
        static $retDefaultFields;
        if(null === $retDefaultFields){
            $fields = self::getFields();
            $defaults_fields = self::$defaults_fields;
            $retDefaultFields = array_filter($fields,function($key)use($defaults_fields){
                return in_array($key,$defaults_fields);
            }, ARRAY_FILTER_USE_KEY);
        }

        return $retDefaultFields;
    }

    protected static function getAdditionalFields(){
        static $retAdditionalFields;
        if(null === $retAdditionalFields){
            $fields = self::getFields();
            $defaults_fields = self::$defaults_fields;
            $retAdditionalFields = array_filter($fields,function($key)use($defaults_fields){
                return !in_array($key,$defaults_fields);
            }, ARRAY_FILTER_USE_KEY);
        }
        return $retAdditionalFields;
    }

    protected static function getRequiredFields(){
        static $retAdditionalFields;
        if(null === $retAdditionalFields){
            $fields = self::getFields();
            $retAdditionalFields = array_filter($fields,[self,'isRequired'], ARRAY_FILTER_USE_KEY);
        }

        return $retAdditionalFields;
    }

    protected static function getCustomerFields(){
        static $retCustomerFields;
        if(null === $retCustomerFields){
            $fields = self::getAdditionalFields();
            $retCustomerFields = array_filter($fields,function($field){
                return !empty($field->get('customer_profile'));
            });
        }

        return $retCustomerFields;
    }

    protected static function getCustomerFieldsNotBookingHidden(){
        static $retCustomerFieldsNotBookingHidden;
        if(null === $retCustomerFieldsNotBookingHidden){
            $fields = self::getAdditionalFields();
            $retCustomerFieldsNotBookingHidden = array_filter($fields,function($field){
                $customer = $field->get('customer_profile');
                return $customer && $customer !== 'booking_hidden';
            });
        }

        return $retCustomerFieldsNotBookingHidden;
    }

    protected static function getCustomerFieldsBookingHidden(){
        static $retCustomerFieldsBookingHidden;
        if(null === $retCustomerFieldsBookingHidden){
            $fields = self::getAdditionalFields();
            $retCustomerFieldsBookingHidden = array_filter($fields,function($field){
                $customer = $field->get('customer_profile');
                return $customer && $customer === 'booking_hidden';
            });
        }

        return $retCustomerFieldsBookingHidden;
    }

    protected static function getNotBookingHiddenFields(){
        static $retNotBookingHiddenFields;
        if(null === $retNotBookingHiddenFields){
            $fields = self::getAdditionalFields();
            $retNotBookingHiddenFields = array_filter($fields,function($field){
                $customer = $field->get('customer_profile');
                return !$customer || ($customer && $customer !== 'booking_hidden');
            });
        }

        return $retNotBookingHiddenFields;
    }

    protected static function getNotCustomerFields(){
        static $retNotCustomerFields;
        if(null === $retNotCustomerFields){
            $fields = self::getAdditionalFields();
            $retNotCustomerFields = array_filter($fields,function($field){
                return !$field->get('customer_profile');
            });
        }

        return $retNotCustomerFields;
    }

    public static function hasSelectFields(){
        static $retSelectFields;
        if(null === $retSelectFields){
            $fields = self::getFields();
            $retSelectFields = array_filter($fields,function($field){
                return $field->get('type') === 'select';
            });
        }

        return (bool) $retSelectFields;
    }
}
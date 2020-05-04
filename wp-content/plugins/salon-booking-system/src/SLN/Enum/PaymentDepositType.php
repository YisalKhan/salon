<?php

class SLN_Enum_PaymentDepositType extends SLN_Enum_AbstractEnum
{
    const _DEFAULT = '0';
    const FIXED    = 'fixed';
    protected static $labels;

    public static function toArray()
    {
        return self::getLabels();
    }

	public static function getLabel($key)
	{
		$labels = self::getLabels();
        return isset($labels[$key]) ? $labels[$key] : $labels[self::_DEFAULT];
	}

    public static function init()
    {
        self::$labels = array(
	        self::_DEFAULT => __('entire amount (disabled)', 'salon-booking-system'),
	        self::FIXED    => __('fixed', 'salon-booking-system'),
	        '10'           => '10%',
	        '20'           => '20%',
	        '30'           => '30%',
	        '40'           => '40%',
	        '50'           => '50%',
	        '60'           => '60%',
	        '70'           => '70%',
	        '80'           => '80%',
	        '90'           => '90%',
        );
    }
}
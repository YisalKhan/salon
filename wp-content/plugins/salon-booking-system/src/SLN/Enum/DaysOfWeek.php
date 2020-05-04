<?php

class SLN_Enum_DaysOfWeek extends SLN_Enum_AbstractEnum
{
    const SUNDAY    = 0;
    const MONDAY    = 1;
    const TUESDAY   = 2;
    const WEDNESDAY = 3;
    const THURSDAY  = 4;
    const FRIDAY    = 5;
    const SATURDAY  = 6;

    protected static $labels;

    public static function toArray()
    {
        return self::getLabels();
    }

    public static function getLabel($key)
    {
        $labels = self::getLabels();
        return isset($labels[$key]) ? $labels[$key] : $labels[self::SUNDAY];
    }

    public static function init()
    {
        self::$labels = array(
            self::SUNDAY    => __('Sunday', 'salon-booking-system'),
            self::MONDAY    => __('Monday', 'salon-booking-system'),
            self::TUESDAY   => __('Tuesday', 'salon-booking-system'),
            self::WEDNESDAY => __('Wednesday', 'salon-booking-system'),
            self::THURSDAY  => __('Thursday', 'salon-booking-system'),
            self::FRIDAY    => __('Friday', 'salon-booking-system'),
            self::SATURDAY  => __('Saturday', 'salon-booking-system'),
        );
    }
}
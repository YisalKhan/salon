<?php

class SLN_Enum_Interval
{
    const _DEFAULT = 15;
    private static $labels = array(
        '5' => '5',
        '10' => '10', 
        '15' => '15', 
        '20' => '20', 
        '30' => '30',
        '45' => '45',
        '60' => '60',
        '75' => '75',
        '90' => '90',
        '105' => '105',
        '120' => '120',
        '135' => '135',
        '150' => '150',
        '240' => '240',
    );

    public static function toArray()
    {
        return self::$labels;
    }

}
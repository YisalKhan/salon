<?php

class SLN_Enum_TimeFormat extends SLN_Enum_AbstractEnum
{
    const _DEFAULT = 'default';
    const _SHORT   = 'short';

    protected static $labels = array();
    private static $phpFormats = array(
        self::_DEFAULT => 'H:i',
        self::_SHORT => 'g:ia',
    );
    #http://www.malot.fr/bootstrap-datetimepicker/#options
    private static $jsFormats = array(
        self::_DEFAULT => 'hh:ii',
        self::_SHORT => 'H:iip'
    );

    public static function toArray()
    {
        return self::getLabels();
    }

    public static function getLabel($key)
    {
        $labels = self::getLabels();
        return isset($labels[$key]) ? $labels[$key] : $labels[self::_DEFAULT];
    }
    public static function getPhpFormat($key)
    {
        return isset(self::$phpFormats[$key]) ? self::$phpFormats[$key] : self::$phpFormats[self::_DEFAULT];
    }
    public static function getJsFormat($key)
    {
        return isset(self::$jsFormats[$key]) ? self::$jsFormats[$key] : self::$jsFormats[self::_DEFAULT];
    }

    public static function init()
    {
        $d = time();
        foreach(self::$phpFormats as $k => $v){
            self::$labels[$k] = SLN_TimeFunc::translateDate($v,$d); 
        }
    }
}
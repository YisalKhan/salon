<?php

class SLN_DateTime extends DateTime
{
    public static $Format = 'Y-m-d H:i:s';

    function __construct($time = "now", $timezone = null){
        if(null === $timezone) $timezone = self::getWpTimezone();
        return parent::__construct($time, $timezone);
    }

    public function __toString()
    {
        return (string)parent::format(self::$Format);
    }

    public static function getWpTimezone() {
        static $static_wp_timezone;
        if(null === $static_wp_timezone ){
            if(function_exists('wp_timezone')){
                $static_wp_timezone = wp_timezone();
                return $static_wp_timezone;
            }
            $static_wp_timezone = new DateTimeZone( self::getWpTimezoneString() );
        }
        return $static_wp_timezone;
    }

    public static function getWpTimezoneString() {
        static $static_timezone_string;

        if(null === $static_timezone_string ){

            if(function_exists('wp_timezone_string')){
                $static_timezone_string = wp_timezone_string();
                return $static_timezone_string;
            }

            $timezone_string = self::getTimezoneWpSettingsOption();
     
            if ( $timezone_string ) {
                $static_timezone_string = $timezone_string;
            }
         
            $offset  = (float) get_option( 'gmt_offset' );
            $hours   = (int) $offset;
            $minutes = ( $offset - $hours );
         
            $sign      = ( $offset < 0 ) ? '-' : '+';
            $abs_hour  = abs( $hours );
            $abs_mins  = abs( $minutes * 60 );
            $tz_offset = sprintf( '%s%02d:%02d', $sign, $abs_hour, $abs_mins );
         
            $static_timezone_string = $tz_offset;
        }
        return $static_timezone_string;        
    }

    public static function getTimezoneObjectFromWpSettingsOption() {
       return  new DateTimeZone(self::getTimezoneWpSettingsOption());
    }

    public static function getTimezoneWpSettingsOption() {
       return apply_filters('sln.date_time.get_timezone_wp_settings_option', get_option('timezone_string'));
    }

}

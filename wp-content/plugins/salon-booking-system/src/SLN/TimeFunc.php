<?php

class SLN_TimeFunc
{
    public static function startRealTimezone()
    {
        /*if ( ( $timezone = self::getTimezoneWpSettingsOption() ) ) {
            date_default_timezone_set($timezone);
        }
	   return $timezone;
       */
    }

    public static function endRealTimezone()
    {
        /*if ( ( $timezone = self::getTimezoneWpSettingsOption() ) ) {
            date_default_timezone_set('UTC');
        }
        return $timezone;
        */
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

    public static function translateDate($format,$timestamp = null, $timezone = null){
        if(function_exists('wp_date')){
            return wp_date($format,$timestamp, $timezone);
        }

        if ( ! is_numeric( $timestamp ) ) {
            $timestamp = time();
        }
        if(date_default_timezone_get () === 'UTC'){
            $datetime = new DateTime;
            $datetime->setTimestamp($timestamp);
            if(!$timezone) $timezone = self::getWpTimezone();
            $datetime->setTimezone($timezone);
            $timestamp = $timestamp + $datetime->getOffset();
        }

        return 'U' === $format ? $timestamp : date_i18n( $format,$timestamp  );
    }

    public static function currentDateTime(){
        if(function_exists('current_datetime')){
            return current_datetime();
        }
        return new DateTimeImmutable( 'now', self::getWpTimezone() );
    }

    public static function getCurrentTimestamp(){
        $datetime = self::currentDateTime();
        return $datetime->getTimestamp();
    }

    public static function getPostDateTime($post = null,$field = 'date', $source = 'local'){
        if(function_exists('get_post_datetime')){
            return get_post_datetime($post,$field, $source);
        }

        $post = get_post( $post );
 
        if ( ! $post ) {
            return false;
        }
     
        $wp_timezone = self::getWpTimezone();
     
        if ( 'gmt' === $source ) {
            $time     = ( 'modified' === $field ) ? $post->post_modified_gmt : $post->post_date_gmt;
            $timezone = new DateTimeZone( 'UTC' );
        } else {
            $time     = ( 'modified' === $field ) ? $post->post_modified : $post->post_date;
            $timezone = $wp_timezone;
        }
     
        if ( empty( $time ) || '0000-00-00 00:00:00' === $time ) {
            return false;
        }
     
        $datetime = date_create_immutable_from_format( 'Y-m-d H:i:s', $time, $timezone );
     
        if ( false === $datetime ) {
            return false;
        }
     
        return $datetime->setTimezone( $wp_timezone );
    }

    public static function getPostTimestamp($post = null,$field = 'date'){
        if(function_exists('get_post_timestamp')){
            return get_post_timestamp($post, $field);
        }
        $datetime = self::getPostDateTime( $post, $field );
 
        if ( false === $datetime ) {
            return false;
        }
     
        return $datetime->getTimestamp();
    }

    public static function evalPickedDate($date)
    {
        if (strpos($date, '-'))
            return $date;
        $initial = $date;
        $f = SLN_Plugin::getInstance()->getSettings()->getDateFormat();
        if ($f == SLN_Enum_DateFormat::_DEFAULT) {
            if(!strpos($date, ' ')) throw new Exception('bad date format');
            $date = explode(' ', $date);
            $k = self::guessMonthNum($date[1]);
            $ret = $date[2] . '-' . ($k < 10 ? '0' . $k : $k) . '-' . $date[0];
            return $ret;
        } elseif ($f == SLN_Enum_DateFormat::_SHORT) {
            $date = explode('/', $date);
            if (count($date) == 3)
                return sprintf('%04d-%02d-%02d', $date[2], $date[1], $date[0]);
            else
                throw new Exception('bad number of slashes');
        }elseif ($f == SLN_Enum_DateFormat::_SHORT_COMMA) {
            $date = explode('-', $date);
            if (count($date) == 3)
                return sprintf('%04d-%02d-%02d', $date[2], $date[1], $date[0]);
            else
                throw new Exception('bad number of commas');
        }else {
            return (new SLN_DateTime($date))->format('Y-m-d');
        }
        throw new Exception('wrong date ' . $initial . ' format: ' . $f);
    }

    public static function guessMonthNum($monthName)
    {
        $months = SLN_Func::getMonths();
        foreach ($months as $k => $v) {
            if ($monthName == $v) {
                return $k;
            }
        }
        foreach ($months as $k => $v) {
            if(SLN_Func::removeAccents($monthName) == SLN_Func::removeAccents($v)) {
                return $k;
            }
        }
        foreach ($months as $k => $v) {
            if (substr($monthName,0,3) == substr($v,0,3)) {
                return $k;
            }
        }
        foreach ($months as $k => $v) {
            if (substr(SLN_Func::removeAccents($monthName),0,3) == substr(SLN_Func::removeAccents($v),0,3)) {
                return $k;
            }
        }

        throw new \Exception(sprintf('month %s not found in months %s', $monthName, implode(', ', $months)));
    }

    public static function evalPickedTime($val){
        if ($val instanceof DateTime || $val instanceof DateTimeImmutable ) {
            $val = $val->format('H:i');
        }
        if (empty($val)) {
            return null;
        }
        if (strpos($val, ':') === false) {
            $val .= ':00';
        }
        return (new SLN_DateTime('1970-01-01 ' . sanitize_text_field($val)))->format('H:i');
    }

    public static function getTimezoneWpSettingsOption() {
       return apply_filters('sln.date_time.get_timezone_wp_settings_option', get_option('timezone_string'));
    }

    public static function strtotime($val){
        return (new SLN_DateTime($val))->getTimestamp();
    }

    public static function date($format,$timestamp = null){
        $timestamp = $timestamp === null ? time() : $timestamp;
        return (new SLN_DateTime)->setTimestamp($timestamp)->format($format);
    }
}

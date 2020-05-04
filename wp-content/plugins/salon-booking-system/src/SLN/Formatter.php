<?php

class SLN_Formatter
{
    private $plugin;

    public function __construct(SLN_Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    public function money($val, $showFree = true, $useDefaultSep = true, $removeDecimals = false, $htmlEntityDecode = false)
    {
        $s = $this->plugin->getSettings();
        $isLeft = $s->get('pay_currency_pos') == 'left';
        $rightSymbol = $isLeft ? '' : $s->getCurrencySymbol();
        $rightSymbol = $htmlEntityDecode ? html_entity_decode($rightSymbol) : $rightSymbol;
        $leftSymbol = $isLeft ? $s->getCurrencySymbol() : '';
        $leftSymbol = $htmlEntityDecode ? html_entity_decode($leftSymbol) : $leftSymbol;
        
        if ($showFree && $val <= 0) {
            $money = '<span class="sln-service-price-free">' . __('free','salon-booking-system') . '</span>';
        }
        else {
            if ($useDefaultSep) {
                $decimalSeparator  = $s->getDecimalSeparatorDefault();
                $thousandSeparator = $s->getThousandSeparatorDefault();
            }
            else {
                $decimalSeparator  = $s->getDecimalSeparator();
                $thousandSeparator = $s->getThousandSeparator();
            }

            $decimals = $removeDecimals && floor($val) === floatval($val) ? 0 : 2;
            $money = ($leftSymbol . number_format($val, $decimals, $decimalSeparator, $thousandSeparator) . $rightSymbol);
        }

        return $money;
    }

    public function moneyFormatted($val, $showFree = true) {
        return $this->money($val, $showFree, false, true);
    }

    public function datetime($val)
    {
        return self::date($val).' '.self::time($val);
    }

    public function date($val)
    {
        if ($val instanceof DateTime || $val instanceof DateTimeImmutable) {
            $val = $val->getTimestamp();
        }else{
            $val = SLN_TimeFunc::strtotime($val);
        }

        $f = $this->plugin->getSettings()->getDateFormat();
        $phpFormat = SLN_Enum_DateFormat::getPhpFormat($f);
        remove_filter( 'date_i18n', 'wp_maybe_decline_date' ); 
        $formatted  = ucwords(SLN_TimeFunc::translateDate($phpFormat, $val ));
        add_filter( 'date_i18n', 'wp_maybe_decline_date' ); 
        return $formatted;
    }

    public function time($val)
    {
	    $f         = $this->plugin->getSettings()->getTimeFormat();
	    $phpFormat = SLN_Enum_TimeFormat::getPhpFormat( $f );
	    if ( $val instanceof DateTime || $val instanceof DateTimeImmutable ) {
		    $val = $val->getTimestamp();
	    } elseif ( $val instanceof \Salon\Util\Time ) {
		    $val = $val->toDateTime()->getTimestamp();
	    }else{
            $val = SLN_TimeFunc::strtotime( $val );
        }

	    return SLN_TimeFunc::translateDate( $phpFormat, $val );
    }

    public function phone($val){
        $s = $this->plugin->getSettings();
        $prefix = $s->get('sms_prefix');
        if($s->get('sms_trunk_prefix') && strpos($val,'0') === 0){
            $val = substr($val,1);
        }
        $val = str_replace(' ','',$val);
        return $prefix . $val;
    }
}

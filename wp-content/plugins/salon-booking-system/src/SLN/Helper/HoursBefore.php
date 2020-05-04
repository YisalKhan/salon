<?php

class SLN_Helper_HoursBefore
{
    private $settings;
    private $from;
    private $to;
    private $fromString;
    private $toString;
    private $fromDate;
    private $toDate;

    public function __construct(SLN_Settings $settings)
    {

        //https://weston.ruter.net/2013/04/02/do-not-change-the-default-timezone-from-utc-in-wordpress/
        //https://wordpress.org/support/topic/why-does-wordpress-set-timezone-to-utc


        $this->settings = $settings;
        $this->from     = $this->settings->getHoursBeforeFrom();
        $this->to       = $this->settings->getHoursBeforeTo();

        $txt = SLN_Func::getIntervalItems();
        if ($this->from) {
            $this->fromString = $txt[$this->from];
        }
        if ($this->to) {
            $this->toString = $txt[$this->to];
        }
        $now = new SLN_DateTime(SLN_TimeFunc::date('Y-m-d H:i:00'));
        $tmp = $now->format('i');
        $i             = SLN_Plugin::getInstance()->getSettings()->getInterval();
        $diff = $tmp % $i;
        if($diff > 0)
            $now->modify('+'.( $i - $diff).' minutes');
        $this->fromDate = $now;
        //$now->setTime($now->format('H'), $minutes);
        $this->toDate = $now2 = clone $now;
        if ($this->from) {
            $now->modify($this->from);
        }
        if ($this->to) {
            $now2->modify($this->to);
        }
        $str = $this->getHoursBeforeString();
        SLN_Plugin::addLog(__CLASS__.'Initialized with'.print_r($str,true));


    }

    public function getFromDate()
    {
        return $this->fromDate;
    }

    public function getToDate()
    {
        return $this->toDate;
    }


    public function check(DateTime $date)
    {
        return $this->isValidFrom($date) && $this->isValidTo($date);
    }

    public function isValidFrom(DateTime $date)
    {
        return $date >= $this->getFromDate();
    }

    public function isValidTo($date)
    {
        $to = $this->getToDate();
        if (!$to) {
            return true;
        }

        return $date <= $to;
    }

    public function getHoursBefore()
    {
        $from = $this->from;
        $to   = $this->to;

        return (object)compact('from', 'to');
    }

    public function getHoursBeforeString()
    {
        $txt = SLN_Func::getIntervalItems();
        $ret = $this->getHoursBefore();
        if ($ret->from) {
            $ret->from = $txt[$ret->from];
        }
        if ($ret->to) {
            $ret->to = $txt[$ret->to];
        }

        return $ret;
    }

    public function getCountDays()
    {
        return SLN_Func::countDaysBetweenDatetimes($this->getFromDate(), $this->getToDate());
    }
}

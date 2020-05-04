<?php

use Salon\Util\Date;

class SLN_Helper_HolidayItem
{
    private $data;
    private $weekDayRules;

    function __construct($data, $weekDayRules = null)
    {
        $this->data              = $data;
        $this->data['from_date'] = isset($this->data['from_date']) ? $this->data['from_date'] : '0';
        $this->data['to_date']   = isset($this->data['to_date']) ? $this->data['to_date'] : '0';
        $this->data['from_time'] = isset($this->data['from_time']) ? $this->data['from_time'] : '00:00';
        $this->data['to_time']   = isset($this->data['to_time']) ? $this->data['to_time'] : '00:00';

        $this->weekDayRules = $weekDayRules;
    }

    public function isValidDate($date) 
    {
	    if ( $date instanceof DateTime || $date instanceof DateTimeImmutable ) {
		    $date = $date->format( 'Y-m-d' );
	    } elseif ( $date instanceof Date ) {
		    $date = $date->toString();
	    }

        
        if (!$this->isDateContained($date)) {
            return true;
        }

        $ret = $this->processWeekDayRules($date);
        if ($ret !== null) {
            return $ret;
        } else {
            return ($this->isValidTime($date) || $this->isValidTime($date.' 23:59:59'));
        }
    }

    public function getData(){
        return $this->data;
    }

    public function isDateContained($date){
        $timestampDate = (new SLN_DateTime($date))->getTimestamp();
        $min           = (new SLN_DateTime($this->data['from_date']))->getTimestamp() ;
        $max           = (new SLN_DateTime($this->data['to_date'].' 23:59:59'))->getTimestamp();
        return $timestampDate >= $min && $timestampDate <= $max;
    }    

    private function processWeekDayRules($date)
    {
        $rules = $this->weekDayRules;
        if (empty($rules)) {
            return;
        }
        $weekDay = (int) ((new SLN_DateTime($date))->format("w"));
        if (isset($rules[$weekDay]) && !empty($rules[$weekDay])) {
            $rules = $rules[$weekDay];
            for ($i = 0; $i < count($rules['from']); $i++) {
                $from = $date.' '.$rules['from'][$i];
                $to   = $date.' '.$rules['to'][$i];
                if ($this->isValidTime($from) || $this->isValidTime($to)) {
                    return true;
                }
            }

            return false;
        }
    }

    public function isValidTime($date)
    {
        $date = (new SLN_DateTime($date))->getTimestamp();
        $from = (new SLN_DateTime($this->data['from_date'].' '.$this->data['from_time']))->getTimestamp();
        $to   = (new SLN_DateTime($this->data['to_date'].' '.$this->data['to_time']))->getTimestamp();

        return !($date >= $from && $date < $to);
    }

    /**
     * @param null $weekDayRules
     */
    public function setWeekDayRules($weekDayRules)
    {
        $this->weekDayRules = $weekDayRules;
    }


}

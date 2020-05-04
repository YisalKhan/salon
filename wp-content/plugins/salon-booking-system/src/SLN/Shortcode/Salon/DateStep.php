<?php

class SLN_Shortcode_Salon_DateStep extends SLN_Shortcode_Salon_Step
{

    protected function dispatchForm()
    {
        $bb     = $this->getPlugin()->getBookingBuilder();
        if(isset($_POST['sln'])){
                $date   = SLN_Func::filter(sanitize_text_field( wp_unslash( $_POST['sln']['date']  ) ), 'date');
                $time   = SLN_Func::filter(sanitize_text_field( wp_unslash( $_POST['sln']['time']  ) ), 'time');
        }
        $bb
            ->removeLastID()
            ->setDate($date)
            ->setTime($time);
        $obj = new SLN_Action_Ajax_CheckDate($this->getPlugin());
        $obj
            ->setDate($date)
            ->setTime($time)
            ->execute();
        foreach ($obj->getErrors() as $err) {
            $this->addError($err);
        }
        if (!$this->getErrors()) {
            $bb->save();

            return true;
        }
    }


}

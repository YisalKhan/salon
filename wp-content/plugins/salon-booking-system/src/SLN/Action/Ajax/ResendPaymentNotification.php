<?php

class SLN_Action_Ajax_ResendPaymentNotification extends SLN_Action_Ajax_Abstract
{
    public function execute()
    {
       if(!current_user_can( 'manage_salon' )) throw new Exception('not allowed');
        $booking = new SLN_Wrapper_Booking(intval($_POST['post_id']));
        $mail = sanitize_email(wp_unslash($_POST['emailto']));
        if(isset($mail)){
            $p = $this->plugin;

            $args       = compact('booking');
            $args['to'] = $mail;
            $p->sendMail('mail/status_pending_payment', $args);

            return array('success' => __('E-mail sent', 'salon-booking-system'));
        }else{
            return array('error' => __('Please specify an email', 'salon-booking-system'));
        }
 
       return $ret;
    }
}

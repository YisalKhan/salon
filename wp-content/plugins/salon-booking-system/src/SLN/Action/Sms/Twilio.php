<?php

class SLN_Action_Sms_Twilio extends SLN_Action_Sms_Abstract
{
    public function send($to, $message)
    {
        $url  = "https://api.twilio.com/2010-04-01/Accounts/{$this->getAccount()}/Messages.json";

        $auth = base64_encode($this->getAccount().':'.$this->getPassword());

        $channel = '';

        if ($this->isWhatsAppEnabled()) {
            $channel = 'whatsapp:';
        }

        $args = array(
            'headers' => array('Authorization' => " Basic $auth"),
            'body'    => array(
                'To'   => $channel . $this->processTo($to),
                'From' => $channel . $this->getFrom(),
                'Body' => $message,
            ),
        );

        $response = wp_remote_post($url, $args);
        $body     = json_decode(wp_remote_retrieve_body($response));

        if (!empty($body->code)) {
            $this->createException('Twilio: '.$body->message, 1000);
        }
    }

    protected function isWhatsAppEnabled()
    {
        return $this->plugin->getSettings()->get('whatsapp_enabled');
    }

}

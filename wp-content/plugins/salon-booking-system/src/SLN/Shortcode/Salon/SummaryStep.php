<?php

class SLN_Shortcode_Salon_SummaryStep extends SLN_Shortcode_Salon_Step
{
    const SLOT_UNAVAILABLE = 'slotunavailable';

    protected function dispatchForm()
    {
        $bb     = $this->getPlugin()->getBookingBuilder();
        $value = isset($_POST['sln']) && isset($_POST['sln']['note']) ? sanitize_text_field(wp_unslash($_POST['sln']['note'])) : '';
        if ( ! $bb->getLastBooking()) {
            if ($bb->isValid()) {
                $bb->set('note', SLN_Func::filter($value));
                $bb->save();
                do_action('sln.shortcode.summary.dispatchForm.before_booking_creation', $this, $bb);
                if ( ! $this->hasErrors()) {
                    $bb->create();
                    do_action('sln.shortcode.summary.dispatchForm.after_booking_creation', $bb);
                    if ($this->getPlugin()->getSettings()->get('confirmation')) {
                        $this->getPlugin()->messages()->sendSummaryMail($bb->getLastBooking());
                    }
                }
            } else {
                $this->addError(self::SLOT_UNAVAILABLE);
            }
        }

        return ! $this->hasErrors();
    }

    public function render()
    {
        $bb = $this->getPlugin()->getBookingBuilder();
        if ($bb->getLastBooking()) {
            $data = $this->getViewData();
            $this->redirect(
                add_query_arg(array('submit_'.$this->getStep() => 1), $data['formAction'])
            );
        } elseif ( ! $bb->getServices()) {
            $this->redirect(
                add_query_arg(array('sln_step_page' => 'services'))
            );
        } else {
            return parent::render();
        }
    }

    public function redirect($url)
    {
        if ($this->isAjax()) {
            throw new SLN_Action_Ajax_RedirectException($url);
        } else {
            wp_redirect($url);
        }
    }

    private function isAjax()
    {
        return defined('DOING_AJAX') && DOING_AJAX;
    }
}

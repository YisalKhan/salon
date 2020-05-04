<?php // algolplus

class SLN_Action_Ajax_SalonCalendar extends SLN_Action_Ajax_Abstract
{
	private $errors = array();

	public function execute()
	{
            $atts = isset($_REQUEST['attrs']) ? $_REQUEST['attrs'] : array();

            $visibility = isset($atts['visibility']) ? $atts['visibility'] : '';

            if (!is_user_logged_in() && $visibility !== SLN_Shortcode_SalonCalendar::VISIBILITY_PUBLIC) {
                return array( 'redirect' => wp_login_url());
            }

            SLN_TimeFunc::startRealTimezone();

            $plugin = SLN_Plugin::getInstance();

            $obj    = new SLN_Shortcode_SalonCalendar($plugin,$atts );

            $ret    = array();
            $ret['content'] = $obj->getContent();

            if ($errors = $this->getErrors()) {
		$ret = compact('errors');
            } else {
                $ret['success'] = 1;
            }

            SLN_TimeFunc::endRealTimezone();

            return $ret;
	}

	protected function addError($err)
	{
		$this->errors[] = $err;
	}

	public function getErrors()
	{
		return $this->errors;
	}
}

<?php 

class SLN_Action_Ajax_AddHolydayRule extends SLN_Action_Ajax_Abstract
{
	private $errors = array();

	public function execute()
	{
		if (!is_user_logged_in()) {
			return array( 'redirect' => wp_login_url());
		}

		$plugin = SLN_Plugin::getInstance();
		$settings = $plugin->getSettings();

		if(current_user_can('manage_salon')) {
			$data = array();

			$data['from_date']	= sanitize_text_field(wp_unslash($_POST['rule']['from_date']));
			$data['to_date']	= sanitize_text_field(wp_unslash($_POST['rule']['to_date']));
			$data['from_time']	= sanitize_text_field(wp_unslash($_POST['rule']['from_time']));
			$data['to_time']	= sanitize_text_field(wp_unslash($_POST['rule']['to_time']));
			$data['daily']		= true;
			if(!empty($data['from_date']) && !empty($data['to_date']) && !empty($data['from_time']) && !empty($data['to_time']) && $this->validateDate($data['from_date']) && $this->validateDate($data['to_date']) ){
			    $applied = apply_filters('sln.add-holiday-rule.add-holidays-daily', false, $data);

			    if (!$applied) {
				$holidays_rules = $settings->get('holidays_daily')?:array();

				//$rule = new SLN_Helper_HolidayItem($rule);
				$holidays_rules[] = $data;

				//$holidays_rules = SLN_Helper_HolidayItems::processSubmission($holidays_rules);

				$settings->set('holidays_daily', $holidays_rules);
				$settings->save();
			    }

			    $bc = $plugin->getBookingCache();
			    $bc->refresh($data['from_date'],$data['to_date']);
			}else{
				$this->addError(__("Something gone wrong with the selection. Please reselect the holyday.", 'salon-booking-system'));
			}
		} else {
			$this->addError(__("You don't have permissions", 'salon-booking-system'));
		}

		$holidays_rules = apply_filters('sln.get-day-holidays-rules', $settings->getDailyHolidayItems());

		if ($errors = $this->getErrors()) {
			$ret = compact('errors');
		} else {
			$ret = array('success' => 1,'rules'=>$holidays_rules);
		}

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

	public function validateDate($date, $format = 'Y-m-d')
	{
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) === $date;
	}
}

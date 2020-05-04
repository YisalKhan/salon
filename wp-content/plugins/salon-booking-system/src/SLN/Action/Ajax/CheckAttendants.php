<?php

class SLN_Action_Ajax_CheckAttendants extends SLN_Action_Ajax_Abstract{
	const STATUS_ERROR = -1;
    const STATUS_UNCHECKED = 0;
    const STATUS_CHECKED = 1;

    /** @var  SLN_Wrapper_Booking_Builder */
    protected $bb;
    /** @var  SLN_Helper_Availability */
    protected $ah;

    protected $date;
    protected $time;
    protected $errors = array();

    public function execute(){
        $this->setBookingBuilder($this->plugin->getBookingBuilder());
        $this->setAvailabilityHelper($this->plugin->getAvailabilityHelper());

        $this->bindDate($_POST);

        $services = isset($_POST['_sln_booking']) && is_array($_POST['_sln_booking']) && isset($_POST['_sln_booking']['service']) && is_array($_POST['_sln_booking']['service']) ? array_map('intval',$_POST['_sln_booking']['service']) : intval($_POST['_sln_booking']['service']) ;

        $bookingData = $_POST['_sln_booking'];

        $selected_service_id = !empty($_POST['_sln_booking_service_select']) ? intval($_POST['_sln_booking_service_select']) : false;

	$ret = $this->initAllAttentansForAdmin($_POST['post_ID'], $services, $bookingData, $selected_service_id);

        $ret = array(
            'success'  => 1,
            'attendants' => $ret,
        );

        return $ret;
    }

    public function initAllAttentansForAdmin($bookingID, $services, $bookingData, $selected_service_id = null)
    {
        $date = $this->getDateTime();
        $this->ah->setDate($date, $this->plugin->createBooking(intval($bookingID)));

        $ret = array();

        $selected_service =  $selected_service_id ? apply_filters('sln.booking_services.buildService', new SLN_Wrapper_Service($selected_service_id)) : false;
        $attendants_repo = $this->plugin->getRepository(SLN_Plugin::POST_TYPE_ATTENDANT);
        $attendants = $attendants_repo->getIds() ?: array();

	$data = array();

	foreach ($services as $sId) {
            $data[$sId] = array(
                'service'        => $sId,
                'attendant'      => sanitize_text_field(wp_unslash($bookingData['attendants'][$sId])),
                'price'          => sanitize_text_field(wp_unslash($bookingData['price'][$sId])),
                'duration'       => SLN_Func::convertToHoursMins(sanitize_text_field(wp_unslash($bookingData['duration'][$sId]))),
                'break_duration' => SLN_Func::convertToHoursMins(sanitize_text_field(wp_unslash($bookingData['break_duration'][$sId]))),
            );
        }

        foreach ($attendants as $k => $attendant_id) {

            $attendant = apply_filters('sln.booking_services.buildAttendant', new SLN_Wrapper_Attendant($attendant_id));

            if($selected_service){

                $attendantErrors = $this->ah->validateAttendantService($attendant, $selected_service);

            }

            if (empty($attendantErrors)){
                if($selected_service){
                    $attendantErrors = $this->ah->validateAttendant($attendant, $date,
                        $selected_service->getTotalDuration()
                    );
                }else{
                    $attendantErrors = $this->ah->validateAttendant($attendant, $date);
                }
            }

            $errors = array();
            if ( ! empty($attendantErrors)) {
                $errors[] = $attendantErrors[0];
            }

            $ret[$attendant_id] = array(
                'status'   => empty($errors) ? self::STATUS_CHECKED : self::STATUS_ERROR,
                'errors'   => $errors
            );
        }

        return $ret;
    }

    private function bindDate($data)
    {
        if ( ! isset($this->date)) {
            if (isset($data['sln'])) {
                $this->date = sanitize_text_field($data['sln']['date']);
                $this->time = sanitize_text_field($data['sln']['time']);
            }
            if (isset($data['_sln_booking_date'])) {
                $this->date = sanitize_text_field($data['_sln_booking_date']);
                $this->time = sanitize_text_field($data['_sln_booking_time']);
            }
        }
    }

    protected function getDateTime()
    {
        $date = $this->date;
        $time = $this->time;
        $ret  = new SLN_DateTime(
            SLN_Func::filter($date, 'date').' '.SLN_Func::filter($time, 'time'.':00')
        );

        return $ret;
    }

    /**
     * @param mixed $date
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @param mixed $time
     * @return $this
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    public function setBookingBuilder($bb)
    {
        $this->bb = $bb;

        return $this;
    }

    public function setAvailabilityHelper($ah)
    {
        $this->ah = $ah;

        return $this;
    }

}
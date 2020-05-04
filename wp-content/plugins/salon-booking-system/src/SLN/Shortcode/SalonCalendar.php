<?php // algolplus

class SLN_Shortcode_SalonCalendar {

        const NAME = 'salon_booking_calendar';

        const VISIBILITY_PUBLIC  = 'public';
        const DEFAULT_SHOW_DAYS  = 7;

	private $plugin;
	private $attrs;

	function __construct(SLN_Plugin $plugin, $attrs)
	{
		$this->plugin = $plugin;
		$this->attrs = $attrs;
	}

	public static function init(SLN_Plugin $plugin)
	{
		add_shortcode(self::NAME, array(__CLASS__, 'create'));
	}

	public static function create($attrs)
	{
		SLN_TimeFunc::startRealTimezone();

		$obj = new self(SLN_Plugin::getInstance(), $attrs);

		$ret = $obj->execute();
		SLN_TimeFunc::endRealTimezone(	);
		return $ret;
	}

	public function execute()
	{
            $visibility = isset($this->attrs['visibility']) ? $this->attrs['visibility'] : '';

            if (!is_user_logged_in() && $visibility !== self::VISIBILITY_PUBLIC) {
                return wp_login_form();
            }

            return $this->getContentFull();
	}

	public function getContentFull() {
        $data = $this->prepareContentData();

        return $this->renderContentFull($data);
    }

	public function getContent() {
        $data = $this->prepareContentData();

        return $this->renderContent($data);
    }

    private function prepareContentData() {

	do_action('sln_salon_calendar_shortcode_before', $this->attrs);

        $plugin    = $this->plugin;
        $formatter = $plugin->format();

        $ret = array();

        $colors   = array();
        $statuses = SLN_Enum_BookingStatus::toArray();
        foreach($statuses as $k => $status) {
            $colors[] = SLN_Enum_BookingStatus::getColor($k);
        }
        $colors = array_values(array_unique($colors));

        $showDays = self::DEFAULT_SHOW_DAYS;

        if (isset($this->attrs['days'])) {
            $showDays = (int)$this->attrs['days'];
        }

        $datetime = SLN_TimeFunc::currentDateTime();
        for ($i = 1; $i <= $showDays; $i++) {
            $key = $datetime->format('Y-m-d');
            $ret['dates'][$key] = SLN_TimeFunc::translateDate('l', $datetime->getTimestamp()) . ' / ' . $formatter->date($key);
            $datetime= $datetime->modify('+1 day');
        }
        unset($datetime);

	$assistantsIDs = array();

        $criteria = array();
	/** @var SLN_Wrapper_Attendant[] $assistants */
	if(!empty($this->attrs['assistants'])){
	    $assistantsIDs	    = explode(",", $this->attrs['assistants']);
	    $criteria ['@wp_query'] = array( 'post__in' =>  $assistantsIDs);
	}

	$criteria = apply_filters('sln_salon_calendar_shortcode_assistants_query_args', $criteria, $this->attrs);

        $assistants = $plugin->getRepository(SLN_Plugin::POST_TYPE_ATTENDANT)->get($criteria);
        $attData    = array();
        foreach($assistants as $k => $assistant) {
            $attData[$assistant->getId()] = array(
                'name'   => $assistant->getTitle(),
                'color'  => $colors[$k % count($colors)],
                'events' => array(),
            );
        }

        $bookings = $this->buildBookings($showDays);
        /** @var SLN_Wrapper_Booking $b */
        foreach($bookings as $b) {
            if ($plugin->getSettings()->isMultipleAttendantsEnabled() && $plugin->getSettings()->getAvailabilityMode() === 'highend') {
                foreach($b->getBookingServices()->getItems() as $bookingService) {
                    if ($bookingService->getAttendant() && ( !$assistantsIDs || in_array($bookingService->getAttendant()->getId(), $assistantsIDs) )) {
                        $date = $bookingService->getStartsAt()->format('Y-m-d');
                        $attData[$bookingService->getAttendant()->getId()]['events'][$date][] = array(
                            'title' => $plugin->format()->time($bookingService->getStartsAt()) . ' - ' . $b->getDisplayName(),
                            'desc'  => $bookingService->getService()->getName() . '<br/><br/><strong>' . SLN_Enum_BookingStatus::getLabel($b->getStatus()) . '</strong>',
                        );
                    }
                }
            }
            else {
                $rows = array();
                foreach($b->getBookingServices()->getItems() as $bookingService) {
                    if ($bookingService->getAttendant() && ( !$assistantsIDs || in_array($bookingService->getAttendant()->getId(), $assistantsIDs) )) {
                        $rows[$bookingService->getAttendant()->getId()][] = $bookingService->getService()->getName();
                    }
                }

                foreach($rows as $attId => $services) {
                    $date = $b->getStartsAt()->format('Y-m-d');
                    $attData[$attId]['events'][$date][] = array(
                        'title' => $plugin->format()->time($b->getStartsAt()) . ' - ' . $b->getDisplayName(),
                        'desc'  => implode('<br/>', $services) . '<br/><br/><strong>' . SLN_Enum_BookingStatus::getLabel($b->getStatus()) . '</strong>',
                    );
                }
            }
        }

        $ret['attendants'] = $attData;

	$ret['attrs'] = $this->attrs;

        return $ret;
    }

	private function buildBookings($showDays)
	{
		$statuses = SLN_Enum_BookingStatus::toArray();
		unset($statuses[SLN_Enum_BookingStatus::CANCELED], $statuses[SLN_Enum_BookingStatus::ERROR]);
		$statuses = array_keys($statuses);

		/** @var SLN_Repository_BookingRepository $repo */
		$repo = $this->plugin->getRepository(SLN_Plugin::POST_TYPE_BOOKING);

		$args = apply_filters('sln_salon_calendar_shortcode_bookings_query_args', array(
			'@wp_query'   => array(
				'post_status' => $statuses,
				'meta_query'  => array(
					'relation' => 'AND',
					array(
						'key'     => '_sln_booking_date',
						'value'   => current_time('Y-m-d'),
						'type'    => 'DATE',
						'compare' => '>=',
					),
					array(
						'key'     => '_sln_booking_date',
						'value'   => SLN_TimeFunc::currentDateTime()->modify(sprintf('+%s days', $showDays))->format('Y-m-d'),
						'type'    => 'DATE',
						'compare' => '<',
					)
				),
			),
			'@query' => array(),
		), $this->attrs);

		$ret  = $repo->get($args);

		usort($ret, array($this, 'orderBy'));

		return $ret;
	}

	/**
	 * @param SLN_Wrapper_Booking $a
	 * @param SLN_Wrapper_Booking $b
	 */
	private function orderBy($a, $b) {
		if ($a->getStartsAt() <= $b->getStartsAt()) {
			return -1;
		}
		else {
			return 1;
		}
	}

	protected function renderContentFull($data)
	{
		return $this->plugin->loadView('shortcode/salon_booking_calendar/calendar_full', compact('data'));
	}

    protected function renderContent($data)
    {
        return $this->plugin->loadView('shortcode/salon_booking_calendar/calendar_content', compact('data'));
    }
}
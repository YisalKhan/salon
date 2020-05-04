<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class SLB_Discount_Plugin {

	const POST_TYPE_DISCOUNT = 'sln_discount';

	/**
	 * @var SLB_Discount_Plugin
	 */
	private static $instance;

	/**
	 * @var SLN_Plugin
	 */
	private $plugin;

	public static function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * SLN_Plugin_Discount constructor.
	 */
	private function __construct() {
		add_action('plugins_loaded', array($this, 'hook_plugins_loaded'));
		add_action('init', array($this, 'hook_init'));
	}

    private function init_admin() {
        $this->init_ajax();
    }

    private function init_ajax() {
    	$enableDiscountSystem = $this->plugin->getSettings()->get('enable_discount_system');
        if (!$enableDiscountSystem) {
            return;
        }
        $callback = array($this, 'ajax');
        add_action('wp_ajax_salon_discount', $callback);
        add_action('wp_ajax_nopriv_salon_discount', $callback);
    }

    public function hook_plugins_loaded() {
		$plugin = SLN_Plugin::getInstance();
		$plugin->templating()->addPath(SLN_PLUGIN_DIR.'/views/discount/%s.php', 11);
		$this->plugin = $plugin;
	}

	public function hook_init() {
		$enableDiscountSystem = $this->plugin->getSettings()->get('enable_discount_system');
        if (!$enableDiscountSystem) {
            return;
        }
		$plugin = $this->getSlnPlugin();
		$plugin->addRepository(
			new SLB_Discount_Repository_DiscountRepository(
				$plugin,
				new SLB_Discount_PostType_Discount($plugin, self::POST_TYPE_DISCOUNT)
			)
		);
		add_action('admin_init', array($this, 'hook_admin_init'));

		add_action('admin_enqueue_scripts', array($this, 'hook_admin_enqueue_scripts'));
		add_action('wp_enqueue_scripts', array($this, 'hook_wp_enqueue_scripts'));

		add_filter('sln.func.isSalonPage', array($this, 'hook_isSalonPage'));

		add_action('sln.metabox.booking.pre_eval',array($this,'hook_metabox_pre_eval'));

		add_filter('sln.booking_builder.getTotal', array($this, 'hook_booking_builder_getTotal'), 10, 2);
		add_action('sln.booking_builder.create', array($this, 'hook_booking_builder_create'), 10, 1);

		add_action('sln.shortcode.summary.dispatchForm.before_booking_creation', array($this, 'hook_summary_dispatchForm_before_booking_creation'), 10, 2);

		add_action('sln.template.summary.before_total_amount', array($this, 'hook_summary_before_total_amount'), 10, 2);
		add_action('sln.template.summary.after_total_amount', array($this, 'hook_summary_after_total_amount'), 10, 2);

		add_filter('sln.template.metabox.booking.total_amount_label', array($this, 'hook_booking_total_amount_label'), 10, 2);
		add_action('sln.template.metabox.booking.total_amount_row', array($this, 'hook_booking_total_amount_row'));

		add_action('sln.booking.setStatus', array($this, 'hook_booking_setStatus'), 10, 3);

        if (is_admin()) {
            $this->init_admin();
        }
	}

	// js
	// sconti cumulabili
	public function hook_metabox_pre_eval($booking){
		$enableDiscountSystem = $this->plugin->getSettings()->get('enable_discount_system');
		if(!$enableDiscountSystem) return;
		$old_discounts = SLB_Discount_Helper_Booking::getBookingDiscountIds($booking);
		$data = array();				
		$items = $booking->getServicesMeta();
		if(!empty($_POST['_sln_booking_discounts']) && !is_array($_POST['_sln_booking_discounts'])){
			if(intval($_POST['_sln_booking_discounts'])) $discounts = array(intval($_POST['_sln_booking_discounts']));
			else return;
		}
		if(!empty($_POST['_sln_booking_discounts']) && is_array($_POST['_sln_booking_discounts'])) $discounts = array_map('intval',$_POST['_sln_booking_discounts']);
		$discounts_to_compare =  empty($discounts) ? array() : $discounts;
		$discounts_to_decrement = array_diff($old_discounts,$discounts_to_compare);
		$discounts_to_increment = array_diff($discounts_to_compare,$old_discounts);
		$dRepo = SLN_Plugin::getInstance()->getRepository(SLB_Discount_Plugin::POST_TYPE_DISCOUNT);
		if (empty($discounts)){ 
			foreach (['discounts',"discount_amount"] as $k) {
	            delete_post_meta($booking->getId(), '_'.SLN_Plugin::POST_TYPE_BOOKING.'_'.$k);
	    	}	
	    	foreach($items as $sId => &$atId) {
	    		$service = new SLN_Wrapper_Service($sId);
				$price       = $service->getPrice();
				$items[$sId] = array_merge($atId,array(					
					'price'     => $price
				));
			}
			foreach ($discounts_to_decrement as $discountId) {
				$discount        = $dRepo->create($discountId);
				$discount->decrementUsagesNumber($booking->getUserId());
				$discount->decrementTotalUsagesNumber();
			}
			update_post_meta($booking->getId(), '_'.SLN_Plugin::POST_TYPE_BOOKING.'_services', $items);
			update_post_meta($booking->getId(), '_'.SLN_Plugin::POST_TYPE_BOOKING.'_discount_'.$discountId, false);
			return;
		}		
		$bookingServices = $booking->getBookingServices();		
		$data["discounts"] = array();
		$discountValues = 0;
		$first = true;
		foreach ($discounts as $discountId) {
			$discount        = $dRepo->create($discountId);
			$discountValues  = $discount->applyDiscountToBookingServices($bookingServices, true);	

			$data["discounts"][] = $discountId;
			$data["discount_{$discountId}"] = true;
			$data["discount_amount"] = array_merge(isset($data["discount_amount"]) ? $data["discount_amount"] : array(),$discountValues);
			foreach($items as $sId => $atId) {
				if($first){ 
					$service = new SLN_Wrapper_Service($sId);
					$price       = $service->getPrice();
				}
				else $price      = isset($items[$sId]['price']) ? $items[$sId]['price'] : $bookingServices->findByService($sId)->getPrice();

				$items[$sId] = array_merge($atId,array(					
					'price'     => $price - $discountValues[$sId]
				));
			}
			if(in_array($discountId,$discounts_to_increment)){
					$discount->incrementUsagesNumber($booking->getUserId());
					$discount->incrementTotalUsagesNumber();
			}
			$first = false;
		}	
		foreach ($discounts_to_decrement as $discountId) {
				$data["discount_{$discountId}"] = false;
				$discount        = $dRepo->create($discountId);
				$discount->decrementUsagesNumber($booking->getUserId());
				$discount->decrementTotalUsagesNumber();
		}				

		$data["services"] = $items;		
					
		foreach ($data as $k => $v) {
	            update_post_meta($booking->getId(), '_'.SLN_Plugin::POST_TYPE_BOOKING.'_'.$k, $v);
	    }	    
	}

	public function hook_admin_init() {
		new SLB_Discount_Metabox_Discount($this->getSlnPlugin(), self::POST_TYPE_DISCOUNT);
	}

	public function hook_admin_enqueue_scripts() {
		wp_enqueue_script('admin-discount', SLN_PLUGIN_URL.'/js/discount/admin-discount.js', array('jquery'), false, true);
	}

	public function hook_wp_enqueue_scripts() {
		wp_enqueue_script('salon-discount', SLN_PLUGIN_URL.'/js/discount/salon-discount.js', array('jquery'), false, true);
	}

	public function hook_isSalonPage($ret) {
		$page = sanitize_text_field( $page );
		$post = sanitize_text_field( $post );
		return (
			$ret ||
	        isset($page) && $page === self::POST_TYPE_DISCOUNT ||
			!empty($post) && get_post_type($post) === self::POST_TYPE_DISCOUNT
		);
	}


	/**
	 * @param float $total
	 * @param SLN_Wrapper_Booking_Builder $bb
	 *
	 * @return float
	 */
	public function hook_booking_builder_getTotal($total, $bb) {
		$discountData = $bb->get('discount');
		if (!empty($discountData)) {
			$total -= $discountData['amount'];
		}
		return $total;
	}

	/**
	 * @param SLN_Wrapper_Booking_Builder $bb
	 */
	public function hook_booking_builder_create($bb) {
		$discountData = $bb->get('discount');
		if (!empty($discountData)) {
			$discountId = $discountData['id'];
			$items      = $bb->get('services');

			if ($items) {
				$bookingServices = SLN_Wrapper_Booking_Services::build($items, $bb->getDateTime());
				$discount        = $this->createDiscount($discountId);
				$discountValues  = $discount->applyDiscountToBookingServices($bookingServices, true);
				foreach($items as $sId => $atId) {
					$price       = $bookingServices->findByService($sId)->getPrice();
					$items[$sId] = array(
						'service'   => $sId,
						'attendant' => $atId,
						'price'     => $price - $discountValues[$sId],
					);
				}

				$bb->set("services", $items);
				$bb->set("discount_{$discountId}", true);
				$bb->set("discount_amount", $discountValues);
				$bb->set("discounts", array($discountId));

				$discount->incrementUsagesNumber(get_current_user_id());
				$discount->incrementTotalUsagesNumber();
			}
		}

		$bb->set('discount', null);
	}

	/**
	 * @param SLN_Shortcode_Salon_SummaryStep $step
	 * @param SLN_Wrapper_Booking_Builder $bb
	 */
	public function hook_summary_dispatchForm_before_booking_creation($step, $bb) {
		$discountData = $bb->get('discount');
		if (!empty($discountData)) {
			$discountId = $discountData['id'];
			$discount   = $this->createDiscount($discountId);

			$errors     = $discount->validateDiscountFullForBB($bb);
			if (!empty($errors)) {
				$bb->set('discount', null);
				$bb->save();
				$step->addError(reset($errors));
			}
		}
	}

	/**
	 * @param SLN_Wrapper_Booking_Builder $bb
	 * @param int $size
	 */
	public function hook_summary_before_total_amount($bb, $size) {
		$plugin   = $this->plugin;

		$discount     = false;
		$discountData = $bb->get('discount');
		if (!empty($discountData)) {
			$discountId = $discountData['id'];
			$discount   = $this->createDiscount($discountId);
			if (!$discount->isValidDiscountFullForBB($bb)) {
				$discount = false;
			}
		}

		if (!$discount) {
			$discountItems = SLB_Discount_Helper_DiscountItems::buildDiscountItems();
			$discount      = $discountItems->getDiscountForBB($bb);
		}

		if ($discount) {
			$discountValue = $discount->applyDiscountToBookingServices($bb->getBookingServices());
			$bb->set('discount', array('id' => $discount->getId(), 'amount' => $discountValue));
			$bb->save();
		}
		else {
			$bb->set('discount', null);
			$bb->save();
			$discountValue = 0.0;
		}

		echo $plugin->loadView('shortcode/_salon_summary_before_total_amount', compact('discountValue', 'size'));
	}

	/**
	 * @param SLN_Wrapper_Booking_Builder $bb
	 * @param int $size
	 */
	public function hook_summary_after_total_amount($bb, $size) {
		$plugin   = $this->plugin;

		echo $plugin->loadView('shortcode/_salon_summary_after_total_amount', compact('size'));
	}

	/**
	 * @param string $label
	 * @param SLN_Wrapper_Booking $booking
	 *
	 * @return string
	 */
	public function hook_booking_total_amount_label($label, $booking) {
		if (SLB_Discount_Helper_Booking::hasAppliedDiscount($booking)) {
			return __('Discounted price', 'salon-booking-system');
		}

		return $label;
	}

	/**
	 * @param SLN_Wrapper_Booking $booking
	 */
	public function hook_booking_total_amount_row($booking) {
		$plugin = $this->plugin;

		if (SLB_Discount_Helper_Booking::hasAppliedDiscount($booking)) {
			echo $plugin->loadView('metabox/_booking_total_amount_row', compact('booking'));
		}
	}

	/**
	 * @param SLN_Wrapper_Booking $booking
	 * @param string $oldStatus
	 * @param string $newStatus
	 */
	public function hook_booking_setStatus($booking, $oldStatus, $newStatus)
	{
		if ($oldStatus !== SLN_Enum_BookingStatus::CANCELED && $newStatus === SLN_Enum_BookingStatus::CANCELED) {
			if (SLB_Discount_Helper_Booking::hasAppliedDiscount($booking)) {
				$pt = $booking->getPostType();

				foreach(SLB_Discount_Helper_Booking::getBookingDiscounts($booking) as $discount) {
					$discount->decrementUsagesNumber($booking->getUserId());
					$discount->decrementTotalUsagesNumber();
					delete_post_meta($booking->getId(), "_{$pt}_discount_{$discount->getId()}");
				}

				$discountAmount = $booking->getMeta('discount_amount');

				$bookingServicesArray = $booking->getBookingServices()->toArrayRecursive();
				foreach($bookingServicesArray as &$item) {
					$serviceId      = $item['service'];
					$item['price'] += isset($discountAmount[$serviceId]) ? $discountAmount[$serviceId] : 0;
				}
				unset($item);
				$booking->setMeta('services', $bookingServicesArray);
				$booking->evalBookingServices();
				$booking->evalTotal();

				delete_post_meta($booking->getId(), "_{$pt}_discount_amount");
				delete_post_meta($booking->getId(), "_{$pt}_discounts");
			}
		}
	}

	/**
	 * @param $discount
	 * @return SLB_Discount_Wrapper_Discount
	 * @throws Exception
	 */
	public function createDiscount($discount)
	{
		return $this->getSlnPlugin()->getRepository(self::POST_TYPE_DISCOUNT)->create($discount);
	}

	public function getSlnPlugin() {
		return $this->plugin;
	}

        public function ajax()
        {
            SLN_TimeFunc::startRealTimezone();

            $method = sanitize_text_field(wp_unslash( $_REQUEST['method'] ));

            $className = 'SLB_Discount_Action_Ajax_'.ucwords($method);

            if ( ! class_exists($className) ) {
                throw new Exception("discount ajax method not found '$method'");
            }

            SLN_Plugin::addLog('discount calling ajax '.$className);

            $obj = new $className($this->getSlnPlugin());

            $ret = $obj->execute();

            SLN_Plugin::addLog("$className returned:\r\n".json_encode($ret));

            if (is_array($ret)) {
                header('Content-Type: application/json');
                echo json_encode($ret);
            } elseif (is_string($ret)) {
                echo $ret;
            } else {
                throw new Exception("no content returned from $className");
            }

            exit();
        }
}
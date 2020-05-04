<?php

/**
 * @method SLN_Wrapper_Booking getOne($criteria = [])
 * @method SLN_Wrapper_Booking[] get($criteria = [])
 * @method SLN_Wrapper_Booking create($data = null)
 */
class SLN_Repository_BookingRepository extends SLN_Repository_AbstractWrapperRepository
{
    public function getWrapperClass()
    {
        return SLN_Wrapper_Booking::_CLASS;
    }

    protected function processCriteria($criteria)
    {
        if (isset($criteria['time@max'])) {
            $criteria['@wp_query']['meta_query'][] =
                array(
                    'key'     => '_sln_booking_time',
                    'value'   => $criteria['time@max']->format('H:i'),
                    'compare' => '<=',
                );
            unset($criteria['time@max']);
        }

        if (isset($criteria['day'])) {
            $criteria['@wp_query']['meta_query'][] =
                array(
                    'key'     => '_sln_booking_date',
                    'value'   => $criteria['day']->format('Y-m-d'),
                    'compare' => isset($criteria['day_compare']) ? $criteria['day_compare'] : '=',
                );
            unset($criteria['day']);
        } else {
            if (isset($criteria['day@min'])) {
                $criteria['@wp_query']['meta_query'][] =
                    array(
                        'key'     => '_sln_booking_date',
                        'value'   => $criteria['day@min']->format('Y-m-d'),
                        'compare' => '>=',
                    );

                unset($criteria['day@min']);
            }
            if (isset($criteria['day@max'])) {
                $criteria['@wp_query']['meta_query'][] =
                    array(
                        'key'     => '_sln_booking_date',
                        'value'   => $criteria['day@max']->format('Y-m-d'),
                        'compare' => '<=',
                    );
                unset($criteria['day@max']);
            }
        }
        
        $criteria = apply_filters('sln.repository.booking.processCriteria', $criteria);

        return parent::processCriteria($criteria);
    }


    /**
     * @param SLN_Wrapper_Booking $a
     * @param SLN_Wrapper_Booking $b
     *
     * @return int
     */
    public static function sortAscByStartsAt($a, $b)
    {
        return ($a->getStartsAt()->getTimestamp() > $b->getStartsAt()->getTimestamp()
        ? 1 : -1);
    }

    /**
     * @param SLN_Wrapper_Booking $a
     * @param SLN_Wrapper_Booking $b
     *
     * @return int
     */
    public static function sortDescByStartsAt($a, $b)
    {
        return ($a->getStartsAt()->getTimestamp() >= $b->getStartsAt()->getTimestamp()
         ? -1 : 1);
    }


    /**
     * @todo add in src/SLN/Helper/Availability/AbstractDayBookings.php
     * @param $date
     * @param SLN_Wrapper_Booking|null $currentBooking
     *
     * @return array
     */
    public function getForAvailability($date, SLN_Wrapper_Booking $currentBooking = null)
    {
        $criteria       = array('day' => $date, 'foravailability' => true);
        $noTimeStatuses = SLN_Enum_BookingStatus::$noTimeStatuses;
        $ret            = array();
        foreach ($this->get($criteria) as $b) {
            if (empty($currentBooking) || $b->getId() != $currentBooking->getId()) {
                if ( ! $b->hasStatus($noTimeStatuses)) {
                    $ret[] = $b;
                }
            }
        }

        return $ret;
    }

    public function getForDaySearch($search,$day)
    {
        $search_parts = explode(' ', $search);
        $search_parts = array_filter($search_parts, function($str){
            return !empty($str);
        });

        if(empty($search_parts)) return [];

        $criteria = [
            'day' => $day,
            '@wp_query' => []
        ];

        $map_query = function($key,$search){
            return array(
                'key'     => $key,
                'value'   => $search,
                'compare' => 'LIKE',
            );
        };

        $id = false;
        $meta_query = [];
        $byId = [];

        foreach ($search_parts as $search_part) {
            if(!$id && ctype_digit(strval($search_part))){
                $id = $search_part;
            }
            $item = ['relation' => 'OR'];
            $item[] = $map_query('_sln_booking_email',$search_part);
            $item[] = $map_query('_sln_booking_firstname',$search_part);
            $item[] = $map_query('_sln_booking_lastname',$search_part);
            $item[] = $map_query('_sln_booking_phone',$search_part);
            $meta_query[] = $item;

        }

        if($id){
            $ids_criteria = $criteria;
            $ids_criteria['@wp_query']['p'] = $id;
            $byId =  $this->get($ids_criteria) ?: [];
        }

        $criteria['@wp_query']['meta_query'] = $meta_query;
        $byField = $this->get($criteria) ?: [];
        $b_temp = array_merge($byId,$byField);

        $bookings = [];
        foreach ($b_temp as $booking) {
            $bookings[$booking->getId()] = $booking;
        }
        $ret = [];
        foreach ($bookings as $b) {
            $item = [
                'customer' => $b->getDisplayName(),
                'start_date' => $this->plugin->format()->datetime($b->getStartsAt()),
                'status' => SLN_Enum_BookingStatus::getLabel($b->getStatus()),
                'services' => [],
                'amount' => $this->plugin->format()->money($b->getAmount()),
                //'cancel_url' => $b->getCancelUrl(),
                'edit_url' =>get_permalink($b->getId()),
                'id' => $b->getId(),
            ];
            $services = $b->getBookingServices()->getItems();
            foreach ($services as $service) {
                $attendant = $service->getAttendant();
                $attendant_name = $attendant ? $attendant->getName() : '';
                $item['services'][] = [
                    'name' => $service->getService()->getName(),
                    'attendant' => $attendant_name,
                ];
            }
            $ret[] = $item;
        }

        return $ret;
    }
}

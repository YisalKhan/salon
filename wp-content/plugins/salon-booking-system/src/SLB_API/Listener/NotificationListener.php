<?php

namespace SLB_API\Listener;

use SLB_API\Listener\Events\BookingEventsListener;

class NotificationListener
{
    public function __construct()
    {
	new BookingEventsListener();
    }


}
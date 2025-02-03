<?php

namespace Modules\Delivery\Listeners;

use Illuminate\Support\Facades\Notification;
use Modules\Delivery\Events\DeliveryCompletedEvent;
use Modules\Delivery\Notifications\DeliveryCompleteNotification;

class DeliveryCompletedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DeliveryCompletedEvent $event): void
    {
        $order = $event->order;
        $customer = $order->customer;

        Notification::send($customer, new DeliveryCompleteNotification($order));
    }
}

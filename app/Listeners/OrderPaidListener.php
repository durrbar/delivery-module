<?php

declare(strict_types=1);

namespace Modules\Delivery\Listeners;

use Modules\Delivery\Services\DeliveryService;
use Modules\Order\Events\OrderPaidEvent;

class OrderPaidListener
{
    private DeliveryService $deliveryService;

    public function __construct(DeliveryService $deliveryService)
    {
        $this->deliveryService = $deliveryService;
    }

    public function handle(OrderPaidEvent $event)
    {
        $order = $event->order;

        // Schedule the delivery
        $this->deliveryService->scheduleDelivery($order);
    }
}

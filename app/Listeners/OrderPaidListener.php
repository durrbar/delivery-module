<?php

declare(strict_types=1);

namespace Modules\Delivery\Listeners;

use Modules\Delivery\Services\DeliveryService;
use Modules\Order\Events\OrderPaidEvent;

class OrderPaidListener
{
    public function __construct(private readonly DeliveryService $deliveryService) {}

    public function handle(OrderPaidEvent $event): void
    {
        $order = $event->order;

        // Schedule the delivery
        $this->deliveryService->scheduleDelivery($order);
    }
}

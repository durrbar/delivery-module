<?php
namespace Modules\Delivery\Listeners;

use Modules\Order\Events\OrderPaidEvent;
use Modules\Delivery\Services\DeliveryService;

class OrderPaidListener
{
    protected DeliveryService $deliveryService;

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

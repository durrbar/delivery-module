<?php

namespace Modules\Delivery\Services;

use Modules\Delivery\Events\DeliveryCompletedEvent;
use Modules\Delivery\Models\Delivery;
use Modules\Order\Models\Order;
use Modules\Order\Services\OrderService;

class DeliveryService
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Schedule a delivery for the order.
     *
     * @param array $deliveryData
     * @return Delivery
     */
    public function scheduleDelivery(Order $order): Delivery
    {
        // Create a new delivery record
        $delivery = $order->delivery()->create([
            'status' => 'pending', // Default status
            'shipping_address' => $order->shipping_address,
            'tracking_number' => $this->generateTrackingNumber(),
            'ship_by' => 'courier', // Example ship method
            // Additional delivery fields can be added
        ]);

        return $delivery;
    }

    /**
     * Update the delivery status.
     *
     * @param Delivery $delivery
     * @param string $status
     * @return void
     */
    public function updateDeliveryStatus(Delivery $delivery, string $status): void
    {
        $delivery->update(['status' => $status]);
    }

    /**
     * Mark delivery as completed and update order status.
     *
     * @param Delivery $delivery
     * @return void
     */
    public function completeDelivery(Delivery $delivery): void
    {
        // Mark the delivery as delivered
        $this->updateDeliveryStatus($delivery, 'delivered');

        // Update order status if payment has been completed
        $order = $delivery->order;
        $this->orderService->updateOrderStatus($order, 'completed');

        // Fire event for delivery completion
        event(new DeliveryCompletedEvent($order));
    }

    /**
     * Generate a unique tracking number for the delivery.
     *
     * @return string
     */
    private function generateTrackingNumber(): string
    {
        return 'TRK-' . now()->format('Ymd') . strtoupper(bin2hex(random_bytes(4)));
    }
}

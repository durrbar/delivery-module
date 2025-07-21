<?php

namespace Modules\Delivery\Services;

use Exception;
use Illuminate\Support\Facades\Log;
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
     * @param  array  $deliveryData
     * @return Delivery
     */
    public function scheduleDelivery(Order $order, string $shipBy = 'courier'): bool
    {
        try {
            // Get all physical items in the order
            $physicalItems = $order->getPhysicalItems();

            if ($physicalItems->isEmpty()) {
                return false; // No physical items to deliver
            }

            // Group items by constraints (e.g., warehouse)
            $groupedItems = $this->groupItemsByConstraints($physicalItems);

            foreach ($groupedItems as $group) {
                // Generate a unique tracking number for the group
                $trackingNumber = $this->generateTrackingNumber();

                // Create a delivery record for the group
                $delivery = $order->delivery()->create([
                    'status' => 'pending', // Default status
                    'shipping_address' => $order->shipping_address,
                    'tracking_number' => $trackingNumber,
                    'ship_by' => $shipBy, // Example ship method
                ]);

                // Add delivery items for the group with extended tracking numbers
                foreach ($group as $index => $item) {
                    $extendedTrackingNumber = $this->generateExtendedTrackingNumber($trackingNumber, $index + 1);
                    $delivery->items()->create([
                        'order_item_id' => $item->id,
                        'extended_tracking_number' => $extendedTrackingNumber,
                        'status' => 'pending',
                    ]);
                }
            }

            return true; // Deliveries successfully scheduled
        } catch (Exception $e) {
            Log::error("Failed to schedule delivery for order {$order->id}: ".$e->getMessage());

            return false;
        }
    }

    /**
     * Update the delivery status.
     */
    public function updateDeliveryStatus(Delivery $delivery, string $status): void
    {
        $delivery->update(['status' => $status]);
    }

    /**
     * Mark delivery as completed and update order status.
     */
    public function markDeliveryCompleted(Delivery $delivery): void
    {
        // Mark the delivery as delivered
        $this->updateDeliveryStatus($delivery, 'delivered');
    }

    /**
     * Mark a single delivery item as delivered.
     *
     * @param  DeliveryItem  $deliveryItem
     */
    public function completeDeliveryItem($deliveryItem): void
    {
        try {
            // Mark the delivery item as delivered
            $deliveryItem->update(['status' => 'delivered']);

            // The parent Delivery model will automatically update its status
            // if all items are delivered (handled in the DeliveryItem model).
        } catch (Exception $e) {
            Log::error("Failed to complete delivery item ID {$deliveryItem->id}: ".$e->getMessage());
            throw $e; // Re-throw the exception after logging
        }
    }

    /**
     * Group items based on constraints (e.g., warehouse).
     *
     * @param  \Illuminate\Support\Collection  $items
     */
    protected function groupItemsByConstraints($items): array
    {
        // Example: Group items by warehouse (assuming each item has a `warehouse_id`)
        return $items->groupBy('warehouse_id')->values()->all();
    }

    /**
     * Generate a unique tracking number for the delivery.
     */
    private function generateTrackingNumber(): string
    {
        return 'TRK-'.now()->format('Ymd').strtoupper(bin2hex(random_bytes(4)));
    }

    /**
     * Generate an extended tracking number for a delivery item.
     */
    private function generateExtendedTrackingNumber(string $trackingNumber, int $itemIndex): string
    {
        return sprintf('%s-%03d', $trackingNumber, $itemIndex); // Example: TRK-20231015-ABCD1234-001
    }
}

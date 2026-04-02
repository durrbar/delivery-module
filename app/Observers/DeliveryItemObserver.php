<?php

declare(strict_types=1);

namespace Modules\Delivery\Observers;

use Modules\Delivery\Enums\DeliveryStatus;
use Modules\Delivery\Models\DeliveryItem;

final class DeliveryItemObserver
{
    public function updated(DeliveryItem $item): void
    {
        // If the item status is updated to delivered, check the parent delivery.
        if ($item->status === DeliveryStatus::Delivered->value) {
            $delivery = $item->delivery;

            if ($delivery && $delivery->areAllItemsDelivered()) {
                $delivery->update(['status' => DeliveryStatus::Delivered->value]);
            }
        }
    }
}

<?php

declare(strict_types=1);

namespace Modules\Delivery\Observers;

use Modules\Delivery\Enums\DeliveryStatus;
use Modules\Delivery\Events\DeliveryCompletedEvent;
use Modules\Delivery\Events\DeliveryScheduledEvent;
use Modules\Delivery\Models\Delivery;

class DeliveryObserver
{
    /**
     * Handle the Delivery "created" event.
     */
    public function created(Delivery $delivery): void
    {
        // Fire an event when delivery is scheduled
        event(new DeliveryScheduledEvent($delivery));
    }

    /**
     * Handle the Delivery "updated" event.
     */
    public function updated(Delivery $delivery): void
    {
        // Check if the delivery status was updated to 'delivered'
        $status = $delivery->status instanceof DeliveryStatus ? $delivery->status->value : $delivery->status;
        if ($delivery->isDirty('status') && $status === DeliveryStatus::Delivered->value) {
            // Fire the DeliveryCompletedEvent
            event(new DeliveryCompletedEvent($delivery));
        }
    }

    /**
     * Handle the Delivery "deleted" event.
     */
    public function deleted(Delivery $delivery): void
    {
        //
    }

    /**
     * Handle the Delivery "restored" event.
     */
    public function restored(Delivery $delivery): void
    {
        //
    }

    /**
     * Handle the Delivery "force deleted" event.
     */
    public function forceDeleted(Delivery $delivery): void
    {
        //
    }
}

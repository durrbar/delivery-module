<?php

namespace Modules\Delivery\Observers;

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
        if ($delivery->isDirty('status') && $delivery->status === 'delivered') {
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

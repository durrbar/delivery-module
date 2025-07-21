<?php

namespace Modules\Delivery\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Modules\Delivery\Models\Delivery;

class DeliveryCompletedEvent
{
    use Dispatchable;

    public Delivery $delivery;

    /**
     * Create a new event instance.
     */
    public function __construct(Delivery $delivery)
    {
        $this->delivery = $delivery;
    }
}

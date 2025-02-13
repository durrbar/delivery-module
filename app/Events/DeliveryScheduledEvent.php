<?php
namespace Modules\Delivery\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Modules\Delivery\Models\Delivery;

class DeliveryScheduledEvent
{
    use Dispatchable;

    public Delivery $delivery;

    /**
     * Create a new event instance.
     *
     * @param Delivery $delivery
     */
    public function __construct(Delivery $delivery)
    {
        $this->delivery = $delivery;
    }
}

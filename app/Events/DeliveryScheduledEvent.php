<?php

declare(strict_types=1);

namespace Modules\Delivery\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Modules\Delivery\Models\Delivery;

class DeliveryScheduledEvent
{
    use Dispatchable;

    /**
     * Create a new event instance.
     */
    public function __construct(public Delivery $delivery) {}
}

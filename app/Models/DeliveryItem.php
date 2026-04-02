<?php

declare(strict_types=1);

namespace Modules\Delivery\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Delivery\Observers\DeliveryItemObserver;
use Modules\Order\Models\OrderItem;

#[ObservedBy([DeliveryItemObserver::class])]
#[Fillable([
    'delivery_id',
    'order_item_id',
    'extended_tracking_number',
    'status',
])]
class DeliveryItem extends Model
{
    use HasUuids;

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }
}

<?php

declare(strict_types=1);

namespace Modules\Delivery\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Delivery\Enums\DeliveryStatus;

final class DeliveryItem extends Model
{
    use HasUuids;

    protected $fillable = [
        'delivery_id',
        'order_item_id',
        'extended_tracking_number',
        'status',
    ];

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(\Modules\Order\Models\OrderItem::class);
    }

    /**
     * Handle the "updated" event for the delivery item.
     *
     * @param  Model  $model
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::updated(function ($item): void {
            // If the item status is updated to 'delivered', check the parent delivery
            if ($item->status === DeliveryStatus::Delivered->value) {
                $delivery = $item->delivery;
                if ($delivery && $delivery->areAllItemsDelivered()) {
                    $delivery->update(['status' => DeliveryStatus::Delivered->value]);
                }
            }
        });
    }
}

<?php
namespace Modules\Delivery\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryItem extends Model
{
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
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($item) {
            // If the item status is updated to 'delivered', check the parent delivery
            if ($item->status === 'delivered') {
                $delivery = $item->delivery;
                if ($delivery && $delivery->areAllItemsDelivered()) {
                    $delivery->update(['status' => 'delivered']);
                }
            }
        });
    }
}

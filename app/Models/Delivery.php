<?php

namespace Modules\Delivery\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Delivery\Observers\DeliveryObserver;

// use Modules\Delivery\Database\Factories\DeliveryFactory;

#[ObservedBy([DeliveryObserver::class])]
class Delivery extends Model
{
    use HasFactory;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): DeliveryFactory
    // {
    //     // return DeliveryFactory::new();
    // }

    public function order(): BelongsTo
    {
        return $this->belongsTo(config('delivery.order.model'), 'order_id', 'id');
    }

    public function items() : HasMany {
        return $this->hasMany(DeliveryItem::class);
    }

    /**
     * Check if all delivery items are delivered.
     *
     * @return bool
     */
    public function areAllItemsDelivered(): bool
    {
        return $this->items()->where('status', '!=', 'delivered')->doesntExist();
    }
}

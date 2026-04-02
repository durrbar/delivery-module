<?php

declare(strict_types=1);

namespace Modules\Delivery\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Delivery\Enums\DeliveryStatus;
use Modules\Delivery\Observers\DeliveryObserver;

// use Modules\Delivery\Database\Factories\DeliveryFactory;

#[ObservedBy([DeliveryObserver::class])]
#[Fillable([])]
class Delivery extends Model
{
    use HasFactory;
    use HasUuids;

    // protected static function newFactory(): DeliveryFactory
    // {
    //     // return DeliveryFactory::new();
    // }

    public function order(): BelongsTo
    {
        return $this->belongsTo(config('delivery.order.model'), 'order_id', 'id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(DeliveryItem::class);
    }

    /**
     * Check if all delivery items are delivered.
     */
    public function areAllItemsDelivered(): bool
    {
        return $this->items()->where('status', '!=', DeliveryStatus::Delivered->value)->doesntExist();
    }

    protected function casts(): array
    {
        return [
            'status' => DeliveryStatus::class,
        ];
    }
}

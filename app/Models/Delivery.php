<?php

namespace Modules\Delivery\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// use Modules\Delivery\Database\Factories\DeliveryFactory;

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
}

<?php

declare(strict_types=1);

namespace Modules\Delivery\Models;

use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Models\Scopes\OrderByUpdatedAtDescScope;
use Modules\Ecommerce\Models\Product;

#[ScopedBy([OrderByUpdatedAtDescScope::class])]
#[Table('shipping_classes')]
#[Unguarded]
class Shipping extends Model
{
    use HasUuids;

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'shipping_class_id');
    }
}

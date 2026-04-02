<?php

declare(strict_types=1);

namespace Modules\Delivery\Tests\Unit;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Modules\Delivery\Enums\DeliveryStatus;
use Modules\Delivery\Models\Delivery;
use Tests\TestCase;

class DeliveryModelTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_are_all_items_delivered_uses_delivery_status_enum_value(): void
    {
        $relation = Mockery::mock(HasMany::class);
        $relation->shouldReceive('where')
            ->once()
            ->with('status', '!=', DeliveryStatus::Delivered->value)
            ->andReturnSelf();
        $relation->shouldReceive('doesntExist')
            ->once()
            ->andReturn(true);

        /** @var Delivery|Mockery\MockInterface $delivery */
        $delivery = Mockery::mock(Delivery::class)->makePartial();
        $delivery->shouldReceive('items')
            ->once()
            ->andReturn($relation);

        $this->assertTrue($delivery->areAllItemsDelivered());
    }
}

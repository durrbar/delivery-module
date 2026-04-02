<?php

declare(strict_types=1);

namespace Modules\Delivery\Tests\Unit;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Modules\Delivery\Enums\DeliveryStatus;
use Modules\Delivery\Models\Delivery;
use Modules\Delivery\Services\DeliveryService;
use Modules\Order\Services\OrderService;
use Tests\TestCase;

class DeliveryServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected DeliveryService $deliveryService;

    protected function setUp(): void
    {
        parent::setUp();

        $orderService = Mockery::mock(OrderService::class);
        $this->deliveryService = new DeliveryService($orderService);
    }

    public function test_mark_delivery_completed_updates_status_to_delivered(): void
    {
        /** @var Delivery|Mockery\MockInterface $delivery */
        $delivery = Mockery::mock(Delivery::class);
        $delivery->shouldReceive('update')
            ->once()
            ->with(['status' => DeliveryStatus::Delivered->value]);

        $this->deliveryService->markDeliveryCompleted($delivery);
    }

    public function test_complete_delivery_item_updates_item_status_to_delivered(): void
    {
        $deliveryItem = Mockery::mock();
        $deliveryItem->id = 'item-1';
        $deliveryItem->shouldReceive('update')
            ->once()
            ->with(['status' => DeliveryStatus::Delivered->value]);

        $this->deliveryService->completeDeliveryItem($deliveryItem);
    }
}

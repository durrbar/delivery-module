<?php

declare(strict_types=1);

namespace Modules\Delivery\Tests\Unit;

use Closure;
use Illuminate\Console\OutputStyle;
use Illuminate\Database\Eloquent\Builder;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Modules\Delivery\Console\CheckDeliveryStatus;
use Modules\Delivery\Enums\DeliveryStatus;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Tests\TestCase;

class CheckDeliveryStatusCommandTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_handle_updates_delivery_when_all_items_are_delivered(): void
    {
        $delivery = new class
        {
            public string $id = 'delivery-1';

            public array $updatedAttributes = [];

            public function areAllItemsDelivered(): bool
            {
                return true;
            }

            public function update(array $attributes): bool
            {
                $this->updatedAttributes = $attributes;

                return true;
            }
        };

        $builder = Mockery::mock(Builder::class);
        $builder->shouldReceive('chunk')
            ->once()
            ->with(100, Mockery::type(Closure::class))
            ->andReturnUsing(static function (int $size, Closure $callback) use ($delivery): void {
                $callback(collect([$delivery]));
            });

        $command = new class($builder) extends CheckDeliveryStatus
        {
            public function __construct(private readonly Builder $builder)
            {
                parent::__construct();
            }

            protected function deliveryQuery(): Builder
            {
                return $this->builder;
            }
        };
        $command->setOutput(new OutputStyle(new ArrayInput([]), new BufferedOutput()));

        $exitCode = $command->handle();

        self::assertSame(CheckDeliveryStatus::SUCCESS, $exitCode);
        self::assertSame(
            DeliveryStatus::Delivered->value,
            $delivery->updatedAttributes['status'] ?? null
        );
    }
}

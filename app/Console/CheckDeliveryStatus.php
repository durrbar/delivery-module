<?php

declare(strict_types=1);

namespace Modules\Delivery\Console;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Modules\Delivery\Enums\DeliveryStatus;
use Modules\Delivery\Models\Delivery;

#[Signature('delivery:check-status')]
#[Description('Check and update the status of deliveries')]
class CheckDeliveryStatus extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting delivery status check...');

        // Fetch non-delivered deliveries in chunks to avoid memory issues
        $this->deliveryQuery()->chunk(100, function ($deliveries): void {
            foreach ($deliveries as $delivery) {
                if ($delivery->areAllItemsDelivered()) {
                    $delivery->update(['status' => DeliveryStatus::Delivered->value]);
                    $this->info("Delivery ID {$delivery->id} marked as delivered.");
                    Log::info("Delivery ID {$delivery->id} marked as delivered.");
                }
            }
        });

        $this->info('Delivery status check completed.');

        return self::SUCCESS;
    }

    protected function deliveryQuery(): Builder
    {
        return Delivery::query()
            ->where('status', '!=', DeliveryStatus::Delivered->value)
            ->with('items'); // Eager load related items to avoid N+1 query problem
    }
}

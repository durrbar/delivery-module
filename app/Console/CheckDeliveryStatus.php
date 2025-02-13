<?php

namespace Modules\Delivery\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\Delivery\Models\Delivery;

class CheckDeliveryStatus extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'delivery:check-status';

    /**
     * The console command description.
     */
    protected $description = 'Check and update the status of deliveries';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting delivery status check...');

        // Fetch non-delivered deliveries in chunks to avoid memory issues
        Delivery::where('status', '!=', 'delivered')
            ->with('items') // Eager load related items to avoid N+1 query problem
            ->chunk(100, function ($deliveries) {
                foreach ($deliveries as $delivery) {
                    if ($delivery->areAllItemsDelivered()) {
                        $delivery->update(['status' => 'delivered']);
                        $this->info("Delivery ID {$delivery->id} marked as delivered.");
                        Log::info("Delivery ID {$delivery->id} marked as delivered.");
                    }
                }
            });

        $this->info('Delivery status check completed.');
    }
}

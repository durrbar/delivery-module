<?php

namespace Modules\Delivery\Console;

use Illuminate\Console\Scheduling\Schedule;

class DeliveryScheduler
{
    public static function schedule(Schedule $schedule)
    {
        $schedule->command('delivery:check-status')->everyThirtyMinutes();
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use Carbon\Carbon;

class ExpireProperties extends Command
{
    protected $signature = 'properties:expire';
    protected $description = 'Mark properties as expired if their expire_date has passed';

    public function handle()
    {
        $now = Carbon::now();

        // Update all properties where expire_date < now and status is still active (assuming status 1 is active, 0 is expired)
        $expiredCount = Property::where('expire_date', '<', $now)
            ->where('status', 1) // optional: only expire active ones
            ->update(['status' => 0]); // mark as expired

        $this->info("Marked {$expiredCount} properties as expired.");

        return 0;
    }
}

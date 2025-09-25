<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserPlan;
use Carbon\Carbon;

class ExpireUserPlans extends Command
{
    protected $signature = 'plans:expire';
    protected $description = 'Expire user plans that have passed the expiration date';

    public function handle()
    {
        $now = Carbon::now();

        $expiredPlans = UserPlan::where('status', 'active')
            ->where('expire_date', '<', $now)
            ->update(['status' => 'expired']);

        $this->info("Expired $expiredPlans user plans.");
    }
}

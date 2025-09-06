<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPlan extends Model
{
    protected $guarded = [];

    public function plan()
    {
        return $this->belongsTo(PackagePlan::class, 'package_id', 'id');
    }
    public function billing()
    {
        return $this->belongsTo(Billing::class, 'billing_id', 'id');
    }
}

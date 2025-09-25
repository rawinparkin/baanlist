<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
     public $timestamps = false;
     protected $guarded = [];
     public function province()
     {
          return $this->belongsTo(Province::class, 'province_id');
     }
}

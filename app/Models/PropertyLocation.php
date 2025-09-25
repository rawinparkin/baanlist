<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyLocation extends Model
{
    protected $guarded = [];

    public function subdistrict()
    {
        return $this->belongsTo(SubDistrict::class, 'sub_district_id', 'id');
    }
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }
}

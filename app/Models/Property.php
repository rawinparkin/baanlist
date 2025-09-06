<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $guarded = [];

    public function type()
    {
        return $this->belongsTo(PropertyType::class, 'property_type_id', 'id');
    }
    public function detail()
    {
        return $this->hasOne(PropertyDetails::class, 'property_id', 'id');
    }

    public function location()
    {
        return $this->hasOne(PropertyLocation::class, 'property_id', 'id');
    }
    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'property_amenities', 'property_id', 'amenity_id');
    }

    public function price()
    {
        return $this->hasOne(PropertyPrice::class, 'property_id', 'id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function purpose()
    {
        return $this->belongsTo(Purpose::class, 'property_status', 'id');
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'property_id', 'id')
            ->orderByDesc('is_cover')  // cover image first
            ->orderBy('sort_order');   // then by custom order
    }
}

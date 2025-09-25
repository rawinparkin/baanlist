<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $guarded = [];

    public function type()
    {
        return $this->belongsTo(BlogCategory::class, 'blogcat_id', 'id');
    }
}

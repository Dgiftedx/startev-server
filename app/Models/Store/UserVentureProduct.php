<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class UserVentureProduct extends Model
{
    protected $guarded = ['id'];


    protected $casts = [
        'images' => 'array',
        'sizes' => 'array',
        'colors' => 'array'
    ];

    public function store()
    {
        return $this->belongsTo(UserStore::class, 'store_id');
    }

}

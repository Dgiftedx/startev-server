<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessVenture extends Model
{
    protected $fillable = [
        'identifier',
        'business_id',
        'venture_name',
        'venture_address',
        'venture_description'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
}

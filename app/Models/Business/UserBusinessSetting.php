<?php

namespace App\Models\Business;

use App\Models\Business;
use Illuminate\Database\Eloquent\Model;

class UserBusinessSetting extends Model
{
    protected $guarded = ['id'];


    protected $casts = [
        'working_days' => 'array'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessSettlementBatch extends Model
{
    protected $guarded = ['id'];

    protected $cast = [
        'orders_pile' => 'array'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
}

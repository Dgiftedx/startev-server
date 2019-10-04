<?php

namespace App\Models;

use App\Models\Store\UserStore;
use Illuminate\Database\Eloquent\Model;

class StoreSettlementBatch extends Model
{
    protected $guarded = ['id'];


    protected $cast = [
        'settlement_reference' => 'array'
    ];


    public function store()
    {
        return $this->belongsTo(UserStore::class, 'store_id');
    }
}

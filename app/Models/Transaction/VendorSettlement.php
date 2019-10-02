<?php

namespace App\Models\Transaction;

use App\Models\Business\UserBusinessOrder;
use Illuminate\Database\Eloquent\Model;

class VendorSettlement extends Model
{
    protected $guarded = ['id'];


    public function order()
    {
        return $this->belongsTo(UserBusinessOrder::class, 'vendor_settlement_id');
    }
}

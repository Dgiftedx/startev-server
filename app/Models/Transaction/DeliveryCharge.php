<?php

namespace App\Models\Transaction;

use App\Models\Business\UserBusinessOrder;
use Illuminate\Database\Eloquent\Model;

class DeliveryCharge extends Model
{
    protected $fillable = [
        'order_id',
        'batch_id',
        'vendor_settlement_id',
        'orders_covered',
        'amount',
        'status'
    ];


    public function order()
    {
        return $this->belongsTo(UserBusinessOrder::class, 'order_id');
    }

    public function settlement()
    {
        return $this->belongsTo(VendorSettlement::class, 'vendor_settlement_id');
    }

    public function scopeByFilter($query,$data){
        $whereClause=[];
        $whereParam=[];
        foreach($data as $k=>$v){
            $whereClause[]=$k.'=?';
            $whereParam[]=$v;
        }
        $whereClause=implode(' and ',$whereClause);
        $result= $query->whereRaw($whereClause,$whereParam); //Collect the result using ->first() or ->get()
        return ($result);
    }

}

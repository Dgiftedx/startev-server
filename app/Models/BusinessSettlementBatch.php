<?php

namespace App\Models;

use App\Models\Business\UserBusinessOrder;
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
    public function userBusinessOrders(){
        return $this->hasMany(UserBusinessOrder::class,'business_settlement_batch_id');
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

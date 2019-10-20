<?php

namespace App\Models;

use App\Models\Store\UserStore;
use App\Models\Store\UserVentureOrder;
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

    public function userVentureOrders(){
        return $this->hasMany(UserVentureOrder::class,'store_settlement_batch_id');
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

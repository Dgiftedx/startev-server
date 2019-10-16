<?php

namespace App\Models;

use App\Models\Business\UserBusinessOrder;
use App\Models\Store\UserStore;
use App\Models\Store\UserVentureOrder;
use Illuminate\Database\Eloquent\Model;

class BatchOrder extends Model
{
    protected $guarded = ['id'];

    public function ordersBusiness()
    {
        return $this->hasMany(UserBusinessOrder::class, 'batch_id', 'batch_id');
    }

    public function ordersStudent()
    {
        return $this->hasMany(UserVentureOrder::class, 'batch_id', 'batch_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function store()
    {
        return $this->belongsTo(UserStore::class, 'store_id');
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

    public function paystackTransaction(){
        return $this->hasOne(PaystackTransaction::class,'batch_id','batch_id');
    }
}

<?php

namespace App\Models\Business;

use App\Models\Store\UserStore;
use App\Models\Store\UserVentureProduct;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserBusinessOrder extends Model
{

    protected $guarded = ['id'];


    public function scopeHasOrders($query, $businessId)
    {
        return $query->where('business_id','=', $businessId)->exists();
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function store()
    {
        return $this->belongsTo(UserStore::class, 'store_id');
    }

    public function product()
    {
        return $this->belongsTo(UserVentureProduct::class,'product_sku');
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

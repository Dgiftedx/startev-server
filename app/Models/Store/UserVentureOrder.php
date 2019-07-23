<?php

namespace App\Models\Store;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserVentureOrder extends Model
{
    protected $fillable = [
        'identifier',
        'store_id',
        'product_id',
        'product_sku',
        'buyer_id',
        'quantity',
        'amount',
        'delivery_address',
        'transaction_ref',
        'forwarded',
        'status'
    ];


    public function scopeHasOrders($query, $store)
    {
        return $query->where('store_id','=', $store)->exists();
    }

    public function store()
    {
        return $this->belongsTo(UserStore::class, 'store_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function product()
    {
        return $this->belongsTo(UserVentureProduct::class, 'product_id');
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

<?php

namespace App\Models\Store;

use App\Models\BatchOrder;
use App\Models\Business\UserBusinessProduct;
use App\Models\BusinessVenture;
use App\Models\StoreSettlementBatch;
use App\Models\Transaction\VendorSettlement;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserVentureOrder extends Model
{
    protected $fillable = [
        'identifier',
        'venture_id',
        'original_product_id',
        'commission',
        'name',
        'email',
        'phone',
        'location',
        'batch_id',
        'store_id',
        'product_id',
        'product_sku',
        'buyer_id',
        'quantity',
        'amount',
        'delivery_address',
        'transaction_ref',
        'forwarded',
        'status',
        'vendor_settlement_id',
        'store_settlement_batch_id',
    ];


    public function scopeHasOrders($query, $store)
    {
        return $query->where('store_id','=', $store)->exists();
    }

    public function store()
    {
        return $this->belongsTo(UserStore::class, 'store_id');
    }

    public function settlement()
    {
        return $this->belongsTo(VendorSettlement::class, 'vendor_settlement_id');
    }


    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function venture()
    {
        return $this->belongsTo(BusinessVenture::class, 'venture_id');
    }

    public function mainProduct()
    {
        return $this->belongsTo(UserBusinessProduct::class,'original_product_id');
    }

    public function product()
    {
        return $this->belongsTo(UserVentureProduct::class, 'product_id');
    }

    public function batch()
    {
        return $this->belongsTo(BatchOrder::class, 'batch_id', 'batch_id');
    }
    public function userVentureCommission(){
        return $this->hasOne(UserVentureCommission::class,'order_id','identifier');
    }

    public function storeSettlementBatch(){
        return $this->belongsTo(StoreSettlementBatch::class,'store_settlement_batch_id');
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

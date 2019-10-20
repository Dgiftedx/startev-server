<?php

namespace App\Models\Business;

use App\Models\BatchOrder;
use App\Models\BusinessSettlementBatch;
use App\Models\BusinessVenture;
use App\Models\Buyer;
use App\Models\Store\UserStore;
use App\Models\Store\UserVentureProduct;
use App\Models\Transaction\VendorSettlement;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserBusinessOrder extends Model
{

    protected $fillable = [
        'identifier',
        'store_id',
        'batch_id',
        'venture_id',
        'original_product_id',
        'name',
        'email',
        'phone',
        'business_id',
        'product_sku',
        'buyer_id',
        'quantity',
        'vendor_settlement_id',
        'commission',
        'delivery_address',
        'transaction_ref',
        'amount',
        'status',
        'notes',
        'business_settlement_batch_id',
    ];


    public function scopeHasOrders($query, $businessId)
    {
        return $query->where('business_id','=', $businessId)->exists();
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function realBuyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id');
    }

    public function store()
    {
        return $this->belongsTo(UserStore::class, 'store_id');
    }

    public function venture()
    {
        return $this->belongsTo(BusinessVenture::class, 'venture_id');
    }

    public function mainProduct()
    {
        return $this->belongsTo(UserBusinessProduct::class, 'original_product_id');
    }

    public function product()
    {
        return $this->belongsTo(UserVentureProduct::class,'product_sku');
    }


    public function batch()
    {
        return $this->belongsTo(BatchOrder::class, 'batch_id', 'batch_id');
    }

//    public function settlement()
//    {
//        return $this->hasOne(VendorSettlement::class, 'id');
//    }
    public function settlement()
    {
        return $this->belongsTo(VendorSettlement::class, 'vendor_settlement_id');
    }

    public function businessSettlementBatch(){
        return $this->belongsTo(BusinessSettlementBatch::class,'business_settlement_batch_id');
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

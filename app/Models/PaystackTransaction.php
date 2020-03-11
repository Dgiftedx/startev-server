<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaystackTransaction extends Model
{
    protected $guarded = ['id'];
    protected $casts=['data'=>'array'];

    public function scopeByFilter($query, $data)
    {
        $whereClause = [];
        $whereParam = [];
        foreach ($data as $k => $v) {
            $whereClause[] = $k . '=?';
            $whereParam[] = $v;
        }
        $whereClause = implode(' and ', $whereClause);
        $result = $query->whereRaw($whereClause, $whereParam); //Collect the result using ->first() or ->get()
        return ($result);
    }

    public function batchOrder(){
        return $this->belongsTo(BatchOrder::class,'batch_id','batch_id');
    }
}

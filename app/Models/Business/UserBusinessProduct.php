<?php

namespace App\Models\Business;

use App\Models\BusinessVenture;
use Illuminate\Database\Eloquent\Model;

class UserBusinessProduct extends Model
{

    protected $guarded = ['id'];

    protected $casts = [
        'images' => 'array',
        'colors' => 'array',
        'sizes' => 'array'
    ];

    public function venture()
    {
        return $this->belongsTo(BusinessVenture::class, 'venture_id');
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

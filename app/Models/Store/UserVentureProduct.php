<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class UserVentureProduct extends Model
{
    protected $guarded = ['id'];


    protected $casts = [
        'images' => 'array',
        'sizes' => 'array',
        'colors' => 'array'
    ];

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

}

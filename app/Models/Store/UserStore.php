<?php

namespace App\Models\Store;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserStore extends Model
{
    /**
     * @var array
     */
    protected $guarded = ['id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeHasStore($query, $user)
    {
        return $query->where('user_id','=', $user)->exists();
    }

    public function scopeStoreId($query, $user)
    {
        return $query->where('user_id', '=', $user)->first()->id;
    }

    public function scopeStoreIdentifier($query, $user)
    {
        return $query->where('user_id', '=', $user)->first()->identifier;
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

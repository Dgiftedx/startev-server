<?php

namespace App\Models\Store;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserCart extends Model
{
    protected  $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(UserVentureProduct::class,'product_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function store()
    {
        return $this->belongsTo(UserStore::class,'store_identifier');
    }
}

<?php

namespace App\Models;

use App\Models\Business\UserBusinessProduct;
use Illuminate\Database\Eloquent\Model;

class BusinessVenture extends Model
{
    protected $fillable = [
        'identifier',
        'business_id',
        'venture_name',
        'venture_address',
        'venture_description'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function partnerVenturePivot()
    {
        return $this->hasOne(Partnership::class, 'venture_id');
    }

    public function partners()
    {
        return $this->belongsToMany(User::class, 'partnerships','user_id','venture_id');
    }

    public function products()
    {
        return $this->hasMany(UserBusinessProduct::class, 'venture_id');
    }
}

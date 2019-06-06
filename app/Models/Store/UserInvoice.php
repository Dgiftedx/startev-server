<?php

namespace App\Models\Store;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserInvoice extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'items' => 'array'
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class,'buyer_id');
    }
}

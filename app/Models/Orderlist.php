<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orderlist extends Model
{
    protected $fillable = [
        'identifier_id',
        'cart_id',
        'buyer_email',
        'buyer_name',
        'buyer_address',
        'buyer_phone',
        'status',
        'amount'
    ];
}

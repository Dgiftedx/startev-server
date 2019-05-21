<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPartner extends Model
{
    protected $fillable = [
        'user_id',
        'partner_id',
        'ref_link',
        'status'
    ];
}

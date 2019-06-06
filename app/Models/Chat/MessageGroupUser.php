<?php

namespace App\Models\Chat;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class MessageGroupUser extends Model
{
    protected $guarded = ['id'];


    public function participant()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}

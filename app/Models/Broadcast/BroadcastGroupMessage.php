<?php

namespace App\Models\Broadcast;

use Illuminate\Database\Eloquent\Model;

class BroadcastGroupMessage extends Model
{
    protected $fillable = [
        'schedule_id',
        'user_avatar',
        'user_name',
        'message',
        'is_mentor'
    ];
}

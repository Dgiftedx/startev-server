<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Model;

class AudioSession extends Model
{
    protected $fillable = [
        'target_user_id',
        'host',
        'host_name',
        'channel_id',
        'status'
    ];
}

<?php

namespace App\Models\Broadcast;

use Illuminate\Database\Eloquent\Model;

class LiveSession extends Model
{
    protected $fillable = [
        'host',
        'schedule_id',
        'host_name',
        'uid',
        'channel_id',
        'status'
    ];
}

<?php

namespace App\Models\Broadcast;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class BroadcastSchedule extends Model
{
    protected $fillable = [
        'identifier',
        'user_id',
        'type',
        'participants',
        'date',
        'invitation_note',
        'status'
    ];

    protected $casts = [
        'participants' => 'array'
    ];

    protected $dates = [
        'date'
    ];

    public function mentor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

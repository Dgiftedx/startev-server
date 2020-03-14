<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class BlockUser extends Model
{
    protected $fillable = [
        'user_id',
        'blocked_user_id',
        'status'
    ];
    public function BlockedUsers() {
        return $this->belongsTo(User::class, 'blocked_user_id');
    }
}

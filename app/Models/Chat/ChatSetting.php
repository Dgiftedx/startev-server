<?php

namespace App\Models\Chat;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ChatSetting extends Model
{
    protected $fillable = [
        'user_id',
        'enable_mentors',
        'enable_followers'
    ];


    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}

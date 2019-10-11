<?php

namespace App\Models\Store;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserVentureCommission extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeHasCommission($query, $user)
    {
        return $query->where('user_id','=', $user)->exists();
    }

    public function store()
    {
        return $this->belongsTo(UserStore::class, 'store_id');
    }
}

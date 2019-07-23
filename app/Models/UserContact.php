<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserContact extends Model
{
    protected $fillable = [
        'user_id',
        'contacts_id'
    ];


    protected $casts = [
        'contacts_id' => 'array'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

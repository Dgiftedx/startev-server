<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vocal extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'ref_code',
        'designation',
        'institution',
        'address'
    ];


    public function registeredUsers()
    {
        return $this->hasMany(VocalReferral::class, 'vocal_id');
    }
}

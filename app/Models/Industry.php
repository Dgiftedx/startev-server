<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    protected $guarded = ['id'];


    public function mentors()
    {
        return $this->belongsToMany(User::class);
    }
}

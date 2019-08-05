<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VocalReferral extends Model
{
    protected $fillable = [
        'user_id',
        'vocal_id',
        'registered_on'
    ];

    public $timestamps = false;

    public function vocal()
    {
        return $this->belongsTo(Vocal::class, 'vocal_id');
    }
}

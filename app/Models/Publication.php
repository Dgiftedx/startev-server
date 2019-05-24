<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'audience',
        'image',
        'videoLink',
        'videoSource'
    ];



    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

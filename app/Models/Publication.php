<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelFollow\Traits\CanBeLiked;

class Publication extends Model
{
    use CanBeLiked;

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

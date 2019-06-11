<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelFollow\Traits\CanBeLiked;

class Feed extends Model
{
    use CanBeLiked;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'image',
        'video',
        'link',
        'time',
        'hasLiked',
        'post_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function feedComments()
    {
        return $this->hasMany(FeedComment::class, 'feed_id');
    }

}

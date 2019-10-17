<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedComment extends Model
{
    protected $guarded = ['id'];


    public function feed()
    {
        return $this->belongsTo(Feed::class, 'feed_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}

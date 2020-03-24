<?php

namespace App\Models;

use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelFollow\Traits\CanBeLiked;

class Feed extends Model implements Searchable
{
    use CanBeLiked;

    protected $fillable = [
        'views',
        'user_id',
        'title',
        'body',
        'image',
        'images',
        'video',
        'link',
        'time',
        'hasLiked',
        'post_type',
        'status'
    ];


    protected $casts = [
        'images' => 'array'
    ];

    /**
     * @return \Spatie\Searchable\SearchResult
     */
    public function getSearchResult(): SearchResult
    {
        $url = "/feed/" .  $this->id;

        return new SearchResult(
            $this,
            $this->title,
            $url
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function feedComments()
    {
        return $this->hasMany(FeedComment::class, 'feed_id');
    }

}

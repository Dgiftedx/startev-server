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
        'images',
        'files',
        'industry_id',
        'category_id'
    ];

    protected $casts = [
        'images' => 'array',
        'files' => 'array',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class, 'industry_id');
    }

    public function category()
    {
        return $this->belongsTo(PublicationCategory::class, 'category_id');
    }
}

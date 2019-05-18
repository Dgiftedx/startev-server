<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'image',
        'video',
        'link',
        'time',
        'post_type'
    ];



}

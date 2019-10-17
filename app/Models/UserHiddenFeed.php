<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserHiddenFeed extends Model
{
    protected $fillable = ['user_id','feed_id'];
    public $timestamps = false;
}

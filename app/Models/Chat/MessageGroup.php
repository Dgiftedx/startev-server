<?php

namespace App\Models\Chat;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class MessageGroup extends Model
{
    protected $guarded = ['id'];


    public function author()
    {
        return $this->belongsTo(User::class,'author_id');
    }
}

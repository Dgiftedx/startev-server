<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportFeed extends Model
{
    protected $fillable = [
    'user_id',
    'post_id',
    'reports',
    'status'
];
    public function reportUser() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function reportFeed() {
        return $this->belongsTo(Feed::class, 'post_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportUser extends Model
{
    protected $fillable = [
        'user_id',
        'reported_user_id',
        'status'
    ];
}

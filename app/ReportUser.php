<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ReportUser extends Model
{
    protected $fillable = [
        'user_id',
        'reported_user_id',
        'status'
    ];

    public function ReportedUser() {
        return $this->belongsTo(User::class, 'reported_user_id');
    }
    public function ReportingUser() {
        return $this->belongsTo(User::class, 'user_id');
    }
//    public function detailedUser() {
//        return $this->belongsTo(User:: class,['reported_user_id', 'user_id']);
//    }
}

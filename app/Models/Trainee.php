<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainee extends Model
{
    protected $fillable = [
        'trainee_id',
        'trainer_id',
        'lock_trainee'
    ];


    public function trainer()
    {
        return $this->hasMany(User::class, 'trainer_id');
    }

    public function loneTrainee()
    {
        return $this->belongsTo(User::class, 'trainee_id', 'id', 'trainees');
    }


    public function trainee()
    {
        return $this->hasMany(User::class, 'trainee_id');
    }

}

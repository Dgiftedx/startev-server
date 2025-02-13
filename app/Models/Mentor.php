<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'current_job_position',
        'organization',
        'total_trainee',
        'verified',
        'verification_status'
    ];

    /**
     * @var array
     */
    protected $casts = [

        'workExperience' => 'array',
        'education' => 'array'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

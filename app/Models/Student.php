<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'institution',
        'faculty',
        'department',
        'careerPath',
        'secondaryCP',
        'level'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

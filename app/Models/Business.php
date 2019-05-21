<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    /**
     * Mass fillable
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'services',
        'phone',
        'website',
        'social_handle'
    ];

    /**
     * json to array
     * @var array
     */
    protected $casts = [
        'services' => 'array',
        'social_handle' => 'array'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ventures()
    {
        return $this->hasMany(BusinessVenture::class, 'business_id');
    }
}

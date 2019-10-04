<?php

namespace App\Models;

use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Illuminate\Database\Eloquent\Model;

class Business extends Model implements Searchable
{
    /**
     * Mass fillable
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * json to array
     * @var array
     */
    protected $casts = [
        'services' => 'array',
        'social_handle' => 'array'
    ];


    /**
     * @return \Spatie\Searchable\SearchResult
     */
    public function getSearchResult(): SearchResult
    {
        $url = "/general-profile/" .  $this->businessUser($this->user_id)->slug;

        return new SearchResult(
            $this,
            $this->name,
            $url
        );
    }

    public function scopeGetUser($query, $id)
    {
        return User::find($query->find($id)->user_id);
    }


    public function businessUser($user_id)
    {
        return User::find($user_id);
    }

    public function scopeBusinessId($query, $user)
    {
        return $query->where('user_id','=',$user)->first()->id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ventures()
    {
        return $this->hasMany(BusinessVenture::class, 'business_id');
    }
}

<?php

namespace App\Models;

use App\Models\Business\UserBusinessProduct;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class BusinessVenture extends Model implements Searchable
{
    protected $fillable = [
        'identifier',
        'business_id',
        'venture_name',
        'venture_address',
        'venture_description'
    ];


    /**
     * @return \Spatie\Searchable\SearchResult
     */
    public function getSearchResult(): SearchResult
    {
        $url = "/partner-view/" .  $this->identifier;

        return new SearchResult(
            $this,
            $this->venture_name,
            $url
        );
    }

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function partnerVenturePivot()
    {
        return $this->hasOne(Partnership::class, 'venture_id');
    }

    public function partners()
    {
        return $this->belongsToMany(User::class, 'partnerships','user_id','venture_id');
    }

    public function products()
    {
        return $this->hasMany(UserBusinessProduct::class, 'venture_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Overtrue\LaravelFollow\Traits\CanFollow;
use Overtrue\LaravelFollow\Traits\CanLike;
use Overtrue\LaravelFollow\Traits\CanBeFollowed;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, CanFollow, CanLike, CanBeFollowed;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'name',
        'dob',
        'avatar',
        'phone',
        'email',
        'password',
        'username',
        'country',
        'state',
        'city',
        'address',
        'bio',
        'bg_image',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    protected $dates = [
        'dob'
    ];



    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Encrypt password field that will be stored in user object
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function mentor()
    {
        return $this->hasOne(Mentor::class);
    }

    public function business()
    {
        return $this->hasOne(Business::class);
    }

    public function industries()
    {
        return $this->belongsToMany(Industry::class, 'user_industry');
    }

    public function partnerUserPivot()
    {
        return $this->hasOne(Partnership::class,'user_id');
    }

    public function businessPartners()
    {
        return $this->belongsToMany(BusinessVenture::class, 'partnerships','user_id','venture_id');
    }

    public function feeds()
    {
        return $this->hasMany(Feed::class, 'user_id');
    }

    public function userComment()
    {
        return $this->belongsTo(FeedComment::class, 'user_id');
    }

    public function publications()
    {
        return $this->hasMany(Publication::class, 'user_id');
    }
}

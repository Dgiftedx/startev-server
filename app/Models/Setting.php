<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

    protected $fillable = ['key','value'];
    public static $STARTEV_PERCENTAGE_CHARGE = 'STARTEV_PERCENTAGE_CHARGE';

    public function value($key)
    {
        return Setting::where('key','=',$key)->first()->value;
    }
}

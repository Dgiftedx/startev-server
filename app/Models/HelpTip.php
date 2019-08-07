<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpTip extends Model
{
    protected $fillable = [
        'title',
        'target',
        'content',
        'link_text',
        'link'
    ];
}

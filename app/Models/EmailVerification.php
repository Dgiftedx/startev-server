<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    protected $fillable = [
        'user_id',
        'verified',
        'code',
        'expired',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ScopeByFilter($query, $data)
    {
        $whereClause = [];
        $whereParam = [];
        foreach ($data as $k => $v) {
            $whereClause[] = $k . '=?';
            $whereParam[] = $v;
        }
        $whereClause = implode(' and ', $whereClause);
        $result = $query->whereRaw($whereClause, $whereParam); //Collect the result using ->first() or->get()
//        dd ($result->get());
        return ($result);
    }

}

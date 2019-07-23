<?php

namespace App\Models\Chat;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = ['id'];


    public function sender()
    {
        return $this->belongsTo(User::class,'sender_id');
    }


    public function receiver()
    {
        return $this->belongsTo(User::class,'receiver_id');
    }

    public function conversation()
    {
        return $this->hasMany(MessageConversation::class,'conversation_id');
    }

    public function group()
    {
        return $this->hasOne(MessageGroup::class,'message_group_id');
    }

    public function scopeByFilter($query,$data){
        $whereClause=[];
        $whereParam=[];
        foreach($data as $k=>$v){
            $whereClause[]=$k.'=?';
            $whereParam[]=$v;
        }
        $whereClause=implode(' and ',$whereClause);
        $result= $query->whereRaw($whereClause,$whereParam); //Collect the result using ->first() or ->get()
        return ($result);
    }

}

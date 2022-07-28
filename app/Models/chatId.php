<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Group;

class chatId extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'group_id',
        'receiver_id',
        'type',
        'last_chat',
        'visible'
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function group(){
        return $this->belongsTo(Group::class);
    }

}

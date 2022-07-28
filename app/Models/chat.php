<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Group;

class chat extends Model
{
    use HasFactory;
    protected $fillable = [
        'message',
        'user_id',
        'chat_id',
        'seen',
        'receiver_id',
        'urls',
        'visible'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function opened(){
        return $this->hasMany(MessageOpened::class);
    }
}

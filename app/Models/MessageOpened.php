<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageOpened extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'message_id',
        'chat_id'
    ];

   
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMessageOpened extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'message_id',
        'group_id'
    ];
}


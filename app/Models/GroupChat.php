<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class GroupChat extends Model
{
    use HasFactory;
    protected $fillable = [
        'message',
        'user_id',
        'group_id',
        'urls',
        'visible'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}

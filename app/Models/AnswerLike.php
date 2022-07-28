<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerLike extends Model
{
    use HasFactory;
    protected $fillable = [
        'answer_id',
        'user_id',
        'auth_id'
    ];

}

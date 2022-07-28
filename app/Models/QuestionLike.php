<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionLike extends Model
{
    use HasFactory;
    protected $fillable = [
        'question_id',
        'user_id',
        'auth_id'
    ];
}

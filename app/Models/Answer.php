<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    protected $fillable = [
        'answer',
        'question_id',
        'user_id',
        'urls',
        'visible'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
     
    public function likes(){
        return $this->hasMany(AnswerLike::class);
    }
}

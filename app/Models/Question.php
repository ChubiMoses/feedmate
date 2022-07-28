<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'question',
        'category',
        'title',
        'user_id',
        'urls',
        'visible'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
     
    public function answers(){
        return $this->hasMany(Answer::class);
    }

    public function likes(){
        return $this->hasMany(QuestionLike::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\CommentLike;


class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'user_id',
        'post_id',
        'visible',
    ];
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function likes(){
        return $this->hasMany(CommentLike::class);
    }
}

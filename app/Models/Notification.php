<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'body',
        'title',
        'seen',
        'token',
        'owner_id',
        'post_id'
    ];
  
    public function user(){
        return $this->belongsTo(User::class);
    }
    
}

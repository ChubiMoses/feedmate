<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;
    protected $fillable = [
        'username',
        'email',
        'about',
        'school_id',
        'auth_id',
        'course_id',
        'profile_picture',
        'phone_number',
        'subscribed',
        'blocked',
        'deactivated',
        'points',
        'rating',
        'token',
        'sub_date',
        'last_visit'
    ];

    public function questions(){
        return $this->hasMany(Question::class);
    }
}

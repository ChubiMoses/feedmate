<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\GroupMember;

class Group extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image',
        'description',
        'doc_id',
        'type',
        'user_id',
        'visible',
        'private'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function members(){
        return $this->hasMany(GroupMember::class);
    }
}

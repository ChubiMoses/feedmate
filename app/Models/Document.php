<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'code',
        'bytes',
        'user_id',
        'category',
        'original_title',
        'school_id',
        'course_id',
        'downloads',
        'reads',
        'url',
        'visible'
    ];

    public function likes(){
        return $this->hasMany(DocumentLike::class);
    }
}

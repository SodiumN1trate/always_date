<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LifeSchoolComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'description',
        'article_id',
        'likes',
        'dislikes',
    ];
}

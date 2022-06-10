<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'life_School_comment_id',
        'rater_id',
        'rating',
    ];
}

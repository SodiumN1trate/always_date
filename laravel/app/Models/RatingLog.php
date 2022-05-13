<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rater_id',
        'rating',
    ];
}

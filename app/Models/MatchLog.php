<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_1',
        'user_2',
        'user_1_rating',
        'user_2_rating',
        'is_match',
    ];
}

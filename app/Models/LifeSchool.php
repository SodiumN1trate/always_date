<?php

namespace App\Models;

use App\Models\Scopes\Ancient33Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LifeSchool extends Model {
    use HasFactory;

    protected $fillable = [
        'title',
        'gender',
        'description',
        'reading_time',
        'number',
    ];

    public function scopeGender($query)
    {
        if(auth()->user()->hasRole('Administrators')) {
            return $query;
        }
        if(auth()->user()->gender) {
            return $query->where('gender', 1);
        }
        return $query->where('gender', 0);
    }
}

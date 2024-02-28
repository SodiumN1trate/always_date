<?php

namespace App\Models;

use App\Models\Scopes\Ancient33Scope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LifeSchool extends Model {
    use HasFactory, Filterable;

    protected $fillable = [
        'title',
        'gender',
        'description',
        'reading_time',
        'number',
    ];

    public function scopeGender($query)
    {
        return $query->where('gender', auth()->user()->gender);
    }

    public function modelFilter() {
        return $this->provideFilter(\App\ModelFilters\LifeSchoolFilter::class);
    }
}

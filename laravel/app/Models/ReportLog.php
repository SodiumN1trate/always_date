<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_id',
        'profile_id',
        'report_type',
    ];

    public function reportType() {
        return $this->belongsTo(ReportType::class, 'report_type');
    }
}

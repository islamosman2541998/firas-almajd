<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    public const STATUSES = ['new', 'reviewed', 'shortlisted', 'rejected'];

    protected $fillable = ['career_job_id', 'name', 'phone', 'email', 'experience', 'summary', 'status', 'locale', 'ip'];

    public function job(): BelongsTo
    {
        return $this->belongsTo(CareerJob::class, 'career_job_id');
    }
}

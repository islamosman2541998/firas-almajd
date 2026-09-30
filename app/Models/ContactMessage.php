<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    public const STATUSES = ['new', 'read', 'replied', 'archived'];

    protected $fillable = ['name', 'phone', 'email', 'service_id', 'message', 'status', 'locale', 'ip'];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}

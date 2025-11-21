<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LicenseValidationLog extends Model
{
    protected $fillable = [
        'license_id',
        'domain',
        'device_id',
        'ip',
        'user_agent',
        'is_valid',
        'message',
    ];

    protected $casts = [
        'is_valid' => 'boolean',
    ];

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }
}

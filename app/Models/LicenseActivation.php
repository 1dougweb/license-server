<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LicenseActivation extends Model
{
    protected $fillable = [
        'license_id',
        'device_id',
        'ip',
        'user_agent',
    ];

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }
}

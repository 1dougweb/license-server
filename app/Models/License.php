<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class License extends Model
{
    protected $fillable = [
        'product_id',
        'token',
        'allowed_domain',
        'device_limit',
        'expires_at',
        'is_blocked',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_blocked' => 'boolean',
        'device_limit' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($license) {
            if (empty($license->token)) {
                $license->token = Str::random(64);
            }
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function activations(): HasMany
    {
        return $this->hasMany(LicenseActivation::class);
    }

    public function validationLogs(): HasMany
    {
        return $this->hasMany(LicenseValidationLog::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isActive(): bool
    {
        return !$this->is_blocked && !$this->isExpired();
    }

    public function canActivateDevice(string $deviceId): bool
    {
        if ($this->is_blocked || $this->isExpired()) {
            return false;
        }

        $existingActivation = $this->activations()->where('device_id', $deviceId)->exists();
        if ($existingActivation) {
            return true;
        }

        $currentActivations = $this->activations()->count();
        return $currentActivations < $this->device_limit;
    }
}

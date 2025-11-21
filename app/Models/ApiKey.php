<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ApiKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
        'hash',
        'is_active',
        'last_used_at',
        'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected $hidden = [
        'key',
    ];

    /**
     * Gera uma nova API key
     */
    public static function generate(string $name, ?\DateTime $expiresAt = null): array
    {
        $key = 'ls_' . Str::random(48); // ls_ = license server prefix
        $hash = hash('sha256', $key);

        $apiKey = static::create([
            'name' => $name,
            'key' => $key,
            'hash' => $hash,
            'is_active' => true,
            'expires_at' => $expiresAt,
        ]);

        return [
            'id' => $apiKey->id,
            'key' => $key, // Retornar apenas uma vez
            'name' => $apiKey->name,
            'created_at' => $apiKey->created_at,
        ];
    }

    /**
     * Valida uma API key
     */
    public static function validate(string $key): ?self
    {
        $hash = hash('sha256', $key);

        $apiKey = static::where('hash', $hash)
            ->where('is_active', true)
            ->first();

        if (!$apiKey) {
            return null;
        }

        // Verificar expiração
        if ($apiKey->expires_at && $apiKey->expires_at->isPast()) {
            return null;
        }

        // Atualizar último uso
        $apiKey->update(['last_used_at' => now()]);

        return $apiKey;
    }

    /**
     * Verifica se está expirada
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}

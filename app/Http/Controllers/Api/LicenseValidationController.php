<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\LicenseActivation;
use App\Models\LicenseValidationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LicenseValidationController extends Controller
{
    public function validate(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'domain' => 'required|string',
            'device_id' => 'required|string',
        ]);

        // Log da API key usada (opcional, para auditoria)
        if ($request->has('api_key')) {
            Log::info('License validation request', [
                'api_key_id' => $request->get('api_key')->id,
                'api_key_name' => $request->get('api_key')->name,
                'domain' => $validated['domain'],
            ]);
        }

        $license = License::where('token', $validated['token'])->first();

        if (!$license) {
            return $this->logAndReturn(false, 'Token inválido', null, $validated);
        }

        // Verificar se está bloqueada
        if ($license->is_blocked) {
            return $this->logAndReturn(false, 'Licença bloqueada', $license, $validated);
        }

        // Verificar expiração
        if ($license->isExpired()) {
            return $this->logAndReturn(false, 'Licença expirada', $license, $validated);
        }

        // Verificar domínio
        if ($license->allowed_domain && $license->allowed_domain !== $validated['domain']) {
            return $this->logAndReturn(false, 'Domínio não permitido', $license, $validated);
        }

        // Verificar limite de dispositivos
        if (!$license->canActivateDevice($validated['device_id'])) {
            return $this->logAndReturn(false, 'Limite de dispositivos atingido', $license, $validated);
        }

        // Registrar ou atualizar ativação
        LicenseActivation::updateOrCreate(
            [
                'license_id' => $license->id,
                'device_id' => $validated['device_id'],
            ],
            [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]
        );

        return $this->logAndReturn(true, 'Licença válida', $license, $validated);
    }

    private function logAndReturn(bool $isValid, string $message, ?License $license, array $data)
    {
        LicenseValidationLog::create([
            'license_id' => $license?->id,
            'domain' => $data['domain'],
            'device_id' => $data['device_id'],
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'is_valid' => $isValid,
            'message' => $message,
        ]);

        return response()->json([
            'valid' => $isValid,
            'message' => $message,
            'license' => $isValid && $license ? [
                'id' => $license->id,
                'product_id' => $license->product_id,
                'expires_at' => $license->expires_at?->toIso8601String(),
                'device_limit' => $license->device_limit,
            ] : null,
        ]);
    }
}

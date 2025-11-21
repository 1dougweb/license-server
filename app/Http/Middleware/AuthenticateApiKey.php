<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ApiKey;

class AuthenticateApiKey
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key') ?? $request->header('Authorization');

        // Se vier no header Authorization, remover "Bearer " se presente
        if ($apiKey && str_starts_with($apiKey, 'Bearer ')) {
            $apiKey = substr($apiKey, 7);
        }

        // NÃO aceitar API key via query parameter por segurança
        // API key DEVE vir apenas nos headers

        if (!$apiKey) {
            // Log de tentativa sem API key
            \Log::warning('Tentativa de acesso à API sem API key', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
            ]);

            return response()->json([
                'error' => 'API key não fornecida',
                'message' => 'Forneça uma API key válida no header X-API-Key ou Authorization',
            ], 401);
        }

        $validKey = ApiKey::validate($apiKey);

        if (!$validKey) {
            // Log de tentativa com API key inválida
            \Log::warning('Tentativa de acesso à API com API key inválida', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'api_key_preview' => substr($apiKey, 0, 8) . '...',
            ]);

            return response()->json([
                'error' => 'API key inválida',
                'message' => 'A API key fornecida é inválida, expirada ou desativada',
            ], 401);
        }

        // Log de acesso bem-sucedido (opcional, pode ser removido em produção se gerar muitos logs)
        \Log::info('Acesso à API autorizado', [
            'api_key_id' => $validKey->id,
            'api_key_name' => $validKey->name,
            'ip' => $request->ip(),
        ]);

        // Adicionar a API key ao request para uso posterior
        $request->merge(['api_key' => $validKey]);

        return $next($request);
    }
}

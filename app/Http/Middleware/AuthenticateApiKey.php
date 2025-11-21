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

        // Se não veio no header, tentar no query parameter (menos seguro, mas útil para testes)
        if (!$apiKey) {
            $apiKey = $request->query('api_key');
        }

        if (!$apiKey) {
            return response()->json([
                'error' => 'API key não fornecida',
                'message' => 'Forneça uma API key válida no header X-API-Key ou Authorization',
            ], 401);
        }

        $validKey = ApiKey::validate($apiKey);

        if (!$validKey) {
            return response()->json([
                'error' => 'API key inválida',
                'message' => 'A API key fornecida é inválida, expirada ou desativada',
            ], 401);
        }

        // Adicionar a API key ao request para uso posterior
        $request->merge(['api_key' => $validKey]);

        return $next($request);
    }
}

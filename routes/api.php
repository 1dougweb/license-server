<?php

use App\Http\Controllers\Api\LicenseValidationController;
use Illuminate\Support\Facades\Route;

// API v1 - Rate limiting mais restritivo para validação de licença
Route::prefix('v1')->group(function () {
    // Rate limit: 30 requisições por minuto por IP
    Route::post('/license/validate', [LicenseValidationController::class, 'validate'])
        ->middleware('throttle:30,1');
});

// Mantém compatibilidade com rota antiga (com rate limiting)
Route::post('/license/validate', [LicenseValidationController::class, 'validate'])
    ->middleware('throttle:30,1');


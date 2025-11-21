<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('products', App\Http\Controllers\ProductController::class);
    Route::resource('licenses', App\Http\Controllers\LicenseController::class);
    Route::post('licenses/{license}/toggle-block', [App\Http\Controllers\LicenseController::class, 'toggleBlock'])->name('licenses.toggle-block');
    Route::post('licenses/{license}/regenerate-token', [App\Http\Controllers\LicenseController::class, 'regenerateToken'])->name('licenses.regenerate-token');
    
    Route::get('logs', [App\Http\Controllers\LicenseLogController::class, 'index'])->name('logs.index');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

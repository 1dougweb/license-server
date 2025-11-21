<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\LicenseValidationLog;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_licenses' => License::count(),
            'active_licenses' => License::where('is_blocked', false)
                ->where(function ($query) {
                    $query->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now());
                })
                ->count(),
            'expired_licenses' => License::where(function ($query) {
                $query->where('is_blocked', true)
                    ->orWhere(function ($q) {
                        $q->whereNotNull('expires_at')
                            ->where('expires_at', '<=', now());
                    });
            })->count(),
        ];

        $recentLogs = LicenseValidationLog::with('license.product')
            ->latest()
            ->limit(10)
            ->get();

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentLogs' => $recentLogs,
        ]);
    }
}

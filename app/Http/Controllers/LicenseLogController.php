<?php

namespace App\Http\Controllers;

use App\Models\LicenseValidationLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LicenseLogController extends Controller
{
    public function index(Request $request)
    {
        $query = LicenseValidationLog::with('license.product')
            ->latest();

        // Filtros
        if ($request->has('license_id') && $request->license_id) {
            $query->where('license_id', $request->license_id);
        }

        if ($request->has('domain') && $request->domain) {
            $query->where('domain', 'like', '%' . $request->domain . '%');
        }

        if ($request->has('device_id') && $request->device_id) {
            $query->where('device_id', $request->device_id);
        }

        if ($request->has('is_valid') && $request->is_valid !== null) {
            $query->where('is_valid', $request->is_valid);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(20)->withQueryString();

        return Inertia::render('LicenseLogs/Index', [
            'logs' => $logs,
            'filters' => $request->only(['license_id', 'domain', 'device_id', 'is_valid', 'date_from', 'date_to']),
        ]);
    }
}

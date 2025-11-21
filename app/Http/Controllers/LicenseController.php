<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class LicenseController extends Controller
{
    public function index()
    {
        $licenses = License::with('product')
            ->latest()
            ->paginate(10);
        
        return Inertia::render('Licenses/Index', [
            'licenses' => $licenses,
        ]);
    }

    public function create()
    {
        $products = Product::all();
        
        return Inertia::render('Licenses/Create', [
            'products' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'allowed_domain' => 'nullable|string|max:255',
            'device_limit' => 'required|integer|min:1',
            'expires_at' => 'nullable|date',
        ]);

        $validated['token'] = Str::random(64);

        License::create($validated);

        return redirect()->route('licenses.index')
            ->with('success', 'Licença criada com sucesso!');
    }

    public function edit(License $license)
    {
        $products = Product::all();
        
        return Inertia::render('Licenses/Edit', [
            'license' => $license->load('product'),
            'products' => $products,
        ]);
    }

    public function update(Request $request, License $license)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'allowed_domain' => 'nullable|string|max:255',
            'device_limit' => 'required|integer|min:1',
            'expires_at' => 'nullable|date',
        ]);

        $license->update($validated);

        return redirect()->route('licenses.index')
            ->with('success', 'Licença atualizada com sucesso!');
    }

    public function destroy(License $license)
    {
        $license->delete();

        return redirect()->route('licenses.index')
            ->with('success', 'Licença excluída com sucesso!');
    }

    public function toggleBlock(License $license)
    {
        $license->update([
            'is_blocked' => !$license->is_blocked,
        ]);

        return redirect()->back()
            ->with('success', $license->is_blocked ? 'Licença bloqueada!' : 'Licença desbloqueada!');
    }

    public function regenerateToken(License $license)
    {
        $license->update([
            'token' => Str::random(64),
        ]);

        return redirect()->back()
            ->with('success', 'Token regenerado com sucesso!');
    }
}

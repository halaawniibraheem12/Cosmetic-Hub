<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $suppliers = Supplier::withCount('products')
            ->with('user')
            ->latest()
            ->paginate(10);

        $totalSuppliers = Supplier::count();

        $totalProductsFromSuppliers =
            Supplier::withCount('products')->get()->sum('products_count');

        $avgProductsPerSupplier = $totalSuppliers > 0
            ? $totalProductsFromSuppliers / $totalSuppliers
            : 0;

        $suppliersWithProducts = Supplier::has('products')->count();

        $coveragePercentage = $totalSuppliers > 0
            ? ($suppliersWithProducts / $totalSuppliers) * 100
            : 0;

        $activeSuppliers = Supplier::withCount('products')
            ->orderByDesc('products_count')
            ->get();

        return view('suppliers.index', compact(
            'suppliers',
            'totalSuppliers',
            'totalProductsFromSuppliers',
            'avgProductsPerSupplier',
            'coveragePercentage',
            'activeSuppliers'
        ));
    }

    public function create()
    {
        $this->authorize('create', Supplier::class);

        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Supplier::class);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email',
        ]);

        $payload = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        if (Schema::hasColumn('suppliers', 'user_id')) {
            $payload['user_id'] = auth()->id();
        }

        Supplier::create($payload);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier created successfully');
    }

    public function edit(Supplier $supplier)
    {
        $this->authorize('update', $supplier);

        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $this->authorize('update', $supplier);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email',
        ]);

        $supplier->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier updated successfully');
    }

    public function destroy(Supplier $supplier)
    {
        $this->authorize('delete', $supplier);

        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully');
    }
}
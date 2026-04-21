<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

class DashboardController extends Controller
{
    /**
     * Display dashboard page
     */
    public function index()
    {
        // Cards counts
        $totalProducts   = Product::count();
        $totalCategories = Category::count();
        $totalSuppliers  = Supplier::count();

        // All products (with relations) + pagination
        $products = Product::with(['category', 'suppliers', 'user'])
            ->latest()
            ->paginate(9);

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalSuppliers',
            'products'
        ));
    }
}
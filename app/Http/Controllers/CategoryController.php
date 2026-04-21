<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $categories = Category::withCount('products')
            ->latest()
            ->paginate(12);

        $totalProducts = Product::count();

        $avgProductsPerCategory = Category::count() > 0
            ? $totalProducts / Category::count()
            : 0;

        return view('categories.index', compact(
            'categories',
            'totalProducts',
            'avgProductsPerCategory'
        ));
    }

    public function create()
    {
        $this->authorize('create', Category::class);

        return view('categories.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Category::class);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $payload = [
            'name' => $request->name,
        ];

        if (Schema::hasColumn('categories', 'user_id')) {
            $payload['user_id'] = auth()->id();
        }

        Category::create($payload);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category created successfully');
    }

    public function edit(Category $category)
    {
        $this->authorize('update', $category);

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category updated successfully');
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category moved to trash successfully');
    }

    public function trash()
    {
        $this->authorize('viewAny', Category::class);

        $query = Category::onlyTrashed()
            ->with('user')
            ->withCount('products')
            ->orderByDesc('deleted_at');

        if (!(method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin())) {
            $query->where('user_id', auth()->id());
        }

        $trashedCategories = $query->paginate(10);

        return view('categories.trash', compact('trashedCategories'));
    }

    public function restore($id)
    {
        $category = Category::onlyTrashed()
            ->with('user')
            ->findOrFail($id);

        $this->authorize('restore', $category);

        $category->restore();

        return redirect()
            ->route('categories.trash')
            ->with('success', 'Category restored successfully.');
    }

    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()
            ->with('user')
            ->findOrFail($id);

        $this->authorize('forceDelete', $category);

        $category->forceDelete();

        return redirect()
            ->route('categories.trash')
            ->with('success', 'Category permanently deleted.');
    }
}
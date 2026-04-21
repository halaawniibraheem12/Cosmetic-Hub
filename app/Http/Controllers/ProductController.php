<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only([
            'create','store','edit','update','destroy',
            'trash','restore','forceDelete'
        ]);
    }

    public function index(Request $request)
    {
        $search     = $request->query('search');
        $categoryId = $request->query('category_id');
        $supplierId = $request->query('supplier_id');
        $sort       = $request->query('sort', 'created_at_desc');
        $mode       = $request->query('mode', 'any');

        $mode = in_array($mode, ['any', 'all'], true) ? $mode : 'any';

        $hasPrice       = Schema::hasColumn('products', 'price');
        $hasDescription = Schema::hasColumn('products', 'description');

        $query = Product::with(['category','suppliers','user'])
            ->withCount('suppliers');

        if ($mode === 'all') {

            if (!empty($search)) {
                $query->where(function ($q) use ($search, $hasDescription) {
                    $q->where('name', 'like', "%{$search}%");

                    if ($hasDescription) {
                        $q->orWhere('description', 'like', "%{$search}%");
                    }
                });
            }

            if (!empty($categoryId)) {
                $query->where('category_id', $categoryId);
            }

            if (!empty($supplierId)) {
                $query->whereHas('suppliers', function ($sq) use ($supplierId) {
                    $sq->where('suppliers.id', $supplierId);
                });
            }

        } else {

            if (!empty($search) || !empty($categoryId) || !empty($supplierId)) {
                $query->where(function ($q) use ($search, $categoryId, $supplierId, $hasDescription) {

                    if (!empty($search)) {
                        $q->where('name', 'like', "%{$search}%");

                        if ($hasDescription) {
                            $q->orWhere('description', 'like', "%{$search}%");
                        }
                    }

                    if (!empty($categoryId)) {
                        if (!empty($search)) {
                            $q->orWhere('category_id', $categoryId);
                        } else {
                            $q->where('category_id', $categoryId);
                        }
                    }

                    if (!empty($supplierId)) {
                        if (!empty($search) || !empty($categoryId)) {
                            $q->orWhereHas('suppliers', function ($sq) use ($supplierId) {
                                $sq->where('suppliers.id', $supplierId);
                            });
                        } else {
                            $q->whereHas('suppliers', function ($sq) use ($supplierId) {
                                $sq->where('suppliers.id', $supplierId);
                            });
                        }
                    }
                });
            }
        }

        $allowedSorts = [
            'created_at_desc' => ['created_at','desc'],
            'created_at_asc'  => ['created_at','asc'],
            'name_asc'        => ['name','asc'],
            'name_desc'       => ['name','desc'],
        ];

        if ($hasPrice) {
            $allowedSorts['price_asc']  = ['price','asc'];
            $allowedSorts['price_desc'] = ['price','desc'];
        }

        if (!array_key_exists($sort, $allowedSorts)) {
            $sort = 'created_at_desc';
        }

        [$field,$dir] = $allowedSorts[$sort];
        $query->orderBy($field, $dir);

        $products = $query->paginate(10)->withQueryString();

        $categories = Category::orderBy('name')->get();
        $suppliers  = Supplier::orderBy('name')->get();

        return view('products.index', compact(
            'products',
            'categories',
            'suppliers',
            'hasPrice',
            'mode'
        ));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $suppliers  = Supplier::orderBy('name')->get();
        return view('products.create', compact('categories','suppliers'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'name'        => $data['name'],
            'price'       => $data['price'] ?? null,
            'category_id' => $data['category_id'],
            'user_id'     => auth()->id(),
            'image_path'  => $imagePath,
        ]);

        $sync = [];
        foreach (($request->suppliers ?? []) as $id => $row) {
            if (!empty($row['selected'])) {
                $sync[$id] = [
                    'cost_price'     => $row['cost_price'] ?? null,
                    'lead_time_days' => $row['lead_time_days'] ?? null,
                ];
            }
        }

        $product->suppliers()->sync($sync);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        $product->load(['category','suppliers','user']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);

        $categories = Category::orderBy('name')->get();
        $suppliers  = Supplier::orderBy('name')->get();

        $product->load('suppliers');

        return view('products.edit', compact('product','categories','suppliers'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $data = $request->validated();

        $updateData = [
            'name'        => $data['name'],
            'price'       => $data['price'] ?? $product->price,
            'category_id' => $data['category_id'],
        ];

        $removeImage = $request->input('remove_image') == '1';

        if ($removeImage) {
            if (!empty($product->image_path) && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }
            $updateData['image_path'] = null;
        }

        if ($request->hasFile('image')) {
            if (!empty($product->image_path) && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }

            $updateData['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($updateData);

        $sync = [];
        foreach (($request->suppliers ?? []) as $id => $row) {
            if (!empty($row['selected'])) {
                $sync[$id] = [
                    'cost_price'     => $row['cost_price'] ?? null,
                    'lead_time_days' => $row['lead_time_days'] ?? null,
                ];
            }
        }

        $product->suppliers()->sync($sync);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product moved to Trash!');
    }

    public function trash(Request $request)
    {
        $trashedProducts = Product::onlyTrashed()
            ->with(['category','suppliers','user'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('products.trash', compact('trashedProducts'));
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        $this->authorize('restore', $product);

        $product->restore();

        return redirect()->route('products.trash')
            ->with('success', 'Product restored successfully!');
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->with('suppliers')->findOrFail($id);

        $this->authorize('forceDelete', $product);

        if (!empty($product->image_path) && Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->suppliers()->detach();

        $product->forceDelete();

        return redirect()->route('products.trash')
            ->with('success', 'Product deleted permanently!');
    }
}
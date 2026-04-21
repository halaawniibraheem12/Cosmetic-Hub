@extends('layouts.app')

@section('title','Dashboard')

@section('content')
@php
    $placeholder = asset('images/placeholder.png');
@endphp

<div class="space-y-8">

    <div class="bg-white rounded-2xl shadow p-6 border border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-500 mt-1">
                Welcome back,
                <span class="font-semibold text-purple-700">
                    {{ auth()->user()->name ?? auth()->user()->email }}
                </span>
            </p>
        </div>

        <div class="flex flex-wrap gap-3">

            @can('create', App\Models\Product::class)
                <a href="{{ route('products.create') }}"
                   class="px-4 py-2 rounded-xl bg-gradient-to-r from-pink-500 to-purple-600 text-white shadow hover:opacity-95 transition flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i>
                    Add Product
                </a>
            @endcan

            @can('create', App\Models\Category::class)
                <a href="{{ route('categories.create') }}"
                   class="px-4 py-2 rounded-xl bg-gradient-to-r from-pink-500 to-purple-600 text-white shadow hover:opacity-95 transition flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i>
                    Add Category
                </a>
            @endcan

            @can('create', App\Models\Supplier::class)
                <a href="{{ route('suppliers.create') }}"
                   class="px-4 py-2 rounded-xl bg-gradient-to-r from-pink-500 to-purple-600 text-white shadow hover:opacity-95 transition flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i>
                    Add Supplier
                </a>
            @endcan

        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white rounded-2xl shadow border p-6">
            <p class="text-sm text-gray-500">Total Products</p>
            <p class="text-3xl font-bold">{{ $totalProducts }}</p>

            <a href="{{ route('products.index') }}"
               class="mt-4 inline-block text-sm text-purple-700 font-semibold">
                View Products →
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow border p-6">
            <p class="text-sm text-gray-500">Total Categories</p>
            <p class="text-3xl font-bold">{{ $totalCategories }}</p>

            <a href="{{ route('categories.index') }}"
               class="mt-4 inline-block text-sm text-purple-700 font-semibold">
                View Categories →
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow border p-6">
            <p class="text-sm text-gray-500">Total Suppliers</p>
            <p class="text-3xl font-bold">{{ $totalSuppliers }}</p>

            <a href="{{ route('suppliers.index') }}"
               class="mt-4 inline-block text-sm text-purple-700 font-semibold">
                View Suppliers →
            </a>
        </div>

    </div>

    <div class="bg-white rounded-2xl shadow border overflow-hidden">

        <div class="p-6 border-b bg-gray-50">
            <h2 class="text-xl font-bold">Products</h2>
            <p class="text-sm text-gray-500">All products</p>
        </div>

        <div class="p-6">

            @if(isset($products) && $products->count())

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                    @foreach($products as $product)

                        @php
                            $img = $product->image_path
                                ? asset('storage/'.$product->image_path)
                                : $placeholder;
                        @endphp

                        <div class="bg-white rounded-xl border shadow-sm hover:shadow-md transition overflow-hidden flex flex-col">

                            <div class="relative bg-gray-50 p-2 flex justify-center">
                                <span class="absolute top-2 right-2 bg-purple-600/70 backdrop-blur text-white text-xs font-semibold px-3 py-1 rounded-full shadow">
                                    ${{ number_format((float)$product->price,2) }}
                                </span>

                                <img src="{{ $img }}"
                                     class="w-full h-auto object-contain rounded-lg"
                                     onerror="this.src='{{ $placeholder }}'">
                            </div>

                            <div class="p-4 space-y-2 flex-1">

                                <h3 class="font-semibold text-gray-900 truncate">
                                    {{ $product->name }}
                                </h3>

                                <p class="text-xs text-gray-500">
                                    {{ $product->category->name ?? '-' }}
                                </p>

                                <div class="pt-2 flex gap-2">
                                    <a href="{{ route('products.show',$product) }}"
                                       class="flex-1 text-center px-3 py-1.5 rounded-lg bg-purple-100 text-purple-700 text-sm">
                                        View
                                    </a>

                                    @can('update', $product)
                                        <a href="{{ route('products.edit',$product) }}"
                                           class="flex-1 text-center px-3 py-1.5 rounded-lg bg-purple-100 text-purple-700 text-sm">
                                            Edit
                                        </a>
                                    @endcan
                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

                <div class="mt-6">
                    {{ $products->links() }}
                </div>

            @else

                <div class="text-center py-16">
                    <p class="text-gray-500">No products yet.</p>

                    @can('create', App\Models\Product::class)
                        <a href="{{ route('products.create') }}"
                           class="mt-4 inline-block px-6 py-2 bg-purple-600 text-white rounded-lg">
                            + Add Product
                        </a>
                    @endcan
                </div>

            @endif

        </div>

    </div>

</div>
@endsection
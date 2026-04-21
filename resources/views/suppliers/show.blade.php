{{-- resources/views/suppliers/show.blade.php --}}
@extends('layouts.app')

@section('title', $supplier->name . ' - Cosmetics Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $supplier->name }}</h1>
            <p class="mt-2 text-sm text-gray-600">Supplier Details & Products</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('suppliers.edit', $supplier) }}" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('suppliers.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Supplier Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Info Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-center">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-100 to-teal-100 flex items-center justify-center">
                                <i class="fas fa-truck text-3xl text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-2xl font-bold text-gray-900">{{ $supplier->name }}</h2>
                                <p class="text-gray-600">Cosmetics Supplier</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            Active Supplier
                        </span>
                    </div>

                    <!-- Contact Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Contact Information</h3>
                                <div class="space-y-2">
                                    @if($supplier->email)
                                    <div class="flex items-center">
                                        <i class="fas fa-envelope text-gray-400 mr-3 w-4"></i>
                                        <a href="mailto:{{ $supplier->email }}" class="text-gray-900 hover:text-blue-600">
                                            {{ $supplier->email }}
                                        </a>
                                    </div>
                                    @endif
                                    @if($supplier->phone)
                                    <div class="flex items-center">
                                        <i class="fas fa-phone text-gray-400 mr-3 w-4"></i>
                                        <a href="tel:{{ $supplier->phone }}" class="text-gray-900 hover:text-blue-600">
                                            {{ $supplier->phone }}
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Location</h3>
                                @if($supplier->address)
                                <div class="flex items-start">
                                    <i class="fas fa-map-marker-alt text-gray-400 mr-3 mt-1"></i>
                                    <span class="text-gray-900">{{ $supplier->address }}</span>
                                </div>
                                @else
                                <p class="text-gray-500 italic">No address provided</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($supplier->notes)
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-3">Notes</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-700">{{ $supplier->notes }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Stats -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-4">Supplier Statistics</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600">{{ $supplier->products_count ?? 0 }}</div>
                                <div class="text-sm text-blue-800">Total Products</div>
                            </div>
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <div class="text-lg font-semibold text-green-800">{{ $supplier->created_at->format('M d, Y') }}</div>
                                <div class="text-sm text-green-700">Added Date</div>
                            </div>
                            <div class="text-center p-3 bg-purple-50 rounded-lg">
                                <div class="text-lg font-semibold text-purple-800">{{ $supplier->updated_at->format('M d, Y') }}</div>
                                <div class="text-sm text-purple-700">Last Updated</div>
                            </div>
                            <div class="text-center p-3 bg-yellow-50 rounded-lg">
                                <div class="text-lg font-semibold text-yellow-800">{{ $supplier->created_at->diffForHumans() }}</div>
                                <div class="text-sm text-yellow-700">Time Since Added</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Supplier's Products -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Products from this Supplier</h2>
                    <p class="text-sm text-gray-600 mt-1">All products supplied by {{ $supplier->name }}</p>
                </div>
                <div class="p-6">
                    @if($products->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($products as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gradient-to-r from-pink-100 to-purple-100 flex items-center justify-center">
                                                <i class="fas fa-box text-pink-600"></i>
                                            </div>
                                            <div class="ml-3">
                                                <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $product->category->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-900">${{ number_format($product->price, 2) }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            {{ $product->quantity > 10 ? 'bg-green-100 text-green-800' : 
                                               ($product->quantity > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $product->quantity }} units
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                            <i class="fas fa-box-open text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-700 mb-2">No Products Yet</h3>
                        <p class="text-gray-500">No products are linked to this supplier</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Actions & Quick Stats -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900">Quick Actions</h2>
                </div>
                <div class="p-6 space-y-4">
                    <a href="{{ route('products.create') }}?supplier_id={{ $supplier->id }}" 
                       class="block w-full text-center px-4 py-3 bg-gradient-to-r from-blue-500 to-teal-500 text-white font-medium rounded-lg hover:from-blue-600 hover:to-teal-600 transition-all duration-200">
                        <i class="fas fa-plus-circle mr-2"></i> Add Product from this Supplier
                    </a>
                    <a href="mailto:{{ $supplier->email }}" 
                       class="block w-full text-center px-4 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-envelope mr-2"></i> Send Email
                    </a>
                    <a href="tel:{{ $supplier->phone }}" 
                       class="block w-full text-center px-4 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-phone mr-2"></i> Call Supplier
                    </a>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="bg-white rounded-xl shadow-lg border border-red-200">
                <div class="px-6 py-4 border-b border-red-200 bg-red-50">
                    <h2 class="text-lg font-bold text-red-900">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Danger Zone
                    </h2>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-4">
                        Deleting this supplier will remove it from the system. Products associated with this supplier will not be deleted.
                    </p>
                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full px-4 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors duration-200">
                            <i class="fas fa-trash mr-2"></i> Delete Supplier
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
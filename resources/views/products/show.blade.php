@extends('layouts.app')

@section('title', 'View Product')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Product Details</h1>
            <p class="text-gray-500 mt-1">View full information about this product</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('products.index') }}"
               class="px-5 py-2.5 rounded-xl bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100 font-semibold transition">
                ← Back
            </a>

            @can('update', $product)
                <a href="{{ route('products.edit', $product) }}"
                   class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 text-white hover:opacity-95 font-semibold shadow-sm transition">
                    Edit
                </a>
            @endcan

            @can('delete', $product)
                <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline-block m-0">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                            class="px-5 py-2.5 rounded-xl bg-red-50 text-red-700 border border-red-200 hover:bg-red-100 font-semibold transition delete-btn"
                            data-name="{{ $product->name }}">
                        Move to Trash 🗑️
                    </button>
                </form>
            @endcan
        </div>
    </div>

    @php
        $imageSrc = $product->image_path
            ? asset('storage/' . $product->image_path)
            : asset('images/placeholder.png');
    @endphp

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Product Image</h3>
                <p class="text-sm text-gray-500">Preview of the product image</p>
            </div>

            @if($product->image_path)
                <a href="{{ $imageSrc }}" target="_blank"
                   class="px-5 py-2.5 rounded-xl bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100 font-semibold transition">
                    View Full Image
                </a>
            @endif
        </div>

        <div class="image-box">
            <img src="{{ $imageSrc }}"
                 alt="Product Image"
                 class="product-img"
                 onerror="this.onerror=null;this.src='{{ asset('images/placeholder.png') }}';">
        </div>

        @if(!$product->image_path)
            <p class="text-sm text-gray-500 mt-3">
                No image uploaded for this product. Showing placeholder.
            </p>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <p class="font-semibold text-gray-900">Main Info</p>
                <p class="text-xs text-gray-500">Product details and suppliers</p>
            </div>

            <div class="p-6 space-y-6">

                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Created: {{ $product->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <div class="md:text-right">
                        <p class="text-sm text-gray-500">Price</p>
                        <p class="text-3xl font-bold text-purple-700">
                            ${{ number_format((float)$product->price,2) }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 border border-gray-100 p-4 rounded-2xl">
                        <p class="text-xs text-gray-500">Category</p>
                        <p class="font-semibold text-gray-900 mt-1">
                            {{ $product->category->name ?? '-' }}
                        </p>
                    </div>

                    <div class="bg-gray-50 border border-gray-100 p-4 rounded-2xl">
                        <p class="text-xs text-gray-500">Owner</p>
                        <p class="font-semibold text-gray-900 mt-1">
                            {{ $product->user->name ?? '-' }}
                            @if($product->user?->email)
                                <span class="text-sm text-gray-500">({{ $product->user->email }})</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-sm font-semibold text-gray-800">Suppliers</p>
                        <span class="text-xs text-gray-500">
                            Total: {{ $product->suppliers->count() }}
                        </span>
                    </div>

                    @if($product->suppliers->count())
                        <div class="border border-gray-200 rounded-2xl overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                        <th class="p-4 text-left">Supplier</th>
                                        <th class="p-4 text-left">Email</th>
                                        <th class="p-4 text-left">Cost Price</th>
                                        <th class="p-4 text-left">Lead Time (days)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($product->suppliers as $supplier)
                                        <tr class="hover:bg-purple-50/30 transition">
                                            <td class="p-4 font-semibold text-gray-900">{{ $supplier->name }}</td>
                                            <td class="p-4 text-gray-700">{{ $supplier->email ?? '-' }}</td>
                                            <td class="p-4 text-gray-900 font-semibold">
                                                ${{ number_format((float)$supplier->pivot->cost_price,2) }}
                                            </td>
                                            <td class="p-4 text-gray-700">{{ $supplier->pivot->lead_time_days }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="rounded-2xl border border-amber-100 bg-amber-50 p-4 text-amber-700">
                            This product has no suppliers.
                        </div>
                    @endif
                </div>

            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <p class="font-semibold text-gray-900">Quick Info</p>
                <p class="text-xs text-gray-500">Fast overview</p>
            </div>

            <div class="p-6 space-y-4">
                <div class="bg-gray-50 border border-gray-100 p-4 rounded-2xl">
                    <p class="text-xs text-gray-500">Product ID</p>
                    <p class="font-semibold text-gray-900 mt-1">#{{ $product->id }}</p>
                </div>

                <div class="bg-gray-50 border border-gray-100 p-4 rounded-2xl">
                    <p class="text-xs text-gray-500">Suppliers Count</p>
                    <p class="font-semibold text-gray-900 mt-1">{{ $product->suppliers->count() }}</p>
                </div>

                <div class="bg-gray-50 border border-gray-100 p-4 rounded-2xl">
                    <p class="text-xs text-gray-500">Last Update</p>
                    <p class="font-semibold text-gray-900 mt-1">{{ $product->updated_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>

    </div>
</div>

<div id="deleteModal" class="fixed inset-0 hidden z-50">
    <div id="deleteBackdrop" class="absolute inset-0 bg-black/50"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
            <div class="p-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-700 text-lg">!</div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Move to Trash</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                Are you sure you want to move
                                <span id="deleteProductName" class="font-semibold text-gray-900"></span>
                                to Trash?
                            </p>
                            <p class="text-xs text-gray-400 mt-2">You can restore it later from Trash.</p>
                        </div>
                    </div>

                    <button type="button" id="closeDeleteModal"
                            class="w-9 h-9 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 flex items-center justify-center">
                        ✕
                    </button>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 flex items-center justify-end gap-3">
                <button id="cancelDelete" type="button"
                        class="px-5 py-2.5 rounded-xl bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100 font-semibold transition">
                    Cancel
                </button>

                <button id="confirmDelete" type="button"
                        class="px-5 py-2.5 rounded-xl bg-red-50 text-red-700 border border-red-200 hover:bg-red-100 font-semibold transition">
                    Move to Trash 🗑️
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.image-box{
    width:100%;
    height:320px;
    border-radius:16px;
    overflow:hidden;
    background:#f3f4f6;
    border:1px solid #e5e7eb;
    display:flex;
    align-items:center;
    justify-content:center;
}

.product-img{
    width:100%;
    height:100%;
    object-fit:contain;
}
</style>

<script>
let deleteForm = null;

document.querySelectorAll('.delete-btn').forEach(btn=>{
    btn.addEventListener('click',function(){
        deleteForm = this.closest('form');
        document.getElementById('deleteProductName').innerText = this.dataset.name || 'this product';
        document.getElementById('deleteModal').classList.remove('hidden');
    });
});

function closeDeleteModal(){
    document.getElementById('deleteModal').classList.add('hidden');
    deleteForm = null;
}

document.getElementById('cancelDelete').addEventListener('click', closeDeleteModal);
document.getElementById('closeDeleteModal').addEventListener('click', closeDeleteModal);
document.getElementById('deleteBackdrop').addEventListener('click', closeDeleteModal);

document.getElementById('confirmDelete').addEventListener('click',()=>{
    if(deleteForm){
        deleteForm.submit();
    }
});

document.addEventListener('keydown', function(e){
    if(e.key === 'Escape'){
        closeDeleteModal();
    }
});
</script>

@endsection
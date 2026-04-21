@extends('layouts.app')

@section('title', 'Add New Product')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Add New Product</h1>
            <p class="text-gray-500 mt-1">Create a new cosmetic product</p>
        </div>

        <a href="{{ route('products.index') }}"
           class="px-5 py-2.5 rounded-xl bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100 font-semibold transition">
            ← Back to Products
        </a>
    </div>

    {{-- General Error Summary --}}
    @if ($errors->any())
        <div class="rounded-2xl border border-red-100 bg-red-50 px-5 py-4">
            <p class="font-semibold text-red-700 mb-2">Please fix the following errors:</p>
            <ul class="list-disc list-inside space-y-1 text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Flash --}}
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 flex justify-between items-center shadow-sm">
            <span class="font-semibold text-emerald-700">{{ session('success') }}</span>
            <button class="w-9 h-9 rounded-xl bg-white/70 hover:bg-white border border-emerald-100 text-emerald-700"
                    onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-red-100 bg-red-50 px-5 py-4 flex justify-between items-center shadow-sm">
            <span class="font-semibold text-red-700">{{ session('error') }}</span>
            <button class="w-9 h-9 rounded-xl bg-white/70 hover:bg-white border border-red-100 text-red-700"
                    onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('products.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        @csrf

        {{-- Name --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Product Name</label>
            <input type="text"
                   name="name"
                   value="{{ old('name') }}"
                   class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-300 focus:ring-2 focus:ring-purple-200 outline-none">
            @error('name')
                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Category --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
            <select name="category_id"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-300 focus:ring-2 focus:ring-purple-200 outline-none">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Price --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Price</label>
            <input type="number"
                   name="price"
                   step="0.01"
                   value="{{ old('price') }}"
                   class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-300 focus:ring-2 focus:ring-purple-200 outline-none">
            @error('price')
                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Image Upload --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Product Image (Optional)</label>

            <div class="flex flex-col md:flex-row gap-4 md:items-center">
                <input type="file"
                       name="image"
                       id="imageInput"
                       accept="image/*"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-300 focus:ring-2 focus:ring-purple-200 outline-none">

                <div class="flex items-center gap-3">
                    <div class="w-20 h-20 rounded-xl border border-gray-200 overflow-hidden bg-gray-100 flex items-center justify-center">
                        <img id="imagePreview"
                             src="{{ asset('images/placeholder.png') }}"
                             alt="Preview"
                             class="w-full h-full object-cover">
                    </div>

                    <button type="button"
                            id="clearImageBtn"
                            class="px-4 py-2.5 rounded-xl bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100 font-semibold transition">
                        Clear
                    </button>
                </div>
            </div>

            <p class="text-xs text-gray-500 mt-2">Allowed: jpg, png, webp… Max size: 2MB</p>

            @error('image')
                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Suppliers --}}
        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="block text-sm font-semibold text-gray-700">Suppliers</label>
                <span class="text-xs text-gray-500">Select at least one supplier</span>
            </div>

            @error('suppliers')
                <p class="text-red-600 text-sm mb-2">{{ $message }}</p>
            @enderror

            <div class="overflow-x-auto border border-gray-200 rounded-2xl">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                            <th class="p-4 text-left">Select</th>
                            <th class="p-4 text-left">Supplier</th>
                            <th class="p-4 text-left">Cost Price</th>
                            <th class="p-4 text-left">Lead Time (days)</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($suppliers as $supplier)
                            @php
                                $oldSelected = old("suppliers.$supplier->id.selected");
                                $oldCost = old("suppliers.$supplier->id.cost_price");
                                $oldLead = old("suppliers.$supplier->id.lead_time_days");
                            @endphp

                            <tr class="hover:bg-purple-50/30 transition">
                                <td class="p-4">
                                    <input type="checkbox"
                                           class="supplier-check w-5 h-5 rounded border-gray-300 text-purple-600 focus:ring-purple-200"
                                           name="suppliers[{{ $supplier->id }}][selected]"
                                           {{ $oldSelected ? 'checked' : '' }}>
                                </td>

                                <td class="p-4">
                                    <div class="font-semibold text-gray-900">{{ $supplier->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $supplier->email ?? '' }}</div>
                                </td>

                                <td class="p-4">
                                    <input type="number"
                                           step="0.01"
                                           name="suppliers[{{ $supplier->id }}][cost_price]"
                                           value="{{ $oldCost }}"
                                           class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-300 focus:ring-2 focus:ring-purple-200 outline-none supplier-input"
                                           placeholder="e.g. 10.00">
                                    @error("suppliers.$supplier->id.cost_price")
                                        <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </td>

                                <td class="p-4">
                                    <input type="number"
                                           name="suppliers[{{ $supplier->id }}][lead_time_days]"
                                           value="{{ $oldLead }}"
                                           class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-300 focus:ring-2 focus:ring-purple-200 outline-none supplier-input"
                                           placeholder="e.g. 3">
                                    @error("suppliers.$supplier->id.lead_time_days")
                                        <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-gray-500">
                                    No suppliers found. Please add suppliers first.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <button type="submit"
                    class="w-full sm:w-auto px-6 py-3 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 text-white hover:opacity-95 font-semibold shadow-sm transition">
                Add Product
            </button>

            <a href="{{ route('products.index') }}"
               class="w-full sm:w-auto px-6 py-3 rounded-xl bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100 font-semibold transition text-center">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function toggleRow(row) {
        const check = row.querySelector('.supplier-check');
        const inputs = row.querySelectorAll('.supplier-input');
        inputs.forEach(inp => {
            inp.disabled = !check.checked;
            inp.classList.toggle('bg-gray-100', !check.checked);
        });
    }

    document.querySelectorAll('tbody tr').forEach(row => {
        if (row.querySelector('.supplier-check')) {
            toggleRow(row);
            row.querySelector('.supplier-check').addEventListener('change', () => toggleRow(row));
        }
    });

    const input = document.getElementById('imageInput');
    const preview = document.getElementById('imagePreview');
    const clearBtn = document.getElementById('clearImageBtn');
    const placeholder = "{{ asset('images/placeholder.png') }}";

    function setPreview(file) {
        if (!file) {
            preview.src = placeholder;
            return;
        }
        const url = URL.createObjectURL(file);
        preview.src = url;
        preview.onload = () => URL.revokeObjectURL(url);
    }

    input.addEventListener('change', function () {
        const file = this.files && this.files[0] ? this.files[0] : null;
        setPreview(file);
    });

    clearBtn.addEventListener('click', function () {
        input.value = '';
        setPreview(null);
    });
});
</script>
@endsection
@extends('layouts.app')

@section('title', 'Edit Supplier')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-start justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Supplier</h1>
            <p class="text-gray-500 mt-1">Update supplier information</p>
        </div>

        <a href="{{ route('suppliers.index') }}"
           class="px-4 py-2 rounded-xl bg-gray-100 text-gray-800 border border-gray-200 hover:bg-gray-200 font-semibold">
            Back
        </a>
    </div>

    <!-- Errors -->
    @if ($errors->any())
        <div class="rounded-2xl border border-red-100 bg-red-50 p-5">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-100 text-red-700 flex items-center justify-center font-bold">
                    !
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-red-700">Please fix the following errors:</p>
                    <ul class="mt-2 list-disc list-inside text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <p class="font-semibold text-gray-900">Supplier Details</p>
            <p class="text-xs text-gray-500">Fields marked are required</p>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('suppliers.update', $supplier) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Supplier Name</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name', $supplier->name) }}"
                           required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-300 focus:ring-2 focus:ring-purple-200 outline-none">
                    @error('name')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email', $supplier->email) }}"
                           placeholder="example@company.com"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-300 focus:ring-2 focus:ring-purple-200 outline-none">
                    @error('email')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="pt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                    <div class="text-xs text-gray-500">
                        <span class="font-semibold text-gray-700">ID:</span> #{{ $supplier->id }}
                    </div>

                    <div class="flex items-center justify-end gap-2">

                        <!-- Cancel -->
                        <a href="{{ route('suppliers.index') }}"
                           class="px-5 py-2.5 rounded-xl bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100 font-semibold transition">
                            Cancel
                        </a>

                        <!-- Update -->
                        <button type="submit"
                                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow hover:from-purple-600 hover:to-pink-600 transition font-semibold">
                            Update Supplier
                        </button>

                    </div>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection
@extends('layouts.app')

@section('title', 'Add New Supplier')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-start justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Add New Supplier</h1>
            <p class="text-gray-500 mt-1">Create a new supplier</p>
        </div>

        <a href="{{ route('suppliers.index') }}"
           class="px-4 py-2 rounded-xl bg-gray-100 text-gray-800 border border-gray-200 hover:bg-gray-200 font-semibold">
            Back
        </a>
    </div>

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

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <p class="font-semibold text-gray-900">Supplier Details</p>
            <p class="text-xs text-gray-500">Fill in the supplier information</p>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('suppliers.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Supplier Name</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🏷️</span>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               required
                               placeholder="Enter supplier name"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-300 focus:ring-2 focus:ring-purple-200 outline-none">
                    </div>
                    @error('name')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">✉️</span>
                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="example@email.com"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-300 focus:ring-2 focus:ring-purple-200 outline-none">
                    </div>
                    @error('email')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-2">
                    <a href="{{ route('suppliers.index') }}"
                       class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-800 border border-gray-200 hover:bg-gray-200 font-semibold">
                        Cancel
                    </a>

                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-pink-500 to-purple-600 text-white shadow hover:opacity-95 transition font-semibold">
                        Save Supplier
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection
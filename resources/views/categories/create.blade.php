@extends('layouts.app')

@section('title', 'Add Category')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h1 class="text-3xl font-bold text-gray-900">Add New Category</h1>
        <p class="text-gray-500 mt-1">Create a new category for your products</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <p class="font-semibold text-gray-900">Category Details</p>
        </div>

        <div class="p-6">

            @if ($errors->any())
                <div class="rounded-2xl border border-red-100 bg-red-50 p-5 mb-6">
                    <p class="font-semibold text-red-700 mb-2">Please fix the following errors:</p>
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('categories.store') }}" class="space-y-6">
                @csrf

                <!-- Category Name -->
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">
                        Category Name
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           placeholder="Enter category name"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50
                                  focus:bg-white focus:border-purple-300 focus:ring-2 focus:ring-purple-200 outline-none">

                    @error('name')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3 pt-2">

                    <a href="{{ route('categories.index') }}"
                       class="px-5 py-2.5 rounded-xl bg-purple-50 text-purple-700
                              border border-purple-200 hover:bg-purple-100 font-semibold transition">
                        Cancel
                    </a>

                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-gradient-to-r
                                   from-purple-500 to-pink-500 text-white
                                   hover:from-purple-600 hover:to-pink-600
                                   shadow font-semibold transition">
                        Save Category
                    </button>

                </div>
            </form>

        </div>
    </div>

</div>
@endsection
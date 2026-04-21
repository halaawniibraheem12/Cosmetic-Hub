@extends('layouts.app')

@section('title', 'Edit Category')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Category</h1>
        <p class="text-gray-500 mt-1">Update category name</p>
    </div>

    <!-- Errors -->
    @if ($errors->any())
        <div class="rounded-2xl border border-red-100 bg-red-50 p-5">
            <p class="font-semibold text-red-700 mb-2">Please fix the following errors:</p>
            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Edit Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <p class="font-semibold text-gray-900">Category Details</p>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('categories.update', $category) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">
                        Category Name
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ old('name', $category->name) }}"
                           required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50
                                  focus:bg-white focus:border-purple-300 focus:ring-2 focus:ring-purple-200 outline-none">

                    @error('name')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3 pt-2">

                    <!-- Cancel -->
                    <a href="{{ route('categories.index') }}"
                       class="px-5 py-2.5 rounded-xl bg-purple-50 text-purple-700
                              border border-purple-200 hover:bg-purple-100 font-semibold transition">
                        Cancel
                    </a>

                    <!-- Update -->
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-gradient-to-r
                                   from-purple-500 to-pink-500 text-white
                                   hover:from-purple-600 hover:to-pink-600
                                   shadow font-semibold transition">
                        Update Category
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- Move to Trash -->
    <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-6 flex justify-between items-center">
        <div>
            <p class="font-semibold text-gray-900">Move Category to Trash</p>
            <p class="text-sm text-gray-500">
                The category will be moved to trash and can be restored later.
            </p>
        </div>

        <button type="button"
                id="openDeleteModalBtn"
                class="px-5 py-2.5 rounded-xl bg-red-50 text-red-600
                       border border-red-200 hover:bg-red-100 font-semibold transition">
            Move to Trash
        </button>
    </div>

</div>

<!-- Trash Confirmation Modal -->
<div id="deleteModal"
     class="fixed inset-0 z-50 hidden"
     role="dialog"
     aria-modal="true">

    <div id="deleteModalBackdrop" class="fixed inset-0 bg-black/50"></div>

    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden">

            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-900">
                    Move Category to Trash
                </h3>
                <p class="mt-2 text-gray-600">
                    Are you sure you want to move this category to trash?
                </p>
                <p class="mt-1 text-sm text-gray-500">
                    You can restore it later from the Trash page.
                </p>
            </div>

            <div class="px-6 pb-6 flex justify-end gap-3">

                <button type="button"
                        id="cancelDeleteBtn"
                        class="px-5 py-2.5 rounded-xl bg-gray-100
                               text-gray-800 border border-gray-200
                               hover:bg-gray-200 font-semibold">
                    Cancel
                </button>

                <form method="POST" action="{{ route('categories.destroy', $category) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-red-600
                                   text-white hover:bg-red-700
                                   font-semibold transition">
                        Move to Trash
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('deleteModal');
    const openBtn = document.getElementById('openDeleteModalBtn');
    const cancelBtn = document.getElementById('cancelDeleteBtn');
    const backdrop = document.getElementById('deleteModalBackdrop');

    function openModal() {
        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }

    openBtn.addEventListener('click', openModal);
    cancelBtn.addEventListener('click', closeModal);
    backdrop.addEventListener('click', closeModal);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
});
</script>
@endsection
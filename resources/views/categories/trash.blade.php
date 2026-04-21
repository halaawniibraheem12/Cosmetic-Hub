@extends('layouts.app')

@section('title', 'Trash - Categories')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Trash - Categories</h1>
            <p class="text-gray-500 mt-1">Manage deleted categories</p>
        </div>

        <a href="{{ route('categories.index') }}"
           class="px-5 py-2.5 rounded-xl bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100 font-semibold transition">
            Back to Categories
        </a>
    </div>

    <!-- Flash -->
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5">
            <p class="font-semibold text-emerald-700">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-red-100 bg-red-50 p-5">
            <p class="font-semibold text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <p class="font-semibold text-gray-900">
                Deleted Categories ({{ $trashedCategories->total() }})
            </p>
            <p class="text-xs text-gray-500">
                Restore or permanently delete categories
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full table-fixed">
                <thead class="bg-white text-xs uppercase text-gray-500 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 w-16 text-left">#</th>
                        <th class="px-6 py-4 text-left">Name</th>
                        <th class="px-6 py-4 w-56 text-left">Owner</th>
                        <th class="px-6 py-4 w-56 text-left">Deleted At</th>
                        <th class="px-6 py-4 w-72 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($trashedCategories as $category)
                        <tr class="hover:bg-purple-50/40">
                            <td class="px-6 py-4 text-gray-700">
                                {{ ($trashedCategories->firstItem() ?? 0) + $loop->index }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 text-white flex items-center justify-center shrink-0">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-900 truncate">{{ $category->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">ID: #{{ $category->id }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-gray-700 truncate">
                                {{ $category->user->name ?? 'Unknown' }}
                            </td>

                            <td class="px-6 py-4 text-gray-700">
                                {{ $category->deleted_at?->format('Y-m-d H:i') ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">

                                    @can('restore', $category)
                                        <form method="POST"
                                              action="{{ route('categories.restore', $category->id) }}"
                                              class="restore-form m-0">
                                            @csrf
                                            <button type="button"
                                                    class="restore-btn px-4 py-2 rounded-xl font-semibold border transition flex items-center gap-2
                                                           bg-purple-50 text-purple-700 border-purple-200 hover:bg-purple-100"
                                                    data-name="{{ $category->name }}">
                                                <i class="fas fa-undo"></i>
                                                Restore
                                            </button>
                                        </form>
                                    @endcan

                                    @can('forceDelete', $category)
                                        <form method="POST"
                                              action="{{ route('categories.forceDelete', $category->id) }}"
                                              class="force-delete-form m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="force-delete-btn px-4 py-2 rounded-xl font-semibold border transition flex items-center gap-2
                                                           bg-pink-50 text-pink-700 border-pink-200 hover:bg-pink-100"
                                                    data-name="{{ $category->name }}">
                                                <i class="fas fa-trash-alt"></i>
                                                Delete Forever
                                            </button>
                                        </form>
                                    @endcan

                                    @if(!auth()->user()->can('restore', $category) && !auth()->user()->can('forceDelete', $category))
                                        <span class="text-xs text-gray-400">No actions</span>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-400 mb-4">
                                        <i class="fas fa-trash text-2xl"></i>
                                    </div>
                                    <p class="text-lg font-semibold text-gray-700">Trash is empty</p>
                                    <p class="text-sm text-gray-400 mt-1">No deleted categories found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-white">
            {{ $trashedCategories->links() }}
        </div>
    </div>

</div>

<!-- Restore Modal -->
<div id="restoreModal" class="fixed inset-0 hidden z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md border border-gray-100 shadow-xl">
        <div class="flex items-center gap-3 text-purple-700 mb-4">
            <div class="w-10 h-10 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center">
                <i class="fas fa-undo"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Confirm Restore</h3>
        </div>

        <p class="text-sm text-gray-600">
            Are you sure you want to restore the category
            <span id="restoreName" class="font-semibold text-gray-900"></span>?
        </p>

        <p class="text-xs text-gray-400 mt-1">This will move it back to active categories.</p>

        <div class="mt-6 flex justify-end gap-3">
            <button id="restoreCancel" type="button"
                    class="px-5 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 border border-gray-200 font-semibold transition">
                Cancel
            </button>
            <button id="restoreConfirm" type="button"
                    class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 text-white hover:opacity-95 shadow font-semibold transition">
                Restore
            </button>
        </div>
    </div>
</div>

<!-- Force Delete Modal -->
<div id="forceModal" class="fixed inset-0 hidden z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md border border-gray-100 shadow-xl">
        <div class="flex items-center gap-3 text-pink-700 mb-4">
            <div class="w-10 h-10 rounded-xl bg-pink-50 border border-pink-100 flex items-center justify-center">
                <i class="fas fa-trash-alt"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Confirm Permanent Deletion</h3>
        </div>

        <p class="text-sm text-gray-600">
            Are you sure you want to permanently delete the category
            <span id="forceName" class="font-semibold text-gray-900"></span>?
        </p>

        <p class="text-xs text-pink-700 mt-1 font-semibold">This action cannot be undone.</p>

        <div class="mt-6 flex justify-end gap-3">
            <button id="forceCancel" type="button"
                    class="px-5 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 border border-gray-200 font-semibold transition">
                Cancel
            </button>
            <button id="forceConfirm" type="button"
                    class="px-5 py-2.5 rounded-xl bg-pink-600 hover:bg-pink-700 text-white shadow font-semibold transition">
                Delete Permanently
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let restoreForm = null;
    let forceForm = null;

    document.querySelectorAll('.restore-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            restoreForm = this.closest('form');
            document.getElementById('restoreName').textContent = this.dataset.name || '';
            document.getElementById('restoreModal').classList.remove('hidden');
        });
    });

    document.getElementById('restoreCancel').addEventListener('click', function() {
        document.getElementById('restoreModal').classList.add('hidden');
        restoreForm = null;
    });

    document.getElementById('restoreConfirm').addEventListener('click', function() {
        if (restoreForm) restoreForm.submit();
    });

    document.querySelectorAll('.force-delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            forceForm = this.closest('form');
            document.getElementById('forceName').textContent = this.dataset.name || '';
            document.getElementById('forceModal').classList.remove('hidden');
        });
    });

    document.getElementById('forceCancel').addEventListener('click', function() {
        document.getElementById('forceModal').classList.add('hidden');
        forceForm = null;
    });

    document.getElementById('forceConfirm').addEventListener('click', function() {
        if (forceForm) forceForm.submit();
    });

    document.getElementById('restoreModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
            restoreForm = null;
        }
    });

    document.getElementById('forceModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
            forceForm = null;
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.getElementById('restoreModal').classList.add('hidden');
            document.getElementById('forceModal').classList.add('hidden');
            restoreForm = null;
            forceForm = null;
        }
    });
});
</script>
@endsection
@extends('layouts.app')

@section('title', 'Suppliers')

@section('content')
<div class="space-y-8">

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Suppliers</h1>
            <p class="text-gray-500 mt-1">Manage your suppliers</p>
        </div>

        <div class="flex items-center gap-2">
            @can('create', App\Models\Supplier::class)
                <a href="{{ route('suppliers.create') }}"
                   class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-pink-500 to-purple-600 text-white shadow hover:opacity-95 transition font-semibold flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Supplier</span>
                </a>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white px-5 py-3 rounded-2xl flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <span class="text-lg">✅</span>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
            <button type="button"
                    class="w-9 h-9 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center"
                    onclick="this.parentElement.remove()">
                ✕
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white px-5 py-3 rounded-2xl flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <span class="text-lg">⚠️</span>
                <span class="font-semibold">{{ session('error') }}</span>
            </div>
            <button type="button"
                    class="w-9 h-9 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center"
                    onclick="this.parentElement.remove()">
                ✕
            </button>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-700 font-bold">
                    {{ $suppliers->total() }}
                </div>
                <div>
                    <p class="font-semibold text-gray-900">All Suppliers</p>
                    <p class="text-xs text-gray-500">
                        Showing {{ $suppliers->count() }} of {{ $suppliers->total() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <th class="px-6 py-4 w-16">#</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4 w-40">Products</th>
                        <th class="px-6 py-4 text-right w-56">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($suppliers as $supplier)
                        <tr class="hover:bg-purple-50/30 transition">
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $suppliers->firstItem() + $loop->index }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-pink-100 to-purple-100 flex items-center justify-center text-purple-700 font-bold">
                                        {{ strtoupper(mb_substr($supplier->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-900 truncate">{{ $supplier->name }}</p>
                                        <p class="text-xs text-gray-500">ID #{{ $supplier->id }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $supplier->email ?? '-' }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-100">
                                    {{ $supplier->products_count ?? 0 }} Products
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    @can('update', $supplier)
                                        <a href="{{ route('suppliers.edit', $supplier) }}"
                                           class="px-3 py-2 rounded-lg bg-purple-50 text-purple-700 hover:bg-purple-100 text-sm font-semibold">
                                            Edit
                                        </a>
                                    @endcan

                                    @can('delete', $supplier)
                                        <button type="button"
                                                data-action="{{ route('suppliers.destroy', $supplier) }}"
                                                data-name="{{ $supplier->name }}"
                                                class="open-delete-modal px-3 py-2 rounded-lg bg-red-50 text-red-700 hover:bg-red-100 text-sm font-semibold">
                                            Delete
                                        </button>
                                    @endcan

                                    @if(!auth()->user()->can('update', $supplier) && !auth()->user()->can('delete', $supplier))
                                        <span class="px-3 py-2 rounded-lg bg-gray-100 text-gray-500 text-sm font-semibold border border-gray-200">
                                            View Only
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="mx-auto max-w-md">
                                    <div class="w-14 h-14 mx-auto rounded-2xl bg-gray-100 flex items-center justify-center text-gray-500 text-xl">
                                        🧾
                                    </div>
                                    <p class="mt-4 font-semibold text-gray-900">No suppliers found</p>
                                    <p class="text-sm text-gray-500">Create your first supplier to get started.</p>

                                    @can('create', App\Models\Supplier::class)
                                        <div class="mt-5">
                                            <a href="{{ route('suppliers.create') }}"
                                               class="inline-flex px-5 py-2.5 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-xl hover:opacity-95 shadow-sm font-semibold items-center gap-2">
                                                <i class="fas fa-plus-circle"></i>
                                                Add Supplier
                                            </a>
                                        </div>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">
            {{ $suppliers->links() }}
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
                            <h3 class="text-lg font-bold text-gray-900">Confirm Delete</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                Are you sure you want to delete
                                <span class="font-semibold text-gray-900" id="deleteSupplierName"></span>?
                            </p>
                            <p class="text-xs text-gray-400 mt-2">This action cannot be undone.</p>
                        </div>
                    </div>

                    <button type="button" id="closeDeleteModal"
                            class="w-9 h-9 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 flex items-center justify-center">
                        ✕
                    </button>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 flex items-center justify-end gap-3">
                <button id="cancelDelete"
                        type="button"
                        class="px-5 py-2 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 font-semibold">
                    Cancel
                </button>

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="px-5 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 font-semibold">
                        Yes, Delete
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('deleteModal');
    const backdrop = document.getElementById('deleteBackdrop');
    const cancelBtn = document.getElementById('cancelDelete');
    const closeBtn = document.getElementById('closeDeleteModal');
    const form = document.getElementById('deleteForm');
    const nameEl = document.getElementById('deleteSupplierName');

    function openModal(actionUrl, supplierName) {
        form.action = actionUrl;
        nameEl.textContent = supplierName || 'this supplier';
        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
        form.action = '';
        nameEl.textContent = '';
    }

    document.querySelectorAll('.open-delete-modal').forEach(btn => {
        btn.addEventListener('click', function () {
            openModal(this.dataset.action, this.dataset.name);
        });
    });

    cancelBtn.addEventListener('click', closeModal);
    closeBtn.addEventListener('click', closeModal);
    backdrop.addEventListener('click', closeModal);

    document.addEventListener('keydown', function(e){
        if(e.key === 'Escape' && !modal.classList.contains('hidden')){
            closeModal();
        }
    });
});
</script>
@endsection
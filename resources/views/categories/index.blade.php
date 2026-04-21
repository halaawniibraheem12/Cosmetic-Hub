@extends('layouts.app')

@section('title', 'Categories - Cosmetics Management')

@section('content')
<div class="space-y-8">

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <div>
                <h1 class="text-3xl font-bold text-gray-900">Categories</h1>
                <p class="text-gray-500 mt-1">Organize your cosmetic products into categories</p>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
                    <input type="text"
                           id="categorySearch"
                           placeholder="Search categories..."
                           class="w-full sm:w-72 pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-300 focus:ring-2 focus:ring-purple-200 outline-none">
                </div>

                <a href="{{ route('categories.trash') }}"
                   class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-800 border border-gray-200 hover:bg-gray-200 font-semibold flex items-center gap-2">
                    <i class="fas fa-trash"></i>
                    Trash
                </a>

                @can('create', App\Models\Category::class)
                    <a href="{{ route('categories.create') }}"
                       class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-pink-500 to-purple-600 text-white shadow hover:opacity-95 transition font-semibold flex items-center gap-2">
                        <i class="fas fa-plus-circle"></i>
                        Add Category
                    </a>
                @endcan
            </div>

        </div>
    </div>

    @if(session('success'))
        <div class="flash-message">
            <div class="bg-gradient-to-r from-pink-500 to-purple-600 text-white px-5 py-3 rounded-2xl shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-xl bg-white/15 flex items-center justify-center">✓</span>
                    <div>
                        <p class="font-semibold">Success</p>
                        <p class="text-sm text-white/90">{{ session('success') }}</p>
                    </div>
                </div>
                <button type="button" onclick="this.closest('.flash-message').remove()"
                        class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/15 flex items-center justify-center">
                    ✕
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="flash-message">
            <div class="bg-gradient-to-r from-red-500 to-pink-600 text-white px-5 py-3 rounded-2xl shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-xl bg-white/15 flex items-center justify-center">!</span>
                    <div>
                        <p class="font-semibold">Error</p>
                        <p class="text-sm text-white/90">{{ session('error') }}</p>
                    </div>
                </div>
                <button type="button" onclick="this.closest('.flash-message').remove()"
                        class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/15 flex items-center justify-center">
                    ✕
                </button>
            </div>
        </div>
    @endif

    @if($categories->count() > 0)

        <div id="categoriesGrid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            @foreach($categories as $category)
                <div class="category-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group"
                     data-name="{{ strtolower($category->name) }}">

                    <div class="p-6 flex items-start gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-r from-pink-500 to-purple-600 flex items-center justify-center text-white shadow-sm">
                            <i class="fas fa-tag text-lg"></i>
                        </div>

                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-bold text-gray-900 truncate">{{ $category->name }}</h3>

                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-100">
                                    <i class="fas fa-box mr-2"></i>
                                    {{ $category->products_count ?? 0 }} Products
                                </span>

                                @if(!empty($category->user?->name))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-pink-50 text-pink-700 border border-pink-100">
                                        <i class="fas fa-user mr-2"></i>
                                        {{ $category->user->name }}
                                    </span>
                                @endif
                            </div>

                            @if(!empty($category->description))
                                <p class="mt-3 text-sm text-gray-500">
                                    {{ \Illuminate\Support\Str::limit($category->description, 120) }}
                                </p>
                            @endif
                        </div>

                        @if(auth()->check() && (auth()->user()->can('update', $category) || auth()->user()->can('delete', $category)))
                            <div class="relative">
                                <button type="button"
                                        class="w-9 h-9 rounded-xl bg-gray-50 hover:bg-gray-100 border border-gray-200 text-gray-600 flex items-center justify-center">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>

                                <div class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-10 hidden group-hover:block">
                                    @can('update', $category)
                                        <a href="{{ route('categories.edit', $category) }}"
                                           class="flex items-center gap-3 px-4 py-3 hover:bg-purple-50 transition-colors text-gray-800">
                                            <i class="fas fa-edit text-purple-600"></i>
                                            <span class="font-semibold">Edit</span>
                                        </a>
                                    @endcan

                                    @can('delete', $category)
                                        <button type="button"
                                                class="w-full text-left flex items-center gap-3 px-4 py-3 hover:bg-pink-50 transition-colors text-pink-700 open-trash-modal"
                                                data-action="{{ route('categories.destroy', $category) }}"
                                                data-name="{{ $category->name }}">
                                            <i class="fas fa-trash"></i>
                                            <span class="font-semibold">Move to Trash</span>
                                        </button>
                                    @endcan

                                    <a href="{{ route('products.index', ['category' => $category->name]) }}"
                                       class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors text-gray-800">
                                        <i class="fas fa-eye text-gray-600"></i>
                                        <span class="font-semibold">View Products</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                        <div class="text-xs text-gray-500">
                            <i class="far fa-clock mr-2"></i>
                            Created {{ $category->created_at?->diffForHumans() ?? '' }}
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('products.index', ['category' => $category->name]) }}"
                               class="px-3 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-100 text-gray-800 text-xs font-semibold">
                                View
                            </a>

                            @can('update', $category)
                                <a href="{{ route('categories.edit', $category) }}"
                                   class="px-3 py-2 rounded-xl bg-purple-50 border border-purple-100 hover:bg-purple-100 text-purple-700 text-xs font-semibold">
                                    Edit
                                </a>
                            @endcan

                            @can('delete', $category)
                                <button type="button"
                                        class="px-3 py-2 rounded-xl bg-pink-50 border border-pink-100 hover:bg-pink-100 text-pink-700 text-xs font-semibold open-trash-modal"
                                        data-action="{{ route('categories.destroy', $category) }}"
                                        data-name="{{ $category->name }}">
                                    Trash
                                </button>
                            @endcan
                        </div>
                    </div>

                </div>
            @endforeach

        </div>

    @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 mx-auto rounded-3xl bg-gradient-to-r from-pink-50 to-purple-50 border border-gray-100 flex items-center justify-center text-purple-700 text-2xl">
                <i class="fas fa-tags"></i>
            </div>
            <h3 class="mt-5 text-2xl font-bold text-gray-900">No Categories Found</h3>
            <p class="mt-2 text-gray-500 max-w-md mx-auto">
                Categories help organize your products. Create your first category to get started.
            </p>

            <div class="mt-6 flex items-center justify-center gap-2">
                <a href="{{ route('categories.trash') }}"
                   class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-800 border border-gray-200 hover:bg-gray-200 font-semibold">
                    Open Trash
                </a>

                @can('create', App\Models\Category::class)
                    <a href="{{ route('categories.create') }}"
                       class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-pink-500 to-purple-600 text-white shadow hover:opacity-95 transition font-semibold">
                        + Add Category
                    </a>
                @endcan
            </div>
        </div>
    @endif

    @if($categories->hasPages())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <p class="text-gray-600">
                    Showing <span class="font-semibold">{{ $categories->firstItem() }}</span> to
                    <span class="font-semibold">{{ $categories->lastItem() }}</span> of
                    <span class="font-semibold">{{ $categories->total() }}</span> categories
                </p>
                <div>{{ $categories->links() }}</div>
            </div>
        </div>
    @endif

</div>

<div id="trashModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div id="trashModalBackdrop" class="fixed inset-0 bg-black/50"></div>

    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
            <div class="p-6">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-pink-50 text-pink-700 flex items-center justify-center border border-pink-100 font-bold">
                        !
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900">Move to Trash</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Are you sure you want to move
                            <span class="font-semibold text-gray-900" id="trashCategoryName"></span>
                            to Trash?
                        </p>
                        <p class="mt-2 text-xs text-gray-400">You can restore it later from the Trash page.</p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <button type="button"
                        id="cancelTrashBtn"
                        class="px-5 py-2.5 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-900 font-semibold">
                    Cancel
                </button>

                <form id="trashCategoryForm" method="POST" action="">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-pink-500 to-purple-600 text-white hover:opacity-95 transition font-semibold">
                        Move to Trash
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .group:hover .group-hover\:block { display: block; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('categorySearch');
    const cards = document.querySelectorAll('.category-card');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const term = this.value.toLowerCase().trim();
            cards.forEach(card => {
                const name = (card.getAttribute('data-name') || '');
                card.style.display = name.includes(term) ? 'block' : 'none';
            });
        });
    }

    document.querySelectorAll('.flash-message').forEach(msg => {
        setTimeout(() => {
            msg.style.opacity = '0';
            msg.style.transform = 'translateY(-10px)';
            msg.style.transition = 'all .5s ease';
            setTimeout(() => msg.remove(), 500);
        }, 4500);
    });

    const modal = document.getElementById('trashModal');
    const backdrop = document.getElementById('trashModalBackdrop');
    const cancelBtn = document.getElementById('cancelTrashBtn');
    const form = document.getElementById('trashCategoryForm');
    const nameHolder = document.getElementById('trashCategoryName');

    function openTrashModal(actionUrl, name) {
        form.action = actionUrl;
        nameHolder.textContent = name || 'this category';
        modal.classList.remove('hidden');
    }

    function closeTrashModal() {
        modal.classList.add('hidden');
        form.action = '';
        nameHolder.textContent = '';
    }

    document.querySelectorAll('.open-trash-modal').forEach(btn => {
        btn.addEventListener('click', () => openTrashModal(btn.dataset.action, btn.dataset.name));
    });

    cancelBtn.addEventListener('click', closeTrashModal);
    backdrop.addEventListener('click', closeTrashModal);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeTrashModal();
    });
});
</script>
@endsection
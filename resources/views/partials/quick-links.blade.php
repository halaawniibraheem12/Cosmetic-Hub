{{-- Global Quick Links --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">

    <h2 class="text-lg font-bold text-gray-900 mb-4">
        Quick Links
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Products -->
        <a href="{{ route('products.index') }}"
           class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition border flex items-center gap-4">

            <div class="w-12 h-12 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                <i class="fas fa-box"></i>
            </div>

            <div>
                <p class="font-semibold text-gray-900">Products</p>
                <p class="text-sm text-gray-500">Manage products</p>
            </div>
        </a>

        <!-- Categories -->
        <a href="{{ route('categories.index') }}"
           class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition border flex items-center gap-4">

            <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                <i class="fas fa-tags"></i>
            </div>

            <div>
                <p class="font-semibold text-gray-900">Categories</p>
                <p class="text-sm text-gray-500">Manage categories</p>
            </div>
        </a>

        <!-- Suppliers -->
        <a href="{{ route('suppliers.index') }}"
           class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition border flex items-center gap-4">

            <div class="w-12 h-12 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                <i class="fas fa-truck"></i>
            </div>

            <div>
                <p class="font-semibold text-gray-900">Suppliers</p>
                <p class="text-sm text-gray-500">Manage suppliers</p>
            </div>
        </a>

    </div>

</div>
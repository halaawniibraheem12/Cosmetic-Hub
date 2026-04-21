<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authenticated & Verified Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    /*
    | Dashboard
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    | Profile
    */
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Products Trash (Soft Delete)
    |--------------------------------------------------------------------------
    */
    Route::get('/products/trash', [ProductController::class, 'trash'])
        ->name('products.trash');

    Route::post('/products/{id}/restore', [ProductController::class, 'restore'])
        ->name('products.restore');

    Route::delete('/products/{id}/force-delete', [ProductController::class, 'forceDelete'])
        ->name('products.forceDelete');

    /*
    |--------------------------------------------------------------------------
    | Products CRUD
    |--------------------------------------------------------------------------
    */
    Route::get('/products', [ProductController::class, 'index'])
        ->name('products.index');

    Route::get('/products/create', [ProductController::class, 'create'])
        ->name('products.create');

    Route::post('/products', [ProductController::class, 'store'])
        ->name('products.store');

    Route::get('/products/{product}', [ProductController::class, 'show'])
        ->name('products.show');

    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
        ->name('products.edit');

    Route::put('/products/{product}', [ProductController::class, 'update'])
        ->name('products.update');

    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
        ->name('products.destroy');

    /*
    |--------------------------------------------------------------------------
    | Categories Trash (Soft Delete)
    |--------------------------------------------------------------------------
    */
    Route::get('/categories/trash', [CategoryController::class, 'trash'])
        ->name('categories.trash');

    Route::post('/categories/{id}/restore', [CategoryController::class, 'restore'])
        ->name('categories.restore');

    Route::delete('/categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])
        ->name('categories.forceDelete');

    /*
    |--------------------------------------------------------------------------
    | Categories CRUD
    |--------------------------------------------------------------------------
    */
    Route::resource('categories', CategoryController::class);

    /*
    |--------------------------------------------------------------------------
    | Suppliers CRUD
    |--------------------------------------------------------------------------
    */
    Route::resource('suppliers', SupplierController::class);

    /*
    |--------------------------------------------------------------------------
    | Settings (Optional)
    |--------------------------------------------------------------------------
    */
    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze / Jetstream)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
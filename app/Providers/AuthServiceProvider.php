<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

use App\Policies\ProductPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\SupplierPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Product::class  => ProductPolicy::class,
        Category::class => CategoryPolicy::class,
        Supplier::class => SupplierPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
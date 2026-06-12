<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Observers\ProductObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Vite::prefetch(concurrency: 3);

        Product::observe(ProductObserver::class);

        if (Schema::hasTable('cache') && Schema::hasTable('product_categories')) {
            $categories = cache()->remember('nav_categories', 3600, function () {
                return ProductCategory::query()
                    ->select(['id', 'name', 'slug', 'icon', 'description'])
                    ->orderBy('position')
                    ->orderBy('name')
                    ->get();
            });

            View::share('navCategories', $categories);
        } else {
            View::share('navCategories', collect());
        }
    }
}

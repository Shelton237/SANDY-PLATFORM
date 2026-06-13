<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductReviewController;
use App\Models\Product;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PushSubscriptionController;
use Inertia\Inertia;

// Accueil
Route::get('/', HomeController::class)->name('home');

// Catalogue produits
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/category/{category}', [ProductController::class, 'byCategory'])->name('products.category');
Route::get('/products/suggestions', [ProductController::class, 'suggestions'])->name('products.suggestions');
Route::get('/products/{product:slug}/reviews/new', [ProductReviewController::class, 'create'])->name('products.reviews.create');
Route::post('/products/{product:slug}/reviews', [ProductReviewController::class, 'store'])->name('products.reviews.store');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Panier
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add/{product:id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{cartItem:id}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{cartItem:id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Checkout & commandes
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/checkout/process', [OrderController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{order}', [OrderController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel', [OrderController::class, 'cancel'])->name('checkout.cancel');

// Push notifications
Route::post('/push/subscribe', [PushSubscriptionController::class, 'subscribe'])->name('push.subscribe');
Route::post('/push/unsubscribe', [PushSubscriptionController::class, 'unsubscribe'])->name('push.unsubscribe');

// Sitemap
Route::get('/sitemap.xml', function () {
    $baseUrl = config('seo.base_url', config('app.url'));
    $staticUrls = [
        ['loc' => $baseUrl . '/', 'priority' => '1.0'],
        ['loc' => $baseUrl . '/products', 'priority' => '0.9'],
    ];

    $products = Product::published()
        ->select('slug', 'updated_at', 'created_at')
        ->orderByDesc('updated_at')
        ->get()
        ->map(fn ($product) => [
            'loc' => $baseUrl . route('products.show', $product->slug, false),
            'lastmod' => optional($product->updated_at ?? $product->created_at)->toDateString(),
            'priority' => '0.8',
        ]);

    $urls = array_merge($staticUrls, $products->toArray());

    return response()
        ->view('sitemap', ['urls' => $urls])
        ->header('Content-Type', 'application/xml');
})->name('sitemap');

// Compte utilisateur (authentifié)
Route::middleware(['auth'])->group(function () {
    Route::get('/account/orders', [OrderController::class, 'index'])->name('account.orders');
    Route::get('/account/orders/{order}', [OrderController::class, 'show'])->name('account.orders.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');
});

// Administration
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('products/images/upload', [AdminProductController::class, 'uploadImage'])->name('products.images.upload');
    Route::resource('products', AdminProductController::class);
    Route::resource('product-categories', ProductCategoryController::class)->except(['create', 'show', 'edit']);

    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update', 'store']);
});

// Auth
require __DIR__.'/auth.php';

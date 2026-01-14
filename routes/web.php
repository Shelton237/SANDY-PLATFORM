<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Models\Product;
use App\Models\BlogPost;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\DeliveryController as AdminDeliveryController;
use App\Http\Controllers\Admin\SupplyController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\ProductionController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Admin\HomeContentController;
use App\Http\Controllers\Admin\SupplyOrderController as AdminSupplyOrderController;
use App\Http\Controllers\Admin\InventoryBatchController as AdminInventoryBatchController;
use App\Http\Controllers\Admin\ProductionBatchController as AdminProductionBatchController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use Inertia\Inertia;
use App\Http\Controllers\HomeController;

// Route d'accueil
Route::get('/', HomeController::class)->name('home');

// Routes des produits
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/category/{category}', [ProductController::class, 'byCategory'])->name('products.category');
Route::get('/products/suggestions', [ProductController::class, 'suggestions'])->name('products.suggestions');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Routes du panier
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{cartItem}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Routes de commande
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/checkout/process', [OrderController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{order}', [OrderController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel', [OrderController::class, 'cancel'])->name('checkout.cancel');

// Routes des pages statiques
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/values', [PageController::class, 'values'])->name('values');
Route::get('/quality', [PageController::class, 'quality'])->name('quality');
Route::get('/delivery', [PageController::class, 'delivery'])->name('delivery');
Route::get('/sitemap.xml', function () {
    $baseUrl = config('seo.base_url', config('app.url'));
    $staticUrls = [
        ['loc' => $baseUrl . '/', 'priority' => '1.0'],
        ['loc' => $baseUrl . '/about', 'priority' => '0.8'],
        ['loc' => $baseUrl . '/values', 'priority' => '0.8'],
        ['loc' => $baseUrl . '/quality', 'priority' => '0.8'],
        ['loc' => $baseUrl . '/delivery', 'priority' => '0.8'],
        ['loc' => $baseUrl . '/products', 'priority' => '0.9'],
        ['loc' => $baseUrl . '/blog', 'priority' => '0.7'],
        ['loc' => $baseUrl . '/contact', 'priority' => '0.6'],
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

    $posts = BlogPost::published()
        ->select('slug', 'updated_at', 'published_at')
        ->orderByDesc('published_at')
        ->get()
        ->map(fn ($post) => [
            'loc' => $baseUrl . route('blog.show', $post->slug, false),
            'lastmod' => optional($post->updated_at ?? $post->published_at)->toDateString(),
            'priority' => '0.6',
        ]);

    $urls = array_merge($staticUrls, $products->toArray(), $posts->toArray());

    return response()
        ->view('sitemap', ['urls' => $urls])
        ->header('Content-Type', 'application/xml');
})->name('sitemap');

// Routes du blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Routes de contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

// Routes du compte utilisateur (nécessitent une authentification)
Route::middleware(['auth'])->group(function () {
    Route::get('/account', function () {
        return Inertia::render('Account/Profile');
    })->name('account.profile');
    
    Route::get('/account/orders', [OrderController::class, 'index'])->name('account.orders');
    Route::get('/account/orders/{order}', [OrderController::class, 'show'])->name('account.orders.show');
    
    Route::get('/account/addresses', function () {
        return Inertia::render('Account/Addresses');
    })->name('account.addresses');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');
});

// Routes d'administration (necessitent des permissions admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/supply', [SupplyController::class, 'index'])->name('supply');
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');
    Route::get('/production', [ProductionController::class, 'index'])->name('production');
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance');

    Route::post('products/images/upload', [AdminProductController::class, 'uploadImage'])->name('products.images.upload');
    Route::resource('products', AdminProductController::class);
    Route::resource('product-categories', ProductCategoryController::class)->except(['create', 'show', 'edit']);

    Route::resource('orders', AdminOrderController::class)
        ->only(['index', 'show', 'update', 'store']);

    Route::resource('deliveries', AdminDeliveryController::class)
        ->only(['index', 'show', 'update']);

    Route::resource('suppliers', SupplierController::class)->only(['store', 'update', 'destroy']);

    Route::post('/supply-orders/{supply_order}/receive', [AdminSupplyOrderController::class, 'receive'])
        ->name('supply-orders.receive');
    Route::resource('supply-orders', AdminSupplyOrderController::class)->only(['store', 'update', 'destroy']);

    Route::post('/inventory-batches/{inventory_batch}/reserve', [AdminInventoryBatchController::class, 'reserve'])
        ->name('inventory-batches.reserve');
    Route::resource('inventory-batches', AdminInventoryBatchController::class)->only(['store', 'update', 'destroy']);

    Route::resource('production-batches', AdminProductionBatchController::class)->only(['store', 'update', 'destroy']);

    Route::resource('contact-messages', AdminContactMessageController::class)
        ->only(['index', 'show', 'update']);
    Route::post('blog-posts/cover/upload', [AdminBlogPostController::class, 'uploadCover'])->name('blog-posts.cover.upload');
    Route::resource('blog-posts', AdminBlogPostController::class);
    Route::get('/experience/home', [HomeContentController::class, 'edit'])->name('home-content.edit');
    Route::put('/experience/home', [HomeContentController::class, 'update'])->name('home-content.update');

    Route::post('/finance/accounts', [FinanceController::class, 'storeAccount'])->name('finance.accounts.store');
    Route::post('/finance/transactions', [FinanceController::class, 'storeTransaction'])->name('finance.transactions.store');
    Route::patch('/finance/transactions/{transaction}', [FinanceController::class, 'updateTransaction'])->name('finance.transactions.update');
});

// Routes API pour les données
Route::middleware(['auth'])->prefix('api')->group(function () {
    // Statistiques des ventes
    Route::get('/sales-stats', function () {
        $stats = [
            'total_orders' => \App\Models\Order::count(),
            'monthly_revenue' => \App\Models\Order::whereMonth('created_at', now()->month)->sum('total_amount'),
            'popular_products' => \App\Models\OrderItem::selectRaw('product_id, count(*) as count')
                ->groupBy('product_id')
                ->orderBy('count', 'desc')
                ->take(5)
                ->get()
                ->load('product')
        ];
        
        return response()->json($stats);
    });
    
    // Données pour les graphiques
    Route::get('/chart-data', function () {
        return response()->json([
            'monthly_sales' => \App\Models\Order::selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
                ->whereYear('created_at', now()->year)
                ->groupBy('month')
                ->get()
                ->pluck('total', 'month'),
            'category_sales' => \App\Models\OrderItem::selectRaw('categories.name, SUM(order_items.quantity) as total')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->groupBy('categories.name')
                ->get()
                ->pluck('total', 'name')
        ]);
    });
});

// Routes d'authentification (gardées de Laravel Breeze)
require __DIR__.'/auth.php';

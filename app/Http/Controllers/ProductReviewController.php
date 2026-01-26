<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductReviewController extends Controller
{
    public function create(Product $product): Response
    {
        $product->loadMissing(['images' => fn ($query) => $query->orderBy('position')->orderBy('id')]);

        $cover = $product->images->first()?->url ?? $product->image_path;

        return Inertia::render('Products/ReviewInvite', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'tagline' => $product->tagline,
                'description' => $product->description,
                'image' => $cover,
                'accent' => $product->accent,
            ],
            'shareLink' => route('products.reviews.create', $product->slug),
        ])->withViewData([
            'seoOverrides' => [
                'title' => sprintf('Avis %s | %s', $product->name, config('app.name', 'Sandy Juice')),
                'description' => $product->description ?? $product->tagline ?? 'Partagez votre expérience Sandy.',
                'canonical' => route('products.reviews.create', $product->slug),
            ],
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'author_name' => ['required', 'string', 'max:120'],
            'author_email' => ['nullable', 'email', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:1000'],
        ]);

        ProductReview::create([
            ...$data,
            'product_id' => $product->id,
            'metadata' => [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
        ]);

        return back()->with('success', 'Merci pour votre retour d\'expérience !');
    }
}

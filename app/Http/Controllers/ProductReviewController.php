<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
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

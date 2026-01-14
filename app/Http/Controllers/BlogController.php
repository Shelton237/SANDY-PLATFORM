<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->only('search', 'category');

        $query = BlogPost::query()->published();

        if ($filters['search'] ?? null) {
            $query->where(function ($sub) use ($filters) {
                $sub->where('title', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('excerpt', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('body', 'like', '%' . $filters['search'] . '%');
            });
        }

        if ($filters['category'] ?? null) {
            $query->where('metadata->category', $filters['category']);
        }

        $posts = $query
            ->with('author:id,name')
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        $featured = BlogPost::published()
            ->where('is_featured', true)
            ->latest('published_at')
            ->take(3)
            ->get(['id', 'title', 'slug', 'excerpt', 'cover_image', 'published_at']);

        $recent = BlogPost::published()
            ->latest('published_at')
            ->take(5)
            ->get(['id', 'title', 'slug', 'published_at']);

        $categories = BlogPost::published()
            ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(metadata, '$.category')) as category")
            ->whereNotNull('metadata')
            ->get()
            ->pluck('category')
            ->filter()
            ->map(fn ($value) => (string) $value)
            ->unique()
            ->values();

        return Inertia::render('Blog/Index', [
            'posts' => $posts,
            'featured' => $featured,
            'recent' => $recent,
            'categories' => $categories,
            'filters' => $filters,
        ]);
    }

    public function show(string $slug): Response
    {
        $post = BlogPost::with('author:id,name')
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $related = BlogPost::published()
            ->where('id', '<>', $post->id)
            ->when(Arr::get($post->metadata, 'category'), function ($query, $category) {
                $query->where('metadata->category', $category);
            })
            ->latest('published_at')
            ->take(3)
            ->get(['id', 'title', 'slug', 'cover_image', 'excerpt', 'published_at']);

        return Inertia::render('Blog/Show', [
            'post' => $post,
            'related' => $related,
        ]);
    }
}

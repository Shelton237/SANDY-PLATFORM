<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;

class BlogPostController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->only('search', 'status', 'category');

        $posts = BlogPost::query()
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%");
                });
            })
            ->when($filters['status'] ?? null, function ($query, $status) {
                return $status === 'published'
                    ? $query->whereNotNull('published_at')
                    : $query->whereNull('published_at');
            })
            ->when($filters['category'] ?? null, function ($query, $category) {
                $query->where('metadata->category', $category);
            })
            ->with('author:id,name')
            ->latest('published_at')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = $this->categoriesList();

        $stats = [
            'total' => BlogPost::count(),
            'published' => BlogPost::published()->count(),
            'drafts' => BlogPost::whereNull('published_at')->count(),
            'featured' => BlogPost::where('is_featured', true)->count(),
        ];

        return Inertia::render('Admin/BlogPosts/Index', [
            'posts' => $posts,
            'filters' => $filters,
            'categories' => $categories,
            'stats' => $stats,
            'statusOptions' => $this->statusOptions(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/BlogPosts/Create', [
            'post' => $this->formPayload(),
            'categories' => $this->categoriesList(),
            'tags' => $this->tagSuggestions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['author_id'] = $request->user()->id;

        $post = BlogPost::create($data);

        return redirect()
            ->route('admin.blog-posts.edit', $post)
            ->with('success', 'Article cree avec succes.');
    }

    public function edit(BlogPost $blogPost): Response
    {
        $blogPost->load('author:id,name');

        return Inertia::render('Admin/BlogPosts/Edit', [
            'post' => $this->formPayload($blogPost),
            'categories' => $this->categoriesList(),
            'tags' => $this->tagSuggestions(),
        ]);
    }

    public function update(Request $request, BlogPost $blogPost): RedirectResponse
    {
        $data = $this->validatedData($request);

        if (!$blogPost->author_id) {
            $data['author_id'] = $request->user()->id;
        }

        $blogPost->update($data);

        return back()->with('success', 'Article mis a jour.');
    }

    public function destroy(BlogPost $blogPost): RedirectResponse
    {
        $blogPost->delete();

        return redirect()
            ->route('admin.blog-posts.index')
            ->with('success', 'Article supprime.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string'],
            'cover_image' => ['nullable', 'string', 'max:2048'],
            'is_featured' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'category' => ['nullable', 'string', 'max:120'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['nullable', 'string', 'max:60'],
            'reading_time' => ['nullable', 'integer', 'min:1', 'max:60'],
        ]);

        $metadata = array_filter([
            'category' => $data['category'] ?? null,
            'tags' => $this->normalizeList($data['tags'] ?? $request->input('tags_text')),
            'reading_time' => $data['reading_time'] ?? null,
        ], fn ($value) => !blank($value));

        return [
            'title' => $data['title'],
            'slug' => $data['slug'] ?? null,
            'excerpt' => $data['excerpt'] ?? null,
            'body' => $data['body'],
            'cover_image' => $data['cover_image'] ?? null,
            'is_featured' => (bool) ($data['is_featured'] ?? false),
            'published_at' => $data['published_at'] ?? null,
            'metadata' => $metadata ?: null,
        ];
    }

    private function statusOptions(): array
    {
        return [
            'published' => 'Publiés',
            'draft' => 'Brouillons',
        ];
    }

    private function formPayload(?BlogPost $post = null): array
    {
        $post ??= new BlogPost([
            'title' => '',
            'excerpt' => '',
            'body' => '',
            'cover_image' => '',
            'is_featured' => false,
            'published_at' => null,
            'metadata' => [],
        ]);

        $metadata = $post->metadata ?? [];

        return [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'excerpt' => $post->excerpt,
            'body' => $post->body,
            'cover_image' => $post->cover_image,
            'is_featured' => (bool) $post->is_featured,
            'published_at' => $post->published_at?->format('Y-m-d\TH:i'),
            'category' => Arr::get($metadata, 'category', ''),
            'tags' => Arr::get($metadata, 'tags', []),
            'reading_time' => Arr::get($metadata, 'reading_time'),
            'updated_at' => $post->updated_at?->toDateTimeString(),
            'author' => $post->author?->only(['id', 'name']),
        ];
    }

    private function categoriesList()
    {
        return BlogPost::query()
            ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(metadata, '$.category')) as category")
            ->whereNotNull('metadata')
            ->pluck('category')
            ->filter()
            ->unique()
            ->values();
    }

    private function tagSuggestions()
    {
        return BlogPost::query()
            ->selectRaw("JSON_EXTRACT(metadata, '$.tags') as tags")
            ->whereNotNull('metadata')
            ->get()
            ->flatMap(function ($row) {
                $value = $row->tags;

                if (is_string($value)) {
                    $decoded = json_decode($value, true);
                    return is_array($decoded) ? $decoded : [$value];
                }

                return is_array($value) ? $value : [];
            })
            ->map(fn ($tag) => trim((string) $tag))
            ->filter()
            ->unique()
            ->values();
    }

    private function normalizeList($input): array
    {
        if (blank($input)) {
            return [];
        }

        if (is_string($input)) {
            $input = explode(',', $input);
        }

        if (!is_array($input)) {
            return [];
        }

        return collect($input)
            ->map(fn ($value) => trim((string) $value))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}

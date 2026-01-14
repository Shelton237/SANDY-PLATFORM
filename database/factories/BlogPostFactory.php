<?php

namespace Database\Factories;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\BlogPost>
 */
class BlogPostFactory extends Factory
{
    protected $model = BlogPost::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(6);

        return [
            'author_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(1, 9999),
            'excerpt' => fake()->sentences(3, true),
            'body' => collect(range(1, 5))
                ->map(fn () => fake()->paragraphs(3, true))
                ->implode("\n\n"),
            'cover_image' => fake()->imageUrl(1200, 630, 'food', true, 'Sandy Juice'),
            'is_featured' => fake()->boolean(20),
            'published_at' => fake()->boolean(80) ? now()->subDays(fake()->numberBetween(1, 60)) : null,
            'metadata' => [
                'reading_time' => fake()->numberBetween(3, 8),
                'tags' => fake()->words(3),
                'category' => fake()->randomElement(['Nutrition', 'Bien-être', 'Production']),
            ],
        ];
    }

    public function draft(): self
    {
        return $this->state(fn () => ['published_at' => null]);
    }
}

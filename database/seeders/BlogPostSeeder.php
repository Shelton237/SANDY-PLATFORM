<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::where('is_admin', true)->first() ?? User::factory()->create([
            'name' => 'Equipe Sandy',
            'email' => 'blogger@sandy.test',
        ]);

        $featuredPosts = [
            [
                'title' => 'Comment nous sourcons les fruits frais de nos producteurs locaux',
                'excerpt' => 'Visite immersive dans notre circuit d’approvisionnement, du verger à l’atelier de production.',
                'body' => $this->formatParagraphs([
                    'Depuis Yaoundé, nous travaillons exclusivement avec des coopératives camerounaises qui garantissent un prix juste aux producteurs.',
                    'Chaque lot réceptionné est tracé dans notre backoffice puis analysé avant d’entrer dans la chaîne de production.',
                    'Cette proximité logistique nous permet d’offrir des jus pressés à froid au goût vibrant, sans compromis sanitaire.',
                ]),
                'cover_image' => '/images/blog/sourcing.jpg',
                'metadata' => [
                    'reading_time' => 4,
                    'tags' => ['Approvisionnement', 'Sourcing local'],
                    'category' => 'Production',
                ],
                'published_at' => now()->subDays(6),
                'is_featured' => true,
            ],
            [
                'title' => 'Nos conseils pour consommer les cures Sandy Juice',
                'excerpt' => 'Hydratation, moments clés de la journée et inspirations pour vos cures bien-être.',
                'body' => $this->formatParagraphs([
                    'Les cures Sandy Juice accompagnent votre organisme lors des pics de chaleur ou après une activité intense.',
                    'Nous recommandons de conserver les bouteilles entre 2° et 4°C et de les consommer dans les 72h.',
                    'Ajoutez un snack riche en fibres pour prolonger la satiété et stabiliser votre énergie.',
                ]),
                'cover_image' => '/images/blog/cures.jpg',
                'metadata' => [
                    'reading_time' => 3,
                    'tags' => ['Conseils', 'Bien-être'],
                    'category' => 'Nutrition',
                ],
                'published_at' => now()->subDays(2),
                'is_featured' => true,
            ],
        ];

        foreach ($featuredPosts as $data) {
            BlogPost::updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                [
                    ...$data,
                    'author_id' => $author->id,
                ]
            );
        }

        BlogPost::factory()
            ->count(8)
            ->for($author, 'author')
            ->create();
    }

    private function formatParagraphs(array $paragraphs): string
    {
        return collect($paragraphs)
            ->map(fn ($paragraph) => trim($paragraph))
            ->implode("\n\n");
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class HomeContentController extends Controller
{
    public function edit(): Response
    {
        $section = LandingSection::key('home_hero')->first();

        return Inertia::render('Admin/HomeContent/Edit', [
            'hero' => $section?->content ?? [],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'eyebrow' => ['nullable', 'string', 'max:120'],
            'headline' => ['required', 'string', 'max:255'],
            'subheadline' => ['nullable', 'string', 'max:500'],
            'highlight_badge' => ['nullable', 'string', 'max:120'],
            'highlight_text' => ['nullable', 'string', 'max:500'],
            'primary_cta.label' => ['nullable', 'string', 'max:120'],
            'primary_cta.route' => ['nullable', 'string', 'max:120'],
            'primary_cta.url' => ['nullable', 'url', 'max:255'],
            'secondary_cta.label' => ['nullable', 'string', 'max:120'],
            'secondary_cta.route' => ['nullable', 'string', 'max:120'],
            'secondary_cta.url' => ['nullable', 'url', 'max:255'],
            'media.image' => ['nullable', 'string', 'max:255'],
            'stats' => ['nullable', 'array', 'max:4'],
            'stats.*.label' => ['required_with:stats', 'string', 'max:120'],
            'stats.*.value' => ['required_with:stats', 'string', 'max:120'],
            'stats.*.icon' => ['nullable', 'string', 'max:60'],
        ]);

        $payload = [
            'eyebrow' => $data['eyebrow'] ?? null,
            'headline' => $data['headline'],
            'subheadline' => $data['subheadline'] ?? null,
            'highlight_badge' => $data['highlight_badge'] ?? null,
            'highlight_text' => $data['highlight_text'] ?? null,
            'primary_cta' => $data['primary_cta'] ?? null,
            'secondary_cta' => $data['secondary_cta'] ?? null,
            'media' => [
                'image' => $data['media']['image'] ?? '/images/hero/bottles.jpg',
            ],
            'stats' => collect($data['stats'] ?? [])
                ->filter(fn ($stat) => !empty($stat['label']) && !empty($stat['value']))
                ->map(fn ($stat) => [
                    'label' => $stat['label'],
                    'value' => $stat['value'],
                    'icon' => $stat['icon'] ?? 'bi bi-droplet',
                ])
                ->values()
                ->all(),
        ];

        LandingSection::updateOrCreate(
            ['key' => 'home_hero'],
            [
                'title' => 'Hero Home',
                'content' => $payload,
                'is_active' => true,
            ]
        );

        return redirect()
            ->route('admin.home-content.edit')
            ->with('success', 'Section d’accueil mise à jour.');
    }
}

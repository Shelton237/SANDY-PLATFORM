<?php

namespace Database\Seeders;

use App\Models\LandingSection;
use Illuminate\Database\Seeder;

class HomeContentSeeder extends Seeder
{
    public function run(): void
    {
        LandingSection::updateOrCreate(
            ['key' => 'home_hero'],
            [
                'title' => 'Hero Home',
                'content' => [
                    'eyebrow' => 'Jus naturels pressés à froid',
                    'headline' => 'Commandez vos jus Sandy et recevez-les en 2h à Yaoundé',
                    'subheadline' => 'Des recettes vitaminées pressées chaque matin, prêtes à être livrées à vos clients ou à votre foyer.',
                    'highlight_badge' => 'Offre lancement',
                    'highlight_text' => 'Paiement à la livraison • Production quotidienne • Ingrédients locaux',
                    'primary_cta' => [
                        'label' => 'Commander en ligne',
                        'route' => 'products',
                    ],
                    'secondary_cta' => [
                        'label' => 'Voir nos engagements',
                        'route' => 'about',
                    ],
                    'media' => [
                        'image' => '/images/hero/bottles.jpg',
                        'theme' => 'sunrise',
                        'carousel' => [
                            '/images/hero/bottles.jpg',
                            '/images/publication/gingembre.jpg',
                            '/images/publication/bissap-bienfaits-infusion-hibiscus.jpg',
                            '/images/publication/pineapple-ginger-juice.webp',
                        ],
                    ],
                    'stats' => [
                        ['label' => 'Commandes/jour', 'value' => '3 200', 'icon' => 'bi bi-bag-check'],
                        ['label' => 'Saveurs actives', 'value' => '24', 'icon' => 'bi bi-cup-straw'],
                        ['label' => 'Livraison', 'value' => '≤ 2h', 'icon' => 'bi bi-lightning-charge'],
                    ],
                ],
            ]
        );
    }
}

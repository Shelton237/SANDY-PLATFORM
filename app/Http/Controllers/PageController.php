<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class PageController extends Controller
{
    public function about()
    {
        return Inertia::render('AboutUs', [
            'title' => 'A propos de Sandy Juice',
            'description' => "Nous orchestrons l'approvisionnement, la production et la livraison de jus naturels presses a froid depuis Yaounde.",
            'teamMembers' => [
                [
                    'name' => 'Sandra F.',
                    'position' => 'Fondatrice & Supply',
                    'bio' => 'Assure la selection des producteurs, la traçabilite des lots et la coherence des recettes signature.',
                ],
                [
                    'name' => 'Helene J.',
                    'position' => 'Head of Production',
                    'bio' => "Dirige l'atelier de pressage, planifie les campagnes et garantit la fraicheur quotidienne.",
                ],
                [
                    'name' => 'Yannick B.',
                    'position' => 'Logistique & Livraison',
                    'bio' => 'Coordonne le stockage, les tournees express et la relation client sur Yaounde.',
                ],
            ],
        ]);
    }

    public function quality()
    {
        return Inertia::render('Quality', [
            'title' => 'Notre demarche qualite',
            'description' => 'Cold chain, analyses nutritives et controle packaging garantissent un jus stable.',
            'certifications' => [
                'ISO 22000 - securite alimentaire',
                'Agrement transformation MINADER',
                'Partenariats laboratoires externes',
            ],
            'processSteps' => [
                'Controle matieres premieres a la reception',
                'Analyses PH / sucre / densite sur chaque lot',
                'Conditionnement sous chaine froide',
                'Traceur livraison & feedback clients',
            ],
        ]);
    }

    public function values()
    {
        return Inertia::render('Values', [
            'title' => 'Nos valeurs fondatrices',
            'description' => 'Respect des filieres locales, transparence tarifaire et equite dans nos ateliers.',
            'values' => [
                [
                    'icon' => 'bi-shield-check',
                    'title' => 'Securite',
                    'description' => 'Cold chain, hygiene stricte et audits externes.',
                ],
                [
                    'icon' => 'bi-clock-history',
                    'title' => 'Ponctualite',
                    'description' => 'Livraisons express planifiees avec suivi temps reel.',
                ],
                [
                    'icon' => 'bi-currency-exchange',
                    'title' => 'Transparence',
                    'description' => 'Frais et marges clairement annonces a chaque commande.',
                ],
                [
                    'icon' => 'bi-heart',
                    'title' => 'Humain',
                    'description' => 'Soutien des cooperatives et primes pour nos equipes.',
                ],
            ],
        ]);
    }

    public function commitments()
    {
        return $this->values();
    }

    public function delivery()
    {
        return Inertia::render('Delivery', [
            'title' => 'Livraison & logistique Sandy',
            'subtitle' => 'Cold chain, suivi des coursiers et creneaux garantis',
            'metrics' => [
                ['label' => 'Creneaux villes', 'value' => '3/jour', 'caption' => 'Yaounde, Douala, Kribi'],
                ['label' => 'Delai urbain', 'value' => '-2h', 'caption' => 'Pickup et express'],
                ['label' => 'Tournees hebdo', 'value' => '42', 'caption' => 'Reseau B2B & retail'],
            ],
            'slots' => [
                ['time' => '06h - 08h', 'label' => 'Pressage & controle', 'badge' => 'Atelier'],
                ['time' => '08h - 10h', 'label' => 'Livraisons PRO', 'badge' => 'B2B'],
                ['time' => '10h - 14h', 'label' => 'Livraisons retail', 'badge' => 'Boutiques'],
                ['time' => '14h - 18h', 'label' => 'Tours particuliers', 'badge' => 'B2C'],
            ],
            'zones' => [
                ['name' => 'Yaounde intra-muros', 'delay' => '-2h', 'fee' => '1 500 FCFA', 'note' => 'Gratuit des 35 000 FCFA'],
                ['name' => 'Douala Bonapriso', 'delay' => '-3h', 'fee' => '2 000 FCFA', 'note' => 'Livraisons l\'apres-midi'],
                ['name' => 'Kribi / peripherie', 'delay' => 'J+1', 'fee' => '3 500 FCFA', 'note' => 'Groupage hebdo'],
            ],
            'gallery' => [
                [
                    'badge' => 'Express',
                    'title' => 'Fleet frigorifique',
                    'caption' => 'Camions et motos isolees',
                    'src' => 'https://images.unsplash.com/photo-1470337458703-46ad1756a187?auto=format&fit=crop&w=1000&q=80',
                    'size' => 'large',
                ],
                [
                    'badge' => 'Suivi',
                    'title' => 'Controle packaging',
                    'caption' => 'Scellage a l\'atelier',
                    'src' => '/images/publication/bissap-bienfaits-infusion-hibiscus.jpg',
                    'tall' => true,
                ],
                [
                    'badge' => 'Pickup',
                    'title' => 'Points relais',
                    'caption' => 'Partenaires coffee shop',
                    'src' => '/images/publication/gingembre.jpg',
                ],
                [
                    'badge' => 'Routiers',
                    'title' => 'Liaisons nationales',
                    'caption' => 'Reseau routier sud Cameroun',
                    'src' => '/images/publication/pineapple-ginger-juice.webp',
                ],
            ],
        ]);
    }

    public function show($page)
    {
        $pages = [
            'conditions-generales' => [
                'title' => 'Conditions generales d\'utilisation',
                'content' => 'Contenu des CGU...',
            ],
            'politique-confidentialite' => [
                'title' => 'Politique de confidentialite',
                'content' => 'Contenu de la politique de confidentialite...',
            ],
            'mentions-legales' => [
                'title' => 'Mentions legales',
                'content' => 'Contenu des mentions legales...',
            ],
        ];

        if (!array_key_exists($page, $pages)) {
            abort(404);
        }

        return Inertia::render('StaticPage', [
            'title' => $pages[$page]['title'],
            'content' => $pages[$page]['content'],
        ]);
    }
}

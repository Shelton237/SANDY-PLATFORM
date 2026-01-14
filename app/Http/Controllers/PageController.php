<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    /**
     * Affiche la page "À propos"
     */
    public function about()
    {
        return Inertia::render('AboutUs', [
            'title' => 'À propos de Sandy Juice',
            'description' => 'Nous orchestrons l’approvisionnement, la production et la livraison de jus naturels pressés à froid à Yaoundé. Chaque journée commence par la sélection des fruits locaux, se poursuit dans notre atelier de pressage et se termine par des livraisons ≤ 2h.',
            'teamMembers' => [
                [
                    'name' => 'Sandra F.',
                    'position' => 'Fondatrice & Supply',
                    'bio' => 'Assure la sélection des producteurs, la traçabilité des lots et l’équilibre des recettes signature.',
                ],
                [
                    'name' => 'Héléne J.',
                    'position' => 'Head of Production',
                    'bio' => 'Dirige l’atelier de pressage à froid, planifie les campagnes et garantit la fraîcheur quotidienne.',
                ],
                [
                    'name' => 'Yannick B.',
                    'position' => 'Logistique & Livraison',
                    'bio' => 'Coordonne le stockage, les tournées express et la relation client sur Yaoundé.',
                ],
            ],
        ]);
    }

    /**
     * Affiche la page "Démarche Qualité"
     */
    public function quality()
    {
        return Inertia::render('Quality', [
            'title' => 'Notre Démarche Qualité',
            'description' => 'Notre engagement pour un service d\'excellence.',
            'certifications' => [
                'Certification ISO 9001',
                'Label Qualicert',
                'Agrément Services à la Personne'
            ],
            'processSteps' => [
                'Recrutement rigoureux de nos intervenants',
                'Formation continue et adaptée',
                'Contrôle qualité régulier',
                'Évaluation de la satisfaction client'
            ]
        ]);
    }

    /**
     * Affiche la page "Nos Valeurs"
     */
    public function values()
    {
        return Inertia::render('Values', [
            'title' => 'Nos Valeurs Fondamentales',
            'description' => 'Nos engagements pour un service fiable et de qualité.',
            'values' => [
                [
                    'icon' => 'bi-shield-check',
                    'title' => 'Sécurité',
                    'description' => 'Des intervenants vérifiés, formés et assurés'
                ],
                [
                    'icon' => 'bi-clock-history',
                    'title' => 'Ponctualité',
                    'description' => 'Respect rigoureux des horaires convenus'
                ],
                [
                    'icon' => 'bi-currency-exchange',
                    'title' => 'Transparence',
                    'description' => 'Aucun frais caché, devis détaillés et suivis réguliers'
                ],
                [
                    'icon' => 'bi-heart',
                    'title' => 'Humanité',
                    'description' => 'Approche bienveillante centrée sur vos besoins'
                ]
            ]
        ]);
    }

    /**
     * Alias rétrocompatible vers la page des valeurs historiques.
     */
    public function commitments()
    {
        return $this->values();
    }

    /**
     * Affiche une page statique générique
     */
    public function show($page)
    {
        $pages = [
            'conditions-generales' => [
                'title' => 'Conditions Générales d\'Utilisation',
                'content' => 'Contenu des CGU...'
            ],
            'politique-confidentialite' => [
                'title' => 'Politique de Confidentialité',
                'content' => 'Contenu de la politique de confidentialité...'
            ],
            'mentions-legales' => [
                'title' => 'Mentions Légales',
                'content' => 'Contenu des mentions légales...'
            ]
        ];

        if (!array_key_exists($page, $pages)) {
            abort(404);
        }

        return Inertia::render('StaticPage', [
            'title' => $pages[$page]['title'],
            'content' => $pages[$page]['content']
        ]);
    }
}

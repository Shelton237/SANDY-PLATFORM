<?php

return [
    'categories' => [
        [
            'slug' => 'detox',
            'name' => 'Cures detox',
            'description' => 'Kale, citron vert et chlorophylle pour repartir a zero.',
            'icon' => 'bi-droplet-half',
            'accent' => 'emerald',
            'tagline' => 'Cures 3 jours disponibles sur commande.'
        ],
        [
            'slug' => 'energy',
            'name' => 'Boost energie',
            'description' => 'Agrumes, gingembre et maca pour rester focus.',
            'icon' => 'bi-lightning-charge',
            'accent' => 'orange',
            'tagline' => 'Jus presses a froid livres sous 2h.'
        ],
        [
            'slug' => 'beauty',
            'name' => 'Glow & beaute',
            'description' => 'Noix de coco, acerola et baies.',
            'icon' => 'bi-stars',
            'accent' => 'rose',
            'tagline' => 'Hydratation et collagene vegetal.'
        ],
        [
            'slug' => 'kids',
            'name' => 'Kids & famille',
            'description' => 'Recettes douces sans sucres ajoutes.',
            'icon' => 'bi-emoji-smile',
            'accent' => 'amber',
            'tagline' => 'Approuve par les minis gourmands.'
        ],
        [
            'slug' => 'calm',
            'name' => 'Relax & focus',
            'description' => 'Matcha, camomille et verveine.',
            'icon' => 'bi-moon-stars',
            'accent' => 'indigo',
            'tagline' => 'Pour ralentir apres 18h.'
        ],
    ],

    'moments' => [
        ['value' => 'morning', 'label' => 'Matin tonique', 'icon' => 'bi-sunrise'],
        ['value' => 'afternoon', 'label' => 'Pause 16h', 'icon' => 'bi-cup'],
        ['value' => 'sport', 'label' => 'Avant sport', 'icon' => 'bi-lightning'],
        ['value' => 'evening', 'label' => 'Soiree zen', 'icon' => 'bi-moon'],
        ['value' => 'kids', 'label' => 'Snack kids', 'icon' => 'bi-emoji-smile']
    ],

    'collections' => [
        [
            'slug' => 'morning-ritual',
            'title' => 'Rituel Morning Glow',
            'description' => 'Le trio best seller pour lancer la journee: detox, vitamine C et shot gingembre.',
            'products' => ['emerald-detox', 'sunrise-shield', 'ginger-shot'],
            'price' => 27.5,
            'duration' => '5 matins',
            'accent' => 'orange',
            'tag' => 'best seller'
        ],
        [
            'slug' => 'beauty-weekend',
            'title' => 'Weekend Glow Pack',
            'description' => 'Hydratation intense et collagene vegetal pour rayonner dimanche.',
            'products' => ['tropical-glow', 'coco-radiance'],
            'price' => 18.0,
            'duration' => '2 jours',
            'accent' => 'rose',
            'tag' => 'edition limitee'
        ],
        [
            'slug' => 'family-smile',
            'title' => 'Family Smile',
            'description' => 'Les recettes preferees des minis et des parents, format partage.',
            'products' => ['kids-smile', 'sunrise-shield'],
            'price' => 22.0,
            'duration' => '6 bouteilles',
            'accent' => 'amber',
            'tag' => 'new'
        ],
    ],

    'featured' => [
        'hero' => 'emerald-detox',
        'new' => 'ruby-roots',
        'limited' => 'nocturne-relax'
    ],

    'catalog' => [
        [
            'slug' => 'emerald-detox',
            'name' => 'Emerald Detox',
            'category' => 'detox',
            'tagline' => 'Kale et pomme verte pressee a froid pour repartir a zero.',
            'description' => 'Reset digestif intense avec kale, epinard, pomme verte et gingembre frais. Un jus tres vert, riche en chlorophylle.',
            'price' => 5.9,
            'size' => '330 ml',
            'availability' => 'pressage quotidien 6h',
            'calories' => 90,
            'sugars' => 13,
            'fibers' => 4.2,
            'popularity' => 98,
            'energy_index' => 3,
            'season' => 'all-year',
            'accent' => 'emerald',
            'moments' => ['morning', 'cleanse'],
            'taste' => ['herbace', 'citronne'],
            'badge' => 'best seller',
            'is_limited' => false,
            'is_new' => false,
            'ingredients' => [
                ['name' => 'Pomme verte', 'percentage' => 30],
                ['name' => 'Kale', 'percentage' => 35],
                ['name' => 'Epinard', 'percentage' => 15],
                ['name' => 'Citron vert', 'percentage' => 10],
                ['name' => 'Gingembre', 'percentage' => 10],
            ],
            'benefits' => [
                'Relance digestive',
                'Chargee en chlorophylle',
                'Riche en vitamine K'
            ],
            'vitamins' => [
                ['label' => 'Vit C', 'value' => '85 mg'],
                ['label' => 'Vit K', 'value' => '140 % AJR'],
            ],
            'image' => '/images/catalog/emerald-detox.jpg'
        ],
        [
            'slug' => 'sunrise-shield',
            'name' => 'Sunrise Shield',
            'category' => 'energy',
            'tagline' => 'Carotte, orange sanguine et maca pour booster l\'immunite.',
            'description' => 'Mix orange, carotte, citron, curcuma et maca. Notes epicees et peps instantane.',
            'price' => 5.5,
            'size' => '330 ml',
            'availability' => 'pressage quotidien 9h',
            'calories' => 110,
            'sugars' => 17,
            'fibers' => 3.1,
            'popularity' => 95,
            'energy_index' => 5,
            'season' => 'all-year',
            'accent' => 'orange',
            'moments' => ['morning', 'sport'],
            'taste' => ['agrumes', 'epice'],
            'badge' => 'immunite',
            'is_limited' => false,
            'is_new' => false,
            'ingredients' => [
                ['name' => 'Orange sanguine', 'percentage' => 40],
                ['name' => 'Carotte', 'percentage' => 25],
                ['name' => 'Citron', 'percentage' => 15],
                ['name' => 'Curcuma', 'percentage' => 10],
                ['name' => 'Maca', 'percentage' => 10],
            ],
            'benefits' => [
                'Immunite quotidienne',
                'Anti inflammatoire naturel',
                'Shot d\'energie propre'
            ],
            'vitamins' => [
                ['label' => 'Vit A', 'value' => '290 % AJR'],
                ['label' => 'Vit C', 'value' => '120 mg'],
            ],
            'image' => '/images/catalog/sunrise-shield.jpg'
        ],
        [
            'slug' => 'tropical-glow',
            'name' => 'Tropical Glow',
            'category' => 'beauty',
            'tagline' => 'Ananas, acerola et basilic thai pour un glow instantane.',
            'description' => 'Ananas Victoria, mangue, acerola et basilic thai pour une hydratation tropicale.',
            'price' => 6.2,
            'size' => '330 ml',
            'availability' => 'vendredi et samedi',
            'calories' => 105,
            'sugars' => 19,
            'fibers' => 2.8,
            'popularity' => 87,
            'energy_index' => 4,
            'season' => 'summer',
            'accent' => 'rose',
            'moments' => ['afternoon'],
            'taste' => ['tropical', 'floral'],
            'badge' => 'glow',
            'is_limited' => false,
            'is_new' => false,
            'ingredients' => [
                ['name' => 'Ananas', 'percentage' => 50],
                ['name' => 'Mangue', 'percentage' => 20],
                ['name' => 'Acerola', 'percentage' => 15],
                ['name' => 'Basilic thai', 'percentage' => 10],
                ['name' => 'Citron vert', 'percentage' => 5],
            ],
            'benefits' => [
                'Hydratation intense',
                'Stimule collagene',
                'Glow naturel'
            ],
            'vitamins' => [
                ['label' => 'Vit C', 'value' => '150 mg'],
                ['label' => 'Manganese', 'value' => '35 % AJR'],
            ],
            'image' => '/images/catalog/tropical-glow.jpg'
        ],
        [
            'slug' => 'ruby-roots',
            'name' => 'Ruby Roots',
            'category' => 'detox',
            'tagline' => 'Betterave, pamplemousse et grenade pour l\'endurence.',
            'description' => 'Betterave crue, pomme, grenade, pamplemousse rose et romarin.',
            'price' => 5.7,
            'size' => '330 ml',
            'availability' => 'pressage lundi et jeudi',
            'calories' => 120,
            'sugars' => 18,
            'fibers' => 3.5,
            'popularity' => 90,
            'energy_index' => 4,
            'season' => 'all-year',
            'accent' => 'rose',
            'moments' => ['sport', 'afternoon'],
            'taste' => ['terreux', 'agrumes'],
            'badge' => 'nouveaute',
            'is_limited' => false,
            'is_new' => true,
            'ingredients' => [
                ['name' => 'Betterave', 'percentage' => 35],
                ['name' => 'Pomme', 'percentage' => 25],
                ['name' => 'Grenade', 'percentage' => 20],
                ['name' => 'Pamplemousse', 'percentage' => 15],
                ['name' => 'Romarin', 'percentage' => 5],
            ],
            'benefits' => [
                'Endurence cardio',
                'Oxygene les muscles',
                'Riche en betalaines'
            ],
            'vitamins' => [
                ['label' => 'Vit B9', 'value' => '45 % AJR'],
                ['label' => 'Potassium', 'value' => '14 % AJR'],
            ],
            'image' => '/images/catalog/ruby-roots.jpg'
        ],
        [
            'slug' => 'coco-radiance',
            'name' => 'Coco Radiance',
            'category' => 'beauty',
            'tagline' => 'Eau de coco, aloe vera et fleur d\'oranger.',
            'description' => 'Base eau de coco verte, aloe vera fraiche, vanille crue et fleur d\'oranger.',
            'price' => 6.0,
            'size' => '350 ml',
            'availability' => 'pressage mardi',
            'calories' => 75,
            'sugars' => 9,
            'fibers' => 1.5,
            'popularity' => 85,
            'energy_index' => 2,
            'season' => 'all-year',
            'accent' => 'rose',
            'moments' => ['afternoon', 'evening'],
            'taste' => ['floral', 'leger'],
            'badge' => 'hydrate',
            'is_limited' => false,
            'is_new' => false,
            'ingredients' => [
                ['name' => 'Eau de coco', 'percentage' => 60],
                ['name' => 'Aloe vera', 'percentage' => 15],
                ['name' => 'Lait d\'amande', 'percentage' => 10],
                ['name' => 'Vanille crue', 'percentage' => 5],
                ['name' => 'Fleur d\'oranger', 'percentage' => 10],
            ],
            'benefits' => [
                'Hydratation profonde',
                'Calme la peau',
                'Recharge electrolytes'
            ],
            'vitamins' => [
                ['label' => 'Potassium', 'value' => '17 % AJR'],
                ['label' => 'Magnesium', 'value' => '9 % AJR'],
            ],
            'image' => '/images/catalog/coco-radiance.jpg'
        ],
        [
            'slug' => 'ginger-shot',
            'name' => 'Ginger Shot+',
            'category' => 'energy',
            'tagline' => 'Shot concentre gingembre, citron, poivre noir.',
            'description' => '60 ml de gingembre peruvien, citron vert, miel brut et poivre noir.',
            'price' => 3.9,
            'size' => '60 ml',
            'availability' => 'pressage quotidien',
            'calories' => 35,
            'sugars' => 6,
            'fibers' => 0.8,
            'popularity' => 99,
            'energy_index' => 5,
            'season' => 'all-year',
            'accent' => 'orange',
            'moments' => ['morning', 'sport'],
            'taste' => ['epice'],
            'badge' => 'shot',
            'is_limited' => false,
            'is_new' => false,
            'ingredients' => [
                ['name' => 'Gingembre', 'percentage' => 60],
                ['name' => 'Citron vert', 'percentage' => 25],
                ['name' => 'Miel brut', 'percentage' => 10],
                ['name' => 'Poivre noir', 'percentage' => 5],
            ],
            'benefits' => [
                'Coup de boost express',
                'Anti-microbien',
                'Relance circulation'
            ],
            'vitamins' => [
                ['label' => 'Vit C', 'value' => '45 mg'],
            ],
            'image' => '/images/catalog/ginger-shot.jpg'
        ],
        [
            'slug' => 'kids-smile',
            'name' => 'Kids Smile',
            'category' => 'kids',
            'tagline' => 'Pomme, fraise, hibiscus sans sucre ajoute.',
            'description' => 'Pomme, poire, fraise, hibiscus doux et vanille verte. Texture velours parfaite pour les kids.',
            'price' => 4.9,
            'size' => '250 ml',
            'availability' => 'mercredi et samedi',
            'calories' => 80,
            'sugars' => 14,
            'fibers' => 2.6,
            'popularity' => 82,
            'energy_index' => 2,
            'season' => 'all-year',
            'accent' => 'amber',
            'moments' => ['kids', 'afternoon'],
            'taste' => ['fruite'],
            'badge' => 'kids friendly',
            'is_limited' => false,
            'is_new' => false,
            'ingredients' => [
                ['name' => 'Pomme', 'percentage' => 40],
                ['name' => 'Poire', 'percentage' => 25],
                ['name' => 'Fraise', 'percentage' => 20],
                ['name' => 'Hibiscus', 'percentage' => 10],
                ['name' => 'Vanille', 'percentage' => 5],
            ],
            'benefits' => [
                'Sans sucres ajoutes',
                'Riche en fibres douces',
                'Format gouter'
            ],
            'vitamins' => [
                ['label' => 'Vit C', 'value' => '65 mg'],
            ],
            'image' => '/images/catalog/kids-smile.jpg'
        ],
        [
            'slug' => 'nocturne-relax',
            'name' => 'Nocturne Relax',
            'category' => 'calm',
            'tagline' => 'Infusion camomille, poire et lavande.',
            'description' => 'Infusion maison camomille bleue, poire conference, lait d\'avoine sans gluten et lavande.',
            'price' => 5.4,
            'size' => '300 ml',
            'availability' => 'edition limitee 200 bouteilles',
            'calories' => 95,
            'sugars' => 11,
            'fibers' => 2.1,
            'popularity' => 76,
            'energy_index' => 1,
            'season' => 'winter',
            'accent' => 'indigo',
            'moments' => ['evening'],
            'taste' => ['floral', 'reconfort'],
            'badge' => 'limitee',
            'is_limited' => true,
            'is_new' => false,
            'ingredients' => [
                ['name' => 'Infusion camomille', 'percentage' => 40],
                ['name' => 'Poire', 'percentage' => 30],
                ['name' => 'Lait d\'avoine', 'percentage' => 20],
                ['name' => 'Lavande', 'percentage' => 5],
                ['name' => 'Miel fleurs', 'percentage' => 5],
            ],
            'benefits' => [
                'Apaise le systeme nerveux',
                'Favorise un sommeil profond',
                'Indice glycemique bas'
            ],
            'vitamins' => [
                ['label' => 'Vit B6', 'value' => '18 % AJR'],
                ['label' => 'Tryptophane', 'value' => '110 mg'],
            ],
            'image' => '/images/catalog/nocturne-relax.jpg'
        ],
        [
            'slug' => 'matcha-focus',
            'name' => 'Matcha Focus',
            'category' => 'calm',
            'tagline' => 'Matcha ceremonial, poire asiatique et spiruline.',
            'description' => 'Base lait d\'avoine, matcha ceremonial Uji, poire asiatique et spiruline bleue. Doux et concentre.',
            'price' => 5.8,
            'size' => '300 ml',
            'availability' => 'pressage mardi et vendredi',
            'calories' => 85,
            'sugars' => 10,
            'fibers' => 2.4,
            'popularity' => 80,
            'energy_index' => 2,
            'season' => 'all-year',
            'accent' => 'indigo',
            'moments' => ['morning', 'afternoon'],
            'taste' => ['umami', 'leger'],
            'badge' => 'focus',
            'is_limited' => false,
            'is_new' => false,
            'ingredients' => [
                ['name' => 'Lait d\'avoine', 'percentage' => 50],
                ['name' => 'Matcha', 'percentage' => 10],
                ['name' => 'Poire asiatique', 'percentage' => 25],
                ['name' => 'Spiruline bleue', 'percentage' => 5],
                ['name' => 'Citron vert', 'percentage' => 10],
            ],
            'benefits' => [
                'Focus sans jitter',
                'Riche en L-theanine',
                'Mineraux doux'
            ],
            'vitamins' => [
                ['label' => 'Vit B2', 'value' => '15 % AJR'],
                ['label' => 'L-theanine', 'value' => '45 mg'],
            ],
            'image' => '/images/catalog/matcha-focus.jpg'
        ],
    ],

    'nutrition_focus' => [
        [
            'title' => 'Pression a froid',
            'value' => '48 h max',
            'description' => 'Extraction douce a 4 degres pour garder 98 % des vitamines.'
        ],
        [
            'title' => 'Fruits frais locaux',
            'value' => '43 fermes',
            'description' => 'Partenariats en circuit court Ile-de-France.'
        ],
        [
            'title' => 'Zero sucre ajoute',
            'value' => '100 % jus',
            'description' => 'Seulement le sucre naturel des fruits, jamais de concentre.'
        ],
        [
            'title' => 'Logistique froide',
            'value' => '2 h chrono',
            'description' => 'Livraison urbaine en caisse isotherme reutilisable.'
        ],
    ],
];

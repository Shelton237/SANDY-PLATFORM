<?php

return [
    'site_name' => env('APP_NAME', 'Sandy Juice'),
    'description' => env('SEO_DESCRIPTION', 'Sandy Juice orchestre l\'approvisionnement local, le pressage a froid et la livraison express de jus naturels au Cameroun.'),
    'keywords' => env('SEO_KEYWORDS', 'sandy juice,jus naturels cameroun,pressage a froid,livraison yaounde,cold chain'),
    'image' => env('SEO_IMAGE', '/images/logo.png'),
    'base_url' => env('APP_URL', config('app.url')),
];

<!DOCTYPE html>
<html lang="fr" class="{{ ($appearance ?? 'system') == 'dark' ? 'dark' : '' }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>JUS NATURELS - Vente de Jus et Accompagnements Bio</title>
    <meta name="description" content="Découvrez notre sélection de jus 100% naturels et d’accompagnements sains. Commandez en ligne et profitez d’une livraison rapide.">
    <meta name="keywords" content="jus naturels, accompagnements bio, jus frais, e-commerce Madagascar, produits sains">
    <meta name="author" content="JUS NATURELS">
    <meta name="robots" content="index, follow">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="JUS NATURELS - Vente en ligne de jus bio">
    <meta property="og:description" content="Commande simple et rapide de jus naturels et snacks sains.">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="JUS NATURELS - E-commerce de jus frais et accompagnements">
    <meta property="twitter:description" content="Votre boutique en ligne de jus bio et d’accompagnements naturels.">
    <meta property="twitter:image" content="{{ asset('images/logo.png') }}">

    <!-- Canonical -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @routes
    @vite(['resources/js/app.js', "resources/js/pages/{$page['component']}.vue"])
    @inertiaHead
  </head>
  <body class="font-sans antialiased pb-8">
    @inertia
    <noscript>
      <div style="text-align: center; padding: 2rem; background: #f8f9fa;">
        <h2>JavaScript requis</h2>
        <p>La boutique JUS NATURELS nécessite JavaScript pour fonctionner correctement.</p>
      </div>
    </noscript>
  </body>
</html>

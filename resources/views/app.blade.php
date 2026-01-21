<!DOCTYPE html>
<html lang="fr" class="{{ ($appearance ?? 'system') == 'dark' ? 'dark' : '' }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php($appName = config('app.name', 'Sandy Juice'))

    <title>{{ $appName }} - Jus naturels & pipeline logistique</title>
    <meta name="application-name" content="{{ $appName }}">
    <meta name="description" content="Sandy Juice orchestre l'approvisionnement local, la production a froid et la livraison express de jus naturels au Cameroun.">
    <meta name="keywords" content="Sandy Juice, jus naturels Cameroun, pressage a froid, livraison Yaounde, pipeline production">
    <meta name="author" content="{{ $appName }}">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#16a34a">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $appName }} - Jus naturels & cold chain">
    <meta property="og:description" content="Commandez nos cures presses a froid, suivez vos lots et recevez-les en moins de 2h.">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="{{ $appName }} - Pipeline complet du jus naturel">
    <meta property="twitter:description" content="Approvisionnement, production, vente et livraison coordonnes depuis Yaounde.">
    <meta property="twitter:image" content="{{ asset('images/logo.png') }}">

    <!-- Canonical -->
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
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

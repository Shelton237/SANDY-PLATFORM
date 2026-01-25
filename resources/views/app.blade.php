<!DOCTYPE html>
@php
    $appName = config('app.name', 'Sandy Juice');
    $seoConfig = config('seo', []);
    $seoOverrides = $seoOverrides ?? [];
    $siteName = $seoOverrides['site_name']
        ?? $seoConfig['site_name']
        ?? $appName;
    $defaultDescription = $seoConfig['description']
        ?? 'Sandy Juice orchestre l\'approvisionnement local, la production a froid et la livraison express de jus naturels au Cameroun.';
    $seoDescription = $seoOverrides['description']
        ?? $defaultDescription;
    $defaultKeywords = $seoConfig['keywords']
        ?? 'sandy juice,jus naturels cameroun,pressage a froid,livraison yaounde,pipeline production';
    $seoKeywords = $seoOverrides['keywords']
        ?? $defaultKeywords;
    $rawImage = $seoOverrides['image']
        ?? $seoConfig['image']
        ?? 'images/logo.png';
    $seoImage = filter_var($rawImage, FILTER_VALIDATE_URL) ? $rawImage : asset(ltrim($rawImage, '/'));
    $configuredBaseUrl = !empty($seoConfig['base_url']) ? rtrim($seoConfig['base_url'], '/') : null;
    $pathInfo = '/' . ltrim(request()->getPathInfo(), '/');
    $canonicalUrl = $seoOverrides['canonical']
        ?? (
            $configuredBaseUrl
                ? $configuredBaseUrl . ($pathInfo === '/' ? '' : $pathInfo)
                : url()->current()
        );
    $htmlLocale = str_replace('_', '-', app()->getLocale() ?? 'fr');
    $localeParts = explode('-', $htmlLocale);
    $ogLocale = count($localeParts) > 1
        ? strtolower($localeParts[0]) . '_' . strtoupper($localeParts[1])
        : strtolower($localeParts[0] ?? $htmlLocale);
    $pageTitle = $seoOverrides['title']
        ?? ($siteName . ' - Jus naturels & pipeline logistique');
@endphp
<html lang="{{ $htmlLocale }}" class="{{ ($appearance ?? 'system') == 'dark' ? 'dark' : '' }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle }}</title>
    <meta name="application-name" content="{{ $siteName }}">
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="keywords" content="{{ $seoKeywords }}">
    <meta name="author" content="{{ $siteName }}">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#16a34a">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:image" content="{{ $seoImage }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="{{ $ogLocale }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="{{ $pageTitle }}">
    <meta property="twitter:description" content="{{ $seoDescription }}">
    <meta property="twitter:image" content="{{ $seoImage }}">

    <!-- Canonical -->
    <link rel="canonical" href="{{ $canonicalUrl }}">
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

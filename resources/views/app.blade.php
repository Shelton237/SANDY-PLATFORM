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
    $seoType = $seoOverrides['type']
        ?? 'website';
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
    <meta name="theme-color" content="#E85A14">

    <!-- PWA iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Sandy Juice">
    <link rel="apple-touch-icon" sizes="72x72"  href="{{ asset('pwa-icons/icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="96x96"  href="{{ asset('pwa-icons/icon-96x96.png') }}">
    <link rel="apple-touch-icon" sizes="128x128" href="{{ asset('pwa-icons/icon-128x128.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('pwa-icons/icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('pwa-icons/icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Open Graph -->
    <meta property="og:type" content="{{ $seoType }}">
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
    <link rel="manifest" href="{{ asset('build/manifest.webmanifest') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
    {{-- Fond rouge dès le premier pixel — couvre tout flash avant le CSS bundle --}}
    <style>html,body{background:#E85A14}</style>
  </head>
  <body class="font-sans antialiased pb-8" style="background:#E85A14">

    <!-- ── Splash loader : visible jusqu'au montage de Vue ─────────────────── -->
    <div id="app-splash" aria-hidden="true">
      <img src="{{ asset('pwa-icons/icon-192x192.png') }}" alt="Sandy Juice">
      <p>Sandy Juice</p>
      <div class="sj-spinner"></div>
    </div>
    <style>
      #app-splash {
        position: fixed; inset: 0; z-index: 9999;
        background: #E85A14;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center; gap: 16px;
        transition: opacity .35s ease;
      }
      #app-splash img {
        width: 96px; height: 96px;
        border-radius: 22px;
        box-shadow: 0 8px 32px rgba(0,0,0,.35);
      }
      #app-splash p {
        color: #fff; font-family: sans-serif;
        font-size: 1.25rem; font-weight: 700;
        letter-spacing: .04em; margin: 0;
      }
      .sj-spinner {
        width: 38px; height: 38px;
        border: 4px solid rgba(255,255,255,.25);
        border-top-color: #f49926;
        border-radius: 50%;
        animation: sj-spin .75s linear infinite;
      }
      @keyframes sj-spin { to { transform: rotate(360deg); } }
      #app-splash.sj-hidden { opacity: 0; pointer-events: none; }
    </style>
    <!-- ────────────────────────────────────────────────────────────────────── -->

    @inertia
    <noscript>
      <div style="text-align: center; padding: 2rem; background: #f8f9fa;">
        <h2>JavaScript requis</h2>
        <p>La boutique JUS NATURELS nécessite JavaScript pour fonctionner correctement.</p>
      </div>
    </noscript>
  </body>
</html>

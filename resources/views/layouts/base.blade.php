<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-canvas text-ink-900 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('brand.name', 'UniGuide Pakistan') }} — {{ config('brand.seo.title_suffix', 'Premium Higher Education Discovery & Admissions Platform') }}</title>
    <meta name="description" content="{{ $description ?? content('seo.default_description', config('brand.description')) }}">

    <!-- Favicon & Brand Icons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset(config('brand.assets.favicon_svg', 'favicon.svg')) }}">
    <link rel="alternate icon" type="image/x-icon" href="{{ asset(config('brand.assets.favicon', 'favicon.ico')) }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset(config('brand.assets.favicon_32', 'favicon-32x32.png')) }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset(config('brand.assets.favicon_16', 'favicon-16x16.png')) }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset(config('brand.assets.apple_touch_icon', 'apple-touch-icon.png')) }}">
    <link rel="manifest" href="{{ asset(config('brand.assets.manifest', 'site.webmanifest')) }}">
    <meta name="theme-color" content="{{ config('brand.seo.theme_color', '#4355E8') }}">

    <!-- Typography: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Vite Compiled Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="min-h-full flex flex-col bg-canvas text-ink-900 font-sans selection:bg-brand-500/20 selection:text-brand-900">
    <!-- Screen Reader Skip Link (WCAG 2.2 AA) -->
    <a href="#main-content" class="skip-to-content sr-only focus:not-sr-only focus:absolute focus:top-3 focus:left-3 focus:z-50 focus:px-4 focus:py-2 focus:bg-brand-600 focus:text-white focus:rounded-lg focus:shadow-elevated">
        {{ content('global.nav.skip_link', 'Skip to main content') }}
    </a>

    <!-- Application Shell Body -->
    {{ $slot }}

    <!-- Global Toast Notifications Viewer -->
    <x-ui.toast />

    <!-- Global Command Palette (⌘K) -->
    <x-nav.command-palette />

    @stack('scripts')
</body>
</html>


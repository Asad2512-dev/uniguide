<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-canvas text-ink-900 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'UniGuide Pakistan' }} — Premium Higher Education Discovery & Admissions Platform</title>
    <meta name="description" content="{{ $description ?? 'UniGuide Pakistan is the national AI-powered platform for university discovery, verified admission merit calculation, degree program comparison, and scholarship tracking.' }}">

    <!-- Favicon & Brand Icons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <meta name="theme-color" content="#4355E8">

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
        Skip to main content
    </a>

    <!-- Application Shell Body -->
    <div class="app-shell flex min-w-0 flex-1 flex-col">
        {{ $slot }}
    </div>

    <!-- Global Toast Notifications Viewer -->
    <x-ui.toast />

    <!-- Global Command Palette (⌘K) -->
    <x-nav.command-palette />

    @stack('scripts')
</body>
</html>

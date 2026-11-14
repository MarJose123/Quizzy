<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="appearance">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title ?? 'Quizzy' }}</title>

        <link rel="icon" href="/favicon.ico?v=2" sizes="any">
        <link rel="icon" href="/favicon.svg?v=2" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png?v=2">

        {{-- Fonts --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        {{-- Styles / Scripts --}}
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                @import 'tailwindcss';
            </style>
        @endif
    </head>
    <body class="bg-base-200 text-base-content antialiased">
        {{ $slot }}

        @livewireScriptConfig
    </body>
</html>

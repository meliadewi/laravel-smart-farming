<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Smart Farming') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col items-center justify-center bg-emerald-50 px-4 py-10">

            <!-- Logo / Brand -->
            <div class="mb-8">
                <a href="/" wire:navigate class="text-3xl font-extrabold text-emerald-700 tracking-tight">
                    Smart Farming
                </a>
            </div>

            <!-- Card -->
            <div class="w-full sm:max-w-md bg-white rounded-2xl shadow-sm border border-emerald-100 px-8 py-10">
                {{ $slot }}
            </div>

            <p class="mt-8 text-sm text-gray-400">
                &copy; {{ date('Y') }} Smart Farming &mdash; Dashboard Lahan Pertanian
            </p>
        </div>
    </body>
</html>

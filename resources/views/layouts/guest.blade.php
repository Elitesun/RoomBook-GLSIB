<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'RoomBook') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<div class="min-h-screen flex">
    {{-- Panneau gauche décoratif --}}
    <div class="hidden lg:flex lg:w-1/2 flex-col justify-between bg-slate-900 p-12 text-white">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-500">
                <x-application-logo class="h-5 w-5 text-white fill-current"/>
            </div>
            <span class="text-base font-semibold">RoomBook</span>
        </div>
        <div>
            <h1 class="text-3xl font-semibold leading-snug text-white">
                Réservez vos salles<br>en quelques clics.
            </h1>
            <p class="mt-4 text-sm text-slate-400 leading-relaxed">
                Gestion des réservations de salles et de matériels pour votre établissement.
                Planning en temps réel, validation en ligne, notifications automatiques.
            </p>
            <div class="mt-8 flex gap-4">
                <div class="rounded-lg bg-slate-800 px-4 py-3 text-center">
                    <p class="text-2xl font-semibold text-blue-400">3</p>
                    <p class="text-xs text-slate-400 mt-0.5">Rôles</p>
                </div>
                <div class="rounded-lg bg-slate-800 px-4 py-3 text-center">
                    <p class="text-2xl font-semibold text-blue-400">0</p>
                    <p class="text-xs text-slate-400 mt-0.5">Conflits</p>
                </div>
                <div class="rounded-lg bg-slate-800 px-4 py-3 text-center">
                    <p class="text-2xl font-semibold text-blue-400">7j/7</p>
                    <p class="text-xs text-slate-400 mt-0.5">Disponible</p>
                </div>
            </div>
        </div>
        <p class="text-xs text-slate-600">IAI-Togo — {{ date('Y') }}</p>
    </div>

    {{-- Panneau droit : formulaire --}}
    <div class="flex flex-1 flex-col justify-center px-6 py-12 lg:px-12 bg-white">
        <div class="mx-auto w-full max-w-sm">
            {{-- Logo mobile --}}
            <div class="lg:hidden flex items-center gap-2 mb-8">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500">
                    <x-application-logo class="h-5 w-5 text-white fill-current"/>
                </div>
                <span class="text-sm font-semibold text-slate-800">RoomBook</span>
            </div>
            {{ $slot }}
        </div>
    </div>
</div>
</body>
</html>

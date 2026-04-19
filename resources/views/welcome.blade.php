<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RoomBook — IAI-Togo</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-900 text-white min-h-screen flex flex-col justify-between">
    <div class="flex flex-col items-center justify-center flex-1 px-6 py-20 text-center">
        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-500 mb-6">
            <x-application-logo class="h-8 w-8 text-white fill-current"/>
        </div>
        <h1 class="text-4xl font-semibold mb-3">RoomBook</h1>
        <p class="text-slate-400 text-lg mb-10 max-w-md">
            Réservation de salles et de matériels pour votre établissement. Simple, rapide, sans conflit.
        </p>
        @auth
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-6 py-3 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                Accéder à mon espace →
            </a>
        @else
            <div class="flex gap-3">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-6 py-3 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    Se connecter
                </a>
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-600 bg-slate-800 px-6 py-3 text-sm font-medium text-slate-200 hover:bg-slate-700 transition-colors">
                    Créer un compte
                </a>
            </div>
        @endauth
    </div>
    <p class="text-center text-xs text-slate-600 pb-6">IAI-Togo — {{ date('Y') }}</p>
</body>
</html>

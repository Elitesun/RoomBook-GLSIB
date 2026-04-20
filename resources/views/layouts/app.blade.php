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
<body class="font-sans antialiased bg-slate-100 text-slate-800">

<div class="flex min-h-screen" x-data="{ mobileOpen: false }">

    @php
        $role = Auth::user()->role ?? 'enseignant';
        $accentBg     = match($role) { 'admin' => 'bg-amber-500',       'responsable' => 'bg-emerald-500', default => 'bg-blue-500'     };
        $accentText   = match($role) { 'admin' => 'text-amber-400',     'responsable' => 'text-emerald-400', default => 'text-blue-400'  };
        $accentActive = match($role) { 'admin' => 'bg-slate-800 text-amber-400', 'responsable' => 'bg-slate-800 text-emerald-400', default => 'bg-slate-800 text-blue-400' };
        $roleLabel    = match($role) { 'admin' => 'Administrateur',     'responsable' => 'Responsable',      default => 'Enseignant'     };
    @endphp

    <div x-show="mobileOpen" @click="mobileOpen=false"
         class="fixed inset-0 z-20 bg-black/50 lg:hidden" x-cloak></div>

    <aside :class="mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed inset-y-0 left-0 z-30 flex w-60 flex-col bg-slate-900 text-slate-100 transition-transform duration-200 lg:static lg:translate-x-0">

        <div class="flex items-center gap-3 px-5 py-5 border-b border-slate-700/60">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ $accentBg }} flex-shrink-0">
                <x-application-logo class="h-5 w-5 text-white fill-current"/>
            </div>
            <span class="text-sm font-semibold">RoomBook</span>
        </div>

        <div class="px-5 py-3">
            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium bg-slate-800 {{ $accentText }} border border-slate-700">
                <span class="h-1.5 w-1.5 rounded-full {{ $accentBg }}"></span>
                {{ $roleLabel }}
            </span>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 pb-4 space-y-0.5">
            @php
                $base = 'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors';
                $idle = 'text-slate-400 hover:bg-slate-800 hover:text-slate-100';
            @endphp

            @if($role === 'enseignant')
                <p class="mt-3 mb-1 px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Mes espaces</p>
                <a href="{{ route('rooms.planning') }}" class="{{ $base }} {{ request()->routeIs('rooms.planning') ? $accentActive : $idle }}">
                    <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="16" y1="2" x2="16" y2="6"/></svg>
                    Planning
                </a>
                <a href="{{ route('bookings.index') }}" class="{{ $base }} {{ request()->routeIs('bookings.index') ? $accentActive : $idle }}">
                    <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="13" y2="16"/></svg>
                    Mes réservations
                </a>
                <a href="{{ route('bookings.create') }}" class="{{ $base }} {{ request()->routeIs('bookings.create') ? $accentActive : $idle }}">
                    <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                    Nouvelle réservation
                </a>
            @endif

            @if(in_array($role, ['responsable', 'admin']))
                <p class="mt-3 mb-1 px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Gestion</p>
                <a href="{{ route('responsable.planning') }}" class="{{ $base }} {{ request()->routeIs('responsable.planning') ? $accentActive : $idle }}">
                    <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="16" y1="2" x2="16" y2="6"/></svg>
                    Planning global
                </a>
                <a href="{{ route('responsable.pending') }}" class="{{ $base }} {{ request()->routeIs('responsable.pending') ? $accentActive : $idle }}">
                    <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><polyline points="12 6 12 12 16 14"/></svg>
                    Demandes en attente
                </a>
            @endif

            @if($role === 'admin')
                <p class="mt-3 mb-1 px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Administration</p>
                <a href="{{ route('admin.dashboard') }}" class="{{ $base }} {{ request()->routeIs('admin.dashboard') ? $accentActive : $idle }}">
                    <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
                    Tableau de bord
                </a>
                <a href="{{ route('admin.rooms.index') }}" class="{{ $base }} {{ request()->routeIs('admin.rooms.*') ? $accentActive : $idle }}">
                    <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/></svg>
                    Salles
                </a>
                <a href="{{ route('admin.equipment.index') }}" class="{{ $base }} {{ request()->routeIs('admin.equipment.*') ? $accentActive : $idle }}">
                    <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>
                    Matériel
                </a>
                <a href="{{ route('admin.users.index') }}" class="{{ $base }} {{ request()->routeIs('admin.users.*') ? $accentActive : $idle }}">
                    <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                    Utilisateurs
                </a>
            @endif
        </nav>

        <div class="border-t border-slate-700/60 px-4 py-3">
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-full {{ $accentBg }} flex-shrink-0">
                    <span class="text-xs font-semibold text-white">{{ strtoupper(substr(Auth::user()->name,0,1)) }}{{ strtoupper(substr(strstr(Auth::user()->name,' '),1,1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-slate-200 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('profile.edit') }}" title="Profil" class="text-slate-500 hover:text-slate-200 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Déconnexion" class="text-slate-500 hover:text-slate-200 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <div class="flex flex-1 flex-col min-w-0">
        <header class="sticky top-0 z-10 flex h-14 items-center gap-3 border-b border-slate-200 bg-white px-4 shadow-sm lg:px-6">
            <button @click="mobileOpen=!mobileOpen" class="lg:hidden text-slate-500 hover:text-slate-800">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="flex-1 flex items-center">
                @isset($header){{ $header }}@endisset
            </div>
            @isset($headerActions)
                <div class="flex items-center gap-2">{{ $headerActions }}</div>
            @endisset
        </header>
        <main class="flex-1 p-4 lg:p-6">
            {{ $slot }}
        </main>
    </div>
</div>
</body>
</html>

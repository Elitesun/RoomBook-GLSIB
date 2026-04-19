<x-app-layout>
    <x-slot name="header">
        <h1 class="text-base font-semibold text-slate-800">Accueil</h1>
    </x-slot>

    @php $role = Auth::user()->role; @endphp

    @if ($role === 'enseignant')
        <div class="grid gap-4 sm:grid-cols-3 mb-6">
            @php
                $cards = [
                    ['label'=>'Réservations totales', 'value'=>$stats['total'] ?? 0,   'color'=>'text-blue-600',   'bg'=>'bg-blue-50',   'ring'=>'ring-blue-200'],
                    ['label'=>'En attente',            'value'=>$stats['pending'] ?? 0, 'color'=>'text-amber-600',  'bg'=>'bg-amber-50',  'ring'=>'ring-amber-200'],
                    ['label'=>'Acceptées',             'value'=>$stats['accepted'] ?? 0,'color'=>'text-emerald-600','bg'=>'bg-emerald-50','ring'=>'ring-emerald-200'],
                ];
            @endphp
            @foreach($cards as $c)
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm flex items-center gap-4">
                    <div class="rounded-lg {{ $c['bg'] }} ring-1 {{ $c['ring'] }} p-3">
                        <span class="text-2xl font-semibold {{ $c['color'] }}">{{ $c['value'] }}</span>
                    </div>
                    <p class="text-sm text-slate-600">{{ $c['label'] }}</p>
                </div>
            @endforeach
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('bookings.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
                Nouvelle réservation
            </a>
            <a href="{{ route('rooms.planning') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/></svg>
                Voir le planning
            </a>
        </div>
    @elseif ($role === 'responsable')
        <div class="grid gap-4 sm:grid-cols-3 mb-6">
            @php
                $cards = [
                    ['label'=>'En attente de validation', 'value'=>$stats['pending'] ?? 0,  'color'=>'text-amber-600',  'href'=>route('responsable.pending')],
                    ['label'=>'Acceptées ce mois',        'value'=>$stats['accepted'] ?? 0, 'color'=>'text-emerald-600','href'=>route('responsable.planning')],
                    ['label'=>'Refusées ce mois',         'value'=>$stats['rejected'] ?? 0, 'color'=>'text-red-600',    'href'=>route('responsable.planning')],
                ];
            @endphp
            @foreach($cards as $c)
                <a href="{{ $c['href'] }}" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow transition-all block">
                    <p class="text-xs text-slate-500 mb-1">{{ $c['label'] }}</p>
                    <p class="text-2xl font-semibold {{ $c['color'] }}">{{ $c['value'] }}</p>
                </a>
            @endforeach
        </div>
        <a href="{{ route('responsable.pending') }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">
            Traiter les demandes en attente
        </a>
    @elseif ($role === 'admin')
        @include('admin.dashboard')
    @endif
</x-app-layout>

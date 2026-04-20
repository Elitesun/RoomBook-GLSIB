<x-app-layout>
    <x-slot name="header">
        <h1 class="text-base font-semibold text-slate-800">Tableau de bord</h1>
    </x-slot>

    <x-alert />

    {{-- Stats --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        @php
            $cards = [
                ['label'=>'Salles',                  'value'=>$stats['rooms_count'],        'color'=>'text-blue-600',   'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6'],
                ['label'=>'Équipements',             'value'=>$stats['equipment_count'],    'color'=>'text-purple-600', 'icon'=>'M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18'],
                ['label'=>'En attente',              'value'=>$stats['pending_count'],      'color'=>'text-amber-600',  'icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label'=>'Réservations cette semaine','value'=>$stats['bookings_this_week'],'color'=>'text-emerald-600','icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ];
        @endphp
        @foreach($cards as $card)
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-medium text-slate-500">{{ $card['label'] }}</p>
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100">
                        <svg class="h-4 w-4 {{ $card['color'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-semibold {{ $card['color'] }}">{{ $card['value'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Accès rapides --}}
    <h2 class="mb-3 text-sm font-semibold text-slate-700">Accès rapides</h2>
    <div class="grid gap-3 sm:grid-cols-3">
        @php
            $links = [
                ['href'=>route('admin.rooms.index'),     'label'=>'Gérer les salles',       'sub'=>'Ajouter, modifier, activer',    'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6'],
                ['href'=>route('admin.equipment.index'), 'label'=>'Gérer le matériel',      'sub'=>'Stocks, disponibilités',        'icon'=>'M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18'],
                ['href'=>route('admin.users.index'),     'label'=>'Gérer les utilisateurs', 'sub'=>'Rôles, comptes, accès',         'icon'=>'M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2'],
            ];
        @endphp
        @foreach($links as $l)
            <a href="{{ $l['href'] }}"
               class="group flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:border-slate-300 hover:shadow transition-all">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-slate-100 group-hover:bg-amber-100 transition-colors">
                    <svg class="h-5 w-5 text-slate-500 group-hover:text-amber-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $l['icon'] }}"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-800">{{ $l['label'] }}</p>
                    <p class="text-xs text-slate-400">{{ $l['sub'] }}</p>
                </div>
            </a>
        @endforeach
    </div>
</x-app-layout>

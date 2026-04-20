@props(['status'])
@php
    $map = [
        'en_attente' => ['label' => 'En attente', 'class' => 'bg-amber-100 text-amber-800 ring-amber-200'],
        'acceptee'   => ['label' => 'Acceptée',   'class' => 'bg-emerald-100 text-emerald-800 ring-emerald-200'],
        'refusee'    => ['label' => 'Refusée',    'class' => 'bg-red-100 text-red-800 ring-red-200'],
        'annulee'    => ['label' => 'Annulée',    'class' => 'bg-slate-100 text-slate-600 ring-slate-200'],
    ];
    $entry = $map[$status] ?? ['label' => ucfirst($status), 'class' => 'bg-slate-100 text-slate-600 ring-slate-200'];
@endphp
<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 {{ $entry['class'] }}">
    {{ $entry['label'] }}
</span>

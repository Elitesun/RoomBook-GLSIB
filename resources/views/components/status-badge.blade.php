@props(['status'])

@php
    $map = [
        'en_attente' => ['label' => 'En attente', 'class' => 'bg-yellow-100 text-yellow-800'],
        'acceptee' => ['label' => 'Acceptée', 'class' => 'bg-green-100 text-green-800'],
        'refusee' => ['label' => 'Refusée', 'class' => 'bg-red-100 text-red-800'],
        'annulee' => ['label' => 'Annulée', 'class' => 'bg-gray-100 text-gray-700'],
    ];

    $entry = $map[$status] ?? ['label' => ucfirst((string) $status), 'class' => 'bg-gray-100 text-gray-700'];
@endphp

<span class="inline-flex rounded px-2 py-1 text-xs font-semibold {{ $entry['class'] }}">
    {{ $entry['label'] }}
</span>

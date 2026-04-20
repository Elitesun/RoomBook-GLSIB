<x-mail::message>
# Votre réservation est acceptée

Bonjour {{ $booking->user->name }},

Votre demande de réservation pour la salle {{ $booking->room->name }} a été acceptée.

- Début: {{ $booking->starts_at->format('d/m/Y H:i') }}
- Fin: {{ $booking->ends_at->format('d/m/Y H:i') }}
- Motif: {{ $booking->purpose }}

Merci,<br>
{{ config('app.name') }}
</x-mail::message>

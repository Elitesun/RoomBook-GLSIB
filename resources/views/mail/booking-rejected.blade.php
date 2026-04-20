<x-mail::message>
# Votre réservation est refusée

Bonjour {{ $booking->user->name }},

Votre demande pour la salle {{ $booking->room->name }} n'a pas été acceptée.

- Début: {{ $booking->starts_at->format('d/m/Y H:i') }}
- Fin: {{ $booking->ends_at->format('d/m/Y H:i') }}
- Motif: {{ $booking->purpose }}
- Raison du refus: {{ $booking->rejection_reason }}

Merci,<br>
{{ config('app.name') }}
</x-mail::message>

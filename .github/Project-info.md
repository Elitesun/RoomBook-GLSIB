# RoomBook

Réservation de salles et de matériels pour une institution

## Contexte

- Un établissement dispose de plusieurs salles de cours et d’un parc de matériels (vidéoprojecteurs, tableaux interactifs, micros). Les enseignants réservent ces ressources en ligne. Un responsable valide les demandes. Les conflits de réservation doivent être détectés automatiquement.

## Fonctionnalités attendues

### Trois rôles : Enseignant, Responsable, Administrateur.

- **Enseignant** : voir le planning des disponibilités (vue semaine), faire une demande de réservation (salle + matériel facultatif, créneau, motif), suivre le statut de ses demandes, annuler une réservation acceptée.
- **Responsable** : voir les demandes en attente, accepter ou refuser avec un motif, voir le planning global.
- **Admin** : gérer les salles (capacité, équipements, disponibilité), gérer le matériel, gérer les utilisateurs.

## Modèle de données attendu (minimum)

| Table             | Colonnes clés                                                                                                 |
| ----------------- | ------------------------------------------------------------------------------------------------------------- |
| users             | name, email, password, role                                                                                   |
| rooms             | name, capacity, building, is_available                                                                        |
| equipment         | name, quantity, is_available                                                                                  |
| bookings          | user_id, room_id, starts_at, ends_at, purpose, status (en attente/acceptée/refusée/annulée), rejection_reason |
| booking_equipment | booking_id, equipment_id, quantity (pivot)                                                                    |

## Contraintes techniques spécifiques

- Détection de conflits : à la soumission, vérifier qu’aucune réservation acceptée ne chevauche le créneau demandé pour la même salle.
- Validation personnalisée (Rule custom) : l’heure de fin doit être strictement supérieure à l’heure de début.
- La vue planning doit afficher les réservations de la semaine courante, naviguable semaine par semaine.
- Notification email (Mailable) à l’enseignant lors de l’acceptation ou du refus.

---

## Questions UX que vous devrez justifier en soutenance

- Comment montre-t-on en un coup d’œil les disponibilités d’une salle sur une semaine ?
- À quel moment dans le parcours de réservation informez-vous l’utilisateur d’un conflit ?
- Comment différenciez-vous visuellement une salle « réservée » d’une salle « en attente de validation » ?
- Que se passe-t-il si l’enseignant tente d’annuler une réservation qui commence dans 30 minutes ?

---

You can test it here:

App: http://localhost:8000/login
Register: http://localhost:8000/register
After login, the redirect depends on role:
enseignant → /bookings
responsable → /responsable/pending
admin → /admin/dashboard
Seeded demo accounts:

admin@roombook.tg / password
responsable1@roombook.tg / password
enseignant1@roombook.tg / password

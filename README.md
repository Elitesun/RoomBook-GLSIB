# RoomBook

Application web de réservation de salles et de matériels pour un établissement.

## Présentation

RoomBook permet aux enseignants de réserver des salles et du matériel (vidéoprojecteurs, micros, tableaux interactifs, etc.), avec validation par un responsable et supervision complète par un administrateur.

L'application gère automatiquement les conflits de réservation et centralise le suivi des demandes dans une interface claire par rôle.

## Fonctionnalités principales

- Gestion de trois rôles: enseignant, responsable, administrateur.
- Consultation d'un planning hebdomadaire des disponibilités.
- Création de demandes de réservation (salle, créneau, motif, matériel optionnel).
- Détection automatique des conflits sur une même salle.
- Validation des demandes par un responsable (acceptation/refus avec motif).
- Suivi du statut des demandes (en attente, acceptée, refusée, annulée).
- Notifications e-mail à l'enseignant lors de l'acceptation ou du refus.
- Administration des salles, matériels et utilisateurs.

## Rôles et parcours

### Enseignant

- Consulter le planning de la semaine.
- Soumettre une demande de réservation.
- Suivre ses demandes.
- Annuler une réservation acceptée (selon les règles métier en place).

### Responsable

- Voir les demandes en attente.
- Accepter ou refuser une demande avec un motif.
- Consulter le planning global.

### Administrateur

- Gérer les salles (capacité, bâtiment, disponibilité).
- Gérer le matériel (quantité, disponibilité).
- Gérer les comptes utilisateurs et les rôles.

## Stack technique

- Backend: Laravel (PHP)
- Frontend: Blade, Vite, Tailwind CSS, JavaScript
- Base de données: MySQL (ou compatible selon configuration)
- Tests: PHPUnit

## Modèle de données (résumé)

- users: informations utilisateur et rôle.
- rooms: salles, capacité, bâtiment, disponibilité.
- equipment: matériel, quantité, disponibilité.
- bookings: réservation, créneau, statut, motif de refus.
- booking_equipment: pivot réservation/matériel avec quantité.

## Règles métier importantes

- Une réservation ne peut pas chevaucher une autre réservation acceptée pour la même salle.
- L'heure de fin doit être strictement supérieure à l'heure de début.
- Le planning est affiché par semaine avec navigation.

## Installation et démarrage

1. Cloner le projet.
2. Installer les dépendances PHP:

```bash
composer install
```

3. Installer les dépendances front:

```bash
npm install
```

4. Copier le fichier d'environnement et générer la clé:

```bash
cp .env.example .env
php artisan key:generate
```

5. Configurer la base de données dans `.env`.
6. Lancer les migrations et les seeders:

```bash
php artisan migrate --seed
```

7. Démarrer l'application:

```bash
php artisan serve
npm run dev
```

## Comptes de démonstration

- admin@roombook.tg / password
- responsable1@roombook.tg / password
- enseignant1@roombook.tg / password

Accès:

- Connexion: http://localhost:8000/login
- Inscription: http://localhost:8000/register

Redirections après connexion:

- enseignant -> /bookings
- responsable -> /responsable/pending
- admin -> /admin/dashboard

## Tests

Pour exécuter la suite de tests:

```bash
php artisan test
```

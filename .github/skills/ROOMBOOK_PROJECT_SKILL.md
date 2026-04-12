# ROOMBOOK — AI Agent Project Skill
## Réservation de salles et de matériels — Laravel 12 | IAI-Togo GLSI-3

---

## 1. WHAT THIS PROJECT IS

RoomBook is a **web application** built with **Laravel 12** that allows an educational institution to manage room and equipment bookings. Teachers request reservations, a supervisor validates or rejects them, and an administrator manages the full inventory of rooms, equipment, and users.

This project is an **academic project** for 3rd-year Software Engineering students (GLSI-3) at IAI-Togo. The teacher (Dabilibe DOUTI) requires that **every major Laravel concept taught in the course** be demonstrably present in the project. This is not optional — it is a grading requirement.

**Do not invent features beyond the spec. Do not skip required Laravel concepts. When in doubt, refer to Section 6 (Mandatory Laravel Concepts Checklist).**

---

## 2. THREE USER ROLES

| Role | French Label | What They Can Do |
|------|-------------|-----------------|
| `enseignant` | Enseignant | View weekly room availability calendar, submit booking requests (room + optional equipment, time slot, purpose), track status of their own requests, cancel an *accepted* booking |
| `responsable` | Responsable | View all pending booking requests, accept or reject with a written reason, view the global weekly planning |
| `admin` | Administrateur | Full CRUD on rooms, full CRUD on equipment, full CRUD on users, view everything |

**Important:** Role is stored as a string enum in the `users` table. Middleware and Policies enforce access. A logged-in user always sees only what their role permits.

---

## 3. DATABASE SCHEMA (Authoritative)

### Table: `users`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| name | string | |
| email | string unique | |
| email_verified_at | timestamp nullable | Breeze default |
| password | string | hashed |
| role | enum('enseignant','responsable','admin') | default: 'enseignant' |
| remember_token | string nullable | Breeze default |
| timestamps | | created_at, updated_at |

### Table: `rooms`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| name | string | e.g. "Salle A101" |
| capacity | integer | number of seats |
| building | string | e.g. "Bâtiment A" |
| is_available | boolean | default: true — admin can disable |
| timestamps | | |

### Table: `equipment`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| name | string | e.g. "Vidéoprojecteur" |
| quantity | integer | total stock |
| is_available | boolean | default: true |
| timestamps | | |

### Table: `bookings`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| user_id | FK → users | the enseignant who requested |
| room_id | FK → rooms | |
| starts_at | datetime | |
| ends_at | datetime | |
| purpose | text | reason for booking |
| status | enum('en_attente','acceptee','refusee','annulee') | default: 'en_attente' |
| rejection_reason | text nullable | filled by responsable on refusal |
| timestamps | | |

### Table: `booking_equipment` (pivot)
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| booking_id | FK → bookings | |
| equipment_id | FK → equipment | |
| quantity | integer | how many units requested |

### Relationships Summary
- `User` hasMany `Booking` (as enseignant)
- `Booking` belongsTo `User`
- `Booking` belongsTo `Room`
- `Booking` belongsToMany `Equipment` through `booking_equipment` (with pivot column `quantity`)
- `Room` hasMany `Booking`
- `Equipment` belongsToMany `Booking`

---

## 4. FEATURE SPECIFICATIONS

### 4.1 Authentication
- Use **Laravel Breeze** (Blade stack)
- Standard routes: /register, /login, /logout, /dashboard
- After login, redirect based on role:
  - enseignant → `/bookings`
  - responsable → `/responsable/pending`
  - admin → `/admin/dashboard`
- Guests cannot access any protected route (middleware `auth`)

### 4.2 Enseignant Features

**Weekly Availability View (`/rooms/planning`)**
- Displays a 7-column calendar grid (Mon–Sun) for the current week
- Each room is a row; each cell shows if the room is free, booked (accepted), or pending
- Color coding: green = available, orange = pending, red = booked/accepted, gray = unavailable (is_available = false)
- Navigation: "Semaine précédente" / "Semaine suivante" buttons (uses query param `?week=YYYY-WW`)
- Clicking a free slot pre-fills the booking form

**Booking Request Form (`/bookings/create`)**
- Fields: room (select), date, starts_at (time), ends_at (time), purpose (textarea), equipment (checkboxes with quantity inputs — optional)
- Conflict detection happens **on submission** (server-side): query for any `accepted` booking on the same room that overlaps the requested time slot
- If conflict → redirect back with error message specifying which booking conflicts
- Custom validation Rule class (`EndAfterStart`) ensures `ends_at > starts_at`
- CSRF protected (`@csrf`)
- Uses `old()` to repopulate on validation failure

**My Bookings (`/bookings`)**
- Table listing the enseignant's own bookings: room, date, time slot, status badge, actions
- Status badges: yellow=en_attente, green=acceptee, red=refusee, gray=annulee
- "Annuler" button visible only for `acceptee` status
  - Cancellation is only allowed if `starts_at > now()` (cannot cancel past or in-progress bookings)
  - Edge case: if starts_at is within 30 minutes of now → show warning confirmation modal before cancelling, but still allow it
  - On cancel → status becomes `annulee`

### 4.3 Responsable Features

**Pending Requests (`/responsable/pending`)**
- List of all bookings with `status = 'en_attente'`
- Shows: enseignant name, room, date/time, purpose, equipment requested
- Two actions per row: "Accepter" (POST) and "Refuser" (POST with modal for rejection_reason)
- On accept: status → `acceptee`, send email notification to enseignant
- On refuse: status → `refusee`, save rejection_reason, send email notification

**Global Planning (`/responsable/planning`)**
- Same weekly grid as enseignant view but shows ALL rooms and ALL bookings (all statuses visible)
- Read-only

### 4.4 Admin Features

All admin routes are prefixed `/admin` and protected by middleware checking `role === 'admin'`.

**Rooms CRUD (`/admin/rooms`)**
- index, create, store, edit, update, destroy
- Fields: name, capacity, building, is_available (toggle)

**Equipment CRUD (`/admin/equipment`)**
- index, create, store, edit, update, destroy
- Fields: name, quantity, is_available (toggle)

**Users CRUD (`/admin/users`)**
- index, create, store, edit, update, destroy
- Admin can set/change roles
- Admin cannot delete their own account

**Admin Dashboard (`/admin/dashboard`)**
- Summary stats: total rooms, total equipment items, pending bookings count, bookings this week

---

## 5. TECHNICAL CONSTRAINTS & IMPLEMENTATION DETAILS

### 5.1 Conflict Detection Logic
```
When storing a new booking for room_id R, starts_at S, ends_at E:
Find any booking where:
  - room_id = R
  - status = 'acceptee'
  - NOT (ends_at <= S OR starts_at >= E)   ← overlap condition
If any found → validation fails with error message
```
This must be implemented as a server-side check inside the `BookingRequest` Form Request or the controller's store method.

### 5.2 Custom Validation Rule
Create a custom Rule class: `php artisan make:rule EndAfterStart`
- Validates that `ends_at` is strictly greater than `starts_at`
- Must be used in the `BookingRequest` Form Request
- This demonstrates the "Rule custom" requirement from the spec

### 5.3 Email Notifications (Mailable)
Create two Mailable classes:
- `BookingAccepted` — sent to enseignant when responsable accepts
- `BookingRejected` — sent to enseignant when responsable refuses (includes rejection_reason)
- Use Laravel's `Mail::to($user)->send(new BookingAccepted($booking))`
- In development, use Mailtrap or log driver (`MAIL_MAILER=log` in .env)
- Mailables must use a Blade view template

### 5.4 Weekly Planning View
- Week is identified by a `?week=` query parameter (format: `YYYY-WW` or use Carbon `startOfWeek`)
- Default is current week
- Controller computes `$weekStart` (Monday) and `$weekEnd` (Sunday) from the param
- Query: all bookings for that week, eager-loaded with room and user
- Pass structured data to the Blade view for rendering the grid

### 5.5 Policies
- `BookingPolicy`:
  - `cancel`: user is the owner AND booking is accepted AND starts_at > now()
  - `view`: user owns the booking OR user is responsable OR user is admin
- `RoomPolicy`, `EquipmentPolicy`: only admin can create/edit/delete
- Register policies in `AuthServiceProvider`

### 5.6 Middleware
- `auth` — Laravel built-in, used on all protected routes
- `EnsureRole` — custom middleware: `php artisan make:middleware EnsureRole`
  - Takes a role parameter, checks `Auth::user()->role`
  - Applied to responsable and admin route groups
  - Returns 403 if role doesn't match

### 5.7 Form Requests
Create dedicated Form Request classes (do NOT validate inline in controllers):
- `StoreBookingRequest` — validates all booking fields + conflict check + EndAfterStart rule
- `UpdateBookingRequest`
- `StoreRoomRequest` / `UpdateRoomRequest`
- `StoreEquipmentRequest` / `UpdateEquipmentRequest`
- `StoreUserRequest` / `UpdateUserRequest`

### 5.8 Route Model Binding
All controller methods that receive a model (Room, Booking, Equipment, User) must use **Route Model Binding** — inject the model directly, never use `findOrFail($id)` manually.

### 5.9 Eager Loading
Wherever bookings are listed, always eager-load: `Booking::with(['user', 'room', 'equipment'])` to prevent N+1 queries.

---

## 6. MANDATORY LARAVEL CONCEPTS CHECKLIST

The teacher requires these to be visible in the project. Each agent working on a feature must ensure the relevant concepts are used:

| Concept | Where It Appears |
|---------|-----------------|
| MVC Architecture | Entire project structure |
| Artisan CLI | All code generation (make:model, make:controller, etc.) |
| Migrations | All 5 tables with proper foreign keys and rollback |
| Eloquent ORM | All database interactions — NO raw SQL |
| Eloquent Relations | hasMany, belongsTo, belongsToMany with pivot |
| Seeders + Factories | DatabaseSeeder with fake users (each role), rooms, equipment, bookings |
| Route Model Binding | All resource controllers |
| Resource Controllers | All CRUD controllers use `Route::resource()` |
| Middlewares | `auth`, custom `EnsureRole` middleware |
| Blade Templates | Layout inheritance, components, @csrf, @error, old() |
| Blade Components | Reusable: status badge, booking card, alert/flash message |
| Form Validation | Form Requests for every form |
| Custom Rule | `EndAfterStart` rule class |
| Policies + Gates | BookingPolicy, checked in controllers and Blade views |
| Laravel Breeze | Authentication scaffold |
| Mailable + Email | BookingAccepted, BookingRejected |
| Pagination | All index listings use `paginate()` |
| Flash Messages | Success/error messages after every action |
| CSRF Protection | `@csrf` on every form |
| @method spoofing | PUT/PATCH/DELETE forms use `@method()` |
| Eager Loading | All listings with relationships |
| `$fillable` | Defined on every model |
| `$casts` | Dates cast to datetime, booleans cast on relevant models |
| Route Groups + Prefixes | Admin routes under `/admin`, responsable under `/responsable` |
| Named Routes | All routes named, all redirects use `route()` helper |
| `validated()` | Always use `$request->validated()`, never `$request->all()` |

---

## 7. PROJECT FILE STRUCTURE

```
app/
  Http/
    Controllers/
      BookingController.php          ← enseignant bookings
      RoomPlanningController.php     ← weekly calendar
      Responsable/
        PendingBookingController.php
        PlanningController.php
      Admin/
        DashboardController.php
        RoomController.php
        EquipmentController.php
        UserController.php
    Middleware/
      EnsureRole.php
    Requests/
      StoreBookingRequest.php
      UpdateBookingRequest.php
      StoreRoomRequest.php
      UpdateRoomRequest.php
      StoreEquipmentRequest.php
      UpdateEquipmentRequest.php
      StoreUserRequest.php
      UpdateUserRequest.php
  Mail/
    BookingAccepted.php
    BookingRejected.php
  Models/
    User.php
    Room.php
    Equipment.php
    Booking.php
  Policies/
    BookingPolicy.php
    RoomPolicy.php
    EquipmentPolicy.php
  Rules/
    EndAfterStart.php

resources/views/
  layouts/
    app.blade.php              ← main layout (nav changes per role)
  components/
    status-badge.blade.php
    alert.blade.php
    booking-card.blade.php
    week-nav.blade.php
  bookings/
    index.blade.php
    create.blade.php
    show.blade.php
  planning/
    index.blade.php            ← weekly grid (shared base)
  responsable/
    pending.blade.php
    planning.blade.php
  admin/
    dashboard.blade.php
    rooms/  (index, create, edit)
    equipment/  (index, create, edit)
    users/  (index, create, edit)
  mail/
    booking-accepted.blade.php
    booking-rejected.blade.php
  auth/                        ← generated by Breeze

routes/
  web.php
  auth.php                     ← generated by Breeze

database/
  migrations/
    ..._create_users_table.php
    ..._create_rooms_table.php
    ..._create_equipment_table.php
    ..._create_bookings_table.php
    ..._create_booking_equipment_table.php
  factories/
    UserFactory.php
    RoomFactory.php
    EquipmentFactory.php
    BookingFactory.php
  seeders/
    DatabaseSeeder.php
```

---

## 8. ROUTES OVERVIEW

```php
// Public
Route::get('/', fn() => redirect('/login'));

// Auth (Breeze)
require __DIR__.'/auth.php';

// Authenticated users
Route::middleware('auth')->group(function () {

    // Enseignant
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/rooms/planning', [RoomPlanningController::class, 'index'])->name('rooms.planning');

    // Responsable
    Route::middleware('role:responsable,admin')->prefix('responsable')->name('responsable.')->group(function () {
        Route::get('/pending', [PendingBookingController::class, 'index'])->name('pending');
        Route::post('/bookings/{booking}/accept', [PendingBookingController::class, 'accept'])->name('accept');
        Route::post('/bookings/{booking}/reject', [PendingBookingController::class, 'reject'])->name('reject');
        Route::get('/planning', [PlanningController::class, 'index'])->name('planning');
    });

    // Admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('rooms', RoomController::class);
        Route::resource('equipment', EquipmentController::class);
        Route::resource('users', UserController::class);
    });
});
```

---

## 9. UI/UX GUIDELINES

### Navigation
- Navbar is role-aware: links shown depend on `Auth::user()->role`
- Use `@auth` / `@guest` and `@if(Auth::user()->role === 'admin')` in the layout

### Flash Messages
- Every successful action (create, update, delete, accept, reject, cancel) must redirect with `->with('success', '...')` or `->with('error', '...')`
- The layout renders these via a reusable `<x-alert>` Blade component

### Status Badges
- Use a `<x-status-badge :status="$booking->status">` component
- Color map: `en_attente`=yellow, `acceptee`=green, `refusee`=red, `annulee`=gray

### Weekly Planning Grid
- Table layout: rows = rooms, columns = days of the week (Mon to Sun)
- Each cell shows time slots of accepted/pending bookings as colored blocks
- Tooltip or small text shows enseignant name + purpose on hover
- Unavailable rooms (`is_available = false`) shown with a strikethrough or gray background

### Cancellation Edge Case (30-minute warning)
- If `starts_at` is between `now()` and `now() + 30 minutes`:
  - Show a JavaScript `confirm()` dialog OR a modal warning: "Cette réservation commence dans moins de 30 minutes. Êtes-vous sûr de vouloir l'annuler ?"
  - User can still proceed — this is a UX warning, not a hard block

---

## 10. SEED DATA (for development & demo)

The DatabaseSeeder must create:
- 1 admin user: `admin@roombook.tg` / `password`
- 2 responsable users
- 5 enseignant users
- 5 rooms (varied buildings, capacities from 20 to 100)
- 4 equipment items (Vidéoprojecteur x5, Tableau interactif x3, Micro x10, Laptop x8)
- 10–15 bookings across different statuses (`en_attente`, `acceptee`, `refusee`, `annulee`) to populate the planning view

---

## 11. ENVIRONMENT & SETUP NOTES

- **Laravel version**: 12
- **PHP**: 8.2+
- **Auth scaffold**: Laravel Breeze (Blade stack)
- **CSS**: Bootstrap 5 (via CDN or npm) — keep it simple and functional; this is an academic project not a design portfolio
- **Database**: MySQL 8 or MariaDB
- **Mail driver**: `log` in development (check `storage/logs/laravel.log` for emails), Mailtrap for testing
- **Queue**: synchronous (`QUEUE_CONNECTION=sync`) — no need for Redis/queues in this scope
- **.env keys to set**: `APP_NAME=RoomBook`, `DB_DATABASE=roombook`, `MAIL_MAILER=log`

---

## 12. WHAT AGENTS MUST NOT DO

- Do NOT use raw SQL — always use Eloquent
- Do NOT validate in controllers directly — always use Form Request classes
- Do NOT use `$request->all()` — always use `$request->validated()`
- Do NOT use `findOrFail($id)` in controllers — use Route Model Binding
- Do NOT skip `@csrf` on any form
- Do NOT forget `@method('PUT')` / `@method('DELETE')` on non-POST forms
- Do NOT create features not listed in this spec
- Do NOT forget to register Policies in `AuthServiceProvider`
- Do NOT forget to register the `EnsureRole` middleware in `bootstrap/app.php` (Laravel 12 uses the new application bootstrap format)

---

## 13. LARAVEL 12 SPECIFIC NOTES

In Laravel 12, middleware registration has changed. Instead of `app/Http/Kernel.php` (which no longer exists), middleware is registered in `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\EnsureRole::class,
    ]);
})
```

Policies are still registered in `app/Providers/AuthServiceProvider.php` or via the new `AppServiceProvider` using `Gate::policy()`.

---

*This SKILL.md is the single source of truth for all agents working on RoomBook. Read it fully before writing any code. If something is unclear, re-read the relevant section rather than making assumptions.*

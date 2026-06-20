# Hospital Management System

A Laravel 11 hospital management system covering three staff-facing modules — **Patients** (receptionist), **Doctors/Queue** (doctor / super_admin), and **Lab Tests** (lab_technician) — with role-based access via [Spatie Laravel-Permission](https://spatie.be/docs/laravel-permission), real-time notifications, and printable patient cards / lab request slips.

---

## Stack

| Layer        | Choice                                              |
|--------------|------------------------------------------------------|
| Framework    | Laravel 11 / PHP 8.3                                 |
| Database     | MySQL                                                |
| Auth         | Laravel Breeze (Blade stack)                         |
| Roles        | Spatie Laravel-Permission                            |
| Frontend     | Blade + Bootstrap 5 (via Vite, no Tailwind/React)    |
| Real-time    | Laravel Notifications (`database` + `broadcast`), Pusher/Echo |

---

## 1. Requirements

- PHP **8.2+** (8.3 recommended) with the usual extensions: `mbstring`, `pdo_mysql`, `xml`, `curl`, `bcmath`, `intl`, `zip`
- Composer 2.x
- MySQL 8.x (or MariaDB 10.6+)
- Node.js 18+ and npm (for compiling Bootstrap/Vite assets)
- A free [Pusher](https://pusher.com) account **(optional)** — only needed if you want the live "new patient" / "lab result ready" popups to appear instantly without a page refresh. Without it, the app still works perfectly; the notification bell just polls every 15 seconds instead.

---

## 2. Setup — step by step

```bash
# 1. Unzip the project and move into it
cd hospital-management-system

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies
npm install

# 4. Copy the environment file and generate an app key
cp .env.example .env
php artisan key:generate

# 5. Create the database (adjust credentials for your MySQL setup)
mysql -u root -p -e "CREATE DATABASE hms_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 6. Edit .env — set DB_DATABASE, DB_USERNAME, DB_PASSWORD to match step 5
#    (defaults already point at hms_db / root / no password)
nano .env

# 7. Run migrations AND seed roles + demo users + demo data in one go
php artisan migrate --seed

# 8. Link the public storage disk (only needed if you later add file uploads)
php artisan storage:link

# 9. Build front-end assets
npm run build
#    --- or, while developing, run this in a second terminal instead ---
#    npm run dev

# 10. Serve the app
php artisan serve
```

Visit **http://localhost:8000** and log in with one of the demo accounts below.

> **Note on PHP/Composer/MySQL installation**: this README assumes you already have them installed locally (e.g. via [Laravel Herd](https://herd.laravel.com), [XAMPP](https://www.apachefriends.org/), `apt install php8.3 mysql-server`, or Docker/Sail). If you'd rather not install anything system-wide, `composer require laravel/sail --dev` followed by `./vendor/bin/sail up` will run PHP + MySQL in containers for you — just swap `php artisan` for `./vendor/bin/sail artisan` in the steps above.

---

## 3. Demo login credentials

All seeded passwords are **`password`**.

| Role             | Email                  | What they can do                                              |
|------------------|-------------------------|----------------------------------------------------------------|
| Super Admin      | `admin@hms.test`       | Everything — staff accounts, all patients, all queues, all labs |
| Doctor           | `dr.amara@hms.test`    | Their own queue, full patient records, order lab tests          |
| Doctor           | `dr.kato@hms.test`     | Same as above (a second doctor, to see independent queues)      |
| Lab Technician   | `lab@hms.test`         | Real-time pending lab test list, enter results                  |
| Receptionist     | `frontdesk@hms.test`   | Register patients, manage the daily queue                       |

The seeder also creates ~20 demo patients with appointments and lab tests in various states, so every dashboard has something to show immediately.

> Staff accounts are **not** self-registrable — there is no public sign-up page. New doctors/lab technicians/receptionists are created by a super_admin from **Staff → Add Staff Member**, which keeps the `users.role` column and the Spatie role pivot in sync automatically.

---

## 4. Enabling real-time popups (optional)

By default `BROADCAST_CONNECTION` is left blank-friendly in `.env.example`; the app falls back to polling (`/notifications` every 15s) so it works out of the box. To get instant popups when a receptionist assigns a patient or a lab result completes:

1. Create a free app at [pusher.com](https://pusher.com) (Channels product).
2. Copy your **App ID**, **Key**, **Secret**, and **Cluster** into `.env`:
   ```
   BROADCAST_CONNECTION=pusher
   PUSHER_APP_ID=your-app-id
   PUSHER_APP_KEY=your-key
   PUSHER_APP_SECRET=your-secret
   PUSHER_APP_CLUSTER=your-cluster
   ```
3. Rebuild assets: `npm run build` (or restart `npm run dev`).
4. Make sure a queue worker is running, since notifications implement `ShouldBroadcast` and are dispatched through the queue:
   ```bash
   php artisan queue:work
   ```

---

## 5. Project structure (where to edit things)

```
app/
  Http/Controllers/      One controller per module (Patient, Appointment, LabTest, User/Staff, Dashboard, Notification)
  Http/Requests/         Validation + per-action authorization (FormRequest::authorize())
  Models/                Eloquent models + relationships + scopes
  Observers/              Patient/Appointment/LabTest "creating/created/updated" hooks
                          (patient_number generation, queue_number generation, audit logging, notifications)
  Notifications/         NewPatientAssigned, LabTestRequested, LabTestCompleted
  Policies/               Per-role authorization rules, registered in AppServiceProvider
database/
  migrations/             Schema, in dependency order
  seeders/                RoleSeeder -> UserSeeder -> DemoDataSeeder
  factories/              Used by both the seeder and the test suite
resources/views/
  layouts via components/app-layout.blade.php (authenticated shell + navbar + notification bell)
  patients/, appointments/, lab_tests/, doctors/  one folder per module
  print/                  patient_card.blade.php, lab_request.blade.php (browser-print friendly)
routes/
  web.php                 All UI routes, grouped by role via the `role:` middleware
  api.php                 Small polling endpoints (queue/lab list, unread count) for when Pusher isn't set up
  channels.php            Private channel authorization for the two broadcast channels used above
```

### Adding a new role-restricted page

1. Add the route in `routes/web.php` inside a `Route::middleware('role:...')` group.
2. Add/extend a Policy method if the action needs per-record checks (not just per-role).
3. Add the Blade view under the matching `resources/views/<module>/` folder, extending `<x-app-layout>`.

### Changing the patient number format

Edit `App\Observers\PatientObserver::nextPatientNumber()`. It currently zero-pads to 4 digits (`P-0001`); the regex parsing of the existing max number (`SUBSTRING(patient_number, 3)`) assumes a 2-character prefix like `P-`, so update that offset too if you change the prefix length.

### Changing what resets the queue daily

Edit `App\Observers\AppointmentObserver::nextQueueNumber()`. It scopes `MAX(queue_number)` by `(doctor_id, appointment_date)` — change the scoping columns there (e.g. add a `department_id`) if you need a different reset boundary.

---

## 6. Running tests

```bash
php artisan test
```

Covers: patient number sequencing (including non-reuse after deletion), per-doctor daily queue reset, role-based access (a lab technician can't register patients, a doctor can't edit lab results, a doctor can't touch another doctor's queue), and the lab-test notification flow.

Tests run against an in-memory SQLite database (configured in `phpunit.xml`), so they don't touch your real MySQL data.

---

## 7. Code style

The codebase follows PSR-12. If you have [Laravel Pint](https://laravel.com/docs/pint) installed (it's in `composer.json` as a dev dependency), you can auto-format everything with:

```bash
./vendor/bin/pint
```

---

## 8. Troubleshooting

- **"could not find driver" on migrate** → install `php8.3-mysql` (or your PHP version's MySQL PDO extension) and restart `php artisan serve`.
- **Styles look unstyled / plain HTML** → you forgot `npm run build` (or `npm run dev` isn't running). Vite assets are required even in production.
- **419 Page Expired on every form** → check `SESSION_DOMAIN`/`APP_URL` in `.env` match the URL you're actually visiting.
- **Notifications never pop up live, only on refresh** → that's expected without Pusher configured (see section 4) — the polling fallback in `resources/js/app.js` is what's filling the bell instead.

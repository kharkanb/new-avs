# AGENTS.md

## Cursor Cloud specific instructions

### Product overview

Single Laravel 12 app (AVS / Automation Equipment Inspection Management System): Persian RTL inspection workflow UI, REST API (`/api/*`), and Laravel Breeze + Inertia/React auth pages.

### System prerequisites (not in update script)

PHP **8.2+** with extensions: `mbstring`, `xml`, `curl`, `zip`, `sqlite3`, `mysql`, `bcmath`, `intl`, `gd`. Composer is expected at `~/.local/bin/composer` (or on `PATH`). Node.js 22+ and npm are preinstalled via nvm.

### Dependency refresh (automatic on VM startup)

```bash
composer install
npm install
```

### First-time app setup (manual, once per fresh clone / missing `.env`)

`.env` is gitignored. Use SQLite for local/cloud dev (no MySQL required):

```bash
cp .env.example .env
touch database/database.sqlite
php artisan key:generate
```

**Important:** `.env.example` sets `CACHE_STORE=database` and `QUEUE_CONNECTION=database`, but this repo’s migrations do **not** create `cache` / `jobs` tables. For SQLite dev, set in `.env`:

```
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

Then:

```bash
php artisan migrate
AVS_ADMIN_PASSWORD=password AVS_TECH_PASSWORD=password php artisan db:seed
```

Seeded users: `admin@avs.com` / `tech@avs.com` (passwords from env vars above).

### Running the application

| Goal | Command |
|------|---------|
| Minimal (inspection form + API) | `php artisan serve --host=0.0.0.0 --port=8000` |
| Full dev (server + Vite + queue + logs) | `composer dev` |
| Pre-built assets only | `public/build/` already exists; Vite optional for inspection form |

Key URLs (default `http://127.0.0.1:8000`):

- `/login` — web auth (Breeze/Inertia)
- `/inspections` — main inspection form (requires login)
- `GET /api/ping` — health check
- `POST /api/login` — Sanctum token auth

### Lint and test

| Check | Command |
|-------|---------|
| Tests (51 tests, in-memory SQLite) | `composer test` |
| PHP style (Laravel Pint) | `./vendor/bin/pint --test` |

Pint may report style issues in existing seeders/tests; that is pre-existing and does not block tests.

### Gotchas

- Inspection form loads CDN assets (Bootstrap, jQuery, etc.); browser needs outbound network.
- `composer dev` starts four processes; use tmux for long-running servers in cloud VMs.
- MySQL is documented in `README.md` for production-style setups; SQLite is sufficient for dev and tests.

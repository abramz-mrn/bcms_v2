# BCMS (Billing & Customer Management System) – ISP

## Ringkasan
Stack:
- **Frontend**: Next.js 22 (SSR), TS, Tailwind
- **Backend**: Laravel 12 (PHP 8.3), Sanctum, Octane (RoadRunner), Horizon
- **DB**: PostgreSQL 18
- **Cache/Queue**: Redis 8
- **Proxy**: Nginx 1.28 (TLS-ready)
- **Storage**: Local (dev), S3-compatible (prod)
- **Container**: Docker/Compose; CI GitHub Actions

## Arsitektur (tingkat tinggi)
- nginx: reverse proxy, TLS termination, route `/` → web, `/api` → api
- web: Next.js SSR admin console
- api: Laravel API (Sanctum) + Octane (RoadRunner)
- horizon: monitor queue
- scheduler: cron `php artisan schedule:run`
- db: PostgreSQL 18
- redis: cache/queue/rate-limit
- optional: minio/S3 for prod artifacts
- observability (opsional): ELK / Prometheus+Grafana

## Cara cepat menjalankan (dev)
1. Prasyarat: Docker + Docker Compose v2, make, git.
2. Salin `.env.example` → `.env` di `apps/api` dan `apps/web`; sesuaikan variabel.
3. Jalankan:
   ```bash
   make install   # composer install + npm install
   make up-dev    # docker compose -f infra/docker/compose/docker-compose.yml -f infra/docker/compose/docker-compose.dev.yml up -d
   make migrate
   make seed
   ```
4. Akses: Web `http://localhost:3000`, API `http://localhost:8000/api/health`.

## Build/Prod (ringkas)
```bash
make up-prod  # compose base + prod overrides
```
Pastikan isi env prod (DB, REDIS, S3, MIDTRANS, XENDIT, SMTP, SMS, WA, ROUTEROS TLS/SSH).

## Env penting (API)
- DB_* (Postgres)
- REDIS_*
- APP_URL, SANCTUM_STATEFUL_DOMAINS
- OCTANE_SERVER=roadrunner
- QUEUE_CONNECTION=redis, HORIZON_PREFIX
- MAIL_*, SMS_* (gateway), WA_* (WA Business API)
- MIDTRANS_*, XENDIT_*
- S3_* atau MINIO_*
- ROUTEROS_API_HOST/PORT/USER/PASS/CERT, ROUTEROS_SSH_*

## Modul & Rute Frontend (scaffold)
- Dashboard, Customers, Subscriptions, Provisionings (ping test placeholder)
- Billing (Invoices/Payments), Products/Internet Services, Promotions
- Users, Groups, Companies, Brands, Routers
- Tickets, Audit Logs, Templates/Reminders

## Job otomatis (stub sudah disiapkan)
- Invoice generator (per billing cycle)
- Reminder engine (H-7/H-3/H-1/H+1/pre-soft-limit/pre-suspend)
- Auto soft-limit / suspend / reactivate
- Provisioning jobs (idempotent + retry/backoff)
- Payment webhooks (Midtrans/Xendit)
- Notifications (Email/SMS/WhatsApp)

## Instalasi Ubuntu (singkat)
1. Update: `apt update && apt upgrade -y`
2. Pasang deps: `apt install -y ca-certificates curl git make docker.io docker-compose-plugin`
3. Tambah user ke grup docker: `usermod -aG docker $USER`
4. Clone repo: `git clone https://github.com/a3ramz-code/bcms_v2.git`
5. Lanjutkan langkah “Cara cepat menjalankan”.

## Keamanan
- Sanctum (SPA/token)
- RBAC granular (users_groups.permissions JSON)
- HTTPS wajib di prod; Let’s Encrypt di nginx (sertakan cert/key path)
- Rate limit middleware
- Audit log middleware
- Patch rutin (composer/npm update)

## Monitoring & Backup (opsional)
- Horizon (queue)
- ELK / Prometheus + Grafana
- Backup DB + storage dengan retention (cron eksternal)

## CI
- Backend: lint (Pint), static analysis (PHPStan stub), test (PHPUnit placeholder)
- Frontend: lint + build
- Docker build (api, web)

## Langkah verifikasi awal
- `make up-dev`
- `make migrate && make seed`
- Hit `GET /api/health` → ok
- Buka dashboard Next.js

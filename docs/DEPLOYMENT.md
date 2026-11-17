# Deployment (Coolify / Docker Compose)

## File terkait

- `Dockerfile` — multi-stage: Vite build (Node 22) → Composer (`--no-dev`) → `serversideup/php:8.5-fpm-nginx`.
- `docker-compose.yaml` — service `app`, `worker` (queue), dan `db` (PostgreSQL 17).
- `.dockerignore` — mengecualikan `vendor`, `node_modules`, `.env`, cache build, dan `bootstrap/cache/*.php`.

Port internal container `app` adalah **8080**. Di Coolify, arahkan domain ke port tersebut.

## Environment wajib

| Variabel | Keterangan |
|---|---|
| `APP_KEY` | Hasil `php artisan key:generate --show`; simpan sebagai secret |
| `APP_URL` | `https://<domain>` |
| `DB_CONNECTION=pgsql` | `DB_HOST=db`, `DB_PORT=5432` |
| `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | `DB_PASSWORD` wajib karena dipakai interpolasi `docker-compose.yaml` |
| `MAIL_*` | Kredensial SMTP Brevo |
| `ML_SERVICE_URL` dan `ML_SERVICE_*` | URL layanan deteksi duplikasi (belum disertakan) |
| `VISION_SERVICE_KEY` | API key OpenAI untuk analisis screenshot |

`SESSION_DRIVER`, `CACHE_STORE`, dan `QUEUE_CONNECTION` memakai `database` (Postgres). Tidak ada Redis.

## Langkah deploy

1. Coolify → New Resource → Docker Compose → repo `AgilePemdigi-2026/TwinBug`, branch `main`, compose path `docker-compose.yaml`.
2. Isi environment di UI Coolify (mark `APP_KEY`, `DB_PASSWORD`, `MAIL_PASSWORD`, `VISION_SERVICE_KEY` sebagai secret).
3. Set domain pada service `app`, port `8080`, aktifkan HTTPS.
4. Deploy. Container `app` otomatis menjalankan `storage:link`, `migrate --force`, dan `optimize` (via `AUTORUN_ENABLED=true`); worker tidak.
5. Verifikasi: `GET /up` = 200, login/registrasi, upload screenshot dapat diakses di `/storage/screenshots/...`, dan log worker memproses job.

## Ditunda / belum disertakan

- **ML service** tidak ada di repo ini dan belum di-deploy. Selama `ML_SERVICE_URL` tidak terjangkau, setiap job deteksi gagal setelah 3 retry (status `Failed`). Fitur lain tetap berjalan.
- **Redis** tidak dipakai; cache, session, dan queue memakai Postgres.
- **Scheduler** belum dijalankan karena belum ada scheduled task di `routes/console.php`.
- **Multi-replica app** belum didukung: `AUTORUN_LARAVEL_MIGRATION` berjalan di setiap boot sehingga replica bisa saling balapan migrasi.
- **CI masih PHP 8.3**, sedangkan image produksi PHP 8.5. Keduanya memenuhi `composer.json`.
- **Backup** volume `db_data` dan `screenshots` belum diatur; perlu backup Coolify atau `pg_dump` terjadwal.
- **Rotasi kredensial**: password SMTP Brevo di `.env` lokal harus dianggap terekspos dan perlu diganti.
- Mengganti `APP_KEY` setelah deploy akan membatalkan session dan data terenkripsi.

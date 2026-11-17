# Deployment (Coolify / Docker Compose)

## File terkait

- `Dockerfile` — multi-stage: Vite build (Node 22) → Composer (`--no-dev`) → `serversideup/php:8.5-fpm-nginx`.
- `docker-compose.yaml` — service `app` dan `worker` (queue), database SQLite.
- `.dockerignore` — mengecualikan `vendor`, `node_modules`, `.env`, cache build, dan `bootstrap/cache/*.php`.

Port internal container `app` adalah **8080**. Di Coolify, arahkan domain ke port tersebut.

## Data persisten

| Volume | Isi |
|---|---|
| `sqlite_data` | `/data/database.sqlite` (database utama) |
| `screenshots` | `/var/www/html/storage/app/public` (lampiran screenshot) |

File SQLite sudah dibuat di image (`/data/database.sqlite`) dan di-mount ke volume, jadi migrasi tidak menimpa folder `database/migrations`.

## Environment wajib

| Variabel | Keterangan |
|---|---|
| `APP_KEY` | Hasil `php artisan key:generate --show`; simpan sebagai secret |
| `APP_URL` | `https://<domain>` |
| `DB_CONNECTION=sqlite` | Dikunci juga di `docker-compose.yaml` |
| `DB_DATABASE=/data/database.sqlite` | Dikunci juga di `docker-compose.yaml` |
| `MAIL_*` | Kredensial SMTP Brevo |
| `ML_SERVICE_URL` dan `ML_SERVICE_*` | URL layanan deteksi duplikasi (belum disertakan) |
| `VISION_SERVICE_KEY` | API key OpenAI untuk analisis screenshot |

`DB_HOST`, `DB_PORT`, `DB_USERNAME`, dan `DB_PASSWORD` **tidak dipakai**; hapus dari Coolify kalau masih ada. `SESSION_DRIVER`, `CACHE_STORE`, dan `QUEUE_CONNECTION` memakai `database` (SQLite). Tidak ada Redis.

SQLite memakai `journal_mode=wal` dan `busy_timeout=5000` agar web dan worker bisa menulis bergantian tanpa langsung `database is locked`.

## Langkah deploy

1. Coolify → New Resource → Docker Compose → repo `AgilePemdigi-2026/TwinBug`, branch `main`, compose path `docker-compose.yaml`.
2. Isi environment di UI Coolify (mark `APP_KEY`, `MAIL_PASSWORD`, `VISION_SERVICE_KEY` sebagai secret).
3. Set domain pada service `app`, port `8080`, aktifkan HTTPS.
4. Deploy. Container `app` otomatis menjalankan `storage:link`, `migrate --force`, dan `optimize` (via `AUTORUN_ENABLED=true`); worker tidak.
5. Verifikasi: `GET /up` = 200, login/registrasi, upload screenshot dapat diakses di `/storage/screenshots/...`, dan log worker memproses job.

## Ditunda / belum disertakan

- **Tidak ada container `db`** — ini by design, bukan error. Database utama adalah file SQLite (`/data/database.sqlite`) di volume `sqlite_data` yang di-share ke `app` dan `worker`.
- **Status worker** — compose memasang healthcheck proses `queue:work`; worker berhenti sendiri tiap ~1 jam (`--max-time=3600`) lalu di-restart, jadi status bisa sesaat tidak healthy.
- **ML service** tidak ada di repo ini dan belum di-deploy. Selama `ML_SERVICE_URL` tidak terjangkau, setiap job deteksi gagal setelah 3 retry (status `Failed`). Fitur lain tetap berjalan.
- **Redis** tidak dipakai; cache, session, dan queue memakai SQLite.
- **Scheduler** belum dijalankan karena belum ada scheduled task di `routes/console.php`.
- **Multi-replica app** belum didukung: SQLite hanya punya satu penulis, dan autorun migrasi akan berjalan di setiap replica.
- **Data lama** di Postgres (volume `db_data` dari deploy sebelumnya) tidak dimigrasikan; SQLite mulai dari database kosong.
- **CI masih PHP 8.3**, sedangkan image produksi PHP 8.5. Keduanya memenuhi `composer.json`.
- **Backup** volume `sqlite_data` dan `screenshots` belum diatur; perlu backup Coolify atau salin file `.sqlite` secara terjadwal.
- **Rotasi kredensial**: password SMTP Brevo di `.env` lokal harus dianggap terekspos dan perlu diganti.
- Mengganti `APP_KEY` setelah deploy akan membatalkan session dan data terenkripsi.

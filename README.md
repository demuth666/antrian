# Antrian — Kerangka Awal Laravel + Docker

Kerangka aplikasi Laravel 13 yang siap dijalankan dengan Docker Compose.

## Stack

| Komponen | Versi |
|---|---|
| PHP | 8.4 FPM (Alpine) |
| Laravel | 13.x |
| Nginx | latest (alpine) |
| PostgreSQL | 17 (alpine) |
| Redis | 7 (alpine) |
| Node.js | 24 (di dalam container `app`) |
| Vite / Tailwind CSS | 8 / 4 |

Ekstensi PHP yang terpasang: `pdo_pgsql`, `mbstring`, `gd`, `imagick`, `intl`, `zip`, `bcmath`, `exif`, `pcntl`, `opcache`, dan `redis` (phpredis).

## Struktur Folder

```
antrian/
├── docker/
│   ├── nginx/default.conf      # Konfigurasi virtual host Laravel
│   └── php/
│       ├── Dockerfile          # Image PHP-FPM + Composer + Node
│       ├── entrypoint.sh       # Menyiapkan permission storage saat start
│       └── local.ini           # Konfigurasi php.ini
├── src/                        # Aplikasi Laravel 13
├── docker-compose.yml
├── .env                        # Variabel untuk Docker Compose
└── README.md
```

## Menjalankan

Prasyarat: Docker Desktop / Docker Engine + Docker Compose.

```bash
docker compose up -d
```

Buka **http://localhost:8081**.

Jika memulai dari clone baru (tanpa folder `vendor`, `.env`, dan `node_modules`):

```bash
copy .env.example .env                      # Windows
# cp .env.example .env                      # Linux/macOS

docker compose build
docker compose up -d
docker compose exec app composer install
docker compose exec app cp .env.example .env
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed --force
docker compose exec app sh -c "npm install && npm run build"
```

## Layanan & Port

| Layanan | Host | Keterangan |
|---|---|---|
| Aplikasi (Nginx) | http://localhost:8081 | Ubah `APP_PORT` di `.env` |
| PostgreSQL | `127.0.0.1:5433` | db `antrian`, user `antrian`, pass `secret` |
| Redis | internal | hostname `redis:6379`, tidak diekspos ke host |
| Vite HMR | http://localhost:5173 | hanya saat service `vite` dijalankan |

Kredensial PostgreSQL untuk development ada di `.env` (variabel Docker Compose) dan `src/.env` (koneksi Laravel). Jika salah satu diubah, samakan keduanya.

Akun admin awal dibuat oleh seeder dari `ADMIN_NAME`, `ADMIN_EMAIL`, dan `ADMIN_PASSWORD` di `src/.env`. Jika email atau password kosong, seeder melewati pembuatan akun admin.

## Perintah Harian

```bash
# Artisan
docker compose exec app php artisan migrate
docker compose exec app php artisan make:model Antrian -m
docker compose exec app php artisan tinker
docker compose exec app php artisan optimize:clear

# Composer
docker compose exec app composer require nama/package

# NPM
docker compose exec app npm install
docker compose exec app npm run build

# Test
docker compose exec app php artisan test

# Shell container
docker compose exec app sh
docker compose exec db psql -U antrian -d antrian

# Log
docker compose logs -f app
docker compose logs -f nginx
```

### Hot reload asset (Vite)

Jalankan service Vite di terminal terpisah:

```bash
docker compose --profile vite up vite
```

Halaman tetap diakses lewat `http://localhost:8081`, aset dev diambil dari port `5173`.

### Queue Worker

```bash
docker compose exec app php artisan queue:work
```

Konfigurasi default: `QUEUE_CONNECTION=database`, `CACHE_STORE=redis`, `SESSION_DRIVER=database`.

## Berhenti & Reset

```bash
docker compose down            # hentikan container
docker compose down -v         # hentikan + hapus volume PostgreSQL & Redis (data hilang)
docker compose build --no-cache app   # rebuild image PHP
```

## Troubleshooting

- **Beralih dari MySQL** — PostgreSQL memakai volume baru `pg-data`, sehingga data lama di volume MySQL (`antrian_db-data`) tetap tersimpan tetapi tidak otomatis dipindahkan. Skema dibuat ulang lewat `php artisan migrate --seed --force`; pindahkan data lama secara manual bila diperlukan.
- **Port bentrok** — ubah `APP_PORT` atau `DB_PORT` di `.env`, lalu `docker compose up -d`.
- **Perubahan `src/.env` tidak terbaca** — jalankan `docker compose exec app php artisan optimize:clear`.
- **Permission storage di Linux** — pastikan folder `src/storage` dan `src/bootstrap/cache` dapat ditulis; entrypoint sudah mencoba `chown` otomatis.
- **Setelah ganti port** — sesuaikan juga `APP_URL` di `src/.env` agar URL aset benar.

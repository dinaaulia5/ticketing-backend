# Deploy ke Railway

Project ini sudah dilengkapi `Dockerfile`, `docker/entrypoint.sh`, dan
`railway.json` sehingga Railway akan otomatis build & jalankan lewat Docker
(bukan Nixpacks) — proses build assets Vite dan install dependency PHP
sudah dihandle di dalam Dockerfile.

## 1. Push project ini ke GitHub

Railway deploy paling gampang dari repo GitHub. Buat repo baru, push isi
folder ini ke sana.

## 2. Buat Project di Railway

1. Buka https://railway.app → **New Project** → **Deploy from GitHub repo**
   → pilih repo ini.
2. Railway akan mendeteksi `Dockerfile` secara otomatis (dikonfirmasi lewat
   `railway.json`).

## 3. Tambahkan Database (PostgreSQL)

1. Di project yang sama, klik **+ New** → **Database** → **Add PostgreSQL**.
2. Railway otomatis membuat variable `DATABASE_URL` di service Postgres.

## 4. Set Environment Variables di service aplikasi (Laravel)

Buka service aplikasi → tab **Variables** → tambahkan:

| Variable | Value |
|---|---|
| `APP_NAME` | `Travel Agentic` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_URL` | `https://<domain-railway-kamu>.up.railway.app` |
| `APP_KEY` | *(kosongkan — akan di-generate otomatis saat container pertama kali start, lihat catatan di bawah)* |
| `DB_CONNECTION` | `pgsql` |
| `DB_URL` | `${{Postgres.DATABASE_URL}}` *(pakai Railway variable reference ke service Postgres)* |
| `SESSION_DRIVER` | `database` |
| `CACHE_STORE` | `database` |
| `QUEUE_CONNECTION` | `database` |
| `BROADCAST_CONNECTION` | `log` |
| `LOG_CHANNEL` | `stderr` |

> **Catatan APP_KEY:** `docker/entrypoint.sh` akan otomatis menjalankan
> `php artisan key:generate` kalau `APP_KEY` kosong saat container start.
> Tapi karena Railway bisa restart container kapan saja, lebih aman generate
> sekali di lokal lalu simpan sebagai variable tetap:
> ```bash
> php artisan key:generate --show
> ```
> lalu paste hasilnya (`base64:...`) sebagai value `APP_KEY` di Railway.

## 5. Set Networking

Di tab **Settings** service aplikasi → **Networking** → klik **Generate
Domain**. Railway otomatis inject variable `PORT`, dan `entrypoint.sh`
sudah membaca `$PORT` untuk menjalankan server di port yang benar — tidak
perlu diatur manual.

## 6. Deploy

Railway otomatis build & deploy setiap kali ada push ke branch utama.
Build pertama akan:

1. `npm ci && npm run build` (compile Vue + Tailwind ke `public/build`)
2. `composer install --no-dev` (install dependency Laravel)
3. Saat container start: `php artisan migrate --force` (auto migrate DB),
   cache config/route/view, lalu jalankan server di `0.0.0.0:$PORT`.

## 7. (Opsional) Seed data demo

Kalau ingin ada data contoh (airlines, airports, flights, bookings) saat
pertama kali online, jalankan sekali lewat Railway CLI:

```bash
railway run php artisan db:seed
```

## Catatan tentang Laravel Reverb (WebSocket / real-time)

Project ini punya `laravel/reverb` untuk notifikasi real-time. Reverb butuh
proses long-running terpisah dari HTTP server. Untuk deploy dasar (dashboard
+ CRUD + auth), fitur ini **tidak wajib** — `BROADCAST_CONNECTION=log` di atas
sudah menonaktifkannya dengan aman (tidak ada error).

Kalau nanti butuh real-time notification:
1. Buat **service Railway kedua** dari repo yang sama.
2. Set **Start Command** service itu ke: `php artisan reverb:start --host=0.0.0.0 --port=$PORT`
3. Set `BROADCAST_CONNECTION=reverb` di service utama, lalu isi
   `REVERB_APP_ID`, `REVERB_APP_KEY`, `REVERB_APP_SECRET`, `REVERB_HOST`
   (domain service Reverb tadi) di kedua service.

## Troubleshooting

- **500 error setelah deploy** → cek `railway logs`, biasanya karena
  `APP_KEY` belum di-set atau `DB_URL` salah format.
- **Assets (CSS/JS) tidak muncul** → pastikan tahap build Docker (`npm run
  build`) selesai tanpa error di build logs Railway.
- **Data hilang tiap deploy** → jangan pakai SQLite di Railway (disk
  ephemeral). Pastikan `DB_CONNECTION=pgsql` sudah terpasang ke Postgres
  plugin, bukan default `sqlite`.

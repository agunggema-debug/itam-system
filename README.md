# 🖥️ ITAM System - IT Asset Management

**Sistem Manajemen Aset TI** berbasis **Laravel 13 + Livewire 4 + PWA** untuk mengelola seluruh siklus hidup aset TI perusahaan (perangkat keras, perangkat lunak, lisensi, dan jaringan).

---

## ✨ Fitur Utama

### 📦 Manajemen Inventaris & Siklus Hidup
- Pencatatan aset lengkap (Kode Aset, Nama, Kategori, Serial Number, Spesifikasi, Lokasi, Status)
- Pelacakan status siklus hidup: `Available`, `Assigned`, `Under Repair`, `Broken`, `Disposed`
- Riwayat kepemilikan otomatis setiap kali aset berpindah tangan

### 📱 Stock Opname via HP (PWA Mobile)
- **QR Code Scanner** menggunakan kamera HP dengan library `Html5-QRCode`
- **Beep sound** notifikasi audio saat QR Code berhasil diverifikasi
- **Feedback visual** instan — layar hijau untuk sukses, merah untuk gagal
- Mode scan berkelanjutan tanpa jeda

### 🔍 Dashboard Admin (PC View)
- **Datatable** dengan server-side pagination
- **Live Search** dengan debounce 300ms pada `asset_code`, `serial_number`, dan `name`
- Filter multi-status (Available, Assigned, Under Repair, Broken, Disposed)
- Statistik real-time (Total Aset, Scan Hari Ini, dll)

### 🔐 Role-Based Access Control (RBAC)
| Role | Akses |
|------|-------|
| **Manager** | Dashboard, Manajemen Aset, Scanner |
| **Staff Gudang** | Scanner (Stock Opname) |
| **Karyawan** | Dashboard (Read Only) |

### ⚡ API Cepat untuk Mobile
- `POST /api/v1/scan-asset` — Response < 1ms
- Log aktivitas di-dispatch via **Laravel Job** (async, non-blocking)
- Update lokasi & status aset secara real-time

---

## 🛠️ Teknologi

| Bagian | Teknologi |
|--------|-----------|
| **Backend** | Laravel 13 (PHP 8.4+) |
| **Frontend** | Livewire 4 + Tailwind CSS |
| **Mobile** | PWA (Service Worker + Web Manifest) |
| **Database** | SQLite (dev), PostgreSQL/MySQL (production) |
| **QR Scanner** | Html5-QRCode (client-side) |
| **Search** | Livewire debounce + server-side pagination |
| **Queue** | Laravel Database Queue (async logging) |

---

## 📋 Prasyarat

- PHP 8.3+
- Composer 2.x
- Node.js 18+ (untuk Vite/build asset)
- Database: SQLite, PostgreSQL, atau MySQL

---

## 🚀 Instalasi & Menjalankan

### 1. Clone & Install Dependencies

```bash
git clone https://github.com/username/itam-system.git
cd itam-system
composer install
npm install
```

### 2. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan konfigurasi database di `.env`:
```env
# SQLite (development)
DB_CONNECTION=sqlite

# atau PostgreSQL (production)
# DB_CONNECTION=pgsql
# DB_HOST=127.0.0.1
# DB_PORT=5432
# DB_DATABASE=itam
# DB_USERNAME=postgres
# DB_PASSWORD=secret
```

### 3. Migrasi & Seeder

```bash
php artisan migrate --seed
```

Perintah ini akan membuat:
- 3 user demo (password: `password`)
  - `manager@itam.test` — IT Manager
  - `staff@itam.test` — Staff Gudang
  - `karyawan@itam.test` — Karyawan
- 50 data aset sample

### 4. Jalankan Aplikasi

```bash
php artisan serve
```

Buka browser: **http://127.0.0.1:8000**

### 5. Build Asset (Vite)

```bash
npm run build
```

---

## 🌐 Akses dari Perangkat Lain

### Jaringan Lokal (WiFi Sama)
```bash
php artisan serve --host=0.0.0.0 --port=8000
```
Akses dari HP: `http://[IP_KOMPUTER]:8000` (cek IP dengan `ipconfig`)

### Via Internet (Ngrok)
```bash
ngrok http http://localhost:8000
```
Ngrok akan memberikan URL publik.

---

## 📁 Struktur Proyek

```
itam/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   └── AssetScanController.php    # API Scanner
│   │   │   └── Auth/
│   │   │       └── AuthController.php          # Login/Logout
│   │   └── Middleware/
│   │       └── CheckRole.php                   # RBAC Middleware
│   ├── Jobs/
│   │   └── LogAssetActivity.php                # Async Logging
│   └── Livewire/
│       ├── Admin/
│       │   ├── AssetList.php                   # Datatable Aset
│       │   └── Dashboard.php                   # Dashboard
│       └── Gudang/
│           └── Scanner.php                     # QR Scanner
├── database/
│   ├── migrations/                             # Migrasi Database
│   └── seeders/
│       └── DatabaseSeeder.php                  # Seeder Data
├── public/
│   ├── manifest.json                           # PWA Manifest
│   ├── service-worker.js                       # Service Worker
│   └── icons/                                  # PWA Icons
├── resources/
│   └── views/
│       ├── auth/
│       │   └── login.blade.php                 # Halaman Login
│       ├── layouts/
│       │   ├── app.blade.php                   # Layout Admin
│       │   └── scanner.blade.php               # Layout Scanner
│       └── livewire/
│           ├── admin/
│           │   ├── asset-list.blade.php
│           │   └── dashboard.blade.php
│           └── gudang/
│               └── scanner.blade.php
└── routes/
    ├── api.php                                 # API Routes
    └── web.php                                 # Web Routes
```

---

## 🔌 API Documentation

### Scan Asset
**Endpoint:** `POST /api/v1/scan-asset`

**Request:**
```json
{
    "asset_code": "ITAM-0001",
    "current_location": "Gudang B"
}
```

**Response (200):**
```json
{
    "status": "success",
    "asset_name": "Acer Desktop 4932",
    "asset_code": "ITAM-0001",
    "message": "Verified"
}
```

---

## 📦 Deploy ke Production

### Opsi 1: Railway.app (Rekomendasi)
1. Push kode ke GitHub
2. Login https://railway.app → New Project → Deploy from GitHub
3. Tambah PostgreSQL database
4. Set environment variables:
   - `APP_KEY` (generate dengan `php artisan key:generate --show`)
   - `APP_ENV=production`
   - `APP_DEBUG=false`
5. Railway akan otomatis build & deploy

### Opsi 2: InfinityFree (Hosting PHP Gratis)
1. Upload file via FTP
2. Restruktur folder (`public/` sebagai root, `laravel_core/` di luar)
3. Buat database MySQL via panel
4. Migrasi via phpMyAdmin atau script setup

---

## 🧪 Akun Demo

| Email | Password | Role |
|-------|----------|------|
| manager@itam.test | password | Manager (Full Access) |
| staff@itam.test | password | Staff Gudang (Scanner) |
| karyawan@itam.test | password | Karyawan (Read Only) |

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan pembelajaran dan manajemen aset TI internal perusahaan.

---

## 🤝 Kontribusi

1. Fork repository
2. Buat branch fitur (`git checkout -b fitur-keren`)
3. Commit perubahan (`git commit -m 'Tambah fitur keren'`)
4. Push ke branch (`git push origin fitur-keren`)
5. Buat Pull Request

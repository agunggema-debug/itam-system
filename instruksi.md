# INSTRUKSI IMPLEMENTASI KODE (AI Assistant Guide)
## Proyek: Sistem Manajemen Aset TI (ITAM System) - Laravel 13 PWA

Dokumen ini berfungsi sebagai instruksi langkah-demi-langkah bagi AI Code Assistant (seperti Cursor, GitHub Copilot, atau ChatGPT) untuk mengimplementasikan sistem ITAM berdasarkan spesifikasi **PRD_ITAM_System-v2.md**.

---

## 🤖 Peran & Konteks AI
Anda adalah seorang Senior Backend Developer dan Expert Architect Laravel 13. Tugas Anda adalah menulis kode yang bersih, aman, berperforma tinggi, dan siap pakai (*production-ready*). 

### Aturan Umum Penulisan Kode:
1. **Laravel 13 Standards:** Gunakan fitur terbaru PHP 8.3+ (Typed properties, readonly classes jika diperlukan, dan PHP Attributes untuk konfigurasi model/routing).
2. **Kepatuhan Performa:** Pastikan semua query database dioptimalkan menggunakan indeks. Hindari masalah N+1 Query dengan memanfaatkan *eager loading* (`with()`).
3. **Pemisahan Kendali:** Logika bisnis berat harus berada di Service Layer atau Jobs (Queue), bukan di Controller atau Livewire Component.

---

## 📋 Langkah Implementasi (Step-by-Step)

### Langkah 1: Setup Lingkungan & Arsitektur Database
Buat migrasi database dengan indeks yang ketat pada kolom pencarian utama.

* **Task 1.1: Migration Table `users` & Roles**
    * Tambahkan kolom `role` (enum: `manager`, `staff_gudang`, `karyawan`).
* **Task 1.2: Migration Table `assets`**
    * Kolom: `id` (UUID/BigInt), `asset_code` (string, unique, index), `name` (string), `category` (enum/string), `serial_number` (string, unique, index), `specification` (text), `location` (string), `status` (enum, index), timestamps.
* **Task 1.3: Migration Table `asset_assignments`**
    * Kolom: `id`, `asset_id` (foreignId, index), `user_id` (foreignId, index), `assigned_at` (datetime), `returned_at` (datetime, nullable).
* **Task 1.4: Migration Table `asset_logs`**
    * Kolom: `id`, `asset_id` (foreignId, index), `user_id` (foreignId, causer), `action` (string), `description` (text), timestamps.

*Perintah untuk AI:* "Buat file migrasi Laravel sesuai dengan spesifikasi kolom di atas, pastikan menambahkan `$table->index()` pada kolom yang ditandai."

---

### Langkah 2: Setup Model dengan Fitur Laravel 13
Gunakan struktur model modern. Manfaatkan *Mass Assignment Protection* dan relasi yang tepat.

* **Task 2.1: Model `Asset`**
    * Relasi: `hasMany(AssetAssignment::class)`, `hasMany(AssetLog::class)`.
    * Gunakan fitur caching otomatis jika ada data statis.
* **Task 2.2: Model `AssetAssignment` & `AssetLog`**
    * Relasi ke `Asset` dan `User` (`belongsTo`).

---

### Langkah 3: Implementasi API Endpoint Ringan untuk Mobile Scan
Buat API khusus yang dikonsumsi oleh modul PWA di HP petugas gudang. API harus merespons dalam waktu < 200ms.

* **Task 3.1: Controller `Api/AssetScanController.php`**
    * Endpoint: `POST /api/v1/scan-asset`
    * Request Payload: `{ "asset_code": "XYZ-123", "current_location": "Gudang B" }`
    * **Logika Bisnis:**
        1. Cari aset menggunakan `Asset::where('asset_code', $request->asset_code)->firstOrFail();` (Memanfaatkan database index).
        2. Update lokasi aset dan status jika ada perubahan.
        3. Dispatch **Laravel Job** (`LogAssetActivity`) ke dalam antrean (Queue Redis/Database) untuk mencatat aktivitas secara *asynchronous* tanpa memblokir response API.
        4. Kembalikan JSON response super ringan: `{ "status": "success", "asset_name": "...", "message": "Verified" }`.

---

### Langkah 4: Setup PWA & Frontend Pemindai (Mobile View)
Fokus pada antarmuka HP untuk petugas gudang menggunakan Livewire atau Blade + Alpine.js.

* **Task 4.1: Integrasi Web Manifest & Service Worker**
    * Buat file `manifest.json` agar aplikasi dapat di-install di Android/iOS.
    * Setup Service Worker dasar untuk melakukan *caching* file CSS, JS, dan file audio "beep".
* **Task 4.2: Komponen Pemindai (`resources/views/livewire/gudang/scanner.blade.php`)**
    * Integrasikan library **Html5-QRCode** (via CDN atau NPM lokal).
    * Pastikan kamera otomatis aktif saat halaman dibuka.
    * Saat kode QR berhasil di-decode oleh Javascript di sisi klien:
        1. Jalankan `fetch('/api/v1/scan-asset', ...)` via Javascript.
        2. Jika response sukses, putar file audio `beep.mp3` menggunakan Web Audio API.
        3. Tampilkan alert sukses warna hijau sekilas (flash), lalu langsung buka kembali scanner untuk aset berikutnya dalam 1 detik.

---

### Langkah 5: Dashboard Admin & Server-Side Pagination (PC View)
Fokus pada pengelolaan data massal untuk Admin di layar PC.

* **Task 5.1: Livewire Datatable Aset**
    * Implementasikan pagination server-side bawaan Livewire (`WithPagination`).
    * Tambahkan fitur *Live Search* pada kolom `asset_code` dan `serial_number`.
    * Gunakan teknik debounce (`wire:model.live.debounce.300ms`) pada input pencarian untuk mencegah *over-querying* ke database.

---

## 🚨 Tolok Ukur Validasi Kode (Definition of Done)
Sebelum menyerahkan kode, pastikan AI memeriksa hal berikut:
1. **No N+1 Queries:** Tidak ada perulangan query di dalam view atau loop controller.
2. **Fast Response:** Endpoint `/api/v1/scan-asset` tidak boleh memproses penulisan log secara sinkron, wajib menggunakan `dispatch(new LogAssetActivity(...))`.
3. **Responsiveness:** Tampilan scanner harus berukuran penuh di layar HP dan mudah ditekan dengan satu jempol.

# PRODUCT REQUIREMENT DOCUMENT (PRD) - V2
## Sistem Manajemen Aset TI (ITAM System)

---

## 1. Ringkasan Proyek (Project Overview)
* **Nama Sistem:** ITAM (IT Asset Management) System
* **Tujuan:** Mengelola seluruh siklus hidup aset TI perusahaan (perangkat keras, perangkat lunak, lisensi, dan jaringan) mulai dari pengadaan, penugasan (*onboarding/offboarding*), audit (*stock opname*), hingga penghapusan (*disposal*).
* **Fokus Utama:** Akses data yang cepat (performa tinggi), antarmuka yang responsif dengan pendekatan **Hybrid PWA**, dan kemudahan pelacakan riwayat aset secara *real-time*.

---

## 2. Arsitektur & Teknologi Rekomendasi
Untuk memenuhi kebutuhan **akses cepat** di PC dan HP serta kemudahan integrasi, arsitektur berikut direkomendasikan:
* **Backend:** **Laravel 13 (PHP 8.3+)** — Memanfaatkan kestabilan tinggi, optimasi *caching* bawaan (`Cache::touch()`), dan ketiadaan *breaking changes* untuk performa API yang efisien.
* **Frontend:** **Livewire 3** atau **Inertia.js (Vue 3 / React)** — Terintegrasi langsung dalam ekosistem Laravel untuk memberikan pengalaman *Single Page Application* (SPA) tanpa *reload* halaman.
* **Mobile & Hybrid Approach:** **PWA (Progressive Web App)**
    * Menggunakan *Service Workers* untuk *caching* aset statis secara agresif di HP petugas gudang.
    * Aplikasi dapat diinstal langsung di HP (Android/iOS) dari *browser* tanpa melalui Play Store/App Store, menghemat *resource* memori ponsel.
* **Database:** PostgreSQL (dengan indeks penuh pada `asset_code`, `serial_number`, dan `status`).
* **Penyimpanan Dokumen:** Cloud Storage (S3/MinIO) — agar database utama tetap ringan.

---

## 3. Strategi Modul Hybrid & Akses Multi-Perangkat

Sistem dikonfigurasi menggunakan pendekatan satu basis kode (*Single Codebase*) yang responsif, namun memisahkan prioritas antarmuka berdasarkan perangkat:

### A. Antarmuka Admin (Prioritas PC / Laptop)
* **Karakteristik:** Padat informasi, membutuhkan layar lebar untuk input data massal, manajemen vendor, dan pelaporan keuangan.
* **Fitur Utama:** *Datatable* dengan *Server-Side Pagination*, filter multi-kolom, grafik analitik depresiasi aset, dan tombol cetak massal label QR Code.

### B. Antarmuka Petugas Gudang (Prioritas HP / Tablet - Mode PWA)
* **Karakteristik:** Ringan, minimalis, fokus pada navigasi satu tangan (*one-handed operation*), dan hemat kuota data.
* **Fitur Utama:** * **Modul Kamera Pemindai (Scan Mode):** Menggunakan library JavaScript lokal (*client-side*) seperti `Html5-QRCode` untuk memproses *scanning* langsung di HP tanpa *delay* pengiriman gambar ke server.
    * **Umpan Balik Audio (Beep Sound):** Notifikasi suara instan saat QR Code berhasil diverifikasi agar petugas tidak perlu terus menatap layar.
    * **Offline Sync (Opsional):** Kemampuan menyimpan data *scan* sementara di *Local Storage* jika area gudang mengalami *blank spot* sinyal, dan otomatis sinkronisasi ulang saat sinyal kembali.

---

## 4. Fitur Utama & Kebutuhan Fungsional (Functional Requirements)

### Modul 1: Manajemen Inventaris & Siklus Hidup (Inventory & Lifecycle)
* **Pencatatan Aset:** Input aset baru via PC dengan detail: Kode Aset, Nama, Kategori, Serial Number, Spesifikasi, Lokasi, dan Status.
* **Manajemen Status Siklus Hidup:** Pelacakan status aset secara dinamis: *Available, Assigned, Under Repair, Broken, Disposed*.

### Modul 2: Pelacakan & Dokumentasi (Tracking & Documentation)
* **Riwayat Kepemilikan (Asset Log):** Log otomatis setiap kali aset berpindah tangan atau di-scan di gudang.
* **Notifikasi Garansi:** Sistem pengingat otomatis di dashboard saat masa garansi atau lisensi akan habis.

### Modul 3: Audit Periodik & Stock Opname (Audit & Stock Opname - HP Optimized)
* **Sistem QR Code / Barcode:** Pembuatan QR Code unik untuk setiap aset yang dapat dicetak.
* **Stock Opname Mobile-Friendly:** Fitur pemindaian (*scanning*) QR Code menggunakan kamera HP untuk verifikasi fisik aset secara cepat di lapangan.
* **Rekonsiliasi Hasil Audit:** Laporan otomatis yang membandingkan jumlah aset di sistem dengan hasil *scanning* di lapangan.

### Modul 4: Pengadaan & Vendor (Procurement & Vendor Management)
* **Manajemen Vendor:** Database kontak vendor dan riwayat pembelian.
* **Modul Pengadaan:** Pencatatan *Purchase Order* (PO) TI hingga dikonversi menjadi aset aktif.

### Modul 5: Alokasi Karyawan (Onboarding & Offboarding Support)
* **Onboarding Checklist:** Alokasi paket perangkat TI ke karyawan baru dalam satu klik.
* **Offboarding Handover:** Formulir pengembalian aset saat karyawan *resign*, dilengkapi dengan pengecekan kondisi fisik akhir via HP oleh tim IT lapangan.

---

## 5. Kebutuhan Non-Fungsional (Non-Functional Requirements)

### Kecepatan & Performa (Performance)
* **Waktu Muat (Load Time):** Dashboard PC dan halaman pemindai HP harus termuat dalam waktu **< 1.2 detik**.
* **Pencarian Cepat:** Fitur *Live Search* merespons dalam waktu < 300ms.
* **Background Queue:** Penulisan log audit dikerjakan di latar belakang menggunakan Laravel Queue (Redis/Database driver) agar tidak menghambat kecepatan *scanning* di HP.

### Keamanan Data (Security & Compliance)
* **Role-Based Access Control (RBAC):** Pembagian hak akses ketat antara IT Manager (PC-Full Access), Staff Gudang (HP-Scan & Audit Only), dan Karyawan Umum (Read-Only).
* **Audit Trail:** Sistem mencatat setiap log perubahan data aset secara permanen.

---

## 6. Alur Pengguna (User Flow) Stock Opname Kilat via HP
1. Petugas Gudang membuka PWA ITAM di HP dan mengeklik tombol **Mulai Stock Opname**.
2. Kamera aktif secara instan. Petugas mengarahkan kamera ke QR Code laptop di rak gudang.
3. Library JavaScript mendeteksi kode -> Mengirim request super ringan ke API `/api/v1/scan-asset`.
4. Database mencocokkan indeks -> Mengembalikan status sukses -> HP mengeluarkan suara *"Beep"* dan warna layar berubah hijau sesaat.
5. Kamera langsung otomatis aktif kembali untuk memindai aset berikutnya tanpa jeda.

# Product Requirements Document (PRD)
## Aplikasi Web Pemesanan Buket Bunga (Flower Bouquet Ordering App)

| | |
|---|---|
| **Versi Dokumen** | 1.0 |
| **Tanggal** | 22 Juli 2026 |
| **Status** | Draft — Siap untuk Development |
| **Disusun oleh** | Product Manager & System Architect |
| **Ditujukan untuk** | Tim Development / AI Coding Assistant |

---

## Daftar Isi
1. [Executive Summary & Project Overview](#1-executive-summary--project-overview)
2. [Sistem Aktor & Hak Akses (User Roles)](#2-sistem-aktor--hak-akses-user-roles)
3. [Penjabaran Rinci Modul Fungsional](#3-penjabaran-rinci-modul-fungsional)
4. [Alur Kerja End-to-End (User Journey & Flow)](#4-alur-kerja-end-to-end-user-journey--flow)
5. [Draft Skema Database](#5-draft-skema-database)
6. [Development Constraints & Coding Rules](#6-development-constraints--coding-rules)

---

## 1. Executive Summary & Project Overview

### 1.1 Deskripsi Singkat

Aplikasi ini adalah **platform web e-commerce** yang dirancang khusus untuk toko buket bunga (florist) dalam mengelola proses pemesanan produk secara digital, end-to-end — mulai dari pelanggan menjelajahi katalog produk, melakukan pemesanan kustom (custom order), berkomunikasi langsung dengan admin/pemilik toko terkait detail buket, hingga memantau status pesanan secara real-time sampai buket diterima.

Aplikasi ini menggantikan alur pemesanan manual berbasis chat WhatsApp/media sosial yang sulit dilacak dan rawan miskomunikasi, dengan sistem terpusat yang memberikan **transparansi status pesanan** dan **manajemen operasional toko yang lebih efisien** bagi admin. Komunikasi pelanggan-admin tetap menggunakan WhatsApp melalui integrasi deep link.

### 1.2 Tujuan Produk (Product Goals)

- Memberikan pengalaman pemesanan buket yang mudah, transparan, dan dapat dilacak bagi pelanggan.
- Mengurangi beban operasional admin dalam mengelola pesanan manual (mencatat, konfirmasi, update status) yang sebelumnya dilakukan lewat chat pribadi.
- Menyediakan data terpusat (riwayat pesanan, rekap penjualan) yang dapat digunakan untuk pengambilan keputusan bisnis.
- Mendukung fitur kustomisasi buket (warna, tulisan pada kartu, dll.) sebagai nilai jual utama toko buket bunga.

### 1.3 Target Pengguna

| Aktor | Deskripsi |
|---|---|
| **Pelanggan (Customer)** | Individu yang ingin memesan buket bunga untuk berbagai keperluan (ulang tahun, wisuda, pernikahan, dll.), baik untuk diambil sendiri (self pick-up) maupun diantar (delivery). |
| **Admin / Pemilik Toko** | Pengelola toko yang bertanggung jawab atas katalog produk, verifikasi & pemrosesan pesanan, komunikasi dengan pelanggan, serta pemantauan performa bisnis melalui dashboard. |

### 1.4 Ruang Lingkup (Scope)

**Termasuk dalam scope (in-scope):**
- Autentikasi pelanggan & admin
- Manajemen katalog produk
- Sistem pemesanan dengan form kustom
- Real-time order tracking dengan log histori
- Integrasi WhatsApp untuk komunikasi pelanggan-admin
- Dashboard analitik admin
- Riwayat dan manajemen akun pelanggan

**Di luar scope (out-of-scope) untuk versi 1.0:**
- Integrasi payment gateway otomatis (pembayaran masih berupa upload bukti transfer manual, diverifikasi admin)
- Aplikasi mobile native (fokus awal: web responsive)
- Sistem multi-toko/multi-tenant
- Integrasi pengiriman pihak ketiga (kurir otomatis) — pengiriman dikelola manual oleh admin

---

## 2. Sistem Aktor & Hak Akses (User Roles)

Sistem menggunakan **Role-Based Access Control (RBAC)** dengan dua role utama: `customer` dan `admin`.

### 2.1 Pelanggan (Customer)

| Kategori | Hak Akses |
|---|---|
| Autentikasi | Registrasi akun baru, login, edit profil, logout |
| Katalog | Melihat & memfilter produk, melihat detail produk |
| Pemesanan | Membuat pesanan baru, mengisi detail kustomisasi, upload bukti pembayaran |
| Tracking | Melihat status pesanan miliknya sendiri secara real-time & riwayat perubahan status |
| WhatsApp | Menghubungi admin via WhatsApp untuk tanya produk atau konfirmasi pesanan |
| Riwayat | Melihat riwayat pesanan sendiri, memesan ulang dari riwayat |
| Batasan | **Tidak dapat** mengakses data pesanan/pelanggan lain, **tidak dapat** mengubah status pesanan, **tidak dapat** mengelola produk |

### 2.2 Admin / Pemilik Toko

| Kategori | Hak Akses |
|---|---|
| Autentikasi | Login dengan akun admin (akun admin dibuat melalui seeding/undangan, **bukan** self-registration publik) |
| Katalog | Full CRUD produk (tambah, edit, hapus/nonaktifkan) |
| Pemesanan | Melihat seluruh pesanan masuk dari semua pelanggan, verifikasi pembayaran, mengubah status pesanan, menambah catatan internal |
| Tracking | Update status pesanan yang otomatis tersinkron ke sisi pelanggan |
| WhatsApp | Menerima pertanyaan dari pelanggan via WhatsApp (nomor toko tertera di profil toko) |
| Dashboard | Akses penuh ke ringkasan penjualan, grafik, dan ekspor data |
| Batasan | Tidak dapat mengubah kredensial login milik pelanggan; setiap aksi admin pada data sensitif (misal ubah status, hapus produk) tercatat dalam log audit |

> **Catatan Desain Keamanan:** Setiap endpoint API harus melakukan pengecekan otorisasi berbasis role di sisi server (bukan hanya di sisi UI/frontend), agar pelanggan tidak dapat mengakses data pesanan pelanggan lain melalui manipulasi request langsung (IDOR — Insecure Direct Object Reference).

---

## 3. Penjabaran Rinci Modul Fungsional

### Modul 1 — Autentikasi

**Tujuan:** Mengelola identitas pengguna dan mengamankan akses ke fitur sesuai role.

**Fitur Pelanggan:**
- **Daftar akun**: input `nama`, `email`, `no. HP`, `password` (+ konfirmasi password). Email dan/atau nomor HP harus unik.
- **Login**: menggunakan `email` + `password`.
- **Edit profil akun**: memperbarui nama, no. HP, alamat, dan foto profil (opsional).
- **Ganti password**: memerlukan verifikasi password lama.

**Fitur Admin:**
- **Login akun admin**: menggunakan kredensial terpisah dari pelanggan (role `admin`), tanpa jalur registrasi publik.
- **Akses dashboard pengelolaan**: setelah login, admin diarahkan ke dashboard, bukan halaman katalog pelanggan.

**Aturan Bisnis & Validasi:**
- Password minimal 8 karakter, kombinasi huruf & angka.
- Password disimpan dalam bentuk **hash** (misalnya bcrypt/argon2), **tidak pernah** disimpan sebagai plain text.
- Sesi login menggunakan token (JWT atau session-based) dengan masa berlaku (expiry) yang wajar, disertai mekanisme refresh token.
- Rate limiting pada endpoint login untuk mencegah brute-force.
- Validasi format email dan nomor HP (numerik, panjang sesuai standar Indonesia).

---

### Modul 2 — Katalog Produk

**Tujuan:** Menyajikan katalog buket bunga yang dapat dijelajahi pelanggan dan dikelola penuh oleh admin.

**Fitur Pelanggan:**
- Melihat daftar produk buket bunga (grid/list view dengan pagination atau infinite scroll).
- **Filter produk** berdasarkan kategori (misal: Ulang Tahun, Wisuda, Pernikahan, Duka Cita) dan rentang harga.
- Melihat **detail produk**: foto (mendukung multiple images/gallery), nama, deskripsi, harga, dan status ketersediaan stok.

**Fitur Admin (CRUD Produk):**
- **Tambah produk baru**: nama, deskripsi, harga, kategori, foto (upload), stok awal.
- **Edit produk**: memperbarui seluruh atribut produk termasuk foto, harga, dan stok.
- **Hapus / nonaktifkan produk**: menggunakan **soft delete** (field `is_active`) agar histori pesanan lama yang mereferensikan produk tersebut tetap valid, bukan hard delete.

**Aturan Bisnis & Validasi:**
- Produk yang stoknya `0` atau `is_active = false` tidak muncul/tidak bisa dipesan di sisi pelanggan, namun tetap tampil di riwayat pesanan lama.
- Upload foto produk dibatasi ukuran & format file (misal: jpg/png/webp, maks. 5MB per file).
- Harga produk disimpan dalam satuan terkecil mata uang (misal: Rupiah, integer, tanpa desimal) untuk menghindari isu floating-point.

---

### Modul 3 — Pemesanan

**Tujuan:** Mengelola alur pembuatan pesanan oleh pelanggan hingga verifikasi dan pemrosesan oleh admin.

#### 3.1 Form Pesanan (diisi Pelanggan)

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| Nama pemesan | Text | ✔ | Bisa auto-fill dari profil, dapat diubah manual |
| Nomor HP / WhatsApp | Text | ✔ | Untuk kontak konfirmasi |
| Tanggal pemesanan | Date | ✔ (auto) | Diisi otomatis oleh sistem (timestamp order dibuat) |
| Tanggal dibutuhkan / pengambilan | Date | ✔ | Tidak boleh kurang dari H+minimal (lihat aturan bisnis) |
| Produk yang dipesan | Reference | ✔ | Satu atau lebih produk (mendukung multi-item order) |
| Jumlah pesanan | Number | ✔ | Per item, minimal 1 |
| Catatan khusus | Textarea | Opsional | Warna, tulisan pada kartu ucapan, dll. |
| Metode pengambilan | Select | ✔ | `Ambil Sendiri` atau `Diantar` |
| Alamat tujuan | Text/Textarea | Kondisional | **Wajib** jika metode = `Diantar` |
| Total harga | Number | ✔ (auto) | Dihitung otomatis: Σ(harga produk × qty) + biaya kirim (jika ada) |
| Bukti pembayaran | File upload | ✔ | Upload foto/screenshot bukti transfer |

#### 3.2 Pengelolaan Pesanan (Admin)

- **Lihat daftar semua pesanan masuk**: dengan filter (status, tanggal, metode pengambilan) dan sorting.
- **Lihat detail tiap pesanan**: seluruh data form, foto bukti bayar, dan riwayat perubahan status.
- **Konfirmasi pembayaran pelanggan**: admin menandai pembayaran valid/tidak valid sebelum pesanan lanjut diproses.
- **Ubah status pesanan**: `Diproses` → `Dikirim` → `Selesai` (detail alur di Modul 4).
- **Tambah catatan internal pesanan**: catatan yang **hanya terlihat oleh admin**, tidak tampil ke pelanggan (misal: "bunga mawar stok terbatas, minta konfirmasi ulang").
- **Cetak / rekap data pesanan**: ekspor data pesanan (misal ke PDF/Excel/CSV) untuk kebutuhan operasional dapur/produksi buket.

**Aturan Bisnis & Validasi:**
- Total harga **wajib dihitung ulang di server** (backend), bukan hanya mengandalkan nilai dari frontend, untuk mencegah manipulasi harga oleh klien.
- Tanggal dibutuhkan harus divalidasi minimal H+1 (atau sesuai kebijakan toko, dijadikan konfigurasi/setting) dari tanggal pemesanan, untuk memberi waktu produksi buket.
- Jika `metode pengambilan = Diantar`, field `alamat tujuan` wajib diisi (conditional required).
- Satu pesanan dapat berisi lebih dari satu produk (relasi one-to-many ke `order_items`).
- Pesanan baru otomatis berstatus **"Menunggu Konfirmasi"** saat dibuat.

---

### Modul 4 — Real-Time Order Tracking & Alur Status

**Tujuan:** Memberikan visibilitas status pesanan secara transparan dan real-time kepada pelanggan.

**Alur Status Pesanan (Order Status State Machine):**

```
Menunggu Konfirmasi → Dikonfirmasi (Admin) → Diproses (Sedang Dibuat) → Dikirim (Dalam Perjalanan) → Selesai (Diterima)
```

| Status | Trigger | Aktor |
|---|---|---|
| `Menunggu Konfirmasi` | Otomatis saat pesanan dibuat | Sistem |
| `Dikonfirmasi` | Admin memverifikasi pesanan & pembayaran | Admin |
| `Diproses` | Admin mulai membuat buket | Admin |
| `Dikirim` / `Siap Diambil` | Buket selesai dibuat, siap kirim/ambil | Admin |
| `Selesai` | Buket sudah diterima/diambil pelanggan | Admin (atau konfirmasi pelanggan, opsional untuk versi mendatang) |

**Fitur Pelanggan:**
- Melihat status pesanan secara real-time pada halaman detail pesanan.
- Melihat **riwayat perubahan status** beserta timestamp setiap perubahan (audit trail).
- Menerima notifikasi (in-app dan/atau email/WhatsApp — tergantung integrasi yang tersedia) setiap kali status berubah.

**Fitur Admin:**
- Update status pesanan melalui dropdown/tombol aksi pada halaman detail pesanan.
- Status yang terupdate **otomatis tersinkron** ke tampilan pelanggan tanpa perlu refresh manual (menggunakan polling berkala atau WebSocket/real-time subscription).
- Setiap perubahan status **wajib tercatat** di tabel log (`tracking_logs`) — menyimpan status sebelumnya, status baru, siapa yang mengubah, dan waktu perubahan.

**Aturan Bisnis & Validasi:**
- Perubahan status **harus mengikuti urutan linear** di atas — admin **tidak diperbolehkan** melompati status (misal langsung dari `Menunggu Konfirmasi` ke `Dikirim`) kecuali untuk kasus pembatalan (`Dibatalkan`, status terminal terpisah).
- Setiap pesanan hanya memiliki **satu status aktif** pada satu waktu; histori status bersifat append-only (tidak pernah diedit/dihapus).
- Notifikasi ke pelanggan dipicu oleh event perubahan status (event-driven), bukan pengecekan manual.

---

### Modul 5 — Integrasi WhatsApp (Komunikasi)

**Tujuan:** Menyediakan jalur komunikasi langsung antara pelanggan dan admin melalui WhatsApp sebagai media yang sudah familiar dan tidak memerlukan infrastruktur chat internal.

**Konsep:**
- Tidak ada tabel chat/conversations di database — komunikasi sepenuhnya dilakukan di luar aplikasi (WhatsApp).
- Aplikasi hanya menyediakan tombol **deep link WhatsApp** (`https://wa.me/628xxxxxxxxxx?text=...`) yang mengarah ke nomor WhatsApp Admin.
- Pesan otomatis sudah terisi (pre-filled text) berdasarkan konteks halaman.

**Implementasi:**

| Lokasi Tombol | Label | Pre-filled Text |
|---|---|---|
| Halaman Detail Produk | "Tanya via WhatsApp" | `Halo, saya tertarik dengan produk [Nama Produk]. Bisa info lebih lanjut?` |
| Halaman Lacak Pesanan (Status) | "Konfirmasi via WhatsApp" | `Halo, saya ingin konfirmasi pesanan [Order Code]. Status: [Status Pesanan]` |
| Halaman Checkout/Pemesanan | "Tanya via WhatsApp" | `Halo, saya ingin bertanya tentang pemesanan buket bunga.` |

**Aturan Bisnis:**
- Nomor WhatsApp Admin disimpan di **config/env** (`WA_ADMIN_NUMBER`), bukan hardcode di view.
- Format nomor: internasional tanpa `+` (contoh: `6281234567890`).
- Pre-filled text harus **URL-encoded** sesuai standar `wa.me` deep link.
- Tombol WhatsApp harus terlihat jelas (menggunakan warna hijau WhatsApp) dan bukan merupakan bagian dari alur checkout wajib — bersifat opsional/komersial.

---

### Modul 6 — Dashboard Admin

**Tujuan:** Memberikan admin gambaran cepat (at-a-glance) mengenai kondisi operasional dan performa bisnis toko.

**Fitur:**
- **Ringkasan total pesanan hari ini**: jumlah pesanan masuk, total omzet harian.
- **Pesanan masuk yang belum diproses**: daftar quick-access ke pesanan berstatus `Menunggu Konfirmasi`/`Dikonfirmasi`.
- **Grafik pesanan per periode**: visualisasi tren (harian/mingguan/bulanan) menggunakan chart (bar/line chart).
- **Notifikasi pesanan baru**: pusat notifikasi real-time (bell icon/counter).
- **Rekap data pesanan (export)**: unduh laporan pesanan dalam rentang tanggal tertentu (format CSV/Excel/PDF).
- **Manajemen pengguna pelanggan**: melihat daftar pelanggan terdaftar (read-only atau dengan kemampuan nonaktifkan akun bermasalah).
- **Manajemen produk katalog**: akses cepat ke CRUD produk (Modul 2).
- **Pengaturan sistem**: konfigurasi umum toko (misal: kategori produk, minimal H+ tanggal pemesanan, info kontak toko).

**Aturan Bisnis & Validasi:**
- Data dashboard sebaiknya menggunakan **caching/aggregation** ringan (bukan real-time query berat setiap saat) untuk menjaga performa, terutama untuk grafik histori panjang.
- Ekspor data hanya dapat dilakukan oleh role `admin`.

---

### Modul 7 — Riwayat & Akun Pelanggan

**Tujuan:** Memberikan pelanggan akses penuh terhadap histori transaksi dan pengelolaan akun pribadi.

**Fitur:**
- **Lihat riwayat semua pesanan** milik pelanggan yang bersangkutan (bukan pesanan pelanggan lain).
- **Detail pesanan lama**: status akhir, produk yang dipesan, dan total harga.
- **Pesan ulang dari riwayat ("Reorder")**: mengisi ulang form pesanan baru secara otomatis berdasarkan data pesanan lama (produk, jumlah, catatan khusus dapat diedit ulang sebelum submit).
- **Edit profil**: nama, no. HP, alamat.
- **Ganti password.**
- **Logout akun.**

**Aturan Bisnis & Validasi:**
- Fitur reorder **tidak mengkloning harga lama** — harga dan ketersediaan produk divalidasi ulang terhadap data katalog saat ini (harga bisa saja sudah berubah).
- Jika produk pada pesanan lama sudah `is_active = false` (dihapus/nonaktif), sistem menampilkan peringatan pada saat reorder dan meminta pelanggan memilih produk pengganti.

---

## 4. Alur Kerja End-to-End (User Journey & Flow)

Berikut adalah alur interaksi paralel antara **Pelanggan** dan **Admin**, berdasarkan diagram alur kerja sistem:

| Langkah | Pelanggan | Admin / Pemilik |
|---|---|---|
| 1 | **Daftar / Login** ke akun | — |
| 2 | **Lihat katalog produk**, filter sesuai kebutuhan | — |
| 3 | **Pilih & pesan produk** | — |
| 4 | **Isi form pesanan** (data pemesan, kustomisasi, metode ambil/antar) | — |
| 5 *(opsional)* | **Tanya via WhatsApp** — klik tombol WhatsApp di detail produk untuk bertanya langsung ke admin | **Balas WhatsApp pelanggan** — memberi info produk/harga via WhatsApp pribadi |
| 6 | **Konfirmasi pesanan** — submit form + upload bukti bayar | **Terima pesanan masuk** — pesanan muncul di dashboard admin dengan status `Menunggu Konfirmasi` |
| 7 | **Pantau status pesanan** via halaman Real-Time Order Tracking | Admin **memverifikasi & memproses pesanan** → update status menjadi `Diproses` |
| 8 | **Terima notifikasi "Dikirim"** — status terupdate otomatis di sisi pelanggan | **Kirim pesanan** → update status menjadi `Dikirim` / `Siap Diambil` |
| 9 | **Terima notifikasi "Selesai"** — pesanan telah diterima/diambil | Admin menandai pesanan **Selesai** → update status menjadi `Selesai` |
| 10 | Pesanan otomatis tersimpan di **Riwayat Pesanan** pelanggan, dapat dipakai untuk reorder di masa depan | Data pesanan masuk ke rekap/analitik Dashboard Admin |

**Prinsip desain alur:**
- Sisi **WhatsApp** bersifat **paralel/opsional** terhadap alur pemesanan utama — pelanggan bisa langsung memesan tanpa chat, atau berdiskusi dulu via WhatsApp sebelum memesan.
- Setiap perubahan status oleh admin harus **secara otomatis** (event-driven, bukan manual refresh) tercermin di sisi pelanggan — inilah inti dari kebutuhan "Real-Time Order Tracking".
- Alur bersifat **linear dan tidak dapat mundur** — status hanya berjalan maju kecuali ada mekanisme pembatalan pesanan eksplisit (`Dibatalkan`) yang dapat ditambahkan sebagai state terminal terpisah di luar alur utama.

---

## 5. Draft Skema Database

Skema ini adalah rancangan relasional minimal (nama tabel & kolom dapat disesuaikan dengan konvensi tim, misalnya snake_case pada PostgreSQL/MySQL).

### 5.1 Tabel `users`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | UUID / BIGINT (PK) | Primary key |
| `name` | VARCHAR | Nama lengkap |
| `email` | VARCHAR (UNIQUE) | Digunakan untuk login |
| `phone` | VARCHAR (UNIQUE) | Nomor HP/WhatsApp |
| `password_hash` | VARCHAR | Password ter-hash |
| `address` | TEXT (nullable) | Alamat default pelanggan |
| `role` | ENUM(`customer`, `admin`) | Penentu hak akses |
| `is_active` | BOOLEAN (default true) | Untuk nonaktifkan akun |
| `created_at` / `updated_at` | TIMESTAMP | Audit standar |

### 5.2 Tabel `products`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | UUID / BIGINT (PK) | Primary key |
| `name` | VARCHAR | Nama produk |
| `description` | TEXT | Deskripsi produk |
| `price` | INTEGER | Harga (satuan terkecil, misal Rupiah) |
| `category` | VARCHAR / FK ke `categories` | Kategori produk |
| `stock` | INTEGER | Jumlah stok tersedia |
| `is_active` | BOOLEAN (default true) | Soft delete flag |
| `created_at` / `updated_at` | TIMESTAMP | Audit standar |

### 5.3 Tabel `product_images`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | UUID / BIGINT (PK) | Primary key |
| `product_id` | FK → `products.id` | Relasi ke produk |
| `image_url` | VARCHAR | Path/URL gambar |
| `is_primary` | BOOLEAN | Penanda foto utama |

### 5.4 Tabel `orders`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | UUID / BIGINT (PK) | Primary key |
| `order_code` | VARCHAR (UNIQUE) | Kode pesanan yang human-readable, misal `ORD-20260722-001` |
| `user_id` | FK → `users.id` | Pelanggan pemesan |
| `orderer_name` | VARCHAR | Nama pemesan (bisa berbeda dari nama akun) |
| `orderer_phone` | VARCHAR | Nomor HP/WA kontak |
| `needed_date` | DATE | Tanggal dibutuhkan/pengambilan |
| `pickup_method` | ENUM(`self_pickup`, `delivery`) | Metode pengambilan |
| `delivery_address` | TEXT (nullable) | Wajib jika `pickup_method = delivery` |
| `special_note` | TEXT (nullable) | Catatan khusus kustomisasi |
| `total_price` | INTEGER | Total harga (dihitung server) |
| `payment_proof_url` | VARCHAR | Path bukti pembayaran |
| `payment_verified` | BOOLEAN (default false) | Status verifikasi pembayaran |
| `status` | ENUM(`menunggu_konfirmasi`, `dikonfirmasi`, `diproses`, `dikirim`, `selesai`, `dibatalkan`) | Status pesanan saat ini |
| `admin_note` | TEXT (nullable) | Catatan internal admin, tidak tampil ke pelanggan |
| `created_at` / `updated_at` | TIMESTAMP | Audit standar |

### 5.5 Tabel `order_items`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | UUID / BIGINT (PK) | Primary key |
| `order_id` | FK → `orders.id` | Relasi ke pesanan |
| `product_id` | FK → `products.id` | Relasi ke produk |
| `product_name_snapshot` | VARCHAR | Snapshot nama produk saat order dibuat (agar histori tetap valid meski produk asli diubah) |
| `price_snapshot` | INTEGER | Snapshot harga saat order dibuat |
| `quantity` | INTEGER | Jumlah item |
| `subtotal` | INTEGER | `price_snapshot × quantity` |

### 5.6 Tabel `tracking_logs`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | UUID / BIGINT (PK) | Primary key |
| `order_id` | FK → `orders.id` | Relasi ke pesanan |
| `previous_status` | VARCHAR (nullable) | Status sebelumnya |
| `new_status` | VARCHAR | Status baru |
| `changed_by` | FK → `users.id` | Admin yang melakukan perubahan |
| `note` | TEXT (nullable) | Catatan opsional saat perubahan |
| `created_at` | TIMESTAMP | Waktu perubahan (bersifat append-only) |

### 5.7 Diagram Relasi (Ringkas)

```
users (1) ──< orders (N) ──< order_items (N) >── products (1)
orders (1) ──< tracking_logs (N)
products (1) ──< product_images (N)
```

---

## 6. Development Constraints & Coding Rules

Bagian ini berfungsi sebagai **panduan wajib** bagi AI coding assistant maupun developer manusia agar implementasi tidak menyimpang dari logika bisnis aplikasi buket bunga ini.

### 6.1 Aturan Modularitas Kode
- Struktur kode **wajib dipisah per modul/domain** sesuai modul-modul di atas (misal: `auth/`, `products/`, `orders/`, `tracking/`, `whatsapp/`, `dashboard/`, `customers/`), baik di backend (services/controllers) maupun frontend (pages/components).
- Setiap modul **tidak boleh mengakses tabel database milik modul lain secara langsung tanpa melalui service/repository layer** — gunakan pola repository/service untuk menjaga separation of concerns.
- Logika perhitungan **total harga pesanan** dan **validasi state machine status pesanan** harus berada di satu tempat terpusat (single source of truth), **bukan diduplikasi** di berbagai endpoint, untuk mencegah inkonsistensi.
- Gunakan environment variable/config file untuk nilai yang dapat berubah (misal: minimal H+ tanggal pemesanan, batas ukuran upload file), **jangan hardcode**.

### 6.2 Penanganan Error (Error Handling)
- Semua endpoint API **wajib mengembalikan format response error yang konsisten**, misal:
  ```json
  { "success": false, "error": { "code": "ORDER_INVALID_DATE", "message": "Tanggal dibutuhkan minimal H+1 dari hari ini" } }
  ```
- Error dari validasi bisnis (misal: stok habis, tanggal tidak valid, transisi status tidak sah) harus menggunakan **kode error spesifik**, bukan pesan generik seperti "Something went wrong", agar frontend dapat menampilkan pesan yang tepat ke pengguna.
- Operasi yang melibatkan **lebih dari satu tabel sekaligus** (misal: membuat order + order_items + tracking_log awal) **wajib menggunakan database transaction** — jika salah satu langkah gagal, seluruh operasi di-rollback agar data tidak setengah tersimpan.
- Upload file (bukti pembayaran, foto produk) harus divalidasi tipe MIME dan ukuran **di sisi server**, bukan hanya di client, sebelum disimpan.

### 6.3 Validasi Form
- **Validasi ganda**: setiap input wajib divalidasi di frontend (UX cepat) **dan** di backend (keamanan & integritas data) — validasi frontend tidak pernah dianggap cukup.
- Field kondisional (misal `delivery_address` wajib hanya jika `pickup_method = delivery`) harus divalidasi secara eksplisit di backend, tidak boleh mengandalkan asumsi frontend selalu mengirim data lengkap.
- Total harga pesanan (`total_price`) **selalu dihitung ulang di server** berdasarkan harga produk terkini pada saat submit, **tidak boleh menerima total harga kiriman dari client** sebagai nilai final tanpa verifikasi.
- Validasi transisi status pesanan (state machine di Modul 4) harus ditegakkan di level backend — tolak request yang mencoba mengubah status di luar urutan yang diizinkan (misal langsung dari `menunggu_konfirmasi` ke `selesai`).

### 6.4 Batasan Teknis & Panduan Interpretasi Logika Bisnis (khusus untuk AI Coding Assistant)

Agar tidak salah menginterpretasikan konteks bisnis aplikasi buket bunga, AI coding assistant **wajib mematuhi asumsi berikut** kecuali dinyatakan lain secara eksplisit oleh product owner:

1. **Ini bukan marketplace multi-penjual.** Hanya ada **satu toko** dengan satu tim admin. Jangan membangun fitur multi-vendor/multi-tenant kecuali diminta.
2. **Pembayaran bersifat manual-verifikasi**, bukan payment gateway otomatis (di versi ini). Alur adalah: pelanggan upload bukti transfer → admin verifikasi manual → status lanjut. Jangan mengasumsikan integrasi Midtrans/Xendit/dsb. kecuali diminta secara eksplisit; jika ditambahkan di masa depan, letakkan sebagai lapisan terpisah tanpa mengubah alur status inti.
3. **Status pesanan bersifat linear dan tidak boleh dilewati (no status skipping)**, kecuali kasus pembatalan yang merupakan state terminal terpisah.
4. **Snapshot data pada order_items** (nama & harga produk) wajib disimpan saat order dibuat, agar histori pesanan tidak berubah retroaktif jika admin mengubah harga/nama produk di kemudian hari.
5. **Komunikasi pelanggan-admin dilakukan via WhatsApp** (deep link), bukan chat internal aplikasi. Aplikasi hanya menyediakan tombol redirect ke WhatsApp dengan pre-filled text.
6. **Konsep "Real-Time"** dalam konteks ini berarti pembaruan status dan notifikasi **tersampaikan tanpa perlu reload manual oleh pengguna** — dapat diimplementasikan dengan polling interval singkat (misal setiap 5–10 detik) di versi awal, atau WebSocket/Server-Sent Events untuk versi yang lebih matang. AI assistant tidak perlu mengasumsikan infrastruktur real-time yang kompleks (misal Kafka) kecuali diminta.
7. **Semua timestamp disimpan dalam UTC** di database, dan dikonversi ke timezone lokal (WIB/Asia-Jakarta) hanya pada saat ditampilkan di UI.
8. **Satuan mata uang adalah Rupiah (IDR)** tanpa desimal — jangan gunakan tipe data floating-point untuk harga; gunakan integer.
9. **Soft delete** digunakan untuk `products` dan `users` (field `is_active`), **bukan hard delete**, untuk menjaga integritas referensial dengan data pesanan historis.
10. Jangan menambahkan fitur di luar 7 modul yang telah didefinisikan (misal: sistem loyalty points, sistem review/rating produk, multi-bahasa) kecuali diminta secara eksplisit — jaga scope tetap sesuai PRD ini untuk versi 1.0.

### 6.5 Standar Kualitas Kode Umum
- Gunakan penamaan variabel dan fungsi yang deskriptif dan konsisten dengan istilah bisnis pada dokumen ini (misal: gunakan istilah `order`, bukan campuran `order`/`transaction`/`booking` untuk entitas yang sama).
- Sertakan komentar pada logika bisnis yang kompleks (misal: perhitungan total harga, validasi state machine status).
- Sediakan automated test minimal untuk: validasi transisi status pesanan, perhitungan total harga, dan validasi field kondisional pada form pemesanan.
- Ikuti prinsip **API versioning** (misal prefix `/api/v1/...`) agar perubahan di masa depan tidak merusak klien yang sudah ada.

---

*Dokumen ini merupakan acuan utama pengembangan. Setiap perubahan scope atau logika bisnis pada modul-modul di atas sebaiknya didiskusikan dan didokumentasikan ulang sebelum diimplementasikan, agar tim development dan AI coding assistant tetap selaras dengan tujuan produk.*

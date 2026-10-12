# AGENTS.md — Konteks Teknis & Panduan Agen SI-AKSEL

Dokumen ini berisi konteks arsitektur, domain bisnis, struktur basis data, aturan bisnis, dan pedoman kerja bagi AI Agent maupun pengembang manusia yang berkontribusi pada pengembangan sistem **SI-AKSEL**.

---

## 1. Ringkasan Proyek (Executive Overview)

- **Nama Sistem:** SI-AKSEL (Sistem Informasi Akuntabilitas Kinerja Selaras / Evaluasi AKIP Kota Makassar)
- **Tujuan Sistem:** Platform berbasis web untuk mengelola, menilai, dan memantau evaluasi **AKIP (Akuntabilitas Kinerja Instansi Pemerintah)** melalui instrumen **LKE (Lembar Kerja Evaluasi)** secara terpusat, transparan, dan real-time bagi Perangkat Daerah (OPD/SKPD) di lingkungan Pemerintah Kota Makassar.
- **Entitas Terkait:**
  - **BRIDA** (Badan Riset dan Inovasi Daerah Kota Makassar) — Pengelola/Inisiator sistem & Administrator utama.
  - **Inspektorat Daerah Kota Makassar** — Pembina teknis, pengawas, penilai (evaluator), dan verifikator lembar kerja.
  - **SKPD / Dinas Binaan** — Perangkat daerah yang melakukan penilaian mandiri (self-assessment) serta mengunggah dokumen bukti (*evidence*).
- **Tipe Arsitektur:** Monolith Laravel 11 berbasis MVC (Model-View-Controller) dengan Blade & Tailwind CSS.

---

## 2. Tech Stack & Environment

| Komponen | Spesifikasi / Pustaka |
| :--- | :--- |
| **Bahasa Pemrograman** | PHP 8.2+ |
| **Framework Backend** | Laravel 11.x |
| **Basis Data** | SQLite (lokal/default di `database/database.sqlite`), kompatibel penuh dengan MySQL/PostgreSQL |
| **Autentikasi & RBAC** | Laravel Breeze 2.4 + Spatie Laravel-Permission 6.25 (`spatie/laravel-permission`) |
| **Frontend Templating** | Laravel Blade Engine |
| **Styling & UI Framework** | Tailwind CSS 3.x (disertai Tailwind Forms, CDN styling terintegrasi), FontAwesome 6, AOS Animation |
| **JavaScript Utility** | Alpine.js 3.4+, Axios 1.6+ |
| **Build Tool** | Vite 5.x, PostCSS 8.x |
| **Testing** | Pest PHP 3.7+, PHPUnit 11 |

### Konfigurasi Identitas Visual & Warna (Branding)
- **Primary / Accent Color:** Teal `#1296b0` (Hover: `teal-700` / `#0d7488`, Light: `teal-50`)
- **Secondary / Highlight Color:** Maroon `#800000` (Red tone untuk aksen hover sidebar)
- **Font:** Inter (`sans-serif`)

---

## 3. Struktur Direktori Proyek

```
aksel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/                      # Autentikasi Breeze (Login, Password, dll.)
│   │   │   ├── BankDokumenController.php  # Pengelolaan dokumen evidence SKPD (PDF)
│   │   │   ├── DashboardController.php    # Dashboard home berbasis role
│   │   │   ├── EvaluasiInstansiController.php # Pemeriksaan & verifikasi LKE oleh Inspektorat
│   │   │   ├── InstitutionController.php  # Master data instansi / OPD
│   │   │   ├── IsiPenilaianController.php # Pengisian LKE mandiri & pengajuan berkas SKPD
│   │   │   ├── ProfileController.php      # Profil pengguna bawaan Breeze
│   │   │   ├── RekapitulasiController.php # Rekapitulasi skor akhir se-Kota Makassar
│   │   │   ├── RiwayatEvaluasiController.php # Histori evaluasi tahunan SKPD
│   │   │   ├── TemplateLkeController.php  # CRUD Komponen, Sub-komponen, Kriteria & Deadline
│   │   │   └── UserController.php         # Manajemen akun & penugasan instansi binaan
│   │   └── Requests/                      # Form validation requests
│   └── Models/
│       ├── Document.php                   # Model arsip evidence instansi
│       ├── Institution.php                # Model data instansi / OPD
│       ├── LkeComponent.php               # Komponen utama LKE (Bobot level 1)
│       ├── LkeCriteria.php                # Kriteria/indikator pertanyaan & bukti yang dicari
│       ├── LkeEvaluation.php              # Nilai, predikat, status verifikasi & catatan
│       ├── LkeSubComponent.php            # Sub-komponen LKE (Bobot level 2)
│       └── User.php                       # Model pengguna & relasi RBAC
├── database/
│   ├── migrations/                        # Skema tabel database (termasuk pivot & app_settings)
│   └── seeders/
│       ├── DatabaseSeeder.php             # Master seeder (memanggil InitSeeder & LkeSeeder)
│       ├── InitSeeder.php                 # Seed peran (5 role), instansi awal, dan super admin
│       └── LkeSeeder.php                  # Seed struktur awal template LKE Kota Makassar
├── resources/
│   ├── views/
│   │   ├── auth/                          # Template Blade login/register
│   │   ├── dashboard/                     # Tampilan modul operasional sistem:
│   │   │   ├── bankdokumen/               # CRUD berkas evidence
│   │   │   ├── evaluasiinstansi/          # Antarmuka periksa LKE instansi binaan
│   │   │   ├── isipenilaianlke/           # Lembar kerja interaktif dinas
│   │   │   ├── kelolatemplatelke/         # Struktur hierarki LKE & setting deadline
│   │   │   ├── manajemenpengguna/         # CRUD user & modal assign dinas binaan
│   │   │   ├── masterinstansi/            # CRUD data SKPD
│   │   │   ├── rekapitulasinilaiakhir/    # Tabel peringkat & rekapitulasi kota
│   │   │   └── riwayatevaluasi/           # Portofolio historis nilai tahunan
│   │   ├── errors/                        # 403.blade.php, 404.blade.php, 500.blade.php
│   │   ├── layouts/
│   │   │   └── dashboard.blade.php        # Master layout sidebar & navbar terpadu
│   │   └── welcome.blade.php              # Landing page publik profil SI-AKSEL
├── routes/
│   ├── auth.php                           # Rute autentikasi Laravel Breeze
│   └── web.php                            # Seluruh rute aplikasi dengan proteksi middleware
└── storage/app/public/documents/          # Direktori penyimpanan fisik berkas PDF evidence
```

---

## 4. Model Data & Skema Basis Data (Database Architecture)

### 4.1 Entitas Utama

1. **`institutions`**
   - Kolom: `id`, `name`, `alias` (uppercase e.g. BRIDA, INSPEKTORAT), `status` (`aktif`/`nonaktif`), `notes`, `timestamps`.
   - Relasi:
     - `hasMany(User::class, 'institution_id')` (Pegawai yang berafiliasi).
     - `belongsToMany(User::class, 'evaluator_assignments')` (Evaluator Inspektorat yang mengawasi).
     - `hasMany(Document::class)` (Arsip berkas).

2. **`users`**
   - Kolom: `id`, `name`, `email`, `password`, `institution_id` (foreign key ke `institutions`), `timestamps`.
   - Relasi:
     - `belongsTo(Institution::class, 'institution_id')` -> `originInstitution`.
     - `belongsToMany(Institution::class, 'evaluator_assignments')` -> `binaanInstitutions` (khusus evaluator Inspektorat).
     - Spatie `HasRoles` (`roles`, `permissions`).

3. **`evaluator_assignments` (Pivot Table)**
   - Menghubungkan user evaluator (`operator_inspektorat`) dengan instansi binaan (`institution_id`).

4. **Hierarki Template LKE (`lke_components` -> `lke_sub_components` -> `lke_criteria`):**
   - **`lke_components`**: `id`, `component_number` (int), `name` (string), `weight` (decimal 5,2).
     - Relasi: `hasMany(LkeSubComponent::class)`.
   - **`lke_sub_components`**: `id`, `lke_component_id`, `code` (e.g. `1.a`), `name` (string), `weight` (decimal 5,2, nullable).
     - Relasi: `belongsTo(LkeComponent::class)`, `hasMany(LkeCriteria::class)`.
   - **`lke_criteria`**: `id`, `lke_sub_component_id`, `criteria_number` (int), `description` (text), `expected_evidence` (text).
     - Relasi: `belongsTo(LkeSubComponent::class)`, `hasMany(LkeEvaluation::class)`.

5. **`documents` (Bank Dokumen Evidence)**
   - Kolom: `id`, `institution_id`, `name`, `year`, `file_path` (disimpan di `public/documents`), `timestamps`.
   - Relasi:
     - `belongsTo(Institution::class)`.
     - `belongsToMany(LkeEvaluation::class, 'document_lke_evaluation')`.
   - **Constraint:** Dokumen tidak boleh dihapus jika masih ditautkan pada evaluasi (`evaluations_count > 0`).

6. **`lke_evaluations` (Tabel Transaksi Penilaian)**
   - Kolom:
     - `id`
     - `institution_id` (FK `institutions`, cascade delete)
     - `lke_criteria_id` (FK `lke_criteria`, cascade delete)
     - `predicate` (`AA`, `A`, `BB`, `B`, `CC`, `C`, `D`, `E`)
     - `final_score` (decimal 5,2)
     - `status` (enum: `menunggu`, `submitted`, `disetujui`, `revisi`)
     - `inspector_notes` (text, catatan perbaikan/revisi)
     - `evaluation_year` (year)
     - `timestamps`
   - Relasi:
     - `belongsTo(Institution::class)`
     - `belongsTo(LkeCriteria::class)`
     - `belongsToMany(Document::class, 'document_lke_evaluation')`

7. **`app_settings` (Konfigurasi Global)**
   - Menyimpan key-value pair sistem, terutama:
     - `key`: `'lke_deadline'` -> `value`: Tanggal/waktu batas akhir pengisian LKE (format ISO datetime).

---

## 5. Role-Based Access Control (RBAC) & Matriks Otorisasi

Sistem mendefinisikan 5 role hierarkis:

| Role | Deskripsi & Tanggung Jawab | Hak Akses Modul |
| :--- | :--- | :--- |
| **`super_admin`** | Hak akses penuh tanpa batas. Mengatur sistem, user, instansi, template, deadline, serta bypass semua batasan. | Semua Modul |
| **`admin`** | Administrator operasional (BRIDA/Sekretariat). Mengelola data master instansi, user, template LKE, dan jadwal deadline. | Master Instansi, Manajemen Pengguna, Template LKE, Evaluasi, Rekapitulasi |
| **`inspektorat`** | Pimpinan/Pejabat Inspektorat. Melihat seluruh instansi aktif, memantau kemajuan, dan mengakses rekapitulasi kota. | Template LKE, Evaluasi Instansi (Semua OPD), Rekapitulasi Nilai Akhir |
| **`operator_inspektorat`** | Tim teknis pemeriksa/auditor. Hanya memeriksa dan memvalidasi LKE instansi yang ditugaskan (*binaan*). | Evaluasi Instansi Binaan, Template LKE, Rekapitulasi Nilai Akhir |
| **`operator_dinas`** | Operator SKPD/OPD. Mengunggah evidence, mengisi evaluasi mandiri, dan mengajukan berkas ke Inspektorat. | Bank Dokumen, Isi Penilaian LKE, Riwayat Evaluasi |

### Matriks Rute & Middleware

```php
// Rute Umum (auth)
GET    /dashboard/home                         -> DashboardController@index

// Rute Admin (role:super_admin|admin)
GET    /dashboard/manajemenpengguna            -> UserController@index
POST   /dashboard/manajemenpengguna/store      -> UserController@store
POST   /dashboard/manajemenpengguna/{id}/assign-> UserController@assignDinas
PUT    /dashboard/manajemenpengguna/{id}       -> UserController@update
DELETE /dashboard/manajemenpengguna/{id}       -> UserController@destroy
GET    /dashboard/masterinstansi               -> InstitutionController@index
POST   /dashboard/masterinstansi/store         -> InstitutionController@store
POST   /dashboard/kelolatemplatelke/deadline   -> TemplateLkeController@updateDeadline

// Rute Inspektorat (role:super_admin|admin|inspektorat|operator_inspektorat)
GET    /dashboard/kelolatemplatelke            -> TemplateLkeController@index
POST   /dashboard/kelolatemplatelke/component  -> TemplateLkeController@storeComponent
POST   /dashboard/kelolatemplatelke/subcomponent-> TemplateLkeController@storeSubComponent
POST   /dashboard/kelolatemplatelke/criteria   -> TemplateLkeController@storeCriteria
DELETE /dashboard/kelolatemplatelke/criteria/{id}-> TemplateLkeController@destroyCriteria
DELETE /dashboard/kelolatemplatelke/component/{id}-> TemplateLkeController@destroyComponent
DELETE /dashboard/kelolatemplatelke/subcomponent/{id}-> TemplateLkeController@destroySubComponent
GET    /dashboard/evaluasiinstansi             -> EvaluasiInstansiController@index
POST   /dashboard/evaluasiinstansi/{inst}/store-> EvaluasiInstansiController@store
GET    /dashboard/rekapitulasinilaiakhir       -> RekapitulasiController@index

// Rute Dinas (role:super_admin|admin|operator_dinas)
GET    /dashboard/bankdokumen                  -> BankDokumenController@index
POST   /dashboard/bankdokumen/store            -> BankDokumenController@store
DELETE /dashboard/bankdokumen/{id}             -> BankDokumenController@destroy
GET    /dashboard/isipenilaianlke              -> IsiPenilaianController@index
POST   /dashboard/isipenilaianlke/store        -> IsiPenilaianController@store
POST   /dashboard/isipenilaianlke/submit       -> IsiPenilaianController@submitToInspektorat
GET    /dashboard/riwayatevaluasi              -> RiwayatEvaluasiController@index
```

---

## 6. Logika Bisnis & Alur Kerja (Core Business Rules & Workflows)

### 6.1 Formula Perhitungan Nilai & Predikat

Perhitungan nilai LKE mengacu pada bobot komponen/sub-komponen dan faktor pengali predikat:

$$\text{Final Score} = \text{Multiplier Predikat} \times \text{Bobot Sub-Komponen / Komponen}$$

**Tabel Konversi Predikat ke Multiplier:**
- **AA** = $1.00$ ($100\%$)
- **A**  = $0.90$ ($90\%$)
- **BB** = $0.80$ ($80\%$)
- **B**  = $0.70$ ($70\%$)
- **CC** = $0.60$ ($60\%$)
- **C**  = $0.50$ ($50\%$)
- **D**  = $0.30$ ($30\%$)
- **E**  = $0.00$ ($0\%$)

**Rentang Predikat Akumulatif AKIP Kota (Skala 0 - 100):**
- $\ge 90.00$ : **AA** (Sangat Memuaskan)
- $\ge 80.00$ : **A** (Memuaskan)
- $\ge 70.00$ : **BB** (Sangat Baik)
- $\ge 60.00$ : **B** (Baik)
- $\ge 50.00$ : **CC** (Cukup)
- $\ge 30.00$ : **C** (Kurang)
- $< 30.00$ : **D** (Sangat Kurang)

---

### 6.2 Siklus Hidup & Status Evaluasi (State Machine)

```
[Belum Mengisi]
       │
       ▼ (Dinas input predikat & link evidence)
   [menunggu]  ──(Dinas klik "Ajukan ke Inspektorat")──► [submitted] (Kunci form dinas)
       ▲                                                      │
       │                                                      ▼ (Inspektorat periksa)
       │                                            ┌─────────┴─────────┐
       │                                            ▼                   ▼
       └──(Inspektorat pilih "revisi")── [revisi]              [disetujui] (Terkunci permanen &
                                                                           masuk rekapitulasi)
```

1. **State `menunggu`:** Dinas telah menginput penilaian mandiri, masih bisa diubah sewaktu-waktu selama belum melewati batas waktu.
2. **State `submitted`:** Dinas telah menekan tombol **Ajukan Berkas Kolektif ke Inspektorat**. Form input mandiri dikunci sementara untuk mencegah manipulasi selama pemeriksaan berlangsung.
3. **State `revisi`:** Evaluator Inspektorat mengembalikan lembar kerja disertai catatan perbaikan (`inspector_notes`). Kunci pengisian dinas terbuka kembali agar dinas dapat memperbaiki predikat atau bukti evidence.
4. **State `disetujui`:** Evaluator Inspektorat menyetujui penilaian. Data dikunci permanen dan dihitung dalam laporan rekapitulasi akhir kota.

---

### 6.3 Aturan Validasi & Proteksi Khusus

1. **Proteksi Batas Waktu (`lke_deadline`):**
   - Sebelum menyimpan penilaian mandiri (`IsiPenilaianController@store`), sistem memvalidasi apakah waktu sekarang $\le$ deadline pada tabel `app_settings`. Jika terlampaui, request ditolak dengan pesan error.
2. **Proteksi Hapus Dokumen Evidence:**
   - Berkas di `BankDokumenController@destroy` tidak boleh dihapus jika memiliki relasi aktif di tabel pivot `document_lke_evaluation` (`evaluations_count > 0`).
   - File fisik PDF wajib dihapus dari storage disk saat record dokumen dihapus.
3. **Proteksi Penugasan Evaluator Inspektorat:**
   - Role `operator_inspektorat` hanya dapat melihat dan menilai instansi yang terdapat di relasi `binaanInstitutions`.
   - Role `inspektorat` (pimpinan) dan `super_admin` dapat melihat seluruh instansi aktif se-Kota Makassar.
4. **Proteksi Akun Sendiri:**
   - Pengguna dilarang menghapus akunnya sendiri pada modul Manajemen Pengguna.

---

## 7. Setup & Perintah Operasional (Developer Cheat-Sheet)

### Inisialisasi Proyek dari Awal

```bash
# 1. Pasang dependensi PHP & Node
composer install
npm install

# 2. Setup konfigurasi lingkungan
cp .env.example .env
php artisan key:generate

# 3. Setup database SQLite & Jalankan Migrasi + Seeder
touch database/database.sqlite
php artisan migrate:fresh --seed

# 4. Buat symbolic link ke public storage (untuk dokumen PDF)
php artisan storage:link

# 5. Jalankan server lokal & aset Vite
npm run dev
php artisan serve
```

### Akun Awal dari Seeder (`database/seeders/InitSeeder.php`)

| Email | Password | Role | Instansi |
| :--- | :--- | :--- | :--- |
| `admin@brida.makassar.go.id` | `password123` | `super_admin` | BRIDA Kota Makassar |
| `andi@inspektorat.makassar.go.id` | `password123` | `operator_inspektorat` | Inspektorat Daerah Kota Makassar |

---

## 8. Panduan & Konvensi untuk AI Agents

Jika Anda adalah AI Agent yang ditugaskan untuk memodifikasi atau menambah fitur pada repositori ini, ikuti pedoman ketat berikut:

1. **Integritas Bahasa & Terminologi Birokrasi:**
   - Gunakan Bahasa Indonesia baku untuk UI, notifikasi toast/session alert, pesan validasi, dan komentar penjelasan.
   - Pahami istilah baku:
     - **AKIP:** Akuntabilitas Kinerja Instansi Pemerintah
     - **LKE:** Lembar Kerja Evaluasi
     - **Evidence:** Bukti Dukung / Data Dukung Kinerja
     - **SKPD / Perangkat Daerah / OPD:** Organisasi Perangkat Daerah
     - **Binaan:** SKPD/Dinas yang ditugaskan kepada tim evaluator tertentu.

2. **Konsistensi UI & Blade Layouts:**
   - Selalu gunakan layout utama: `@extends('layouts.dashboard')` untuk halaman internal dashboard.
   - Gunakan skema warna Tailwind yang telah dikonfigurasi: aksen utama teal `accent` (`#1296b0`) dan tombol/badge responsif.
   - Gunakan FontAwesome (`fas fa-...`) untuk ikon navigasi dan aksi.
   - Terapkan alert flash message session (`session('success')`, `session('error')`) pada bagian atas form.

3. **Integritas Relasi Eloquent:**
   - Selalu perhatikan bahwa `LkeCriteria` menggunakan nama tabel kustom `protected $table = 'lke_criteria';` (karena bentuk tunggal bukan `lke_criterias`).
   - Saat mengambil data untuk tabel rekapitulasi atau LKE masif, **wajib gunakan eager loading** (misal: `with(['subComponents.criteria.evaluations.documents'])`) guna menghindari isu N+1 Query.

4. **Validasi & Integritas File:**
   - Unggahan dokumen evidence **wajib berformat PDF** dengan batas ukuran maksimal 10 MB (`mimes:pdf|max:10240`).
   - Simpan dokumen pada disk `public` di direktori `documents/`.

5. **Keamanan Rute & Controller:**
   - Semua rute baru di bawah dashboard wajib dilindungi middleware `auth`.
   - Gunakan middleware Spatie `role:...` sesuai matriks hak akses pada rute `routes/web.php`.
   - Jangan pernah mengizinkan modifikasi evaluasi jika evaluasi tersebut telah berstatus `disetujui` kecuali dilakukan oleh `super_admin`.


# 📖 Dokumentasi Proyek

## 📑 Daftar Isi
- [✨ Fitur Utama](#-fitur-utama)  
- [🚀 Teknologi yang Digunakan](#-teknologi-yang-digunakan)  
- [⚙️ Panduan Instalasi & Setup](#-panduan-instalasi--setup)  
- [🔑 Akun Default](#-akun-default)  
- [📂 Struktur Proyek](#-struktur-proyek)  
- [🤝 Kontribusi](#-kontribusi)  
- [📄 Lisensi](#-lisensi)

---

## ✨ Fitur Utama

### 🧑‍💼 Untuk Pengguna (Karyawan)
- **Dashboard Personal**: Menampilkan ringkasan status tiket yang telah dibuat.
- **Pembuatan Tiket Terstruktur**: Formulir intuitif dengan pilihan kategori, departemen, dan upload bukti.
- **Pelacakan Tiket Real-time**: Lihat status terkini dari tiket yang diajukan.
- **Knowledge Base**: Akses panduan, solusi, dan FAQ secara mandiri.
- **Otentikasi Google**: Login dan registrasi mudah menggunakan akun Google.

### 👨‍💻 Untuk Tim Teknis (Developer/Admin)
- **Developer Dashboard**: Panel analitik dengan grafik dan statistik kunci.
- **Manajemen Tiket Terpusat**:
  - *Ambil Tiket*: Lihat dan tangani tiket baru.
  - *Tiket Saya*: Kelola tiket yang sedang ditangani.
  - *Kelola Semua Tiket*: Admin dapat mengelola semua tiket dalam sistem.
- **Manajemen Akun**: CRUD untuk data akun pengguna dan developer.
- **Manajemen Knowledge Base**: CRUD untuk artikel, video, dan dokumen solusi.
- **Sistem Peran (RBAC)**: Pemisahan hak akses antara user dan developer.

---

## 🚀 Teknologi yang Digunakan

| Teknologi     | Deskripsi                                                                 |
|---------------|---------------------------------------------------------------------------|
| Laravel 10    | Framework PHP modern untuk membangun aplikasi web yang elegan dan kuat.  |
| PostgreSQL    | Sistem basis data relasional objek yang tangguh dan open-source.         |
| Tailwind CSS  | Framework utility-first untuk desain frontend yang efisien dan fleksibel.|
| Alpine.js     | Framework JavaScript ringan untuk interaktivitas minimalis.              |
| Vite          | Build tool generasi baru untuk frontend dengan kecepatan tinggi.         |

---

## ⚙️ Panduan Instalasi & Setup

Ikuti langkah-langkah berikut untuk menjalankan proyek secara lokal:

### 1. Prasyarat
Pastikan Anda sudah menginstal:
- PHP ≥ 8.2  
- Composer ≥ 2.x  
- Node.js & NPM (atau Yarn)  
- PostgreSQL

### 2. Clone & Instalasi
```bash
git clone https://github.com/username/nama-proyek-anda.git
cd nama-proyek-anda

# Instal dependensi PHP
composer install

# Instal dependensi JavaScript
npm install
```

### 3. Konfigurasi Lingkungan
```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env` sesuai pengaturan database lokal Anda:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sitikdb
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

**(Opsional)**: Aktifkan login Google dengan menambahkan:
```env
GOOGLE_CLIENT_ID=xxxxxxxx.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=xxxxxxxx
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### 4. Setup Database

Buat database `sitikdb` di PostgreSQL, lalu pilih salah satu metode:

#### 🔹 Opsi A – Menggunakan Migrasi (Direkomendasikan)
```bash
php artisan migrate
```
> 💡 Gunakan seeder (`php artisan make:seeder`) untuk mengisi data awal seperti `roles` dan akun admin.

#### 🔹 Opsi B – Mengimpor File SQL
```bash
psql -U nama_user_db -d sitikdb -f path/ke/sitikdb.sql
```

### 5. Finalisasi & Menjalankan Aplikasi
```bash
# Buat symbolic link agar file upload dapat diakses
php artisan storage:link

# Jalankan frontend
npm run dev

# Jalankan server Laravel
php artisan serve
```

Buka browser dan akses: [http://localhost:8000](http://localhost:8000)

---

## 🔑 Akun Default

Jika menggunakan file `.sql`, Anda dapat login dengan akun berikut:

| Peran      | Email             | Password   |
|------------|-------------------|------------|
| Developer  | dev@example.com   | password   |
| User       | user@example.com  | password   |

---

## 📂 Struktur Proyek

```
.
├── app
│   ├── Http
│   │   ├── Controllers      # Controller untuk Auth, User, Developer
│   │   └── Middleware       # Middleware kustom (contoh: CheckRole)
│   └── Models               # Model Eloquent
├── database
│   ├── migrations           # Skema database
│   └── seeders              # Seeder untuk data awal
├── resources
│   ├── css                  # File CSS utama
│   ├── js                   # File JavaScript utama
│   └── views                # File Blade (UI)
└── routes
    └── web.php              # Rute web aplikasi
```

---

## 🤝 Kontribusi

Kami menyambut kontribusi dari siapa pun!  
Silakan fork repositori ini, buat branch baru, dan kirimkan Pull Request untuk fitur atau perbaikan Anda.

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

SITIK - Sistem Informasi Ticketing & Knowledge Base
SITIK adalah aplikasi web helpdesk modern yang dirancang untuk menyederhanakan proses pelaporan masalah, manajemen tiket, dan dokumentasi solusi di dalam sebuah organisasi. Dibangun dengan Laravel dan PostgreSQL, aplikasi ini menyediakan platform yang terpusat dan efisien bagi pengguna untuk meminta bantuan dan bagi tim teknis (developer) untuk menyelesaikan masalah secara efektif.

Aplikasi ini memisahkan alur kerja antara Pengguna (yang melaporkan masalah) dan Developer (yang menangani masalah), menciptakan sistem yang terorganisir dan mudah dilacak.

✨ Fitur Utama
Untuk Pengguna (Karyawan)
Dashboard Personal: Melihat ringkasan status tiket yang pernah dibuat.

Pembuatan Tiket Terstruktur: Formulir intuitif untuk melaporkan masalah lengkap dengan pilihan kategori, departemen, dan lampiran bukti.

Pelacakan Tiket Real-time: Pengguna dapat melihat status terkini dari setiap tiket yang mereka ajukan.

Knowledge Base: Akses mandiri ke arsip solusi, panduan, dan FAQ untuk menyelesaikan masalah umum tanpa perlu membuat tiket.

Notifikasi: Pemberitahuan (jika diimplementasikan) untuk setiap pembaruan status tiket.

Otentikasi Google: Kemudahan login dan registrasi menggunakan akun Google.

Untuk Tim Teknis (Developer/Admin)
Developer Dashboard: Panel analitik yang menampilkan statistik kunci seperti jumlah tiket masuk, tiket yang sedang dikerjakan, dan tiket selesai. Dilengkapi dengan grafik visual.

Manajemen Tiket Terpusat:

Ambil Tiket: Melihat daftar semua tiket baru yang belum ditangani dan mengambilnya untuk dikerjakan.

Tiket Saya: Mengelola semua tiket yang sedang menjadi tanggung jawabnya.

Kelola Semua Tiket: Panel admin untuk melihat dan mengelola seluruh tiket dalam sistem.

Manajemen Akun: Fitur CRUD (Create, Read, Update, Delete) untuk semua akun pengguna dan developer dalam sistem.

Manajemen Knowledge Base: Fitur CRUD untuk membuat, mengedit, dan menghapus artikel, panduan, atau video solusi yang akan ditampilkan kepada pengguna.

Sistem Peran (Role-based Access Control): Pemisahan hak akses yang jelas antara user dan developer.

🚀 Teknologi yang Digunakan
Proyek ini dibangun menggunakan teknologi modern dan andal:

Teknologi

Deskripsi

Laravel 10

Framework PHP progresif untuk membangun aplikasi web yang elegan dan kokoh.

PostgreSQL

Sistem database relasional objek yang kuat dan open-source.

Tailwind CSS

Framework CSS utility-first untuk membangun desain kustom dengan cepat.

Alpine.js

Framework JavaScript minimalis untuk menambahkan interaktivitas pada antarmuka.

Vite

Build tool generasi baru yang memberikan pengalaman pengembangan frontend yang sangat cepat.

⚙️ Panduan Instalasi & Setup
Ikuti langkah-langkah berikut untuk menjalankan proyek ini di lingkungan lokal Anda.

1. Prasyarat
PHP 8.2 atau lebih tinggi

Composer versi 2.x

Node.js & NPM (atau Yarn)

PostgreSQL Database Server

2. Clone Repositori
Clone repositori ini ke komputer lokal Anda:

git clone https://github.com/username/nama-proyek-anda.git
cd nama-proyek-anda

3. Instalasi Dependensi
Instal semua dependensi PHP dan JavaScript:

# Instal dependensi PHP
composer install

# Instal dependensi JavaScript
npm install

4. Konfigurasi Lingkungan
Salin file .env.example menjadi .env:

cp .env.example .env

Buat kunci aplikasi baru:

php artisan key:generate

Buka file .env dan konfigurasikan koneksi database Anda. Pastikan nama database, user, dan password sudah benar.

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sitikdb # Ganti dengan nama database Anda
DB_USERNAME=postgres # Ganti dengan username database Anda
DB_PASSWORD=password # Ganti dengan password database Anda

(Opsional) Jika Anda ingin menggunakan fitur login Google, tambahkan kredensial Anda:

GOOGLE_CLIENT_ID=xxxxxxxx.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=xxxxxxxx
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

5. Setup Database
Buat database baru di PostgreSQL dengan nama yang sama seperti yang Anda atur di .env.

Opsi A (Rekomendasi - Menggunakan Migrasi): Jalankan semua migrasi untuk membuat struktur tabel dari awal.

php artisan migrate:fresh --seeder

6. Buat Symbolic Link
Buat symbolic link agar file yang di-upload (seperti lampiran tiket atau PDF knowledge base) dapat diakses dari web.

php artisan storage:link

7. Jalankan Aplikasi
Kompilasi aset frontend:

npm run dev

Jalankan server pengembangan lokal di terminal lain:

php artisan serve

Aplikasi Anda sekarang akan berjalan di http://localhost:8000.

🔑 Akun Default
Anda dapat menggunakan akun berikut untuk login dan mencoba aplikasi (jika Anda menggunakan file .sql yang disediakan):

Peran

Email

Password

Developer

dev@example.com

password

User

user@example.com

password

📂 Struktur Proyek
Berikut adalah gambaran singkat dari direktori-direktori penting:

.
├── app
│   ├── Http
│   │   ├── Controllers   # Berisi semua controller (Auth, User, Developer)
│   │   └── Middleware    # Berisi middleware kustom (CheckRole)
│   └── Models            # Berisi semua model Eloquent
├── database
│   ├── migrations        # Skema struktur database
│   └── seeders           # Data awal untuk database
├── resources
│   ├── css               # File CSS utama
│   ├── js                # File JavaScript utama
│   └── views             # Berisi semua file Blade (tampilan)
└── routes
    └── web.php           # Definisi semua rute web aplikasi

🤝 Kontribusi
Kontribusi sangat kami hargai! Jika Anda ingin berkontribusi, silakan fork repositori ini, buat branch baru untuk fitur Anda, dan kirimkan Pull Request.

📄 Lisensi
Proyek ini dilisensikan di bawah Lisensi MIT.

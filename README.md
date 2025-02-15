# RetaliWeb

## Deskripsi
RetaliWeb adalah aplikasi berbasis web yang dirancang untuk memantau dan mengelola perjalanan Tour Leader dengan fitur-fitur seperti pelacakan lokasi, manajemen grup, dan dokumentasi perjalanan. Aplikasi ini dibangun menggunakan framework Laravel dan memiliki integrasi dengan aplikasi mobile berbasis Flutter.

## Fitur Utama
- **Autentikasi**: Login dan logout untuk Tour Leader.
- **Manajemen Luggage**: Pemindaian dan pelacakan bagasi.
- **Manajemen Grup**: Melihat informasi grup dan jadwal perjalanan.
- **Pelacakan Lokasi**: Update lokasi Tour Leader secara real-time.
- **Notifikasi**: Menerima dan membaca notifikasi.
- **Manajemen Konten**: Mengelola dan membagikan konten dalam aplikasi.
- **Laporan Perjalanan**: Membuat dan mengelola laporan perjalanan Tour Leader.

## Persyaratan Sistem
- PHP 8.1 atau lebih baru
- Composer
- Laravel 10
- MySQL/MariaDB
- Node.js & NPM (untuk pengelolaan frontend, jika diperlukan)

## Instalasi
Ikuti langkah-langkah berikut untuk menjalankan aplikasi di lingkungan lokal:

1. Clone repository:
   ```bash
   git clone https://github.com/Dkuli/RetaliWeb.git
   ```
2. Masuk ke direktori proyek:
   ```bash
   cd RetaliWeb
   ```
3. Instal dependensi:
   ```bash
   composer install
   ```
4. Salin file `.env.example` menjadi `.env` dan atur konfigurasi yang diperlukan:
   ```bash
   cp .env.example .env
   ```
5. Generate application key:
   ```bash
   php artisan key:generate
   ```
6. Jalankan migrasi database:
   ```bash
   php artisan migrate
   ```
7. Jalankan server aplikasi:
   ```bash
   php artisan serve
   ```

## Konfigurasi Tambahan
- Pastikan `.env` sudah dikonfigurasi dengan benar, terutama pengaturan database dan Firebase untuk notifikasi.
- Gunakan `php artisan storage:link` jika aplikasi memerlukan penyimpanan file di `storage/app/public`.



🍌 Tani Pisang — Sistem Manajemen Kebun Pisang Cavendish
Aplikasi manajemen usaha tani pisang cavendish berbasis web, dibangun untuk mengelola operasional kebun nyata milik pribadi — mulai dari pencatatan keuangan, pemantauan progress lahan, pelaporan masalah, pengelolaan siklus panen, hingga laporan konsolidasi untuk evaluasi usaha.
> ⚠️ **Catatan:** Aplikasi ini sedang aktif digunakan untuk operasional bisnis nyata dan berisi data finansial pribadi, sehingga tidak dibuka untuk akses publik. Source code tersedia di sini untuk keperluan portofolio dan bisa dijalankan sendiri secara lokal — lihat bagian [Instalasi Lokal](#-instalasi-lokal).
---
📌 Latar Belakang
Proyek ini lahir dari kebutuhan nyata: mengelola usaha tani pisang cavendish yang melibatkan kemitraan tiga pihak (pemodal/pengelola, penyedia bibit sekaligus pembeli, dan pemilik lahan), dengan skema kepemilikan lahan yang bisa berbeda-beda (sewa vs bagi hasil), serta siklus panen pisang yang unik — jumlah pohon produktif berubah tiap siklus karena sistem anakan (tunas) yang dipangkas dan bisa dijual, dipindah ke lahan lain, atau dijadikan pupuk.
Kebutuhan spesifik inilah yang membuat aplikasi generik (spreadsheet atau software akuntansi umum) terasa kurang pas — sehingga dibangun sistem khusus yang memodelkan alur bisnis ini secara langsung.
✨ Fitur Utama
Manajemen Lahan — setiap lahan berdiri mandiri dengan datanya sendiri (sistem "lahan-first"), bisa switch antar lahan tanpa data tercampur
Keuangan — pencatatan transaksi dengan dukungan transaksi non-kas (bibit hibah, anakan yang dipindah dengan nilai estimasi)
Progress Tracking — log perkembangan lahan berkala dengan foto, untuk pemantauan jarak jauh
Trouble Report — pelaporan masalah dengan alur status (Dilaporkan → Ditindaklanjuti → Selesai) dan riwayat tindak lanjut
Panen & Anakan — pencatatan tiap siklus panen dengan tracking nasib anakan (dijual sebagai bibit / dipindah ke lahan lain / dijadikan pupuk)
Partner & Kesepakatan — pengelolaan mitra dengan sistem versioning kesepakatan (riwayat kesepakatan lama tetap tersimpan saat direvisi)
Aset — pencatatan aset (pompa air, dll) dengan alokasi penggunaan lintas lahan
Jadwal & Reminder — jadwal perawatan berulang dengan riwayat pelaksanaan
Role-Based Access Control — pemisahan akses Admin (pemilik) dan Staff (anggota tim lapangan)
Dashboard & Visualisasi — grafik tren keuangan, perbandingan antar lahan, ringkasan aset
Export Laporan PDF — laporan konsolidasi untuk evaluasi berkala
🛠️ Tech Stack
Backend: Laravel 11
Frontend: Blade + Alpine.js + Tailwind CSS
Database: MySQL
PDF Generation: barryvdh/laravel-dompdf
Charting: Chart.js
Auth: Laravel Breeze
📸 Screenshot
<!-- Tambahkan screenshot di sini, contoh: -->
<!-- ![Dashboard](docs/screenshots/dashboard.png) -->
<!-- ![Detail Lahan](docs/screenshots/detail-lahan.png) -->
<!-- ![Panen](docs/screenshots/panen.png) -->
(Screenshot menyusul — lihat folder `docs/screenshots/`)
🚀 Instalasi Lokal
Untuk menjalankan aplikasi ini di komputer sendiri (dengan data contoh, bukan data asli):
```bash
# Clone repository
git clone https://github.com/username/tani-pisang.git
cd tani-pisang

# Install dependency
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Konfigurasi database di .env, lalu jalankan migrasi + data contoh
php artisan migrate --seed

# Buat symlink storage untuk upload foto
php artisan storage:link

# Jalankan server development
php artisan serve
npm run dev
```
Setelah itu, buka `http://localhost:8000`, register akun baru, lalu jadikan admin lewat Tinker:
```bash
php artisan tinker
```
```php
$user = App\Models\User::where('email', 'emailkamu@example.com')->first();
$user->role = 'admin';
$user->save();
```
🤖 Catatan Transparansi
Aplikasi ini dibangun dengan bantuan Claude AI (Anthropic) sebagai asisten pemrograman — mulai dari diskusi arsitektur, penulisan kode, hingga debugging saat deployment. Saya berperan mendefinisikan kebutuhan bisnis, mengambil keputusan desain, melakukan testing menyeluruh, dan menyelesaikan proses deploy ke production secara mandiri.
Saya membagikan ini secara terbuka karena percaya transparansi soal proses kerja itu penting — terutama di era di mana AI menjadi bagian normal dari alur kerja developer, sama seperti dokumentasi, Stack Overflow, atau kolaborasi dengan sesama engineer.
📄 Lisensi
Proyek pribadi untuk keperluan operasional usaha tani sendiri.
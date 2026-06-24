# SIMEKS - Sistem Informasi Manajemen Ekstrakurikuler Sekolah

**SIMEKS** (Sistem Informasi Manajemen Ekstrakurikuler Sekolah) adalah platform berbasis web yang dirancang khusus untuk **SMAN 2 Sukatani** guna mengelola seluruh kegiatan ekstrakurikuler secara digital, efisien, dan terintegrasi. Sistem ini memudahkan siswa dalam mengeksplorasi minat bakat serta membantu pihak sekolah dalam manajemen data anggota, absensi, dan prestasi.

---

## ✨ Fitur Utama

### 👤 Untuk Siswa
*   **Pendaftaran Online**: Memilih dan mendaftar kegiatan ekskul tanpa perlu formulir kertas.
*   **Dashboard Personal**: Pantau status keanggotaan, jadwal kegiatan, dan prestasi yang diraih.
*   **Pencatatan Absensi**: Melihat riwayat kehadiran dalam setiap pertemuan ekskul.
*   **Pengumuman & Notifikasi**: Dapatkan update penting langsung dari dashboard.
*   **E-Sertifikat**: Unduh sertifikat prestasi atau partisipasi secara langsung.

### 🛡️ Untuk Administrator
*   **Manajemen Master Data**: Kelola data siswa, daftar ekskul, dan pelatih (pembina).
*   **Verifikasi Pendaftaran**: Sistem persetujuan/penolakan pendaftaran siswa secara real-time.
*   **Manajemen Kehadiran**: Input dan rekap absensi anggota per kegiatan.
*   **Pencatatan Prestasi**: Dokumentasi piala dan penghargaan yang diraih siswa.
*   **Pelaporan Lengkap**: Generate laporan dalam format PDF atau Excel untuk dokumentasi sekolah.
*   **Monitoring Keamanan**: Log aktivitas sistem untuk memantau keamanan data.

---

## 🚀 Teknologi yang Digunakan

| Komponen | Teknologi |
| :--- | :--- |
| **Bahasa Pemrograman** | PHP 8.x, JavaScript |
| **Database** | MySQL (MariaDB) |
| **Frontend Framework** | Bootstrap 5, CSS3, HTML5 |
| **Desain & Ikon** | FontAwesome 6, Google Fonts (Inter) |
| **Animasi** | Animate.css, AOS (Animation on Scroll) |
| **Reporting** | FPDF / DOMPDF (PDF Export), PHPExcel / SimpleXLSX |

---

## 📁 Struktur Proyek

```text
SIMEKS/
├── admin/          # Modul manajemen untuk Administrator
├── siswa/          # Dashboard dan fitur untuk Siswa
├── config/         # Konfigurasi database dan sistem
├── includes/       # Komponen reusable (Header, Footer, Sidebar)
├── database/       # File SQL database dan skema
├── assets/         # Resource statis (CSS, JS, Images, Uploads)
├── uploads/        # Direktori penyimpanan file yang diupload
└── index.php       # Landing page utama
```

---

## 🛠️ Instalasi & Persiapan

1.  **Clone atau Unduh** file proyek SIMEKS.
2.  Pindahkan folder ke direktori `htdocs` (jika menggunakan XAMPP).
3.  Buka **phpMyAdmin** dan buat database baru dengan nama `simeks_db`.
4.  Impor file database yang terletak di `database/schema.sql`.
5.  Sesuaikan konfigurasi database di file `config/koneksi.php` (jika perlu).
6.  Akses sistem melalui browser di alamat `http://localhost/SIMEKS`.

---

## 🔑 Detail Login Default
*   **Admin**: `admin@simeks.com` / `password`
*   **Siswa**: Sesuai dengan akun yang didaftarkan melalui menu registrasi.

---
© 2026 SIMEKS - SMAN 2 Sukatani. All Rights Reserved.

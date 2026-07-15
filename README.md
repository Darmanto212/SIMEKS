# SIMEKS - Sistem Informasi Manajemen Ekstrakurikuler Sekolah

**SIMEKS** (Sistem Informasi Manajemen Ekstrakurikuler Sekolah) adalah platform berbasis web modern yang dirancang khusus untuk **SMAN 2 Sukatani** guna mengelola seluruh kegiatan ekstrakurikuler secara digital, efisien, dan terintegrasi. Platform ini memberikan pengalaman pengguna yang responsif, minimalis, dan berkinerja tinggi baik bagi siswa, pembina, maupun administrator sekolah.

---

## ✨ Fitur Utama & Pembaruan Sistem

### 👤 1. Modul Siswa (Dashboard Siswa)
*   **Dashboard Personal Minimalis (Desain Baru):** Tampilan dasbor ringkas satu layar penuh yang seimbang secara visual (grid horizontal) yang menyandingkan:
    *   **Jadwal Latihan Pekan Ini** berdampingan dengan grafik donat **Statistik Kehadiran** (kehadiran real-time).
    *   **Pengumuman Terbaru** berdampingan dengan linimasa **Status Pendaftaran**.
*   **Pendaftaran Ekskul Online:** Memilih dan mendaftar kegiatan ekskul secara online dengan sistem otomatisasi kuota per ekskul pada periode akademik aktif.
*   **Pencatatan Absensi Mandiri:** Memantau persentase dan riwayat kehadiran dalam setiap sesi pertemuan ekskul yang dicatat oleh pembina.
*   **Kelola Prestasi:** Pencatatan mandiri prestasi akademik/non-akademik siswa yang dapat ditinjau oleh pihak sekolah.

### 🛡️ 2. Modul Admin & Pembina (Dashboard Admin & Pembina)
*   **Dasbor Admin Master & Pembina Padat (Desain Baru):** Penataan letak layout horizontal berdampingan untuk grafik statistik "Tren Pendaftaran (7 Hari Terakhir)" dan "Pendaftaran Terbaru" guna efisiensi layar browser skala 100% tanpa scroll berlebih.
*   **Kelola Periode Akademik (Fitur Baru):** Manajemen terpusat untuk menambahkan, mengedit, dan mengaktifkan tahun ajaran serta semester. Sistem menjamin keamanan data dengan mencegah bentrok status aktif ganda secara otomatis.
*   **Pencarian Instan (Real-time Search):** Filter pencarian data instan berbasis Client-Side JS tanpa memicu reload halaman pada halaman:
    *   *Kelola Siswa*
    *   *Kelola Admin*
    *   *Kelola Pendaftaran*
    *   *Kelola Pengumuman*
    *   *Audit Trail (Logs)*
*   **Tabel Scrollable & Sticky Header:** Seluruh tabel panjang kini dibatasi tinggi maksimalnya (`550px` / `500px`) dengan header tabel tetap melayang (*sticky*) saat digulirkan agar layout tetap rapi.
*   **Pusat Laporan 3 Kolom Minimalis:** Halaman cetak/ekspor laporan yang dikemas menjadi 3 kolom horizontal:
    1.  *Siswa & Absensi* (Unduh rekap absensi)
    2.  *Rekap Prestasi* (Unduh rekap prestasi)
    3.  *Daftar Ekskul* (Unduh statistik ekskul - khusus admin utama)
*   **Audit Trail & Log Notifikasi:** Pelacakan aktivitas sistem lengkap dengan riwayat log notifikasi terlipat (hanya menampilkan 5 terbaru secara langsung dengan tombol ekspansi "Tampilkan Lebih Banyak").

---

## 🚀 Teknologi & Arsitektur

| Komponen | Teknologi |
| :--- | :--- |
| **Backend & Autentikasi** | PHP 8.x (PDO Secure Queries), Sesi Terproteksi |
| **Database Engine** | MySQL (MariaDB) dengan Relasi Foreign Key Terintegrasi |
| **Frontend Layout** | Bootstrap 5, Vanilla CSS, HTML5 |
| **Ikon & Tipografi** | FontAwesome 6, Google Fonts (Inter / Outfit) |
| **Grafik & Visual** | Chart.js (Doughnut & Line Charts) |
| **Pencarian & Animasi** | Vanilla JS Instant Filter, Animate.css, AOS |

---

## 📁 Struktur Direktori Proyek

```text
SIMEKS/
├── admin/          # Panel kontrol Administrator & Pembina (Eskul, Absensi, Laporan, Periode)
├── siswa/          # Panel kontrol Siswa (Pendaftaran, Jadwal, Absensi, Prestasi)
├── config/         # Konfigurasi koneksi database PDO (koneksi.php)
├── includes/       # Komponen visual reusable (Navbar, Sidebar, Header, Footer)
├── database/       # Skema migrasi & rollback basis data (schema_baru.sql)
├── assets/         # Aset statis (CSS kustom, JavaScript, Gambar Logo)
├── uploads/        # Direktori penyimpanan bukti pendaftaran/prestasi siswa
└── index.php       # Landing Page utama SMAN 2 Sukatani (Navigasi & Pengumuman Sekolah)
```

---

## 🛠️ Instalasi & Konfigurasi Lokal

1.  **Unduh File Proyek:** Ekstrak berkas SIMEKS ke dalam direktori server lokal Anda (contoh: `C:\xampp\htdocs\SIMEKS-main`).
2.  **Siapkan Database:**
    *   Jalankan server **Apache** dan **MySQL** melalui XAMPP Control Panel.
    *   Buka browser dan buka **phpMyAdmin** (`http://localhost/phpmyadmin`).
    *   Buat database baru bernama `simeks_db`.
    *   Impor file database yang terletak di `database/schema_baru.sql`.
3.  **Konfigurasi Koneksi:**
    *   Sesuaikan konfigurasi database (Host, Username, Password, Database Name) pada berkas `config/koneksi.php` jika diperlukan.
4.  **Jalankan Aplikasi:**
    *   Akses platform melalui browser pada alamat: `http://localhost/SIMEKS-main`.

---

## 🔑 Hak Akses & Kredensial Default

*   **Role Admin Utama (Master):**
    *   **Email:** `admin@simeks.com`
    *   **Password:** `password`
*   **Role Pembina Ekskul:**
    *   **Email:** Sesuai email pembina yang didaftarkan oleh Admin Utama.
    *   **Password:** Password yang diatur oleh Admin Utama/Pembina bersangkutan.
*   **Role Siswa:**
    *   Pendaftaran mandiri melalui tombol **Registrasi** di halaman utama (Login).

---
© 2026 SIMEKS - SMAN 2 Sukatani. All Rights Reserved.

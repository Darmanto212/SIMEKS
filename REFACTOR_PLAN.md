# Rencana Refaktorisasi Keamanan & Otorisasi SIMEKS (REFACTOR_PLAN.md)

Dokumen ini berisi rencana persiapan, penanganan, dan verifikasi untuk proses refaktorisasi sistem **SIMEKS** berdasarkan hasil audit keamanan sebelumnya.

---

## 1. Kondisi Awal Proyek (Baseline)
Sebelum proses refaktorisasi dimulai, kondisi proyek saat ini diidentifikasi sebagai berikut:
*   **Teknologi**: PHP Native (monolitik), MySQL/MariaDB (PDO), Bootstrap 5, Vanilla JS.
*   **Keamanan**: 
    *   Mengalami kerentanan Remote Code Execution (RCE) pada fitur unggah gambar ekskul.
    *   Memiliki 10 berkas debug/sampah pengembangan di direktori utama yang dapat diakses publik.
    *   Mengalami kebocoran informasi data pengguna (`check_users.php`) dan struktur database (`check_db.php`).
    *   Mengalami kerentanan Stored XSS pada dashboard siswa dan tidak memiliki proteksi CSRF di seluruh form mutasi data.
*   **Otorisasi**: Fungsi `check_auth('admin')` meloloskan admin master dan pembina secara bersamaan ke folder `admin/`, yang menyebabkan bypass hak akses pada kelola pengumuman (Pembina dapat mengedit pengumuman) dan pelanggaran tugas (Admin dapat memproses absensi/pendaftaran).
*   **Database**: Berkas skema dump `database/simeks_db.sql` tertinggal dan belum mencakup kolom `pembina_id`, `target_pertemuan`, `tingkat` prestasi, serta tabel `eskul_libur`.

---

## 2. Titik Pemulihan (Recovery Point)
Untuk mengantisipasi kegagalan selama proses refaktorisasi, titik pemulihan telah dibuat menggunakan Git:
*   **Commit ID Baseline**: `d4e1a86b976db573ab06e788bc5f89104523f380` (Keadaan kerja bersih / clean working tree).
*   **Git Branch Pemulihan**: `backup-before-refactor`
*   **Git Tag Pemulihan**: `v1.0-audit-baseline`

---

## 3. Rencana Perubahan per Tahap (5 Fase Refaktorisasi)

### Tahap 1: Pembersihan Berkas Sampah & Pengamanan Database Basic
*   **Tujuan**: Menghapus titik masuk berbahaya (file debug) dan menonaktifkan cetakan error SQL yang bocor.
*   **Daftar File yang Disentuh**:
    *   `[DELETE]` [reset_admin_password.php](file:///c:/xampp/htdocs/SIMEKS-main/reset_admin_password.php)
    *   `[DELETE]` [check_users.php](file:///c:/xampp/htdocs/SIMEKS-main/check_users.php)
    *   `[DELETE]` [check_db.php](file:///c:/xampp/htdocs/SIMEKS-main/check_db.php)
    *   `[DELETE]` [create_test_pembina.php](file:///c:/xampp/htdocs/SIMEKS-main/create_test_pembina.php)
    *   `[DELETE]` [migrate_rbac.php](file:///c:/xampp/htdocs/SIMEKS-main/migrate_rbac.php)
    *   `[DELETE]` [migrate_libur.php](file:///c:/xampp/htdocs/SIMEKS-main/migrate_libur.php)
    *   `[DELETE]` [migrate_perfection.php](file:///c:/xampp/htdocs/SIMEKS-main/migrate_perfection.php)
    *   `[DELETE]` [migrate_logs.php](file:///c:/xampp/htdocs/SIMEKS-main/migrate_logs.php)
    *   `[DELETE]` [migrate_pengumuman.php](file:///c:/xampp/htdocs/SIMEKS-main/migrate_pengumuman.php)
    *   `[DELETE]` [migrate_prestasi.php](file:///c:/xampp/htdocs/SIMEKS-main/migrate_prestasi.php)
    *   `[MODIFY]` [config/koneksi.php](file:///c:/xampp/htdocs/SIMEKS-main/config/koneksi.php) (Menyembunyikan output detail exception error database).
    *   `[MODIFY]` [proses_register.php](file:///c:/xampp/htdocs/SIMEKS-main/proses_register.php) (Menyembunyikan output detail catch exception database).
*   **Rencana Pengujian (Verifikasi)**:
    *   Akses kesepuluh file di atas melalui browser (misal `http://localhost/SIMEKS-main/check_users.php`), pastikan server mengembalikan kode respon HTTP 404 (Not Found).
    *   Uji proses registrasi siswa baru dan pastikan tidak ada error database mentah yang tampil di layar jika terjadi kegagalan data ganda.

---

### Tahap 2: Pengamanan Fitur Unggah & Proteksi Sesi
*   **Tujuan**: Mencegah kerentanan Remote Code Execution (RCE) melalui filter upload, serta memitigasi Session Fixation.
*   **Daftar File yang Disentuh**:
    *   `[MODIFY]` [admin/kelola-eskul.php](file:///c:/xampp/htdocs/SIMEKS-main/admin/kelola-eskul.php) (Validasi tipe MIME dan whitelist ekstensi `.jpg`, `.jpeg`, `.png` pada file gambar ekskul).
    *   `[MODIFY]` [proses_login.php](file:///c:/xampp/htdocs/SIMEKS-main/proses_login.php) (Penerapan `session_regenerate_id(true)` pasca login sukses & perapihan import global).
*   **Rencana Pengujian (Verifikasi)**:
    *   Coba unggah berkas berekstensi `.php`, `.phtml`, atau `.txt` pada form tambah/edit ekskul. Sistem harus menolak berkas tersebut secara tegas.
    *   Unggah berkas gambar valid (`.png` / `.jpg`), pastikan lolos verifikasi dan tersimpan dengan baik.
    *   Buka browser Developer Tools (Tab Application/Storage -> Cookies), catat ID Sesi sebelum login. Lakukan login, lalu verifikasi bahwa ID Sesi berganti menjadi ID baru.

---

### Tahap 3: Pemisahan Granular Otorisasi & Hak Akses
*   **Tujuan**: Memastikan Admin Master tidak dapat memproses data operasional (absensi, pendaftaran, prestasi) dan Pembina tidak dapat mengelola aspek sekolah umum (pengumuman) atau data pembina lain.
*   **Daftar File yang Disentuh**:
    *   `[MODIFY]` [includes/auth_check.php](file:///c:/xampp/htdocs/SIMEKS-main/includes/auth_check.php) (Membuat fungsi otorisasi ketat baru: `check_admin_only()` and `check_pembina_only()`).
    *   `[MODIFY]` [admin/kelola-pengumuman.php](file:///c:/xampp/htdocs/SIMEKS-main/admin/kelola-pengumuman.php) (Proteksi ketat hanya untuk Admin Master).
    *   `[MODIFY]` [admin/kelola-pendaftaran.php](file:///c:/xampp/htdocs/SIMEKS-main/admin/kelola-pendaftaran.php) (Hanya Pembina yang diizinkan memproses POST persetujuan pendaftaran).
    *   `[MODIFY]` [admin/kelola-absensi.php](file:///c:/xampp/htdocs/SIMEKS-main/admin/kelola-absensi.php) (Hanya Pembina terkait yang diizinkan mengirim POST presensi).
    *   `[MODIFY]` [admin/kelola-prestasi.php](file:///c:/xampp/htdocs/SIMEKS-main/admin/kelola-prestasi.php) (Hanya Pembina terkait yang diizinkan mengirim POST manipulasi prestasi).
    *   `[MODIFY]` [admin/kelola-siswa.php](file:///c:/xampp/htdocs/SIMEKS-main/admin/kelola-siswa.php) (Batasi edit profil siswa agar hanya bisa dilakukan oleh admin master).
*   **Rencana Pengujian (Verifikasi)**:
    *   Login sebagai Pembina: akses halaman `kelola-pengumuman.php`. Sistem harus menolak akses dan mengarahkan kembali ke dashboard.
    *   Login sebagai Admin Master: buka halaman `kelola-absensi.php` / `kelola-pendaftaran.php` / `kelola-prestasi.php`. Halaman harus bersifat read-only (tidak ada tombol aksi edit/setuju/hapus atau input dinonaktifkan). Jika memaksakan request POST, backend harus menolak aksi tersebut.

---

### Tahap 4: Perlindungan Terhadap Serangan XSS & CSRF
*   **Tujuan**: Mengeliminasi kerentanan Stored XSS pada dashboard siswa dan menutup celah CSRF di seluruh form mutasi data.
*   **Daftar File yang Disentuh**:
    *   `[MODIFY]` [siswa/dashboardsiswa.php](file:///c:/xampp/htdocs/SIMEKS-main/siswa/dashboardsiswa.php) (Sanitasi output dinamis `$user['nama']` and `$row->jadwal` menggunakan `htmlspecialchars`).
    *   `[MODIFY]` [includes/auth_check.php](file:///c:/xampp/htdocs/SIMEKS-main/includes/auth_check.php) (Pembuatan helper generate & verify CSRF token).
    *   `[MODIFY]` [siswa/daftar-eskul.php](file:///c:/xampp/htdocs/SIMEKS-main/siswa/daftar-eskul.php) (Mengubah pendaftaran dari link GET menjadi POST dengan token CSRF).
    *   `[MODIFY]` Seluruh form mutasi di direktori `admin/` dan `siswa/` (Penyisipan field hidden `csrf_token` dan pengecekan di backend file penerima POST).
*   **Rencana Pengujian (Verifikasi)**:
    *   Masukkan karakter script HTML (seperti `<script>alert('xss')</script>`) ke dalam nama siswa atau jadwal eskul lewat database. Buka halaman dashboard siswa, pastikan script dirender sebagai teks biasa dan tidak dieksekusi.
    *   Kirimkan form POST (seperti update profil atau approval pendaftaran) tanpa menyertakan `csrf_token` atau dengan token yang tidak cocok. Sistem harus memblokir request tersebut dan menampilkan pesan kesalahan ("Invalid CSRF Token").

---

### Tahap 5: Harmonisasi Skema Database & Verifikasi Akhir
*   **Tujuan**: Memperbarui berkas database SQL dump agar merepresentasikan skema database terkini secara akurat.
*   **Daftar File yang Disentuh**:
    *   `[MODIFY]` [database/simeks_db.sql](file:///c:/xampp/htdocs/SIMEKS-main/database/simeks_db.sql) (Penyatuan query migrasi tabel `users` enum, `eskul`, tabel baru `eskul_libur`, dan kolom `tingkat` prestasi).
*   **Rencana Pengujian (Verifikasi)**:
    *   Lakukan drop database lokal saat ini, buat database baru kosong, impor berkas `database/simeks_db.sql`.
    *   Jalankan seluruh sistem, lakukan registrasi, masuk sistem, dan operasikan fitur-fitur utama. Sistem harus berjalan normal tanpa kegagalan query akibat ketidakcocokan tabel/kolom.

---

## 4. Prosedur Rollback (Mengembalikan Sistem)
Jika di tengah proses refaktorisasi terjadi kegagalan fatal yang tidak dapat diselesaikan dengan cepat, Anda dapat mengembalikan kode program ke kondisi awal yang bersih dengan langkah berikut:

1.  Buka terminal PowerShell pada direktori `c:\xampp\htdocs\SIMEKS-main`.
2.  Jalankan perintah berikut untuk membatalkan seluruh perubahan lokal dan kembali ke baseline yang tersimpan pada tag git:
    ```powershell
    git reset --hard v1.0-audit-baseline
    ```
3.  Jalankan perintah berikut untuk menghapus file baru yang tidak terlacak (jika ada):
    ```powershell
    git clean -fd
    ```
4.  Pastikan aplikasi kembali berjalan dengan normal seperti semula.

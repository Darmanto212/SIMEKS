# Catatan Perubahan Refaktorisasi SIMEKS (CHANGELOG_REFACTOR.md)

Seluruh pembaruan, perbaikan keamanan, dan modifikasi kode program yang dilakukan selama proses refaktorisasi akan dicatat secara detail pada berkas ini.

---

## [Unreleased]

### Tahap 1: Pembersihan Berkas Sampah & Pengamanan Database Basic
*   **Selesai**: Berkas sampah dibersihkan, migrasi diamankan di database/migrations/, file .gitignore & .env dibuat, error handling diamankan.

---

## [1.1.0-security-baseline] - 2026-07-10

### Keamanan & Pembersihan (Security & Cleanup)
*   Menghapus berkas debug, tes, dan reset password: `reset_admin_password.php`, `check_users.php`, `check_db.php`, `create_test_pembina.php`.
*   Memindahkan berkas migrasi database ke direktori [database/migrations/](file:///c:/xampp/htdocs/SIMEKS-main/database/migrations/) dan membatasi aksesnya agar hanya dapat dijalankan melalui CLI (SAPI cli).
*   Menghapus folder duplikat `SIMEKS-main/` dari root workspace.

### Konfigurasi & Lingkungan (Configuration & Environment)
*   Memisahkan konfigurasi database dari kode sumber dengan memparsing berkas `.env` eksternal.
*   Membuat berkas template [.env.example](file:///c:/xampp/htdocs/SIMEKS-main/.env.example) bebas dari kredensial rahasia.
*   Memperbarui [.gitignore](file:///c:/xampp/htdocs/SIMEKS-main/.gitignore) untuk mengecualikan `.env`, file log, zip backup, dan berkas unggahan siswa.

### Penanganan Kesalahan (Error Handling)
*   Mengubah block catch koneksi & registrasi agar tidak membocorkan path direktori server atau detail database. Error asli dicatat ke server log (`error_log()`) dan pengguna menerima pesan umum yang ramah.
*   Mengatur charset koneksi default menjadi `utf8mb4`.

### Tahap 2: Pengamanan Fitur Unggah & Proteksi Sesi
*   *Menunggu Pelaksanaan*

### Tahap 3: Pemisahan Granular Otorisasi & Hak Akses
*   *Menunggu Pelaksanaan*

### Tahap 4: Perlindungan Terhadap Serangan XSS & CSRF
*   *Menunggu Pelaksanaan*

### Tahap 5: Harmonisasi Skema Database & Verifikasi Akhir
*   *Menunggu Pelaksanaan*

---

## [1.0.0-audit-baseline] - 2026-07-10

### Ditambahkan (Added)
*   Membuat berkas rencana refaktorisasi [REFACTOR_PLAN.md](file:///c:/xampp/htdocs/SIMEKS-main/REFACTOR_PLAN.md) yang menjabarkan rencana 5 tahapan refaktorisasi keamanan dan otorisasi.
*   Membuat berkas catatan pembaruan [CHANGELOG_REFACTOR.md](file:///c:/xampp/htdocs/SIMEKS-main/CHANGELOG_REFACTOR.md) (berkas ini) untuk memantau progres pengerjaan.
*   Membuat titik pemulihan git tag `v1.0-audit-baseline` dan git branch `backup-before-refactor` sebagai restore point cadangan sebelum kode disentuh.

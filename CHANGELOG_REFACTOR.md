# Catatan Perubahan Refaktorisasi SIMEKS (CHANGELOG_REFACTOR.md)

Seluruh pembaruan, perbaikan keamanan, dan modifikasi kode program yang dilakukan selama proses refaktorisasi akan dicatat secara detail pada berkas ini.

---

## [Unreleased]

### Tahap 3: Pemisahan Granular Otorisasi & Hak Akses
*   *Menunggu Pelaksanaan*

### Tahap 4: Perlindungan Terhadap Serangan XSS & CSRF
*   *Menunggu Pelaksanaan*

### Tahap 5: Harmonisasi Skema Database & Verifikasi Akhir
*   *Menunggu Pelaksanaan*

---

## [1.2.0-auth-roles] - 2026-07-10

### Tahap 2: Pengamanan Autentikasi, Hak Akses, & Otorisasi Role (AUTHENTICATION & ROLES)
*   **Selesai**: Celah autentikasi ditutup dengan password_hash() dan password_verify(), session didesentralisasi, pembatasan login fail + lockout 15 menit diterapkan, penonaktifan akun, NISN activation flow siswa, dan force change password bawaan admin.

### Autentikasi & Sesi (Authentication & Sessions)
*   Mengaktifkan strict session options secara programmatik: `session.use_strict_mode`, HttpOnly, SameSite=Lax, dan Secure pada HTTPS.
*   Mengimplementasikan `session_regenerate_id(true)` setelah login berhasil untuk mencegah Session Fixation.
*   Menghapus penyimpanan password langsung pada session variabel.
*   Menyimpan flat session data: `user_id`, `nama`, `role`, dan `last_activity` dengan fallback mapping untuk backward compatibility.
*   Menerapkan session timeout (30 menit) berbasis aktivitas pada helper auth.
*   Membuat helper otorisasi yang clean: `require_login()`, `require_role()`, `require_any_role()`, `current_user_id()`, `current_user_role()`, `logout_user()`.
*   Menghapus seluruh penggunaan role non-standar (`master`, `admin_master`, `superadmin`) dari view.

### Pembatasan & Proteksi Brute-Force (Rate Limiting)
*   Menambahkan pembatasan login hingga maksimal 5 kali berturut-turut dengan lockout sementara selama 15 menit.
*   Menerapkan mitigasi timing attack untuk username/email non-eksisten menggunakan dummy password verification.
*   Menyembunyikan informasi keberadaan akun dengan menampilkan pesan kesalahan umum: `"Identitas pengguna atau kata sandi tidak sesuai."`
*   Menolak upaya login dari akun dengan status `nonaktif`.

### Registrasi & Aktivasi Siswa (Student Registration)
*   Menghapus skema mock email `username@siswa.com` dan mewajibkan alamat email asli yang unik.
*   Mengubah skema registrasi bebas menjadi alur Aktivasi Akun berbasis NISN yang telah didaftarkan terlebih dahulu oleh Admin.
*   Enforce panjang password minimal 8 karakter dan password tidak boleh sama dengan NISN.
*   Menerapkan deteksi `needs_password_change` (Wajib Ganti Password Awal) bagi pengguna yang baru ditambahkan/di-reset oleh admin. Pengguna akan secara otomatis diarahkan ke halaman profil dan diblokir dari mengakses halaman lain sampai mereka mengubah password bawaan tersebut.

### Pembersihan Kode Sesi Redundan (Session Cleanup)
*   Menghapus deklarasi `session_start();` dari 18 berkas view di folder `admin/` dan `siswa/` agar session lifecycle dikendalikan penuh oleh helper terpusat.

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

---

## [1.0.0-audit-baseline] - 2026-07-10

### Ditambahkan (Added)
*   Membuat berkas rencana refaktorisasi [REFACTOR_PLAN.md](file:///c:/xampp/htdocs/SIMEKS-main/REFACTOR_PLAN.md) yang menjabarkan rencana 5 tahapan refaktorisasi keamanan dan otorisasi.
*   Membuat berkas catatan pembaruan [CHANGELOG_REFACTOR.md](file:///c:/xampp/htdocs/SIMEKS-main/CHANGELOG_REFACTOR.md) (berkas ini) untuk memantau progres pengerjaan.
*   Membuat titik pemulihan git tag `v1.0-audit-baseline` dan git branch `backup-before-refactor` sebagai restore point cadangan sebelum kode disentuh.

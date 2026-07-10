# Dokumentasi Hubungan Tabel Database SIMEKS (Entity Relationship Documentation)

Dokumen ini menjabarkan hubungan (relasi) antar-tabel, jenis relasi (kardinalitas), indeks, constraint unik, serta tindakan referensial (`ON DELETE` / `ON UPDATE`) pada skema database SIMEKS yang baru.

---

## 1. Diagram Hubungan Entitas (Entity Relationship Diagram - ERD)

```
                       [periode]
                           | (1)
                           |
                           +------------------+
                           | (1..N)           | (1..N)
                           v                  v
  [users] (1) -------> [pendaftaran] <------- [eskul] <------- [eskul_libur]
     |                     ^                     ^                  | (1..N)
     |                     | (diproses)          |                  |
     |                     +---------------------+------------------+ (created_by)
     | (1)                                       | (1)
     +---------------> [absensi] <---------------+
     | (1)                 |
     |                     + (dicatat_oleh)
     +---------------> [prestasi] <--------------+ (1)
     | (1)
     +---------------> [pengumuman] (1..N)
     | (1)
     +---------------> [notifikasi] (1..N)
     | (1)
     +---------------> [logs] (1..N)
```

---

## 2. Rincian Hubungan Referensial & Integritas Data

### A. Tabel `eskul` (Ekstrakurikuler)
*   **Kolom Hubungan**: `pembina_id` -> `users.id`
*   **Kardinalitas**: `users (1)` ke `eskul (0..1)` (Seorang pengguna pembina dapat mengajar 0 atau 1 eskul).
*   **Tindakan Referensial**: `ON DELETE SET NULL`
    *   *Alasan*: Jika akun pembina dihapus atau dinonaktifkan dari sistem, data ekstrakurikuler tidak boleh terhapus secara otomatis, melainkan kolom `pembina_id` diatur menjadi `NULL` (dapat ditugaskan ke pembina lain nanti).

### B. Tabel `pendaftaran` (Registrasi Ekskul Siswa)
*   **Kolom Hubungan**:
    1.  `user_id` -> `users.id` (Relasi: `ON DELETE CASCADE`)
        *   *Alasan*: Jika siswa dihapus secara permanen (bukan dinonaktifkan), data transaksi pendaftarannya dibersihkan. Namun untuk siswa yang memiliki riwayat, direkomendasikan menggunakan mekanisme *soft delete* (`deleted_at` pada `users`) agar data transaksional tetap utuh.
    2.  `eskul_id` -> `eskul.id` (Relasi: `ON DELETE CASCADE`)
        *   *Alasan*: Serupa dengan siswa, jika data eskul dihapus permanen, pendaftaran terkait ikut terhapus. Sebaiknya gunakan *soft delete* (`deleted_at` pada `eskul`) agar riwayat pendaftaran historis tidak hilang.
    3.  `periode_id` -> `periode.id` (Relasi: `ON DELETE RESTRICT`)
        *   *Alasan*: Melindungi data periode akademik penting agar tidak terhapus selama masih ada riwayat pendaftaran siswa yang merujuk padanya.
    4.  `diproses_oleh` -> `users.id` (Relasi: `ON DELETE SET NULL`)
        *   *Alasan*: Menyimpan rekam jejak verifikator. Jika akun verifikator dihapus, status verifikasi pendaftaran tetap sah dengan kolom verifikator diatur menjadi `NULL`.
*   **Constraint Unik**: `UNIQUE KEY unique_user_eskul_periode` (`user_id`, `eskul_id`, `periode_id`)
    *   *Alasan*: Mencegah siswa mendaftar ke ekstrakurikuler yang sama lebih dari satu kali dalam periode ajaran yang sama.

### C. Tabel `absensi`
*   **Kolom Hubungan**:
    1.  `user_id` -> `users.id` (Relasi: `ON DELETE CASCADE`)
    2.  `eskul_id` -> `eskul.id` (Relasi: `ON DELETE CASCADE`)
    3.  `periode_id` -> `periode.id` (Relasi: `ON DELETE RESTRICT`)
    4.  `dicatat_oleh` -> `users.id` (Relasi: `ON DELETE SET NULL`)
        *   *Alasan*: Menyimpan riwayat pembina yang mencatat kehadiran. Jika pembina tersebut keluar, data riwayat absensi siswa tetap utuh.
*   **Constraint Unik**: `UNIQUE KEY unique_user_eskul_tanggal` (`user_id`, `eskul_id`, `tanggal`)
    *   *Alasan*: Memastikan seorang siswa hanya memiliki satu status kehadiran per ekstrakurikuler pada satu hari tertentu.

### D. Tabel `prestasi`
*   **Kolom Hubungan**:
    1.  `user_id` -> `users.id` (Relasi: `ON DELETE CASCADE`)
    2.  `eskul_id` -> `eskul.id` (Relasi: `ON DELETE CASCADE`)
    3.  `created_by` -> `users.id` (Relasi: `ON DELETE SET NULL`)
    4.  `updated_by` -> `users.id` (Relasi: `ON DELETE SET NULL`)

### E. Tabel `pengumuman`
*   **Kolom Hubungan**: `created_by` -> `users.id` (Relasi: `ON DELETE SET NULL`)

### F. Tabel `notifikasi`
*   **Kolom Hubungan**: `user_id` -> `users.id` (Relasi: `ON DELETE CASCADE`)
*   **Constraint Unik**: `UNIQUE KEY unique_event_key` (`event_key`)
    *   *Alasan*: Mencegah pembuatan notifikasi redundan dari pemicu/event sistem yang sama (misalnya, notifikasi ganda ketika menekan tombol verifikasi berkali-kali).

### G. Tabel `logs`
*   **Kolom Hubungan**: `user_id` -> `users.id` (Relasi: `ON DELETE SET NULL`)
    *   *Alasan*: Audit log aktivitas harus tetap tersimpan untuk kebutuhan historis meskipun akun pengguna yang melakukan tindakan tersebut dihapus di kemudian hari.

### H. Tabel `eskul_libur`
*   **Kolom Hubungan**:
    1.  `eskul_id` -> `eskul.id` (Relasi: `ON DELETE CASCADE`)
        *   *Alasan*: Jika eskul dihapus, agenda libur terkait tidak lagi relevan.
    2.  `created_by` -> `users.id` (Relasi: `ON DELETE SET NULL`)
*   **Constraint Unik**: `UNIQUE KEY unique_eskul_tanggal` (`eskul_id`, `tanggal`)
    *   *Alasan*: Mencegah pencatatan agenda libur ganda untuk eskul yang sama pada tanggal yang sama.

---

## 3. Ketentuan Penggunaan InnoDB & Soft Delete

1.  **Storage Engine**: Seluruh tabel dikonfigurasi menggunakan **InnoDB** untuk mendukung transaksi data ACID, keamanan data melalui Kunci Tamu (Foreign Key), dan performa penguncian baris (*row-level locking*) yang optimal.
2.  **Karakter Set**: Menggunakan **utf8mb4** dan kolasi **utf8mb4_general_ci** untuk memastikan kompatibilitas penuh penyimpanan berbagai karakter emoji, nama kompleks, dan simbol internasional.
3.  **Mekanisme Soft Delete**:
    *   Tabel `users` dan `eskul` ditambahkan kolom `deleted_at`.
    *   Jika eskul atau pengguna ingin dihapus dari sistem, aplikasi tidak menjalankan perintah `DELETE` fisik, melainkan melakukan `UPDATE` kolom `deleted_at` dengan timestamp waktu saat ini.
    *   Mekanisme ini mencegah rusaknya integritas referensial dan data historis penting pada tabel transaksi utama (`absensi`, `pendaftaran`, `prestasi`, `logs`) sehingga riwayat operasional sekolah tetap terjaga 100%.

# Perancangan Database (Struktur Tabel Database)
**Sistem Informasi Manajemen Ekstrakurikuler Sekolah (SIMEKS) - SMAN 2 Sukatani**

Dokumen ini berisi spesifikasi teknis dan perancangan basis data relasional untuk sistem SIMEKS. Rancangan database ini menggunakan DBMS **MySQL/MariaDB** dengan Storage Engine **InnoDB** untuk menjaga integritas data melalui konstrain kunci tamu (*Foreign Key*).

---

## 📂 1. Ringkasan Entitas & Tabel Database

Berdasarkan analisis skema fisik basis data (`simeks_db.sql`), sistem SIMEKS memiliki **8 (delapan) tabel** utama yang saling berelasi:

1. **`users`**: Menyimpan data akun pengguna sistem, mencakup data siswa terdaftar maupun administrator utama yang dibedakan melalui kolom `role`.
2. **`eskul`**: Menyimpan master data kegiatan ekstrakurikuler sekolah, jadwal, lokasi, pembina, serta kuota maksimal peserta.
3. **`pendaftaran`**: Tabel transaksi yang mencatat pendaftaran siswa ke suatu ekstrakurikuler serta status verifikasinya.
4. **`absensi`**: Mencatat kehadiran presensi siswa pada setiap sesi pertemuan latihan ekstrakurikuler.
5. **`prestasi`**: Menyimpan catatan perolehan prestasi atau penghargaan yang diraih oleh siswa pada kegiatan ekskul tertentu.
6. **`notifikasi`**: Mencatat pemberitahuan sistem yang dikirimkan secara khusus ke akun siswa terkait status pendaftaran atau pengumuman penting.
7. **`logs`**: Menyimpan riwayat jejak audit aktivitas pengguna untuk keperluan keamanan sistem dan pelacakan error.
8. **`pengumuman`**: Menyimpan informasi publik atau pengumuman sekolah yang dibagikan secara masal ke landing page dan dasbor siswa.

---

## 📊 2. Struktur Detil Tabel Database (Kamus Data)

Berikut adalah rincian field, tipe data, indeks, dan relasi kunci untuk masing-masing tabel yang dirancang sesuai dengan standar penulisan skripsi/tugas akhir:

### A. Tabel `users` (Data Siswa & Administrator)
*   **Fungsi**: Menyimpan data autentikasi dan profil pengguna (Siswa & Admin).
*   **Primary Key**: `id`
*   **Index Tambahan**: `email` (Unique), `nisn` (Unique)

| No | Nama Field | Tipe Data | Nullable | Nilai Default | Keterangan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **id** | INT(11) | NO | *Auto Increment* | Primary Key (ID unik pengguna) |
| 2 | **nisn** | VARCHAR(20) | YES | NULL | Nomor Induk Siswa Nasional (Unique, khusus Siswa) |
| 3 | **nama** | VARCHAR(100) | NO | - | Nama lengkap pengguna |
| 4 | **email** | VARCHAR(100) | NO | - | Alamat email (Unique, digunakan untuk login) |
| 5 | **password** | VARCHAR(255) | NO | - | Kata sandi akun (Terenkripsi hash Bcrypt PHP) |
| 6 | **kelas** | VARCHAR(50) | YES | NULL | Kelas siswa (misal: "X MIPA 5") |
| 7 | **role** | ENUM('admin','siswa') | NO | 'siswa' | Peran/hak akses pengguna |
| 8 | **foto** | VARCHAR(255) | NO | 'default.png' | Nama file foto profil yang diunggah |
| 9 | **created_at**| TIMESTAMP | NO | `current_timestamp()` | Tanggal dan waktu registrasi akun |

---

### B. Tabel `eskul` (Data Ekstrakurikuler)
*   **Fungsi**: Menyimpan daftar ekstrakurikuler beserta pengelolanya.
*   **Primary Key**: `id`

| No | Nama Field | Tipe Data | Nullable | Nilai Default | Keterangan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **id** | INT(11) | NO | *Auto Increment* | Primary Key (ID unik ekskul) |
| 2 | **nama_eskul** | VARCHAR(100) | NO | - | Nama kegiatan ekstrakurikuler |
| 3 | **deskripsi** | TEXT | YES | NULL | Informasi detail/deskripsi ekskul |
| 4 | **pembina** | VARCHAR(100) | YES | NULL | Nama guru pembina ekstrakurikuler |
| 5 | **kuota** | INT(11) | YES | 30 | Batas maksimal jumlah pendaftar |
| 6 | **jadwal** | VARCHAR(100) | YES | NULL | Hari dan jam kegiatan latihan rutin |
| 7 | **lokasi** | VARCHAR(100) | YES | NULL | Tempat/ruang latihan dilaksanakan |
| 8 | **gambar** | VARCHAR(255) | YES | 'default_eskul.jpg' | Nama file gambar/banner ekskul |
| 9 | **status** | ENUM('aktif','non-aktif') | YES | 'aktif' | Status aktif/tidaknya ekskul di sekolah |

---

### C. Tabel `pendaftaran` (Transaksi Pengajuan Anggota)
*   **Fungsi**: Mencatat pengajuan pendaftaran siswa baru ke ekstrakurikuler.
*   **Primary Key**: `id`
*   **Foreign Key**: `user_id` (Referensi `users.id`), `eskul_id` (Referensi `eskul.id`)
*   **Constraint**: `ON DELETE CASCADE` (jika user/eskul dihapus, data pendaftaran terhapus otomatis)

| No | Nama Field | Tipe Data | Nullable | Nilai Default | Keterangan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **id** | INT(11) | NO | *Auto Increment* | Primary Key (ID unik pendaftaran) |
| 2 | **user_id** | INT(11) | YES | NULL | Foreign Key ke tabel `users` (Siswa pendaftar) |
| 3 | **eskul_id** | INT(11) | YES | NULL | Foreign Key ke tabel `eskul` (Eskul yang dipilih) |
| 4 | **tanggal_daftar** | DATE | YES | NULL | Tanggal pengajuan pendaftaran |
| 5 | **status** | ENUM('menunggu','diterima','ditolak')| YES | 'menunggu' | Status persetujuan oleh Admin |
| 6 | **catatan** | TEXT | YES | NULL | Catatan tambahan/alasan jika ditolak |

---

### D. Tabel `absensi` (Presensi Latihan)
*   **Fungsi**: Mencatat kehadiran siswa terdaftar di setiap pertemuan ekskul.
*   **Primary Key**: `id`
*   **Foreign Key**: `user_id` (Referensi `users.id`), `eskul_id` (Referensi `eskul.id`)
*   **Constraint**: `ON DELETE CASCADE` (jika user/eskul dihapus, data absensi terhapus otomatis)

| No | Nama Field | Tipe Data | Nullable | Nilai Default | Keterangan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **id** | INT(11) | NO | *Auto Increment* | Primary Key (ID unik absensi) |
| 2 | **user_id** | INT(11) | YES | NULL | Foreign Key ke tabel `users` (Siswa) |
| 3 | **eskul_id** | INT(11) | YES | NULL | Foreign Key ke tabel `eskul` (Eskul terkait) |
| 4 | **tanggal** | DATE | YES | NULL | Tanggal latihan diadakan |
| 5 | **status** | ENUM('hadir','izin','sakit','alpa')| YES | 'hadir' | Status kehadiran siswa |
| 6 | **keterangan** | VARCHAR(255) | YES | NULL | Detail alasan (jika izin atau sakit) |
| 7 | **created_at**| TIMESTAMP | NO | `current_timestamp()` | Tanggal dan waktu penginputan data |

---

### E. Tabel `prestasi` (Penghargaan Siswa)
*   **Fungsi**: Mencatat riwayat prestasi siswa dari perwakilan ekstrakurikuler.
*   **Primary Key**: `id`
*   **Foreign Key**: `user_id` (Referensi `users.id`), `eskul_id` (Referensi `eskul.id`)
*   **Constraint**: `ON DELETE CASCADE` (jika user/eskul dihapus, data prestasi terhapus otomatis)

| No | Nama Field | Tipe Data | Nullable | Nilai Default | Keterangan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **id** | INT(11) | NO | *Auto Increment* | Primary Key (ID unik prestasi) |
| 2 | **user_id** | INT(11) | YES | NULL | Foreign Key ke tabel `users` (Siswa peraih) |
| 3 | **eskul_id** | INT(11) | YES | NULL | Foreign Key ke tabel `eskul` (Eskul asal prestasi) |
| 4 | **nama_prestasi** | VARCHAR(255) | YES | NULL | Nama kejuaraan/penghargaan |
| 5 | **tahun** | YEAR(4) | YES | NULL | Tahun pencapaian prestasi |
| 6 | **deskripsi** | TEXT | YES | NULL | Deskripsi/detail prestasi yang diraih |

---

### F. Tabel `notifikasi` (Pesan Masuk Siswa)
*   **Fungsi**: Menyimpan pemberitahuan khusus dari sistem ke akun siswa.
*   **Primary Key**: `id`
*   **Foreign Key**: `user_id` (Referensi `users.id`)
*   **Constraint**: `ON DELETE CASCADE` (jika user dihapus, data notifikasi terhapus otomatis)

| No | Nama Field | Tipe Data | Nullable | Nilai Default | Keterangan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **id** | INT(11) | NO | *Auto Increment* | Primary Key (ID unik notifikasi) |
| 2 | **user_id** | INT(11) | YES | NULL | Foreign Key ke tabel `users` (Penerima) |
| 3 | **judul** | VARCHAR(100) | YES | NULL | Ringkasan judul notifikasi |
| 4 | **pesan** | TEXT | YES | NULL | Isi lengkap notifikasi |
| 5 | **type** | VARCHAR(20) | YES | 'info' | Jenis notifikasi (misal: 'success', 'danger') |
| 6 | **is_read** | TINYINT(1) | YES | 0 | Status baca (0 = belum dibaca, 1 = sudah) |
| 7 | **created_at**| TIMESTAMP | NO | `current_timestamp()` | Tanggal dan waktu notifikasi dibuat |

---

### G. Tabel `logs` (Audit Aktivitas Keamanan)
*   **Fungsi**: Merekam jejak audit keamanan sistem (log aktivitas penting).
*   **Primary Key**: `id`
*   **Foreign Key**: `user_id` (Referensi `users.id`)
*   **Constraint**: `ON DELETE SET NULL` (jika user dihapus, data log tetap dipertahankan dengan nilai user_id NULL)

| No | Nama Field | Tipe Data | Nullable | Nilai Default | Keterangan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **id** | INT(11) | NO | *Auto Increment* | Primary Key (ID unik log) |
| 2 | **user_id** | INT(11) | YES | NULL | Foreign Key ke tabel `users` (Pelaku aktivitas) |
| 3 | **aktivitas** | VARCHAR(255) | NO | - | Judul aktivitas (misal: "Login Berhasil") |
| 4 | **keterangan** | TEXT | YES | NULL | Deskripsi detail parameter aktivitas |
| 5 | **tipe** | ENUM('INFO','SUKSES','PERINGATAN','BAHAYA')| YES | 'INFO' | Level prioritas log keamanan |
| 6 | **tanggal** | TIMESTAMP | NO | `current_timestamp()` | Tanggal dan waktu kejadian aktivitas |
| 7 | **ip_address** | VARCHAR(45) | YES | NULL | IP address dari perangkat pengguna |

---

### H. Tabel `pengumuman` (Informasi Publik Sekolah)
*   **Fungsi**: Menyimpan informasi publik/berita kegiatan ekskul.
*   **Primary Key**: `id`

| No | Nama Field | Tipe Data | Nullable | Nilai Default | Keterangan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **id** | INT(11) | NO | *Auto Increment* | Primary Key (ID unik pengumuman) |
| 2 | **judul** | VARCHAR(255) | NO | - | Judul berita/pengumuman |
| 3 | **isi** | TEXT | NO | - | Detail teks isi pengumuman |
| 4 | **kategori** | VARCHAR(50) | YES | 'Umum' | Kategori informasi (misal: EVENT, UMUM) |
| 5 | **tanggal** | TIMESTAMP | NO | `current_timestamp()` | Tanggal penerbitan pengumuman |
| 6 | **status** | ENUM('aktif','nonaktif')| YES | 'aktif' | Status publikasi pengumuman |

---

## 🔑 3. Integritas Data & Konstrain Kunci (Referential Integrity Constraints)

Dalam perancangan ini, integritas data dijaga secara ketat melalui relasi *Foreign Key* berikut:
1. Penghapusan data siswa (`users`) atau data ekstrakurikuler (`eskul`) akan menghapus secara otomatis (*Cascading*) data transaksi yang bersangkutan pada tabel `pendaftaran`, `absensi`, `prestasi`, dan `notifikasi` guna menghindari data sampah (*orphan data*).
2. Penghapusan pengguna pada tabel `logs` diatur menggunakan `ON DELETE SET NULL` agar data audit sistem tetap tersimpan demi kepentingan tracking keamanan.

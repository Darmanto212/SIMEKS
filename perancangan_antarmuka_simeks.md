# Perancangan Antarmuka (User Interface Design)
**Sistem Informasi Manajemen Ekstrakurikuler Sekolah (SIMEKS) - SMAN 2 Sukatani**

Dokumen ini berisi spesifikasi perancangan antarmuka (User Interface) untuk sistem SIMEKS. Rancangan ini mencakup fungsionalitas publik (Landing Page, Detail Ekskul, Login, Registrasi), halaman khusus Siswa (Dashboard, Pendaftaran Ekskul, Riwayat Absensi, Prestasi & E-Sertifikat, Profil), serta halaman Pengelola (Admin Master & Pembina) untuk administrasi data dan pelaporan.

---

## 🗺️ 1. Daftar Rancangan Antarmuka (UI Mockup List)
Berikut adalah daftar rancangan antarmuka yang akan digambarkan dalam sub-bab perancangan sistem:

1. **`3.6.1 Perancangan Landing Page (Halaman Utama)`**
2. **`3.6.2 Perancangan Halaman Detail Ekstrakurikuler`**
3. **`3.6.3 Perancangan Halaman Login`**
4. **`3.6.4 Perancangan Halaman Registrasi Siswa`**
5. **`3.6.5 Perancangan Halaman Dashboard Siswa`**
6. **`3.6.6 Perancangan Halaman Daftar & Form Pendaftaran Ekskul (Siswa)`**
7. **`3.6.7 Perancangan Halaman Riwayat Kehadiran (Siswa)`**
8. **`3.6.8 Perancangan Halaman Prestasi & E-Sertifikat (Siswa)`**
9. **`3.6.9 Perancangan Halaman Profil Siswa`**
10. **`3.6.10 Perancangan Halaman Dashboard Admin / Pembina`**
11. **`3.6.11 Perancangan Halaman Kelola Data Ekstrakurikuler (Admin)`**
12. **`3.6.12 Perancangan Halaman Kelola Akun Pembina (Admin)`**
13. **`3.6.13 Perancangan Halaman Kelola Data Siswa (Admin)`**
14. **`3.6.14 Perancangan Halaman Verifikasi Pendaftaran (Admin/Pembina)`**
15. **`3.6.15 Perancangan Halaman Kelola Absensi Kehadiran (Admin/Pembina)`**
16. **`3.6.16 Perancangan Halaman Kelola Prestasi Siswa (Admin/Pembina)`**
17. **`3.6.17 Perancangan Halaman Kelola Pengumuman (Admin)`**
18. **`3.6.18 Perancangan Halaman Laporan & Cetak Laporan (Admin/Pembina)`**
19. **`3.6.19 Perancangan Halaman Monitoring Audit Logs (Admin Master)`**
20. **`3.6.20 Perancangan Halaman Pengaturan Sistem (Admin)`**

---

## 🎨 2. Spesifikasi Detail Rancangan Antarmuka

### 3.6.1 Perancangan Landing Page (Halaman Utama)
*   **Tujuan**: Memberikan informasi umum kepada publik/siswa mengenai program ekstrakurikuler SMAN 2 Sukatani dan memfasilitasi akses masuk ke dalam sistem.
*   **Tata Letak (Layout Outline)**:
    *   **Header (Navigation Bar)**: Logo Sekolah/SIMEKS, Menu Link (Beranda, Ekskul, Prestasi, Tentang), Tombol "Masuk" & "Daftar".
    *   **Hero Section**: Judul Utama ("Salurkan Bakat & Minatmu di SMAN 2 Sukatani"), Deskripsi Sistem, Tombol Call to Action ("Daftar Sekarang" & "Lihat Ekskul"), Background Ilustrasi/Foto Kegiatan Ekskul.
    *   **Section Kenapa Memilih Kami**: Penjelasan singkat keunggulan sistem (Sistem Digital, Real-Time Info, Data Aman, Fokus Prestasi).
    *   **Section Ekskul Aktif**: Grid card menampilkan ekstrakurikuler yang aktif dengan deskripsi singkat, sisa kuota, jadwal, dan tombol "Detail".
    *   **Section Prestasi Terbaru**: Slider/Grid prestasi siswa terupdate beserta logo/kategori lomba.
    *   **Footer**: Info kontak sekolah, copyright, link sosial media.

### 3.6.2 Perancangan Halaman Detail Ekstrakurikuler
*   **Tujuan**: Menampilkan informasi lengkap dan mendalam tentang suatu ekstrakurikuler sebelum siswa memutuskan untuk mendaftar.
*   **Tata Letak (Layout Outline)**:
    *   **Header & Footer**: Sama dengan Landing Page.
    *   **Sidebar Kiri/Kanan**: Detail Banner Gambar Ekskul, Kategori, Nama Guru Pembina, Hari/Jam Latihan, Lokasi Latihan, Sisa Kuota, Tombol "Daftar Sekarang" (mengarahkan ke login/pendaftaran).
    *   **Main Content Area**:
        *   Deskripsi lengkap/Profil kegiatan.
        *   Daftar Prestasi yang pernah diraih oleh ekskul tersebut.
        *   Syarat dan Ketentuan Pendaftaran (jika ada).

### 3.6.3 Perancangan Halaman Login
*   **Tujuan**: Memverifikasi kredensial pengguna (Siswa, Pembina, Admin) untuk masuk ke halaman dasbor masing-masing.
*   **Tata Letak (Layout Outline)**:
    *   **Split Layout**: Sisi kiri berupa ilustrasi visual/foto sekolah, sisi kanan berupa box formulir login.
    *   **Formulir Login**:
        *   Input Username/Email/NISN.
        *   Input Password (dengan opsi toggle icon mata untuk *show/hide password*).
        *   Tombol "Masuk / Login".
        *   Link "Kembali ke Beranda" & "Belum punya akun? Daftar di sini".

### 3.6.4 Perancangan Halaman Registrasi Siswa
*   **Tujuan**: Memfasilitasi siswa baru SMAN 2 Sukatani yang ingin membuat akun sistem secara mandiri.
*   **Tata Letak (Layout Outline)**:
    *   **Split Layout**: Sisi kiri berupa ilustrasi visual pendaftaran, sisi kanan berupa formulir pendaftaran yang clean.
    *   **Formulir Registrasi**:
        *   Input NISN (Nomor Induk Siswa Nasional).
        *   Input Nama Lengkap.
        *   Input Email Aktif.
        *   Input Kelas (Dropdown: X, XI, XII dan jurusannya).
        *   Input Password & Konfirmasi Password.
        *   Tombol "Daftar Akun".
        *   Link "Sudah punya akun? Login".

### 3.6.5 Perancangan Halaman Dashboard Siswa
*   **Tujuan**: Halaman utama setelah siswa masuk, menampilkan informasi status terkini aktivitas ekstrakurikuler mereka secara ringkas.
*   **Tata Letak (Layout Outline)**:
    *   **Sidebar**: Navigasi Menu Siswa (Dashboard, Daftar Ekskul, Riwayat Absensi, Prestasi & E-Sertifikat, Profil Saya, Logout).
    *   **Header**: Foto Profil Siswa, Nama Siswa, Role (Siswa), Icon Notifikasi.
    *   **Widget Kartu Informasi (Stats Banner)**:
        *   Kartu 1: Status Pendaftaran Ekskul (Aktif/Menunggu/Ditolak).
        *   Kartu 2: Persentase Kehadiran Latihan (Gauge chart/Persentase, misal: 85%).
        *   Kartu 3: Jumlah Prestasi yang Diraih.
    *   **Main Content Area**:
        *   **Box Pengumuman Terbaru**: Daftar pengumuman sekolah/ekskul dengan scrollable list.
        *   **Grafik Kehadiran Mingguan**: Grafik batang kehadiran latihan.

### 3.6.6 Perancangan Halaman Daftar & Form Pendaftaran Ekskul (Siswa)
*   **Tujuan**: Tempat siswa menelusuri ekskul yang aktif, melihat sisa kuota, serta mengajukan permohonan pendaftaran.
*   **Tata Letak (Layout Outline)**:
    *   **Tampilan Daftar**: Grid Card dari semua ekstrakurikuler yang masih membuka pendaftaran. Setiap card memuat nama ekskul, nama pembina, jadwal, sisa kuota, status pendaftaran saat ini ("Daftar", "Menunggu Verifikasi", "Anggota Aktif").
    *   **Modal Form Pendaftaran**: Popup yang muncul ketika tombol "Daftar" diklik. Berisi konfirmasi data diri siswa dan tombol "Kirim Pengajuan".

### 3.6.7 Perancangan Halaman Riwayat Kehadiran (Siswa)
*   **Tujuan**: Memungkinkan siswa memantau kehadiran latihan mereka secara berkala.
*   **Tata Letak (Layout Outline)**:
    *   **Header Riwayat**: Ringkasan absensi (Hadir: X, Sakit: Y, Izin: Z, Alpa: W).
    *   **Tabel Riwayat Absensi**:
        *   Kolom: No, Tanggal Sesi, Nama Ekskul, Status Kehadiran (Hadir/Sakit/Izin/Alpa dengan badge warna), Keterangan.
    *   **Filter Pencarian**: Dropdown filter berdasarkan nama ekskul dan rentang bulan.

### 3.6.8 Perancangan Halaman Prestasi & E-Sertifikat (Siswa)
*   **Tujuan**: Menampilkan penghargaan yang didapatkan siswa dan menyediakan link download dokumen sertifikat resmi.
*   **Tata Letak (Layout Outline)**:
    *   **Grid Card Prestasi**: Menampilkan nama kejuaraan, nama ekskul, tingkat kejuaraan (Kabupaten, Provinsi, Nasional), peringkat (Juara 1/2/3), tanggal perolehan.
    *   **Tombol Aksi**: Di setiap card terdapat tombol "Unduh E-Sertifikat" yang mengarah pada file PDF sertifikat berstempel resmi.

### 3.6.9 Perancangan Halaman Profil Siswa
*   **Tujuan**: Halaman edit data diri siswa dan mengganti foto profil pribadi.
*   **Tata Letak (Layout Outline)**:
    *   **Sisi Kiri (Avatar Area)**: Foto profil saat ini, tombol "Unggah Foto Baru" (max size 2MB, format JPG/PNG).
    *   **Sisi Kanan (Form Profil)**:
        *   Input NISN (Disabled/Read-only).
        *   Input Nama Lengkap (Editable).
        *   Input Email (Editable).
        *   Input Kelas (Editable).
        *   Input Password Lama & Password Baru (untuk ganti password).
        *   Tombol "Simpan Perubahan".

---

### 3.6.10 Perancangan Halaman Dashboard Admin / Pembina
*   **Tujuan**: Memberikan ringkasan data statistik sistem serta pintasan menu kelola untuk Admin dan Pembina.
*   **Tata Letak (Layout Outline)**:
    *   **Sidebar**: Navigasi Menu Admin (Dashboard, Kelola Ekskul, Kelola Pembina, Kelola Siswa, Kelola Pendaftaran, Kelola Absensi, Kelola Prestasi, Kelola Pengumuman, Laporan, Logs Audit, Pengaturan, Logout). *Catatan: Untuk pembina, sidebar disesuaikan agar hanya menampilkan menu kelola absensi, kelola prestasi, kelola pendaftaran ekskul binaan, dan laporan.*
    *   **Widget Ringkasan Statistik (Stat Cards Grid)**:
        *   Card 1: Total Ekstrakurikuler Aktif.
        *   Card 2: Total Akun Guru Pembina.
        *   Card 3: Total Siswa Aktif Terdaftar.
        *   Card 4: Total Pendaftaran Menunggu Verifikasi.
    *   **Main Content Area**:
        *   **Grafik Keaktifan Ekskul**: Chart (misal: Pie Chart/Bar Chart) jumlah anggota aktif per eskul.
        *   **Tabel Pendaftaran Terbaru**: Tabel 5 baris pendaftaran terbaru yang masuk untuk respon cepat.

### 3.6.11 Perancangan Halaman Kelola Data Ekstrakurikuler (Admin)
*   **Tujuan**: Halaman CRUD bagi admin untuk memelihara data master ekskul sekolah.
*   **Tata Letak (Layout Outline)**:
    *   **Header Menu**: Tombol "Tambah Ekskul Baru", kolom pencarian.
    *   **Tabel Data Ekskul**:
        *   Kolom: No, Banner/Gambar, Nama Ekskul, Pembina, Jadwal, Lokasi, Kuota Terisi/Maksimal, Status (Aktif/Non-aktif), Aksi (Edit, Hapus).
    *   **Modal Form Tambah/Edit Ekskul**:
        *   Input Nama Ekskul, Deskripsi, Dropdown Guru Pembina, Input Kuota (Angka), Input Jadwal (Hari & Jam), Input Lokasi, Upload Banner Ekskul, Radio Button Status (Aktif/Non-aktif).

### 3.6.12 Perancangan Halaman Kelola Akun Pembina (Admin)
*   **Tujuan**: Mengelola akun guru pembina ekskul agar mereka bisa masuk ke sistem.
*   **Tata Letak (Layout Outline)**:
    *   **Header Menu**: Tombol "Tambah Pembina", kolom pencarian.
    *   **Tabel Data Pembina**:
        *   Kolom: No, Foto, Nama Pembina, Email, Nomor WhatsApp, Ekskul yang Dibina, Aksi (Edit, Hapus, Reset Password).
    *   **Modal Form Tambah/Edit Pembina**:
        *   Input Nama Lengkap, Email, Nomor WhatsApp, Dropdown Ekskul Binaan (Multiselect/Single select), Password Default.

### 3.6.13 Perancangan Halaman Kelola Data Siswa (Admin)
*   **Tujuan**: Mengelola data biodata dan verifikasi akun seluruh siswa sekolah.
*   **Tata Letak (Layout Outline)**:
    *   **Header Menu**: Kolom Pencarian, Filter Kelas, Tombol "Export Excel" data siswa.
    *   **Tabel Data Siswa**:
        *   Kolom: No, NISN, Nama Lengkap, Kelas, Email, Jumlah Ekskul Aktif, Aksi (Edit, Hapus, Reset Password).

### 3.6.14 Perancangan Halaman Verifikasi Pendaftaran (Admin/Pembina)
*   **Tujuan**: Memproses permohonan pendaftaran ekskul siswa (diterima/ditolak).
*   **Tata Letak (Layout Outline)**:
    *   **Tabel Pendaftaran**:
        *   Kolom: No, Tanggal Pengajuan, NISN, Nama Siswa, Kelas, Ekskul yang Diajukan, Status (Badge: Menunggu, Diterima, Ditolak), Aksi (Tombol Hijau "Terima", Tombol Merah "Tolak").
    *   **Modal Konfirmasi Penolakan**: Form input wajib "Alasan Penolakan" apabila admin/pembina mengklik tombol "Tolak" (menghasilkan catatan di notifikasi siswa).

### 3.6.15 Perancangan Halaman Kelola Absensi Kehadiran (Admin/Pembina)
*   **Tujuan**: Menginput presensi siswa anggota eskul pada setiap sesi latihan.
*   **Tata Letak (Layout Outline)**:
    *   **Form Filter Awal**: Dropdown Pilih Ekskul, Input Tanggal Pertemuan. Tombol "Buka Presensi".
    *   **Tabel Penginputan Absensi**:
        *   Kolom: No, NISN, Nama Siswa, Kelas, Opsi Kehadiran (Radio Button: Hadir, Sakit, Izin, Alpa), Input Kolom Keterangan.
    *   **Tombol Aksi**: "Simpan Absensi Hari Ini".

### 3.6.16 Perancangan Halaman Kelola Prestasi Siswa (Admin/Pembina)
*   **Tujuan**: Mencatat prestasi/kejuaraan yang diperoleh anggota ekskul dan mengunggah berkas e-sertifikat.
*   **Tata Letak (Layout Outline)**:
    *   **Header Menu**: Tombol "Input Prestasi Baru".
    *   **Tabel Data Prestasi**:
        *   Kolom: No, Nama Siswa, Kelas, Nama Ekskul, Nama Kejuaraan, Tingkat Lomba, Peringkat/Juara, Berkas Sertifikat (Link Download), Aksi (Edit, Hapus).
    *   **Modal Form Input/Edit Prestasi**:
        *   Dropdown Pilih Siswa (Autocomplete/Pencarian), Dropdown Ekskul, Input Nama Lomba/Kejuaraan, Dropdown Tingkat Kejuaraan, Dropdown Juara (1, 2, 3, Harapan, Partisipan), Upload Berkas Sertifikat (PDF/JPG).

### 3.6.17 Perancangan Halaman Kelola Pengumuman (Admin)
*   **Tujuan**: Mempublikasikan info penting sekolah/ekskul ke landing page dan dasbor siswa.
*   **Tata Letak (Layout Outline)**:
    *   **Header Menu**: Tombol "Tambah Pengumuman Baru".
    *   **Tabel Data Pengumuman**:
        *   Kolom: No, Judul Pengumuman, Tanggal Publish, Kategori (Umum/Eskul Tertentu), Status (Aktif/Draft), Pembuat (Admin), Aksi (Edit, Hapus).
    *   **Modal Form Tambah/Edit Pengumuman**:
        *   Input Judul, Textarea Konten Pengumuman (Rich Text Editor), Dropdown Kategori Target (Semua Siswa / Eskul Tertentu), Radio Button Status.

### 3.6.18 Perancangan Halaman Laporan & Cetak Laporan (Admin/Pembina)
*   **Tujuan**: Menyediakan antarmuka cetak laporan rekapitulasi data anggota ekskul dan rekap persentase kehadiran untuk diserahkan kepada kepala sekolah.
*   **Tata Letak (Layout Outline)**:
    *   **Form Filter Laporan**:
        *   Dropdown Jenis Laporan (Daftar Anggota Ekskul / Rekap Absensi Ekskul).
        *   Dropdown Pilih Ekskul (Khusus Admin: semua ekskul; Pembina: hanya ekskul miliknya).
        *   Input Periode Tanggal / Bulan.
        *   Tombol "Tampilkan Data" & "Cetak Laporan / PDF".
    *   **Preview Area**: Tampilan print-friendly rekap data dalam format tabel sebelum dicetak.

### 3.6.19 Perancangan Halaman Monitoring Audit Logs (Admin Master)
*   **Tujuan**: Menyediakan log audit keamanan aktivitas yang dilakukan pengguna dalam sistem (Login, Logout, Input Data, Edit Data, Hapus Data, Gagal Login).
*   **Tata Letak (Layout Outline)**:
    *   **Filter Logs**: Kolom pencarian aktivitas, filter tanggal log.
    *   **Tabel Audit Logs**:
        *   Kolom: No, Waktu Kejadian (Timestamp), Nama Pengguna, Peran/Role, Nama Aktivitas (misal: "Hapus Ekskul"), Deskripsi Detail, Status Aktivitas (Sukses / Gagal).

### 3.6.20 Perancangan Halaman Pengaturan Sistem (Admin)
*   **Tujuan**: Memungkinkan administrator utama mengonfigurasi parameter global aplikasi SIMEKS.
*   **Tata Letak (Layout Outline)**:
    *   **Form Parameter Sistem**:
        *   Input Nama Sekolah ("SMAN 2 Sukatani").
        *   Input Tahun Ajaran Aktif (misal: "2025/2026").
        *   Upload Logo Instansi Sekolah / Logo SIMEKS.
        *   Input Batas Minimal Kehadiran Anggota (dalam persen, misal: "75%").
        *   Tombol "Simpan Pengaturan".

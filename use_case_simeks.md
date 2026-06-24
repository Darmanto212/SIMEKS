# Dokumentasi & Analisis Use Case SIMEKS
**Sistem Informasi Manajemen Ekstrakurikuler Sekolah (SIMEKS) - SMAN 2 Sukatani**

Dokumen ini berisi analisis detail mengenai aktor (peran) dan *use case* (fungsionalitas) yang berjalan di dalam aplikasi SIMEKS berdasarkan analisis struktur database (`simeks_db.sql`) dan kode halaman (`admin/` dan `siswa/`). 

Di bagian akhir dokumen ini, terdapat rancangan script **PlantUML Use Case Diagram** yang rapi, profesional, dan siap pakai untuk digenerate menjadi gambar diagram.

---

## 👥 1. Analisis Aktor (Actors)

Berdasarkan analisis file login, registrasi, dan session, terdapat **3 aktor utama** yang berinteraksi dengan sistem SIMEKS:

| No | Nama Aktor | Deskripsi |
| :--- | :--- | :--- |
| 1 | **Pengunjung (Siswa Baru)** | Siswa yang belum memiliki akun. Aktor ini hanya dapat melihat halaman utama (landing page), melihat detail ekstrakurikuler yang tersedia, melakukan registrasi akun, dan mengakses halaman login. |
| 2 | **Siswa Terdaftar** | Siswa yang telah memiliki akun dan berhasil login. Aktor ini dapat mendaftar kegiatan ekskul secara online, mengelola profil pribadi, memantau kehadiran (absensi), melihat pengumuman, serta melihat & mengunduh E-Sertifikat prestasi. |
| 3 | **Administrator (Admin)** | Pengelola sistem utama (sekolah/pembina utama). Aktor ini memiliki hak akses penuh untuk mengelola master data eskul, data siswa, memverifikasi pendaftaran, menginput absensi, mengelola pengumuman & prestasi, memantau log keamanan, dan mengekspor laporan. |

---

## 🎯 2. Deskripsi Fungsionalitas (*Use Cases*)

Berikut adalah daftar fungsionalitas yang dikelompokkan berdasarkan aktor yang dapat mengaksesnya:

### A. Fungsionalitas Umum & Autentikasi
*   **Registrasi Akun**: Siswa baru membuat akun dengan mengisi data NISN, Nama, Email, Kelas, dan Password.
*   **Login Sistem**: Pengguna (Siswa/Admin) memverifikasi identitas untuk masuk ke dashboard yang sesuai.
*   **Melihat Landing Page & Info Ekskul**: Pengguna dapat melihat informasi ekskul (Nama Ekskul, Deskripsi, Jadwal, Lokasi, Pembina, Sisa Kuota).

### B. Fungsionalitas Siswa (Siswa Terdaftar)
*   **Mengelola Profil**: Siswa dapat memperbarui data biodata diri.
    *   *<<extend>>* **Unggah Foto Profil**: Siswa dapat mengganti foto profil mereka.
*   **Mendaftar Ekskul**: Siswa memilih ekskul yang aktif dan mengajukan pendaftaran. Status pendaftaran default adalah 'menunggu' sebelum diproses oleh Admin.
*   **Melihat Riwayat Absensi**: Siswa melihat kehadiran mereka (Hadir, Izin, Sakit, Alpa) per kegiatan eskul.
*   **Melihat Pengumuman & Notifikasi**: Siswa menerima update kegiatan dan status pendaftaran.
*   **Melihat & Cetak E-Sertifikat Prestasi**: Siswa dapat mengunduh sertifikat prestasi dalam format PDF apabila datanya telah diinput oleh admin.

### C. Fungsionalitas Administrator (Admin)
*   **Mengakses Dashboard Admin**: Memantau statistik total siswa, jumlah ekskul aktif, pengajuan pendaftaran masuk, dan log aktivitas terbaru.
*   **Kelola Data Ekskul**: Operasi CRUD (Create, Read, Update, Delete) pada tabel `eskul` (mengatur pembina, jadwal, lokasi, kuota, status aktif/non-aktif).
*   **Kelola Data Siswa**: Memantau siswa yang terdaftar dan menghapus/mengedit akun siswa bila diperlukan.
*   **Verifikasi Pendaftaran Ekskul**: Menolak atau menerima pengajuan pendaftaran ekskul oleh siswa.
*   **Kelola Absensi Siswa**: Memilih pertemuan ekskul dan menginput status kehadiran siswa (Hadir, Izin, Sakit, Alpa).
*   **Kelola Prestasi Siswa**: Menginput prestasi yang diraih oleh siswa pada ekskul tertentu.
*   **Kelola Pengumuman**: Membuat pengumuman berdasarkan kategori (Event, Info Umum, dll).
*   **Melihat Log Aktivitas**: Melacak seluruh aktivitas penting sistem (Upaya login gagal, ekspor laporan, update profil, dll) untuk keperluan audit keamanan data.
*   **Cetak & Export Laporan**: Mengunduh data rekapitulasi sistem.
    *   *<<include>>* **Pilih Format Laporan (Excel/Word/PDF)**: Laporan dapat diekspor sesuai format yang diinginkan admin.

---

## 📊 3. Script PlantUML Use Case Diagram

Berikut adalah script **PlantUML** untuk menghasilkan Diagram Use Case yang rapi, terstruktur, dan memiliki estetika visual premium (dilengkapi dengan warna yang harmonis dan format standard UML):

```plantuml
@startuml
skinparam actorStyle awesome
skinparam packageStyle rectangle
skinparam roundcorner 10
skinparam shadowing false

' Palet Warna Modern & Estetik
skinparam ArrowColor #4A5568
skinparam ActorBorderColor #2B6CB0
skinparam ActorBackgroundColor #EBF8FF
skinparam UseCaseBorderColor #319795
skinparam UseCaseBackgroundColor #E6FFFA
skinparam UseCaseFontSize 12
skinparam UseCaseFontName "Arial"
skinparam ActorFontName "Arial"
skinparam ActorFontSize 12

left to right direction

' --- Aktor ---
actor "Pengunjung\n(Siswa Baru)" as Guest
actor "Siswa Terdaftar" as Siswa
actor "Administrator" as Admin

' --- Batasan Sistem (System Boundary) ---
rectangle "Sistem Informasi Manajemen Ekstrakurikuler Sekolah (SIMEKS)" {
  
  ' Use Cases - Autentikasi & Landing Page
  usecase "Registrasi Akun" as UC_Register
  usecase "Login Sistem" as UC_Login
  usecase "Melihat Landing Page\n& Info Ekskul" as UC_ViewInfo
  
  ' Use Cases - Fitur Siswa
  usecase "Mendaftar Ekskul" as UC_JoinEskul
  usecase "Mengelola Profil" as UC_ManageProfile
  usecase "Melihat Riwayat Absensi" as UC_ViewAttendance
  usecase "Melihat Pengumuman\n& Notifikasi" as UC_ViewNotif
  usecase "Melihat & Cetak\nE-Sertifikat Prestasi" as UC_PrintCert
  
  ' Sub-Use Case Siswa (Extend)
  usecase "Unggah Foto Profil" as UC_UploadPhoto
  
  ' Use Cases - Fitur Admin
  usecase "Mengakses Dashboard Admin" as UC_AdminDash
  usecase "Kelola Data Ekskul" as UC_ManageEskul
  usecase "Kelola Data Siswa" as UC_ManageSiswa
  usecase "Verifikasi Pendaftaran\nEkskul" as UC_VerifyReg
  usecase "Kelola Absensi Siswa" as UC_ManageAbsence
  usecase "Kelola Prestasi Siswa" as UC_ManagePrestasi
  usecase "Kelola Pengumuman" as UC_ManageNotif
  usecase "Melihat Log Aktivitas" as UC_ViewLogs
  usecase "Cetak & Export Laporan" as UC_ExportReport
  
  ' Sub-Use Case Admin (Include)
  usecase "Pilih Format Laporan\n(Excel/Word/PDF)" as UC_ReportFormat
}

' --- Relasi Pengunjung ---
Guest --> UC_Register
Guest --> UC_ViewInfo
Guest --> UC_Login

' --- Relasi Siswa Terdaftar ---
Siswa --> UC_Login
Siswa --> UC_ViewInfo
Siswa --> UC_JoinEskul
Siswa --> UC_ManageProfile
Siswa --> UC_ViewAttendance
Siswa --> UC_ViewNotif
Siswa --> UC_PrintCert

' --- Relasi Administrator ---
Admin --> UC_Login
Admin --> UC_AdminDash
Admin --> UC_ManageEskul
Admin --> UC_ManageSiswa
Admin --> UC_VerifyReg
Admin --> UC_ManageAbsence
Admin --> UC_ManagePrestasi
Admin --> UC_ManageNotif
Admin --> UC_ViewLogs
Admin --> UC_ExportReport

' --- Hubungan Include & Extend ---
UC_ManageProfile <.. UC_UploadPhoto : <<extend>>
UC_ExportReport ..> UC_ReportFormat : <<include>>

@endum
```

---

## 🛠️ 4. Cara Menampilkan/Menggenerate Diagram dari Script

Kamu dapat dengan mudah mengubah script di atas menjadi gambar diagram yang rapi dengan beberapa opsi berikut:

1. **Menggunakan PlantUML Server Resmi (Gratis & Cepat)**:
   * Kunjungi situs **[PlantUML Web Server](http://www.plantuml.com/plantuml/)**.
   * Salin (Copy) seluruh kode di dalam blok `@startuml` sampai `@endum` di atas.
   * Tempelkan (Paste) ke dalam kolom teks di situs tersebut.
   * Klik tombol **Submit** untuk melihat hasilnya. Kamu bisa mengunduh gambarnya dalam format PNG atau SVG.

2. **Menggunakan VS Code Extension**:
   * Jika menggunakan VS Code, pasang ekstensi bernama **PlantUML** oleh *jebbs*.
   * Buat file baru dengan ekstensi `.puml` (misal: `usecase.puml`), tempelkan script di atas, lalu tekan `Alt + D` untuk melihat preview grafis secara langsung.

# Dokumentasi & Perancangan Use Case Diagram SIMEKS
**Sistem Informasi Manajemen Ekstrakurikuler Sekolah (SIMEKS) - SMAN 2 Sukatani**

Dokumen ini berisi rancangan **Use Case Diagram** dan **Skenario Use Case** untuk sistem SIMEKS yang telah disederhanakan menjadi **15 Kegiatan Utama (Base Use Case)** beserta hubungannya dengan **Use Case Perluasan (Extend Use Case)**. Rancangan ini disesuaikan dengan kebutuhan revisi skripsi dan implementasi kodingan di dalam aplikasi.

---

## 👥 1. Identifikasi Aktor Sistem (Actors Definition)
Sistem SIMEKS memiliki 3 (tiga) aktor utama yang berinteraksi dengan sistem dengan hak akses (RBAC - *Role-Based Access Control*) yang berbeda:

| No | Aktor | Deskripsi Peran (Role) |
| :--- | :--- | :--- |
| 1 | **Siswa Aktif** | Pengguna yang dapat menjelajahi ekskul, mendaftar ekskul, melihat riwayat kehadiran pribadi, dan mengunduh E-Sertifikat Prestasi miliknya. |
| 2 | **Pembina Ekstrakurikuler** | Guru pembina yang mengelola absensi latihan, menginput prestasi siswa, memverifikasi pendaftaran anggota baru, dan mencetak laporan khusus untuk ekskul binaannya. |
| 3 | **Admin Master** | Administrator sistem utama yang memiliki hak penuh (full access) atas seluruh data sekolah. Mengelola data master eskul, data pembina/siswa, pengumuman, logs audit, dan mewarisi hak akses Pembina. |

---

## 🗺️ 2. Pemetaan 15 Kegiatan Utama & Relasi Extend
Berikut adalah pemetaan fungsionalitas sistem SIMEKS ke dalam **15 Kegiatan Utama (Base Use Case)** beserta relasi perluasan opsional (**Extend Use Case**):

| No | Kegiatan Utama (Base Use Case) | Aktor Utama | Use Case Perluasan (Extend Use Case) | Keterangan Hubungan Extend |
| :--- | :--- | :--- | :--- | :--- |
| 1 | **Login Sistem** | Siswa, Pembina, Admin | **Registrasi Akun Baru** | Siswa baru dapat melakukan registrasi secara mandiri jika belum memiliki akun untuk login. |
| 2 | **Mengelola Profil Mandiri** | Siswa, Pembina, Admin | **Mengubah Foto Profil & Password** | Pengguna dapat memperbarui foto profil dan password mereka secara opsional di halaman profil. |
| 3 | **Menjelajahi Informasi Ekskul** | Siswa Aktif | **Melihat Detail Pembina & Kuota** | Siswa dapat secara opsional melihat info sisa kuota dan kontak pembina saat melihat daftar ekskul. |
| 4 | **Melakukan Pendaftaran Ekskul**| Siswa Aktif | **Membatalkan Pengajuan Pendaftaran**| Siswa dapat membatalkan pengajuan sebelum status pendaftarannya diverifikasi pembina. |
| 5 | **Verifikasi Pendaftaran Anggota**| Pembina, Admin | **Menginput Catatan Penolakan** | Pembina wajib menginput catatan/alasan penolakan jika status pendaftaran siswa ditolak. |
| 6 | **Mengelola Jadwal Latihan** | Pembina, Admin | **Mengubah Lokasi & Jam Latihan** | Pembina dapat memperbarui jam atau lokasi latihan jika ada perubahan mendadak. |
| 7 | **Melakukan Absensi Kehadiran** | Pembina, Admin | **Menginput Keterangan Izin/Sakit** | Pembina menginput alasan khusus jika siswa tidak hadir latihan. |
| 8 | **Melihat Riwayat Absensi** | Siswa Aktif | **Melihat Statistik Kehadiran** | Siswa melihat grafik persentase kehadirannya dalam bentuk doughnut chart. |
| 9 | **Mengelola Data Prestasi** | Pembina, Admin | **Mengunggah File Dokumentasi Lomba**| Pembina dapat mengunggah bukti foto dokumentasi piala/juara secara opsional. |
| 10 | **Mengelola E-Sertifikat** | Siswa, Pembina, Admin | **Mengunduh E-Sertifikat PDF** | Siswa dapat secara mandiri mengunduh e-sertifikat prestasi mereka dalam format PDF. |
| 11 | **Melihat Laporan Ekskul** | Pembina, Admin | **Mencetak Laporan (Excel/PDF)** | Aktor dapat mengekspor/mencetak rekap data kehadiran/anggota ke Excel atau PDF. |
| 12 | **Mengelola Data Ekstrakurikuler**| Admin Master | **Mengatur Penugasan Guru Pembina**| Admin menghubungkan akun pembina ke ekskul terkait saat membuat/mengedit data ekskul. |
| 13 | **Mengelola Akun Pengguna** | Admin Master | **Mengubah Status Aktif Akun** | Admin dapat menonaktifkan atau mengaktifkan kembali akun pembina/siswa yang bermasalah. |
| 14 | **Mengelola Pengumuman & Agenda**| Admin Master | **Mengirim Notifikasi Real-Time** | Admin dapat mengirimkan notifikasi instan ke portal siswa saat merilis pengumuman baru. |
| 15 | **Monitoring Audit Logs** | Admin Master | **Ekspor Log Aktivitas Sistem** | Admin mengekspor riwayat log aktivitas keamanan sistem untuk kebutuhan pengarsipan. |

---

## 📋 3. Kamus Use Case (Use Case Dictionary)

Kamus ini menjelaskan fungsi prosedural dari 15 Kegiatan Utama di dalam sistem SIMEKS:

1. **Login Sistem**: Proses otentikasi kredensial pengguna (email/username dan password) untuk masuk ke dashboard. *(Meng-include: Validasi Data)*.
2. **Mengelola Profil Mandiri**: Fitur untuk melihat biodata pribadi akun yang sedang aktif.
3. **Menjelajahi Informasi Ekskul**: Portal siswa untuk mencari dan melihat seluruh program ekstrakurikuler yang ditawarkan di SMAN 2 Sukatani.
4. **Melakukan Pendaftaran Ekskul**: Proses siswa mengirimkan formulir pengajuan diri bergabung menjadi anggota ekskul aktif.
5. **Verifikasi Pendaftaran Anggota**: Aksi Pembina/Admin untuk menentukan status penerimaan calon anggota eskul (`diterima` atau `ditolak`).
6. **Mengelola Jadwal Latihan**: Menginput dan mengatur waktu pertemuan berkala untuk latihan rutin eskul.
7. **Melakukan Absensi Kehadiran**: Pencatatan presensi kehadiran siswa terdaftar di setiap pertemuan latihan (Hadir, Sakit, Izin, Alpa).
8. **Melihat Riwayat Absensi**: Halaman siswa untuk memantau rekap absensi kehadiran dirinya sendiri.
9. **Mengelola Data Prestasi**: Penginputan data kemenangan, kejuaraan, atau penghargaan yang didapatkan oleh siswa/ekskul terkait.
10. **Mengelola E-Sertifikat**: Halaman repositori sertifikat digital siswa atas prestasi yang dicapai.
11. **Melihat Laporan Ekskul**: Fitur pengelola untuk memantau visualisasi rekap kehadiran latihan dan statistik anggota eskul.
12. **Mengelola Data Ekstrakurikuler**: CRUD (Create, Read, Update, Delete) informasi eskul sekolah oleh administrator.
13. **Mengelola Akun Pengguna**: CRUD akun Guru Pembina, Siswa, dan Admin pendukung oleh Administrator Master.
14. **Mengelola Pengumuman & Agenda**: Fitur untuk mempublikasikan berita penting sekolah atau jadwal agenda besar eskul.
15. **Monitoring Audit Logs**: Rekaman jejak audit aktivitas yang terjadi pada database demi keamanan aplikasi.

---

## 📝 4. Skenario Use Case Utama (Detailed Scenarios)

Berikut adalah beberapa skenario fungsionalitas utama yang dapat dicantumkan pada bab pembahasan skripsi Anda:

### Skenario 1: Melakukan Pendaftaran Ekskul (Base Use Case 4)
*   **Aktor**: Siswa Aktif
*   **Alur Utama**:
    1. Siswa masuk ke menu "Daftar Ekskul".
    2. Siswa mencari ekstrakurikuler yang diminati.
    3. Siswa menekan tombol "Daftar" dan sistem menyimpan status awal `Menunggu`.
*   **Alur Perluasan (Extend - Membatalkan Pengajuan)**:
    1. Sebelum pembina menyetujui, siswa mengakses menu dashboard.
    2. Siswa menekan opsi "Batalkan" pada riwayat pendaftaran.
    3. Sistem menghapus pengajuan dari database.

### Skenario 2: Melihat Laporan Ekskul & Cetak Laporan (Base Use Case 11)
*   **Aktor**: Pembina Ekstrakurikuler, Admin Master
*   **Alur Utama**:
    1. Aktor mengakses menu "Laporan".
    2. Sistem memuat grafik tren kehadiran latihan dan keaktifan anggota.
*   **Alur Perluasan (Extend - Mencetak Laporan)**:
    1. Aktor memilih tombol "Cetak PDF" atau "Ekspor Excel".
    2. Sistem mengekspor berkas rekap absensi bulanan ke format yang dipilih pengguna.

---

## 📊 5. Script PlantUML Use Case Diagram (Vertical Layout)

Salin kode di bawah ini ke editor **[PlantUML Web Server](http://www.plantuml.com/plantuml/)** untuk menghasilkan diagram secara otomatis dengan format vertikal:

```plantuml
@startuml
' Arah tata letak diatur vertikal secara default
skinparam packageStyle rectangle
skinparam roundcorner 10
skinparam shadowing false
skinparam ArrowThickness 1.2

' Tema Warna Premium SIMEKS (Deep Blue)
skinparam ArrowColor #4A5568
skinparam ActorBorderColor #2B6CB0
skinparam ActorBackgroundColor #E2E8F0
skinparam ActorFontSize 12
skinparam UseCaseBorderColor #2B6CB0
skinparam UseCaseBackgroundColor #F7FAFC
skinparam UseCaseFontSize 11

title Use Case Diagram SIMEKS (15 Kegiatan Utama & Extend)

' Aktor
actor "Admin Master" as admin
actor "Pembina Ekstrakurikuler" as pembina
actor "Siswa Aktif" as siswa

' Pewarisan hak akses (Admin mewarisi hak Pembina)
pembina <|-- admin

rectangle "Sistem Informasi Manajemen Ekstrakurikuler (SIMEKS)" {
  
  ' --- 15 Use Case Utama & Pasangan Extend-nya ---
  
  ' 1. Login
  usecase "Login Sistem" as UC_Login
  usecase "Registrasi Akun Baru" as UC_Register
  usecase "Validasi Data" as UC_Validasi
  UC_Register ..> UC_Login : <<extend>>
  UC_Login ..> UC_Validasi : <<include>>
  
  ' 2. Profil
  usecase "Mengelola Profil Mandiri" as UC_Profil
  usecase "Mengubah Foto Profil & Password" as UC_EditProfil
  UC_EditProfil ..> UC_Profil : <<extend>>
  
  ' 3. Jelajah Ekskul
  usecase "Menjelajahi Informasi Ekskul" as UC_JelajahEskul
  usecase "Melihat Detail Pembina & Kuota" as UC_DetailEskul
  UC_DetailEskul ..> UC_JelajahEskul : <<extend>>
  
  ' 4. Daftar Ekskul
  usecase "Melakukan Pendaftaran Ekskul" as UC_DaftarEskul
  usecase "Membatalakan Pengajuan Pendaftaran" as UC_BatalDaftar
  UC_BatalDaftar ..> UC_DaftarEskul : <<extend>>
  
  ' 5. Verifikasi
  usecase "Verifikasi Pendaftaran Anggota" as UC_Verifikasi
  usecase "Menginput Catatan Penolakan" as UC_TolakCatatan
  UC_TolakCatatan ..> UC_Verifikasi : <<extend>>
  
  ' 6. Jadwal
  usecase "Mengelola Jadwal Latihan" as UC_Jadwal
  usecase "Mengubah Lokasi & Jam Latihan" as UC_EditJadwal
  UC_EditJadwal ..> UC_Jadwal : <<extend>>
  
  ' 7. Absensi
  usecase "Melakukan Absensi Kehadiran" as UC_Absen
  usecase "Menginput Keterangan Izin/Sakit" as UC_KeteranganAbsen
  UC_KeteranganAbsen ..> UC_Absen : <<extend>>
  
  ' 8. Riwayat Absen Siswa
  usecase "Melihat Riwayat Absensi" as UC_RiwayatAbsen
  usecase "Melihat Statistik Kehadiran" as UC_StatistikAbsen
  UC_StatistikAbsen ..> UC_RiwayatAbsen : <<extend>>
  
  ' 9. Prestasi
  usecase "Mengelola Data Prestasi" as UC_Prestasi
  usecase "Mengunggah File Dokumentasi Lomba" as UC_UploadDokumentasi
  UC_UploadDokumentasi ..> UC_Prestasi : <<extend>>
  
  ' 10. Sertifikat
  usecase "Mengelola E-Sertifikat" as UC_Sertifikat
  usecase "Mengunduh E-Sertifikat PDF" as UC_UnduhSertifikat
  UC_UnduhSertifikat ..> UC_Sertifikat : <<extend>>
  
  ' 11. Laporan
  usecase "Melihat Laporan Ekskul" as UC_Laporan
  usecase "Mencetak Laporan (Excel/PDF)" as UC_CetakLaporan
  UC_CetakLaporan ..> UC_Laporan : <<extend>>
  
  ' 12. CRUD Ekskul Admin
  usecase "Mengelola Data Ekstrakurikuler" as UC_CRUDEskul
  usecase "Mengatur Penugasan Guru Pembina" as UC_TugasPembina
  UC_TugasPembina ..> UC_CRUDEskul : <<extend>>
  
  ' 13. CRUD Pengguna Admin
  usecase "Mengelola Akun Pengguna" as UC_CRUDUser
  usecase "Mengubah Status Aktif Akun" as UC_StatusAkun
  UC_StatusAkun ..> UC_CRUDUser : <<extend>>
  
  ' 14. CRUD Pengumuman Admin
  usecase "Mengelola Pengumuman & Agenda" as UC_CRUDPengumuman
  usecase "Mengirim Notifikasi Real-Time" as UC_Notifikasi
  UC_Notifikasi ..> UC_CRUDPengumuman : <<extend>>
  
  ' 15. Audit Logs Admin
  usecase "Monitoring Audit Logs" as UC_Logs
  usecase "Ekspor Log Aktivitas Sistem" as UC_EksporLogs
  UC_EksporLogs ..> UC_Logs : <<extend>>
}

' --- Asosiasi Aktor ke Use Case Utama ---

' Siswa Aktif
siswa --> UC_Login
siswa --> UC_Profil
siswa --> UC_JelajahEskul
siswa --> UC_DaftarEskul
siswa --> UC_RiwayatAbsen
siswa --> UC_Sertifikat

' Pembina Ekstrakurikuler
pembina --> UC_Login
pembina --> UC_Profil
pembina --> UC_Verifikasi
pembina --> UC_Jadwal
pembina --> UC_Absen
pembina --> UC_Prestasi
pembina --> UC_Laporan
pembina --> UC_Sertifikat

' Admin Master
admin --> UC_CRUDEskul
admin --> UC_CRUDUser
admin --> UC_CRUDPengumuman
admin --> UC_Logs

' Pengaturan susunan vertikal
UC_Login -[hidden]down-> UC_Profil
UC_Profil -[hidden]down-> UC_JelajahEskul
UC_JelajahEskul -[hidden]down-> UC_DaftarEskul
UC_DaftarEskul -[hidden]down-> UC_Verifikasi
UC_Verifikasi -[hidden]down-> UC_Jadwal
UC_Jadwal -[hidden]down-> UC_Absen
UC_Absen -[hidden]down-> UC_RiwayatAbsen
UC_RiwayatAbsen -[hidden]down-> UC_Prestasi
UC_Prestasi -[hidden]down-> UC_Sertifikat
UC_Sertifikat -[hidden]down-> UC_Laporan
UC_Laporan -[hidden]down-> UC_CRUDEskul
UC_CRUDEskul -[hidden]down-> UC_CRUDUser
UC_CRUDUser -[hidden]down-> UC_CRUDPengumuman
UC_CRUDPengumuman -[hidden]down-> UC_Logs

@enduml
```

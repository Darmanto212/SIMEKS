# Dokumentasi & Perancangan Sequence Diagram SIMEKS
**Sistem Informasi Manajemen Ekstrakurikuler Sekolah (SIMEKS) - SMAN 2 Sukatani**

*Sequence diagram* (diagram sekuens) adalah jenis diagram interaksi yang menjelaskan bagaimana objek-objek dalam sistem berkolaborasi dan berkomunikasi satu sama lain berdasarkan urutan waktu (kronologis). Diagram ini berfokus pada aliran pesan (*messages*) yang dikirimkan antara *actor*, *boundary/view* (antarmuka), *control* (proses logika backend), dan *entity* (tabel database).

Untuk skripsi Anda, perancangan diagram sekuens dibagi menjadi **4 skenario utama** yang merepresentasikan proses inti dari aplikasi SIMEKS:

---

## 📊 1. Sequence Diagram: Registrasi Akun Siswa
Menjelaskan proses saat siswa baru mendaftarkan akun ke dalam sistem agar datanya tercatat dan mendapatkan hak akses untuk masuk ke portal siswa.

### 🖥️ Script PlantUML
```plantuml
@startuml
autonumber
skinparam style strictuml
skinparam roundcorner 10
skinparam boxPadding 10
skinparam participantPadding 15

' Tema Warna Premium
skinparam ArrowColor #4A5568
skinparam ActorBorderColor #2B6CB0
skinparam ActorBackgroundColor #E2E8F0
skinparam ParticipantBorderColor #2B6CB0
skinparam ParticipantBackgroundColor #F7FAFC
skinparam DatabaseBorderColor #2F855A
skinparam DatabaseBackgroundColor #E6FFFA

actor Siswa as U
box "Frontend (Client Side)" #F7FAFC
  participant "Halaman Registrasi" as V
end box
box "Backend & Database (Server Side)" #EDF2F7
  participant "proses_register.php" as C
  database "simeks_db" as DB
end box

U -> V : Membuka form registrasi
V --> U : Tampilkan Form (NISN, Nama, Email, Password, Kelas)
U -> V : Mengisi & submit form registrasi

V -> C : Kirim data registrasi via POST
activate C

C -> DB : Cek duplikasi NISN & Email\n(SELECT FROM siswa)
activate DB
DB --> C : Mengembalikan hasil pengecekan
deactivate DB

alt Akun Sudah Terdaftar
  C --> V : Kirim pesan error (NISN/Email sudah ada)
  V --> U : Tampilkan pesan registrasi gagal
else Akun Belum Terdaftar
  C -> C : Enkripsi Password (password_hash)
  C -> DB : Simpan data siswa baru\n(INSERT INTO siswa)
  activate DB
  DB --> C : Status Query Sukses
  deactivate DB
  
  C --> V : Response sukses & redirect ke Login
  deactivate C
  V --> U : Tampilkan pesan sukses & alihkan ke Halaman Login
end

@endum
```

---

## 📊 2. Sequence Diagram: Login Pengguna (Siswa & Admin)
Menjelaskan proses otentikasi kredensial pengguna (email & password) dan bagaimana sistem menentukan hak akses (*role*) untuk mengarahkan pengguna ke dasbor yang sesuai.

### 🖥️ Script PlantUML
```plantuml
@startuml
autonumber
skinparam style strictuml
skinparam roundcorner 10
skinparam boxPadding 10
skinparam participantPadding 15

' Tema Warna Premium
skinparam ArrowColor #4A5568
skinparam ActorBorderColor #2B6CB0
skinparam ActorBackgroundColor #E2E8F0
skinparam ParticipantBorderColor #2B6CB0
skinparam ParticipantBackgroundColor #F7FAFC
skinparam DatabaseBorderColor #2F855A
skinparam DatabaseBackgroundColor #E6FFFA

actor "Pengguna (Siswa/Admin)" as U
box "Frontend (Client Side)" #F7FAFC
  participant "Halaman Login" as V
  participant "Dashboard" as Dash
end box
box "Backend & Database (Server Side)" #EDF2F7
  participant "proses_login.php" as C
  database "simeks_db" as DB
end box

U -> V : Mengakses Halaman Login
V --> U : Tampilkan form login (Email & Password)
U -> V : Input Email & Password, klik login

V -> C : Kirim data login via POST
activate C

C -> DB : Ambil data user berdasarkan email\n(SELECT FROM users/siswa)
activate DB
DB --> C : Kembalikan data user (password hash, role)
deactivate DB

C -> C : Verifikasi password (password_verify)

alt Kredensial Tidak Cocok
  C --> V : Kirim pesan error "Login Gagal"
  V --> U : Tampilkan pesan "Email/Password salah"
else Kredensial Cocok (Sukses)
  C -> C : Inisiasi Session User
  C -> DB : Catat riwayat login ke log aktivitas\n(INSERT INTO logs)
  activate DB
  DB --> C : Log tersimpan
  deactivate DB
  
  C --> V : Response sukses & data session
  deactivate C
  
  V -> Dash : Alihkan ke Dashboard sesuai Role
  activate Dash
  Dash --> U : Tampilkan antarmuka Dashboard (Siswa/Admin)
  deactivate Dash
end

@endum
```

---

## 📊 3. Sequence Diagram: Pendaftaran Ekstrakurikuler oleh Siswa
Menjelaskan interaksi objek ketika seorang siswa memilih salah satu ekstrakurikuler yang aktif dan mengajukan pendaftaran ke sistem.

### 🖥️ Script PlantUML
```plantuml
@startuml
autonumber
skinparam style strictuml
skinparam roundcorner 10
skinparam boxPadding 10
skinparam participantPadding 15

' Tema Warna Premium
skinparam ArrowColor #4A5568
skinparam ActorBorderColor #2B6CB0
skinparam ActorBackgroundColor #E2E8F0
skinparam ParticipantBorderColor #2B6CB0
skinparam ParticipantBackgroundColor #F7FAFC
skinparam DatabaseBorderColor #2F855A
skinparam DatabaseBackgroundColor #E6FFFA

actor Siswa as U
box "Frontend (Client Side)" #F7FAFC
  participant "Dashboard Siswa" as V
end box
box "Backend & Database (Server Side)" #EDF2F7
  participant "proses_daftar_ekskul.php" as C
  database "simeks_db" as DB
end box

U -> V : Pilih menu "Pendaftaran Ekskul"
activate V
V -> C : Request daftar ekskul yang tersedia
activate C
C -> DB : Ambil ekskul aktif & kuota\n(SELECT FROM eskul)
activate DB
DB --> C : Kembalikan daftar ekskul
deactivate DB
C --> V : Kirim data ekskul
deactivate C
V --> U : Tampilkan daftar pilihan ekskul
deactivate V

U -> V : Pilih ekskul & klik tombol "Daftar"
activate V
V -> C : Kirim data pendaftaran (siswa_id, eskul_id)
activate C

C -> DB : Cek apakah sudah pernah mendaftar ekskul ini\n(SELECT FROM pendaftaran)
activate DB
DB --> C : Status pendaftaran sebelumnya
deactivate DB

alt Sudah Pernah Mendaftar
  C --> V : Kirim pesan error "Sudah terdaftar"
  V --> U : Tampilkan notifikasi peringatan
else Belum Pernah Mendaftar
  C -> DB : Simpan pengajuan pendaftaran (Status: Menunggu)\n(INSERT INTO pendaftaran)
  activate DB
  DB --> C : Pengajuan tersimpan
  deactivate DB
  
  C --> V : Response sukses pendaftaran
  deactivate C
  V --> U : Tampilkan notifikasi "Pendaftaran Berhasil Diajukan"
  deactivate V
end

@endum
```

---

## 📊 4. Sequence Diagram: Verifikasi Pendaftaran oleh Admin
Menjelaskan bagaimana administrator melakukan verifikasi (menyetujui atau menolak) pengajuan pendaftaran siswa baru, yang berdampak pada otomatisasi pengurangan kuota ekstrakurikuler.

### 🖥️ Script PlantUML
```plantuml
@startuml
autonumber
skinparam style strictuml
skinparam roundcorner 10
skinparam boxPadding 10
skinparam participantPadding 15

' Tema Warna Premium
skinparam ArrowColor #4A5568
skinparam ActorBorderColor #2B6CB0
skinparam ActorBackgroundColor #E2E8F0
skinparam ParticipantBorderColor #2B6CB0
skinparam ParticipantBackgroundColor #F7FAFC
skinparam DatabaseBorderColor #2F855A
skinparam DatabaseBackgroundColor #E6FFFA

actor Admin as U
box "Frontend (Client Side)" #F7FAFC
  participant "Dashboard Admin" as V
end box
box "Backend & Database (Server Side)" #EDF2F7
  participant "verifikasi_pendaftaran.php" as C
  database "simeks_db" as DB
end box

U -> V : Pilih menu "Kelola Pendaftaran"
activate V
V -> C : Request data pengajuan pendaftaran
activate C
C -> DB : Ambil pendaftaran status 'Menunggu'\n(SELECT FROM pendaftaran WHERE status='Menunggu')
activate DB
DB --> C : Kembalikan data pendaftaran & profil siswa
deactivate DB
C --> V : Kirim data pendaftaran
deactivate C
V --> U : Tampilkan daftar pengajuan verifikasi
deactivate V

U -> V : Tentukan Status (Terima / Tolak) & klik simpan
activate V
V -> C : Kirim keputusan verifikasi (pendaftaran_id, status, catatan)
activate C

C -> DB : Update status pendaftaran\n(UPDATE pendaftaran SET status=...)
activate DB
DB --> C : Status Terupdate
deactivate DB

alt Keputusan: Terima (Disetujui)
  C -> DB : Kurangi kuota ekskul secara otomatis\n(UPDATE ekskul SET kuota = kuota - 1)
  activate DB
  DB --> C : Kuota terupdate
  deactivate DB
else Keputusan: Tolak (Ditolak)
  ' Tidak mengurangi kuota ekskul, hanya mencatat catatan penolakan
end

C -> DB : Simpan log aktivitas & kirim notifikasi ke siswa\n(INSERT INTO notifikasi & logs)
activate DB
DB --> C : Log & Notifikasi tersimpan
deactivate DB

  
  C --> V : Response sukses verifikasi
  deactivate C
  V --> U : Tampilkan pesan sukses verifikasi pendaftaran
  deactivate V
  
@endum
```

---

## 📊 5. Sequence Diagram: Penginputan Absensi Kehadiran (Oleh Admin)
Menjelaskan bagaimana administrator melakukan pencatatan kehadiran (presensi) untuk siswa yang telah resmi terdaftar pada suatu ekstrakurikuler.

### 🖥️ Script PlantUML
```plantuml
@startuml
autonumber
skinparam style strictuml
skinparam roundcorner 10
skinparam boxPadding 10
skinparam participantPadding 15

' Tema Warna Premium
skinparam ArrowColor #4A5568
skinparam ActorBorderColor #2B6CB0
skinparam ActorBackgroundColor #E2E8F0
skinparam ParticipantBorderColor #2B6CB0
skinparam ParticipantBackgroundColor #F7FAFC
skinparam DatabaseBorderColor #2F855A
skinparam DatabaseBackgroundColor #E6FFFA

actor Admin as U
box "Frontend (Client Side)" #F7FAFC
  participant "Dashboard Admin" as V
end box
box "Backend & Database (Server Side)" #EDF2F7
  participant "proses_absensi.php" as C
  database "simeks_db" as DB
end box

U -> V : Pilih Menu "Kelola Absensi" & pilih Ekskul
activate V
V -> C : Request daftar siswa terdaftar pada ekskul tersebut
activate C
C -> DB : Ambil siswa berstatus 'Diterima'\n(SELECT FROM pendaftaran & siswa)
activate DB
DB --> C : Kembalikan daftar siswa
deactivate DB
C --> V : Kirim daftar siswa
deactivate C
V --> U : Tampilkan daftar siswa & form absensi (Hadir, Izin, Sakit, Alpa)
deactivate V

U -> V : Input Tanggal & Kehadiran Siswa, klik "Simpan Absensi"
activate V
V -> C : Kirim data absensi via POST
activate C
C -> DB : Simpan data presensi per siswa\n(INSERT/UPDATE INTO absensi)
activate DB
DB --> C : Status Query Sukses
deactivate DB
C --> V : Response sukses absensi
deactivate C
V --> U : Tampilkan notifikasi "Data Kehadiran Berhasil Disimpan"
deactivate V

@endum
```

---

## 📊 6. Sequence Diagram: Pencatatan Prestasi & Unduh E-Sertifikat
Menjelaskan alur saat administrator menginput prestasi kejuaraan siswa, serta bagaimana siswa dapat melihat dan mengunduh E-Sertifikat Prestasi dalam format PDF.

### 🖥️ Script PlantUML
```plantuml
@startuml
autonumber
skinparam style strictuml
skinparam roundcorner 10
skinparam boxPadding 10
skinparam participantPadding 15

' Tema Warna Premium
skinparam ArrowColor #4A5568
skinparam ActorBorderColor #2B6CB0
skinparam ActorBackgroundColor #E2E8F0
skinparam ParticipantBorderColor #2B6CB0
skinparam ParticipantBackgroundColor #F7FAFC
skinparam DatabaseBorderColor #2F855A
skinparam DatabaseBackgroundColor #E6FFFA

actor "Admin & Siswa" as U
box "Frontend (Client Side)" #F7FAFC
  participant "Dashboard User" as V
end box
box "Backend & Database (Server Side)" #EDF2F7
  participant "proses_prestasi.php" as C
  database "simeks_db" as DB
end box

== Bagian 1: Input Prestasi oleh Admin ==
U -> V : Mengakses Menu "Input Prestasi" (Sebagai Admin)
V --> U : Tampilkan form data prestasi siswa
U -> V : Input data prestasi (siswa_id, nama_prestasi, peringkat) & submit
activate V
V -> C : Kirim data prestasi via POST
activate C
C -> DB : Simpan riwayat juara ke database\n(INSERT INTO prestasi)
activate DB
DB --> C : Status query sukses
deactivate DB
C --> V : Response sukses input prestasi
deactivate C
V --> U : Tampilkan pesan "Prestasi Berhasil Disimpan"
deactivate V

== Bagian 2: Lihat & Unduh Sertifikat oleh Siswa ==
U -> V : Masuk Menu "Sertifikat Prestasi" (Sebagai Siswa)
activate V
V -> C : Request riwayat prestasi milik siswa terkait
activate C
C -> DB : Ambil prestasi siswa\n(SELECT FROM prestasi WHERE siswa_id=...)
activate DB
DB --> C : Kembalikan data prestasi siswa
deactivate DB
C --> V : Kirim data prestasi
deactivate C
V --> U : Tampilkan daftar prestasi & tombol Unduh E-Sertifikat
deactivate V

U -> V : Klik tombol "Unduh E-Sertifikat"
activate V
V -> C : Request generate PDF sertifikat
activate C
C -> C : Inisiasi & buat file PDF (FPDF/Dompdf)
C --> V : Kirim file PDF sebagai stream download
deactivate C
V --> U : Browser mengunduh berkas E-Sertifikat.pdf
deactivate V

@endum
```

---

## 📊 7. Sequence Diagram: Pengelolaan Data Ekstrakurikuler (CRUD oleh Admin)
Menjelaskan bagaimana administrator mengelola data ekstrakurikuler (tambah, edit, dan hapus data) yang terhubung ke database.

### 🖥️ Script PlantUML
```plantuml
@startuml
autonumber
skinparam style strictuml
skinparam roundcorner 10
skinparam boxPadding 10
skinparam participantPadding 15

' Tema Warna Premium
skinparam ArrowColor #4A5568
skinparam ActorBorderColor #2B6CB0
skinparam ActorBackgroundColor #E2E8F0
skinparam ParticipantBorderColor #2B6CB0
skinparam ParticipantBackgroundColor #F7FAFC
skinparam DatabaseBorderColor #2F855A
skinparam DatabaseBackgroundColor #E6FFFA

actor Admin as U
box "Frontend (Client Side)" #F7FAFC
  participant "Halaman Kelola Ekskul" as V
end box
box "Backend & Database (Server Side)" #EDF2F7
  participant "kelola-eskul.php" as C
  database "simeks_db" as DB
end box

U -> V : Membuka Halaman Kelola Ekskul
activate V
V -> C : Request data eskul
activate C
C -> DB : Ambil seluruh data ekskul\n(SELECT * FROM eskul)
activate DB
DB --> C : Mengembalikan data ekskul
deactivate DB
C --> V : Kirim data eskul
deactivate C
V --> U : Tampilkan daftar ekskul & tombol aksi (Tambah/Edit/Hapus)
deactivate V

== Proses Tambah/Edit Ekskul ==
U -> V : Klik "Tambah/Edit", isi form ekskul, & klik simpan
activate V
V -> C : Kirim data ekskul via POST
activate C
C -> DB : Simpan/Update data ekskul\n(INSERT INTO / UPDATE eskul)
activate DB
DB --> C : Query Berhasil
deactivate DB
C --> V : Response Sukses
deactivate C
V --> U : Tampilkan notifikasi "Data Ekskul Berhasil Disimpan"
deactivate V

== Proses Hapus Ekskul ==
U -> V : Klik tombol "Hapus" pada salah satu ekskul
activate V
V -> C : Kirim request hapus (eskul_id) via GET/POST
activate C
C -> DB : Hapus data ekskul\n(DELETE FROM eskul WHERE id=...)
activate DB
DB --> C : Query Berhasil
deactivate DB
C --> V : Response Sukses
deactivate C
V --> U : Tampilkan notifikasi "Data Ekskul Berhasil Dihapus"
deactivate V

@enduml
```

---

## 📊 8. Sequence Diagram: Pengelolaan & Penayangan Pengumuman
Menjelaskan alur saat admin memposting pengumuman baru hingga pengumuman tersebut tampil pada halaman pengumuman di portal siswa.

### 🖥️ Script PlantUML
```plantuml
@startuml
autonumber
skinparam style strictuml
skinparam roundcorner 10
skinparam boxPadding 10
skinparam participantPadding 15

' Tema Warna Premium
skinparam ArrowColor #4A5568
skinparam ActorBorderColor #2B6CB0
skinparam ActorBackgroundColor #E2E8F0
skinparam ParticipantBorderColor #2B6CB0
skinparam ParticipantBackgroundColor #F7FAFC
skinparam DatabaseBorderColor #2F855A
skinparam DatabaseBackgroundColor #E6FFFA

actor Admin as A
actor Siswa as S
box "Frontend (Client Side)" #F7FAFC
  participant "Halaman Admin" as VA
  participant "Halaman Siswa" as VS
end box
box "Backend & Database (Server Side)" #EDF2F7
  participant "kelola-pengumuman.php" as CA
  participant "pengumuman.php" as CS
  database "simeks_db" as DB
end box

== Bagian 1: Publish Pengumuman oleh Admin ==
A -> VA : Buka Halaman Kelola Pengumuman
activate VA
VA --> A : Tampilkan daftar pengumuman & form input
A -> VA : Isi Judul & Isi Pengumuman, klik "Kirim"
VA -> CA : Kirim data pengumuman via POST
activate CA
CA -> DB : Simpan pengumuman baru\n(INSERT INTO pengumuman)
activate DB
DB --> CA : Query Berhasil
deactivate DB
CA --> VA : Response Sukses
deactivate CA
VA --> A : Tampilkan notifikasi "Pengumuman Berhasil Diterbitkan"
deactivate VA

== Bagian 2: Melihat Pengumuman oleh Siswa ==
S -> VS : Masuk ke Menu "Pengumuman" (Portal Siswa)
activate VS
VS -> CS : Request data pengumuman terbaru
activate CS
CS -> DB : Ambil pengumuman aktif\n(SELECT * FROM pengumuman ORDER BY tanggal DESC)
activate DB
DB --> CS : Kembalikan daftar pengumuman
deactivate DB
CS --> VS : Kirim data pengumuman
deactivate CS
VS --> S : Tampilkan daftar pengumuman terupdate kepada siswa
deactivate VS

@enduml
```

---

## 📊 9. Sequence Diagram: Pembuatan Laporan & Cetak/Ekspor Data oleh Admin
Menjelaskan bagaimana administrator melakukan penyaringan (filtering) data pendaftaran dan kehadiran untuk dicetak (PDF) atau diekspor ke Microsoft Excel.

### 🖥️ Script PlantUML
```plantuml
@startuml
autonumber
skinparam style strictuml
skinparam roundcorner 10
skinparam boxPadding 10
skinparam participantPadding 15

' Tema Warna Premium
skinparam ArrowColor #4A5568
skinparam ActorBorderColor #2B6CB0
skinparam ActorBackgroundColor #E2E8F0
skinparam ParticipantBorderColor #2B6CB0
skinparam ParticipantBackgroundColor #F7FAFC
skinparam DatabaseBorderColor #2F855A
skinparam DatabaseBackgroundColor #E6FFFA

actor Admin as U
box "Frontend (Client Side)" #F7FAFC
  participant "Halaman Laporan" as V
  participant "Window Print/Excel" as W
end box
box "Backend & Database (Server Side)" #EDF2F7
  participant "laporan.php" as C
  participant "cetak.php / export.php" as E
  database "simeks_db" as DB
end box

U -> V : Membuka Menu "Laporan" & pilih filter (Ekskul/Status)
activate V
V -> C : Kirim filter pencarian
activate C
C -> DB : Ambil data pendaftaran & kehadiran sesuai filter\n(SELECT JOIN siswa, eskul, absensi)
activate DB
DB --> C : Kembalikan data laporan
deactivate DB
C --> V : Tampilkan tabel data laporan di layar
deactivate C
V --> U : Tampilkan pratinjau laporan & tombol cetak/ekspor
deactivate V

== Opsi A: Cetak Laporan (Print PDF/Kertas) ==
U -> V : Klik tombol "Cetak Laporan"
activate V
V -> E : Redirect ke cetak.php dengan parameter filter
activate E
E -> DB : Ambil data laporan terupdate
activate DB
DB --> E : Kembalikan data
deactivate DB
E -> E : Render layout halaman cetak bersih (Print View)
E --> W : Kirim halaman HTML cetak & trigger window.print()
deactivate E
activate W
W --> U : Tampilkan dialog cetak browser (Save to PDF / Print)
deactivate W
deactivate V

== Opsi B: Ekspor Laporan ke Excel ==
U -> V : Klik tombol "Ekspor Excel"
activate V
V -> E : Redirect ke export.php dengan parameter filter
activate E
E -> DB : Ambil data laporan
activate DB
DB --> E : Kembalikan data
deactivate DB
E -> E : Set Header HTTP ke Excel format (application/vnd-ms-excel)
E --> V : Stream file Excel (.xls)
deactivate E
V --> U : Browser mengunduh file Excel secara otomatis
deactivate V

@enduml
```

---

## 📊 10. Sequence Diagram: Pembaruan Profil & Foto Siswa
Menjelaskan alur saat siswa memperbarui data pribadi dan mengunggah foto profil baru yang disimpan ke direktori aset server dan database.

### 🖥️ Script PlantUML
```plantuml
@startuml
autonumber
skinparam style strictuml
skinparam roundcorner 10
skinparam boxPadding 10
skinparam participantPadding 15

' Tema Warna Premium
skinparam ArrowColor #4A5568
skinparam ActorBorderColor #2B6CB0
skinparam ActorBackgroundColor #E2E8F0
skinparam ParticipantBorderColor #2B6CB0
skinparam ParticipantBackgroundColor #F7FAFC
skinparam DatabaseBorderColor #2F855A
skinparam DatabaseBackgroundColor #E6FFFA

actor Siswa as U
box "Frontend (Client Side)" #F7FAFC
  participant "Halaman Profil" as V
end box
box "Backend & Database (Server Side)" #EDF2F7
  participant "profil.php" as C
  database "simeks_db" as DB
end box

U -> V : Mengakses Halaman Profil Siswa
activate V
V -> C : Request profil berdasarkan session siswa_id
activate C
C -> DB : Ambil data siswa\n(SELECT * FROM siswa WHERE id=...)
activate DB
DB --> C : Kembalikan biodata lengkap siswa
deactivate DB
C --> V : Kirim data profil
deactivate C
V --> U : Tampilkan data profil saat ini & form update (termasuk upload foto)
deactivate V

U -> V : Edit biodata, pilih file foto profil baru, klik "Simpan Perubahan"
activate V
V -> C : Kirim data form via POST (multipart/form-data)
activate C

alt Ada Upload Foto Baru
  C -> C : Validasi format & ukuran file gambar
  alt File Valid
    C -> C : Upload file foto ke direktori `/assets/img/profil/`
  else File Tidak Valid
    C --> V : Kirim pesan error "File tidak valid/terlalu besar"
    V --> U : Tampilkan pesan kesalahan upload
  end
end

C -> DB : Perbarui biodata & nama file foto profil di database\n(UPDATE siswa SET nama, kelas, email, foto=...)
activate DB
DB --> C : Query Berhasil
deactivate DB
C --> V : Response Sukses
deactivate C
V --> U : Tampilkan notifikasi "Profil Berhasil Diperbarui"
deactivate V

@enduml
```

---

## 🛠️ Cara Menggenerate/Membuka Diagram dari Script
Anda dapat melihat tampilan grafis dari script PlantUML di atas dengan langkah-langkah berikut:
1. **Salin Script**: Copy script di dalam blok `@startuml` sampai `@enduml` pada skenario yang ingin Anda buat.
2. **Buka PlantUML Web Server**: Kunjungi website **[PlantUML Web Server](http://www.plantuml.com/plantuml/)**.
3. **Tempel & Submit**: Paste script yang telah disalin ke kolom teks yang disediakan, lalu klik **Submit**. Anda bisa mengunduh hasilnya dalam format `.png` atau `.svg` berkualitas tinggi untuk disisipkan langsung ke dokumen skripsi Anda.


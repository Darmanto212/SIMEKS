# Dokumentasi & Rancangan Flowchart SIMEKS
**Sistem Informasi Manajemen Ekstrakurikuler Sekolah (SIMEKS) - SMAN 2 Sukatani**

*Flowchart* (diagram alir) adalah diagram yang menggambarkan langkah-langkah sistematis dari suatu algoritma atau alur proses logika program. Berbeda dengan *activity diagram* yang berfokus pada pembagian peran (siapa melakukan apa), *flowchart* berfokus pada detail urutan instruksi, keputusan percabangan, serta input/output data dalam aplikasi SIMEKS dari awal hingga akhir.

---

## 📊 1. Alur Logika Flowchart Sistem

Berikut adalah alur logika yang digambarkan dalam diagram alir sistem SIMEKS secara keseluruhan:

1. **Mulai (Start)**: Pengguna mengakses website SIMEKS.
2. **Landing Page**: Menampilkan informasi umum dan daftar ekstrakurikuler yang aktif.
3. **Pemeriksaan Akun (Decision)**: Apakah pengguna sudah terdaftar?
   * Jika **Belum**, pengguna masuk ke form **Registrasi Akun**, menginput NISN, nama, email, password, kelas, lalu menekan daftar. Sistem menyimpan data ke DB, lalu diarahkan ke halaman login.
   * Jika **Sudah**, lanjut ke **Halaman Login**.
4. **Verifikasi Login (Decision)**: Pengguna menginput email dan password. 
   * Jika **Gagal**, sistem menampilkan pesan error dan pengguna harus login ulang.
   * Jika **Berhasil**, sistem memeriksa hak akses (role) pengguna.
5. **Pemeriksaan Role (Decision)**:
   * Jika **Role = Siswa**: Masuk ke **Dashboard Siswa**.
     * Siswa dapat memilih beberapa menu: *Update Profil/Foto*, *Pengajuan Pendaftaran Ekskul*, *Melihat Riwayat Absensi*, *Membaca Pengumuman*, dan *Mengunduh E-Sertifikat Prestasi*.
   * Jika **Role = Admin**: Masuk ke **Dashboard Admin**.
     * Admin dapat mengelola menu: *CRUD Data Ekskul*, *Verifikasi Pendaftaran Anggota Baru*, *Input Absensi Kehadiran*, *Input Prestasi Siswa*, *Posting Pengumuman*, *Monitoring Logs Audit*, dan *Ekspor Laporan (Excel/Word/PDF)*.
6. **Selesai (End)**: Pengguna melakukan logout dari sistem.

---

## 🖥️ 2. Script PlantUML Flowchart

Berikut adalah script **PlantUML** untuk menghasilkan Flowchart Sistem SIMEKS secara lengkap, rapi, dan menggunakan representasi warna modern:

```plantuml
@startuml
skinparam conditionStyle diamond
skinparam roundcorner 10
skinparam shadowing false

' Tema Warna Premium & Harmonis
skinparam ArrowColor #4A5568
skinparam ConnectorColor #2B6CB0
skinparam StartEndBackgroundColor #E2E8F0
skinparam StartEndBorderColor #4A5568
skinparam ActivityBackgroundColor #F7FAFC
skinparam ActivityBorderColor #2B6CB0
skinparam DecisionBackgroundColor #FEFCBF
skinparam DecisionBorderColor #D69E2E

title Flowchart Alur Sistem SIMEKS

start

:Mengakses Halaman Landing Page\n(Informasi Ekstrakurikuler);

if (Sudah Memiliki Akun?) then (Belum)
  :Input Data Registrasi\n(NISN, Nama, Email, Password, Kelas);
  :Sistem Validasi Registrasi;
  :Simpan Data Siswa Baru ke Database;
  :Arahkan ke Halaman Login;
endif

repeat
  :Input Email & Password;
  :Sistem Memvalidasi Kredensial;
backward:Tampilkan Pesan Login Gagal;
repeat while (Login Berhasil?) is (Tidak) not (Ya)

:Catat Aktivitas ke Logs Keamanan;

if (Status Hak Akses [Role]?) then (Siswa)
  :Masuk Dashboard Siswa;
  
  partition "Fitur Siswa" {
    split
      :Mengelola Profil & Foto;
    split again
      :Memilih Ekstrakurikuler\nyang ingin didaftar;
      :Simpan Data Pendaftaran\n(Status: Menunggu);
    split again
      :Melihat Riwayat Absensi;
    split again
      :Membaca Pengumuman;
    split again
      :Melihat & Unduh\nE-Sertifikat Prestasi;
    end split
  }

else (Admin)
  :Masuk Dashboard Admin;
  
  partition "Fitur Administrator" {
    split
      :Kelola Data Ekskul\n(Tambah/Edit/Hapus Ekskul);
    split again
      :Verifikasi Pendaftaran Siswa\n(Terima/Tolak & Tulis Catatan);
      if (Pendaftaran Diterima?) then (Ya)
        :Update Status Pendaftaran (Diterima);
        :Kurangi Kuota Ekskul Otomatis;
      else (Tidak)
        :Update Status Pendaftaran (Ditolak);
      endif
      :Kirim Notifikasi ke Siswa;
    split again
      :Menginput Kehadiran Siswa\n(Hadir, Izin, Sakit, Alpa);
      :Simpan Data Presensi ke Database;
    split again
      :Kelola Prestasi Siswa\n(Input Riwayat Juara);
    split again
      :Mengelola Pengumuman Sekolah\n(Kategori: Event/Umum);
    split again
      :Ekspor Laporan Rekapitulasi\n(Format: Excel, Word, PDF);
    split again
      :Monitoring Log Aktivitas Sistem;
    end split
  }

endif

:Melakukan Logout;
stop

@endum
```

---

## 🛠️ 3. Cara Menampilkan/Menggenerate Diagram dari Script

Anda dapat mengonversi script PlantUML di atas menjadi gambar diagram yang rapi dengan langkah-langkah berikut:

1. **Menggunakan PlantUML Server Resmi (Gratis & Cepat)**:
   * Kunjungi situs **[PlantUML Web Server](http://www.plantuml.com/plantuml/)**.
   * Salin (*Copy*) seluruh kode di dalam blok `@startuml` sampai `@endum` di atas.
   * Tempelkan (*Paste*) ke dalam kolom teks di situs tersebut.
   * Klik tombol **Submit** untuk melihat hasilnya. Anda bisa mengunduh gambarnya dalam format PNG atau SVG.

2. **Menggunakan VS Code Extension**:
   * Pasang ekstensi bernama **PlantUML** oleh *jebbs* di VS Code Anda.
   * Buat file baru dengan ekstensi `.puml` (misal: `flowchart.puml`), tempelkan script di atas, lalu tekan `Alt + D` untuk melihat pratinjau grafis secara langsung.

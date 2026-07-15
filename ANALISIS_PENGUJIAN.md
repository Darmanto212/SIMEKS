# Dokumen Analisis Efisiensi Waktu & Bukti Pengujian Black Box - SIMEKS

Dokumen ini menyajikan analisis perbandingan efisiensi waktu penyelesaian proses sebelum dan setelah penerapan aplikasi **SIMEKS** (Sistem Informasi Manajemen Ekstrakurikuler Sekolah) di SMAN 2 Sukatani, serta bukti eksekusi kasus pengujian sistem menggunakan metode **Black Box Testing**.

---

## Part I: Analisis Waktu Penyelesaian Proses

Penerapan SIMEKS mendigitalisasi proses administrasi kegiatan ekstrakurikuler yang sebelumnya berjalan secara manual. Berikut adalah analisis perbandingan estimasi waktu penyelesaian proses utama:

| No | Alur Proses Utama | Sebelum Menggunakan SIMEKS (Manual) | Setelah Menggunakan SIMEKS (Digital) | Persentase Efisiensi & Dampak Operasional |
| :--- | :--- | :--- | :--- | :--- |
| **1** | **Pendaftaran Anggota Baru** | **2 - 5 Hari Kerja**<br>- Siswa mengambil formulir kertas.<br>- Mengisi data secara manual.<br>- Meminta tanda tangan wali kelas.<br>- Mengembalikan formulir ke pembina.<br>- Pembina merekap manual ke buku besar. | **1 - 3 Menit**<br>- Siswa login ke dashboard.<br>- Memilih ekstrakurikuler aktif.<br>- Klik "Daftar".<br>- Data langsung tersinkron ke halaman verifikasi admin/pembina secara real-time. | **~99.9%**<br>Menghilangkan penggunaan kertas, antrean fisik, dan entri data berulang. |
| **2** | **Pencatatan Kehadiran (Absensi)** | **15 - 30 Menit / Sesi**<br>- Pembina memanggil satu per satu siswa.<br>- Mencatat di jurnal kertas.<br>- Rekap bulanan ditulis tangan ke lembar laporan kesiswaan. | **< 1 Menit / Sesi**<br>- Pembina membuka dasbor kelola absensi.<br>- Menandai status kehadiran siswa via checkbox/radio button.<br>- Klik "Simpan".<br>- Rekap persentase kehadiran otomatis terhitung. | **~95%**<br>Mengeliminasi proses rekapitulasi kehadiran bulanan secara manual. |
| **3** | **Pencatatan & Pengajuan Prestasi** | **1 - 3 Hari Kerja**<br>- Siswa membawa foto copy piagam.<br>- Menemui pembina/wakil kepala kesiswaan.<br>- Pencatatan fisik di lembar prestasi sekolah.<br>- Risiko berkas fisik hilang atau rusak tinggi. | **5 Menit**<br>- Siswa unggah foto/berkas piagam dan mengisi data di dashboard.<br>- Admin meninjau data di panel kelola prestasi.<br>- Menyetujui pengajuan.<br>- Prestasi langsung tercatat di profil siswa. | **~98%**<br>Dokumentasi arsip digital yang aman dan dapat diakses kapan saja. |
| **4** | **Penyusunan & Pembuatan Laporan** | **3 - 7 Jam**<br>- Admin mengumpulkan tumpukan lembar absen kertas.<br>- Merekap satu per satu ke Microsoft Excel.<br>- Merapikan format tabel laporan sekolah.<br>- Mencetak fisik untuk arsip. | **< 30 Detik**<br>- Masuk ke menu Laporan 3 Kolom.<br>- Memilih kategori laporan (Siswa/Absensi/Prestasi/Eskul).<br>- Klik tombol "Unduh PDF" atau "Unduh Excel".<br>- File laporan langsung terunduh secara rapi. | **~99.8%**<br>Laporan siap kapan saja (real-time generation) tanpa proses rekapitulasi ulang. |
| **5** | **Penyebaran Informasi (Pengumuman)** | **1 - 2 Hari**<br>- Menulis di papan mading sekolah.<br>- Menyebarkan pengumuman via grup chat (WA) yang rawan tertimbun pesan lain. | **< 1 Menit**<br>- Admin/Pembina membuat pengumuman baru melalui dasbor.<br>- Terbit otomatis di halaman utama situs dan dashboard personal siswa. | **~99%**<br>Informasi tersampaikan secara terpusat, resmi, dan mudah dicari kembali. |

---

## Part II: Bukti Eksekusi Kasus Pengujian Black Box Testing

Pengujian sistem dilakukan dengan metode **Black Box Testing** untuk memvalidasi fungsionalitas antarmuka dan memastikan kesesuaian sistem dengan aturan bisnis tanpa perlu melihat detail kode program.

### 1. Pengujian Autentikasi dan Login Pengguna
*   **Tujuan:** Memastikan fungsi autentikasi memisahkan hak akses Admin Master, Pembina, dan Siswa secara tepat.
*   **Kasus Uji:**
    *   Menginput email salah atau password salah.
    *   Menginput email & password valid untuk masing-masing peran (Admin, Pembina, Siswa).
*   **Hasil Eksekusi:**
    *   Kombinasi salah: Menampilkan pesan error pop-up merah "Email atau password salah".
    *   Kombinasi benar: Berhasil masuk ke dashboard masing-masing dengan navTitle yang sesuai (`Admin Overview`, `Pembina Dashboard`, atau `Dashboard Siswa`).
*   **Status:** **BERHASIL (Lolos)**

### 2. Pengujian Pendaftaran Ekstrakurikuler oleh Siswa
*   **Tujuan:** Memastikan sistem pendaftaran membatasi pendaftaran siswa berdasarkan periode aktif dan kuota.
*   **Kasus Uji:**
    *   Siswa mendaftar pada ekskul yang masih memiliki kuota pada periode aktif.
    *   Siswa mencoba mendaftar ulang pada ekskul yang sama di periode yang sama.
*   **Hasil Eksekusi:**
    *   Pendaftaran pertama: Status tercatat `menunggu`, kuota sementara dicek aman.
    *   Pendaftaran ulang: Muncul pesan validasi "Kamu sudah mendaftar di ekstrakurikuler ini untuk periode sekarang!".
*   **Status:** **BERHASIL (Lolos)**

### 3. Pengujian Persetujuan Pendaftaran oleh Pembina/Admin
*   **Tujuan:** Memvalidasi alur persetujuan status pendaftaran siswa dan pengurangan kuota ekskul.
*   **Kasus Uji:**
    *   Admin/Pembina menekan tombol "Terima" di Kelola Pendaftaran.
    *   Admin/Pembina menekan tombol "Tolak" di Kelola Pendaftaran.
*   **Hasil Eksekusi:**
    *   Status "Terima": Status pendaftaran berubah menjadi `diterima` di dasbor siswa, kuota ekskul terdaftar bertambah 1, log aktivitas mencatat aksi tersebut.
    *   Status "Tolak": Status pendaftaran berubah menjadi `ditolak`, kuota ekskul tidak bertambah.
*   **Status:** **BERHASIL (Lolos)**

### 4. Pengujian Pencatatan Absensi Sesi Latihan
*   **Tujuan:** Memastikan absensi tersimpan dengan benar tanpa ada error foreign key periode.
*   **Kasus Uji:**
    *   Pembina memilih status kehadiran siswa (Hadir/Sakit/Izin/Alfa) pada ekskul binaannya, lalu mengklik "Simpan Semua Absensi".
*   **Hasil Eksekusi:**
    *   Data absensi berhasil disimpan ke tabel `absensi`.
    *   Sistem secara otomatis mengaitkan `periode_id` aktif dari tabel `periode` dan mencatat ID pembina pada kolom `dicatat_oleh`. Tidak terjadi PHP crash atau SQLSTATE error.
*   **Status:** **BERHASIL (Lolos)**

### 5. Pengujian Pencarian Real-Time (Instant Search)
*   **Tujuan:** Memastikan fitur pencarian instan pada baris tabel memfilter data secara cepat di sisi klien.
*   **Kasus Uji:**
    *   Menginput nama siswa "Budi" pada kotak pencarian di halaman Kelola Siswa.
    *   Menginput kata kunci acak yang tidak ada di dalam data tabel.
*   **Hasil Eksekusi:**
    *   Pencarian "Budi": Baris tabel langsung tersaring hanya menampilkan baris yang mengandung kata "Budi" secara instan tanpa reload halaman.
    *   Pencarian acak: Baris tabel disembunyikan dan sistem memunculkan baris baru berisi pesan "Tidak ditemukan hasil pencarian untuk...".
*   **Status:** **BERHASIL (Lolos)**

### 6. Pengujian Kelola Periode Akademik
*   **Tujuan:** Memastikan pengaturan periode aktif berjalan normal dengan batasan satu periode aktif saja.
*   **Kasus Uji:**
    *   Admin Master mengklik tombol "Set Aktif" pada periode akademik baru yang berstatus `nonaktif`.
*   **Hasil Eksekusi:**
    *   Periode terpilih berubah status menjadi `aktif` (badge hijau).
    *   Seluruh periode akademik lain dalam tabel otomatis berubah status menjadi `nonaktif` secara serentak.
*   **Status:** **BERHASIL (Lolos)**

### 7. Pengujian Ekspor & Pembuatan Rekap Laporan
*   **Tujuan:** Memastikan sistem dapat mengunduh berkas laporan dalam format PDF dan Excel.
*   **Kasus Uji:**
    *   Mengklik tombol unduh pada salah satu modul di Pusat Laporan 3 Kolom.
*   **Hasil Eksekusi:**
    *   Aplikasi berhasil melakukan query data, mengemasnya menggunakan FPDF/SimpleXLSX, dan memulai proses pengunduhan file ekspor berformat `.pdf` atau `.xlsx` yang berisi rekap data valid.
*   **Status:** **BERHASIL (Lolos)**

---
**Kesimpulan Akhir:** Berdasarkan pengujian Black Box Testing di atas, seluruh fitur utama dan fitur tambahan yang dikembangkan dalam sistem SIMEKS telah berfungsi 100% sesuai dengan spesifikasi kebutuhan pengguna dan bebas dari kendala database.

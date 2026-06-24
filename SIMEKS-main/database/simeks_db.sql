-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Bulan Mei 2026 pada 09.03
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simeks_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi`
--

CREATE TABLE `absensi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `eskul_id` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status` enum('hadir','izin','sakit','alpa') DEFAULT 'hadir',
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `absensi`
--

INSERT INTO `absensi` (`id`, `user_id`, `eskul_id`, `tanggal`, `status`, `keterangan`, `created_at`) VALUES
(1, 2, 2, '2026-02-04', 'hadir', 'hadir', '2026-02-04 13:21:59');

-- --------------------------------------------------------

--
-- Struktur dari tabel `eskul`
--

CREATE TABLE `eskul` (
  `id` int(11) NOT NULL,
  `nama_eskul` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `pembina` varchar(100) DEFAULT NULL,
  `kuota` int(11) DEFAULT 30,
  `jadwal` varchar(100) DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT 'default_eskul.jpg',
  `status` enum('aktif','non-aktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `eskul`
--

INSERT INTO `eskul` (`id`, `nama_eskul`, `deskripsi`, `pembina`, `kuota`, `jadwal`, `lokasi`, `gambar`, `status`) VALUES
(1, 'Futsal', 'Olahraga tim untuk kesehatan fisik dan sportivitas tinggi.', 'Bpk. Ahmad', 30, 'Selasa, 15:30', 'Lapangan Utama', 'eskul_1768743004.jpg', 'aktif'),
(2, 'English Club', 'Tingkatkan kemampuan bahasa asing untuk masa depan global.', 'Ibu Siti', 30, 'Kamis, 14:00', 'Ruang Multimedia', 'eskul_1768742968.jpg', 'aktif'),
(3, 'Paskibra', 'Membentuk kedisiplinan dan jiwa kepemimpinan yang tangguh.', 'Bpk. Hendra', 30, 'Sabtu, 08:00', 'Lapangan Utama', 'default_eskul.png', 'aktif'),
(4, 'SENI', 'ikuti eskul seni untuk mendapatkan pengalaman yang terbaik\r\n', 'FAUZANI RAHMAN. S.Pd', 30, 'sabtu, 8:00 wib', 'ruang seni', 'default_eskul.png', 'aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `tipe` enum('INFO','SUKSES','PERINGATAN','BAHAYA') DEFAULT 'INFO',
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `aktivitas`, `keterangan`, `tipe`, `tanggal`, `ip_address`) VALUES
(1, 1, 'Update Ekskul', 'ID: 2', 'INFO', '2026-01-17 18:14:57', '::1'),
(2, 1, 'Logout Berhasil', 'Pengguna keluar dari sistem', 'INFO', '2026-01-17 18:23:58', '::1'),
(3, NULL, 'Upaya Login Gagal', 'Seseorang mencoba masuk dengan email: Admin', 'PERINGATAN', '2026-01-17 18:24:18', '::1'),
(4, NULL, 'Upaya Login Gagal', 'Seseorang mencoba masuk dengan email: admin@simeks.com', 'PERINGATAN', '2026-01-17 18:25:07', '::1'),
(5, NULL, 'Upaya Login Gagal', 'Seseorang mencoba masuk dengan email: admin@simeks.com', 'PERINGATAN', '2026-01-17 18:25:07', '::1'),
(6, 1, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai admin', 'SUKSES', '2026-01-17 18:25:24', '::1'),
(7, 1, 'Logout Berhasil', 'Pengguna keluar dari sistem', 'INFO', '2026-01-17 18:26:44', '::1'),
(8, 1, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai admin', 'SUKSES', '2026-01-17 18:27:21', '::1'),
(9, 1, 'Logout Berhasil', 'Pengguna keluar dari sistem', 'INFO', '2026-01-17 18:28:26', '::1'),
(10, 1, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai admin', 'SUKSES', '2026-01-17 18:28:36', '::1'),
(11, 1, 'Logout Berhasil', 'Pengguna keluar dari sistem', 'INFO', '2026-01-17 18:37:57', '::1'),
(12, NULL, 'Upaya Login Gagal', 'Seseorang mencoba masuk dengan email: darma', 'PERINGATAN', '2026-01-17 18:38:36', '::1'),
(13, NULL, 'Upaya Login Gagal', 'Seseorang mencoba masuk dengan email: darmanto', 'PERINGATAN', '2026-01-17 18:38:49', '::1'),
(14, NULL, 'Upaya Login Gagal', 'Seseorang mencoba masuk dengan email: darmanto', 'PERINGATAN', '2026-01-17 18:39:45', '::1'),
(15, 2, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai siswa', 'SUKSES', '2026-01-17 18:40:06', '::1'),
(16, 1, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai admin', 'SUKSES', '2026-01-17 18:59:02', '::1'),
(17, 2, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai siswa', 'SUKSES', '2026-01-17 19:01:33', '::1'),
(18, 2, 'Update Profil', 'Siswa memperbarui data diri', 'INFO', '2026-01-17 19:04:56', '::1'),
(19, 2, 'Logout Berhasil', 'Pengguna keluar dari sistem', 'INFO', '2026-01-17 19:07:28', '::1'),
(20, 1, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai admin', 'SUKSES', '2026-01-17 19:08:10', '::1'),
(21, 1, 'Tambah Ekskul', 'Nama: SENI', 'SUKSES', '2026-01-17 19:09:42', '::1'),
(22, 1, 'Export Laporan', 'Export siswa dalam format excel', 'INFO', '2026-01-17 19:15:24', '::1'),
(23, 1, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai admin', 'SUKSES', '2026-01-17 19:27:05', '::1'),
(24, 2, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai siswa', 'SUKSES', '2026-01-17 19:44:07', '::1'),
(25, 2, 'Update Foto', 'Siswa mengganti foto profil', 'INFO', '2026-01-17 19:49:23', '::1'),
(26, NULL, 'Upaya Login Gagal', 'Seseorang mencoba masuk dengan email: Admin', 'PERINGATAN', '2026-01-18 07:08:19', '::1'),
(27, 1, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai admin', 'SUKSES', '2026-01-18 07:08:28', '::1'),
(28, 1, 'Proses Pendaftaran', 'Admin memproses pendaftaran ID 2 menjadi diterima', 'INFO', '2026-01-18 07:08:46', '::1'),
(29, 1, 'Proses Pendaftaran', 'Admin memproses pendaftaran ID 2 menjadi diterima', 'INFO', '2026-01-18 07:08:46', '::1'),
(30, 1, 'Proses Pendaftaran', 'Admin memproses pendaftaran ID 3 menjadi diterima', 'INFO', '2026-01-18 07:08:47', '::1'),
(31, 1, 'Export Laporan', 'Export siswa dalam format word', 'INFO', '2026-01-18 07:10:21', '::1'),
(32, 2, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai siswa', 'SUKSES', '2026-01-18 07:12:49', '::1'),
(33, 1, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai admin', 'SUKSES', '2026-01-18 13:28:57', '::1'),
(34, 1, 'Update Ekskul', 'ID: 2', 'INFO', '2026-01-18 13:29:27', '::1'),
(35, 1, 'Update Ekskul', 'ID: 2', 'INFO', '2026-01-18 13:29:28', '::1'),
(36, 1, 'Update Ekskul', 'ID: 1', 'INFO', '2026-01-18 13:30:04', '::1'),
(37, 1, 'Logout Berhasil', 'Pengguna keluar dari sistem', 'INFO', '2026-01-18 13:30:09', '::1'),
(38, 1, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai admin', 'SUKSES', '2026-01-18 13:30:24', '::1'),
(39, 2, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai siswa', 'SUKSES', '2026-01-18 13:32:16', '::1'),
(40, 2, 'Logout Berhasil', 'Pengguna keluar dari sistem', 'INFO', '2026-01-18 13:35:09', '::1'),
(41, 1, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai admin', 'SUKSES', '2026-01-18 13:36:13', '::1'),
(42, 1, 'Tambah Pengumuman', 'Judul: DIES NATALIS ESKUL SENI 2026', 'SUKSES', '2026-01-18 13:37:40', '::1'),
(43, 2, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai siswa', 'SUKSES', '2026-01-18 13:39:07', '::1'),
(44, 2, 'Logout Berhasil', 'Pengguna keluar dari sistem', 'INFO', '2026-01-18 13:43:36', '::1'),
(45, 1, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai admin', 'SUKSES', '2026-01-20 07:23:27', '::1'),
(46, 1, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai admin', 'SUKSES', '2026-01-20 07:54:56', '::1'),
(47, 1, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai admin', 'SUKSES', '2026-01-20 07:54:57', '::1'),
(48, 1, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai siswa', 'SUKSES', '2026-01-20 07:58:24', '::1'),
(49, 1, 'Logout Berhasil', 'Pengguna keluar dari sistem sebagai admin', 'INFO', '2026-01-20 07:59:54', '::1'),
(50, 2, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai siswa', 'SUKSES', '2026-01-25 04:22:04', '::1'),
(51, 2, 'Logout Berhasil', 'Pengguna keluar dari sistem sebagai siswa', 'INFO', '2026-01-25 04:23:13', '::1'),
(52, 1, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai admin', 'SUKSES', '2026-01-25 04:23:25', '::1'),
(53, 1, 'Export Laporan', 'Export siswa dalam format excel', 'INFO', '2026-01-25 04:23:54', '::1'),
(54, 1, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai admin', 'SUKSES', '2026-01-26 09:48:46', '::1'),
(55, 1, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai admin', 'SUKSES', '2026-01-26 09:48:48', '::1'),
(56, 1, 'Export Laporan', 'Export siswa dalam format excel', 'INFO', '2026-01-26 09:51:06', '::1'),
(57, 1, 'Logout Berhasil', 'Pengguna keluar dari sistem sebagai admin', 'INFO', '2026-01-26 10:17:38', '::1'),
(58, NULL, 'Upaya Login Gagal', 'Seseorang mencoba masuk dengan email: coba1', 'PERINGATAN', '2026-01-26 10:18:32', '::1'),
(59, 3, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai siswa', 'SUKSES', '2026-01-26 10:18:44', '::1'),
(60, 3, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai siswa', 'SUKSES', '2026-01-26 10:18:46', '::1'),
(61, 1, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai admin', 'SUKSES', '2026-01-26 10:20:23', '::1'),
(62, 4, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai siswa', 'SUKSES', '2026-02-04 13:18:42', '::1'),
(63, 4, 'Upaya Login Gagal', 'Seseorang mencoba masuk dengan email: Admin', 'PERINGATAN', '2026-02-04 13:19:17', '::1'),
(64, 1, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai admin', 'SUKSES', '2026-02-04 13:19:25', '::1'),
(65, 1, 'Proses Pendaftaran', 'Admin memproses ID 7 menjadi diterima', 'INFO', '2026-02-04 13:20:42', '::1'),
(66, 1, 'Input Absensi', 'Input absensi ekskul ID 2 tanggal 2026-02-04', 'INFO', '2026-02-04 13:21:59', '::1'),
(67, 1, 'Input Absensi', 'Input absensi ekskul ID 2 tanggal 2026-02-04', 'INFO', '2026-02-04 13:22:18', '::1'),
(68, 1, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai admin', 'SUKSES', '2026-02-24 18:30:59', '::1'),
(69, 1, 'Proses Pendaftaran', 'Admin memproses ID 4 menjadi diterima', 'INFO', '2026-02-24 18:31:53', '::1'),
(70, 1, 'Proses Pendaftaran', 'Admin memproses ID 5 menjadi diterima', 'INFO', '2026-02-24 18:31:55', '::1'),
(71, 1, 'Proses Pendaftaran', 'Admin memproses ID 6 menjadi diterima', 'INFO', '2026-02-24 18:31:57', '::1'),
(72, 1, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai siswa', 'SUKSES', '2026-02-24 18:32:18', '::1'),
(73, 1, 'Update Profil', 'Siswa memperbarui data diri', 'INFO', '2026-02-24 18:32:34', '::1'),
(74, 1, 'Update Profil', 'Siswa memperbarui data diri', 'INFO', '2026-02-24 18:32:37', '::1'),
(75, NULL, 'Upaya Login Gagal', 'Seseorang mencoba masuk dengan email: darmanto12', 'PERINGATAN', '2026-03-26 17:47:52', '::1'),
(76, 2, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai siswa', 'SUKSES', '2026-03-26 17:48:01', '::1'),
(77, 2, 'Cetak Sertifikat', 'Cetak sertifikat prestasi ID 3', 'INFO', '2026-03-26 17:48:20', '::1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `judul` varchar(100) DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `type` varchar(20) DEFAULT 'info',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `notifikasi`
--

INSERT INTO `notifikasi` (`id`, `user_id`, `judul`, `pesan`, `type`, `is_read`, `created_at`) VALUES
(1, 2, 'Pendaftaran Diterima! ✅', 'Selamat! Pendaftaran kamu di ekskul Futsal telah disetujui.', 'success', 1, '2026-01-18 07:08:46'),
(2, 2, 'Pendaftaran Diterima! ✅', 'Selamat! Pendaftaran kamu di ekskul Futsal telah disetujui.', 'success', 1, '2026-01-18 07:08:46'),
(3, 2, 'Pendaftaran Diterima! ✅', 'Selamat! Pendaftaran kamu di ekskul SENI telah disetujui.', 'success', 1, '2026-01-18 07:08:47'),
(4, 2, 'Pengumuman Baru: DIES NATALIS ESKUL SENI 2026', 'Ada info baru kategori EVENT', 'info', 1, '2026-01-18 13:37:40'),
(5, 4, 'Pendaftaran Diterima! ✅', 'Selamat! Pendaftaran kamu di ekskul Futsal telah disetujui.', 'success', 0, '2026-02-04 13:20:42'),
(6, 3, 'Pendaftaran Diterima! ✅', 'Selamat! Pendaftaran kamu di ekskul English Club telah disetujui.', 'success', 0, '2026-02-24 18:31:53'),
(7, 3, 'Pendaftaran Diterima! ✅', 'Selamat! Pendaftaran kamu di ekskul Futsal telah disetujui.', 'success', 0, '2026-02-24 18:31:55'),
(8, 3, 'Pendaftaran Diterima! ✅', 'Selamat! Pendaftaran kamu di ekskul Paskibra telah disetujui.', 'success', 0, '2026-02-24 18:31:57');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pendaftaran`
--

CREATE TABLE `pendaftaran` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `eskul_id` int(11) DEFAULT NULL,
  `tanggal_daftar` date DEFAULT NULL,
  `status` enum('menunggu','diterima','ditolak') DEFAULT 'menunggu',
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pendaftaran`
--

INSERT INTO `pendaftaran` (`id`, `user_id`, `eskul_id`, `tanggal_daftar`, `status`, `catatan`) VALUES
(1, 2, 2, '2026-01-18', 'diterima', NULL),
(2, 2, 1, '2026-01-18', 'diterima', NULL),
(3, 2, 4, '2026-01-18', 'diterima', NULL),
(4, 3, 2, '2026-01-26', 'diterima', ''),
(5, 3, 1, '2026-01-26', 'diterima', ''),
(6, 3, 3, '2026-01-26', 'diterima', ''),
(7, 4, 1, '2026-02-04', 'diterima', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `kategori` varchar(50) DEFAULT 'Umum',
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('aktif','nonaktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengumuman`
--

INSERT INTO `pengumuman` (`id`, `judul`, `isi`, `kategori`, `tanggal`, `status`) VALUES
(1, 'DIES NATALIS ESKUL SENI 2026', 'JANGAN LUPA HADIRI DIES NATALIS ESKUL\r\n', 'EVENT', '2026-01-18 13:37:40', 'aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `prestasi`
--

CREATE TABLE `prestasi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `eskul_id` int(11) DEFAULT NULL,
  `nama_prestasi` varchar(255) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `prestasi`
--

INSERT INTO `prestasi` (`id`, `user_id`, `eskul_id`, `nama_prestasi`, `tahun`, `deskripsi`) VALUES
(1, 2, 1, 'juara 1', '2026', 'mantap'),
(2, 2, 1, 'juara 1', '2026', 'sdad'),
(3, 2, 2, 'juara 1', '2026', 'dasdfasknf');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nisn` varchar(20) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `role` enum('admin','siswa') DEFAULT 'siswa',
  `foto` varchar(255) DEFAULT 'default.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nisn`, `nama`, `email`, `password`, `kelas`, `role`, `foto`, `created_at`) VALUES
(1, NULL, 'Administrator', 'admin@simeks.com', '$2y$10$3z8oWY2my2ulGt3Ix0awyue4EuYQREMTOCoZTHCJhVhHp.b/GnD.i', NULL, 'admin', 'default.png', '2026-01-17 17:28:20'),
(2, '312210423', 'darmanto', 'darma@siswa.com', '$2y$10$fqjCTTrxqAhqFSYhNt6rVeutGNVKz8VDJZ7RcXunjXL6DZ/dwMBwC', 'X MIPA 5', 'siswa', 'user_2_1768679362.jpg', '2026-01-17 18:38:25'),
(3, '101010', 'coba1', 'coba1@siswa.com', '$2y$10$JX3mkqz8bsGKIcZc37EAlu3dPEKvHswSek1z/BP5loGyVH3V/u7jO', NULL, 'siswa', 'default.png', '2026-01-26 10:18:22'),
(4, '312210456', 'rifki', 'rifki12@siswa.com', '$2y$10$eAwyCQ3sGSTUMGpJZxTF8OTQr1Hoj.CWaH7Uwa3bxiuzAfVw4/nUC', NULL, 'siswa', 'default.png', '2026-02-04 13:17:50');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `eskul_id` (`eskul_id`);

--
-- Indeks untuk tabel `eskul`
--
ALTER TABLE `eskul`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `eskul_id` (`eskul_id`);

--
-- Indeks untuk tabel `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `prestasi`
--
ALTER TABLE `prestasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `eskul_id` (`eskul_id`),
  ADD KEY `fk_prestasi_user` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nisn` (`nisn`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `eskul`
--
ALTER TABLE `eskul`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `pendaftaran`
--
ALTER TABLE `pendaftaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `prestasi`
--
ALTER TABLE `prestasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absensi_ibfk_2` FOREIGN KEY (`eskul_id`) REFERENCES `eskul` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD CONSTRAINT `pendaftaran_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pendaftaran_ibfk_2` FOREIGN KEY (`eskul_id`) REFERENCES `eskul` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `prestasi`
--
ALTER TABLE `prestasi`
  ADD CONSTRAINT `fk_prestasi_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prestasi_ibfk_1` FOREIGN KEY (`eskul_id`) REFERENCES `eskul` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

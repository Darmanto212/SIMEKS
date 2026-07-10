-- SIMEKS Database Schema (schema_baru.sql)
-- Clean installation file for empty database
-- Target Engine: InnoDB, Charset: utf8mb4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nisn` varchar(20) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `role` enum('admin','pembina','siswa') NOT NULL DEFAULT 'siswa',
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `must_change_password` tinyint(1) NOT NULL DEFAULT 0,
  `failed_login_attempts` int(11) NOT NULL DEFAULT 0,
  `locked_until` timestamp NULL DEFAULT NULL,
  `foto` varchar(255) DEFAULT 'default.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nisn` (`nisn`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `periode`
--

CREATE TABLE `periode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tahun_ajaran` varchar(20) NOT NULL,
  `semester` enum('ganjil','genap') NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'nonaktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `eskul`
--

CREATE TABLE `eskul` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `pembina_id` int(11) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `jadwal` varchar(100) DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `kuota` int(11) DEFAULT 30,
  `target_pertemuan` int(11) DEFAULT 16,
  `gambar` varchar(255) DEFAULT 'default_eskul.png',
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pembina_id` (`pembina_id`),
  CONSTRAINT `fk_eskul_pembina` FOREIGN KEY (`pembina_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pendaftaran`
--

CREATE TABLE `pendaftaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `eskul_id` int(11) NOT NULL,
  `periode_id` int(11) NOT NULL,
  `status` enum('menunggu','diterima','ditolak') NOT NULL DEFAULT 'menunggu',
  `alasan_penolakan` varchar(255) DEFAULT NULL,
  `diproses_oleh` int(11) DEFAULT NULL,
  `diproses_pada` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_eskul_periode` (`user_id`,`eskul_id`,`periode_id`),
  KEY `eskul_id` (`eskul_id`),
  KEY `periode_id` (`periode_id`),
  KEY `diproses_oleh` (`diproses_oleh`),
  CONSTRAINT `fk_pendaftaran_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pendaftaran_eskul` FOREIGN KEY (`eskul_id`) REFERENCES `eskul` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pendaftaran_periode` FOREIGN KEY (`periode_id`) REFERENCES `periode` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_pendaftaran_diproses` FOREIGN KEY (`diproses_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `eskul_id` int(11) NOT NULL,
  `periode_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('hadir','izin','sakit','alpa') NOT NULL DEFAULT 'hadir',
  `keterangan` varchar(255) DEFAULT NULL,
  `dicatat_oleh` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_eskul_tanggal` (`user_id`,`eskul_id`,`tanggal`),
  KEY `eskul_id` (`eskul_id`),
  KEY `periode_id` (`periode_id`),
  KEY `dicatat_oleh` (`dicatat_oleh`),
  CONSTRAINT `fk_absensi_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_absensi_eskul` FOREIGN KEY (`eskul_id`) REFERENCES `eskul` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_absensi_periode` FOREIGN KEY (`periode_id`) REFERENCES `periode` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_absensi_dicatat` FOREIGN KEY (`dicatat_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prestasi`
--

CREATE TABLE `prestasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `eskul_id` int(11) NOT NULL,
  `nama_prestasi` varchar(255) NOT NULL,
  `tingkat` varchar(50) DEFAULT NULL,
  `tanggal_prestasi` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `eskul_id` (`eskul_id`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`),
  CONSTRAINT `fk_prestasi_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_prestasi_eskul` FOREIGN KEY (`eskul_id`) REFERENCES `eskul` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_prestasi_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_prestasi_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `kategori` varchar(50) DEFAULT 'Umum',
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_by` int(11) DEFAULT NULL,
  `tanggal_terbit` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `fk_pengumuman_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `pesan` text NOT NULL,
  `reference_type` varchar(50) DEFAULT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `event_key` varchar(100) DEFAULT NULL,
  `type` varchar(20) DEFAULT 'info',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_event_key` (`event_key`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_notifikasi_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `activity` varchar(255) NOT NULL,
  `result` text DEFAULT NULL,
  `entity_type` varchar(50) DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `tipe` enum('INFO','SUKSES','PERINGATAN','BAHAYA') DEFAULT 'INFO',
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `eskul_libur`
--

CREATE TABLE `eskul_libur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eskul_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `alasan` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_eskul_tanggal` (`eskul_id`,`tanggal`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `fk_libur_eskul` FOREIGN KEY (`eskul_id`) REFERENCES `eskul` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_libur_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;

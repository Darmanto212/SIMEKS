-- ======================================================
-- SIMEKS DATABASE MASTER SCHEMA (LENGKAP)
-- Database: simeks_db
-- ======================================================

CREATE DATABASE IF NOT EXISTS simeks_db;
USE simeks_db;

-- 1. Table for Users (Admin and Siswa)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nisn VARCHAR(20) UNIQUE NULL,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    kelas VARCHAR(50) NULL,
    role ENUM('admin', 'siswa', 'master') DEFAULT 'siswa',
    foto VARCHAR(255) DEFAULT 'default.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Table for Extracurricular Activities
CREATE TABLE IF NOT EXISTS eskul (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_eskul VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    pembina VARCHAR(100),
    kuota INT DEFAULT 30,
    jadwal VARCHAR(100),
    lokasi VARCHAR(100),
    gambar VARCHAR(255) DEFAULT 'default_eskul.jpg',
    status ENUM('aktif', 'non-aktif') DEFAULT 'aktif'
);

-- 3. Table for Registrations
CREATE TABLE IF NOT EXISTS pendaftaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    eskul_id INT,
    tanggal_daftar DATE,
    status ENUM('menunggu', 'diterima', 'ditolak') DEFAULT 'menunggu',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (eskul_id) REFERENCES eskul(id) ON DELETE CASCADE
);

-- 4. Table for Achievements
CREATE TABLE IF NOT EXISTS prestasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    eskul_id INT,
    nama_prestasi VARCHAR(255),
    tingkat VARCHAR(50),
    tahun YEAR,
    deskripsi TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (eskul_id) REFERENCES eskul(id) ON DELETE CASCADE
);

-- 5. Table for Announcements
CREATE TABLE IF NOT EXISTS pengumuman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    isi TEXT NOT NULL,
    kategori ENUM('PENTING', 'INFO', 'EVENT', 'UPDATE') DEFAULT 'INFO',
    tanggal DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 6. Table for Activity Logs (Security Monitoring)
CREATE TABLE IF NOT EXISTS logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    aktivitas VARCHAR(255) NOT NULL,
    keterangan TEXT,
    tipe ENUM('INFO', 'SUKSES', 'PERINGATAN', 'BAHAYA') DEFAULT 'INFO',
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- 7. Table for Attendance
CREATE TABLE IF NOT EXISTS absensi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    eskul_id INT,
    tanggal DATE,
    status ENUM('hadir', 'izin', 'sakit', 'alpa') DEFAULT 'hadir',
    keterangan VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (eskul_id) REFERENCES eskul(id) ON DELETE CASCADE
);

-- 8. Table for Notifications
CREATE TABLE IF NOT EXISTS notifikasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    judul VARCHAR(100),
    pesan TEXT,
    type VARCHAR(20) DEFAULT 'info',
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ======================================================
-- SEED DATA (OPSIONAL)
-- ======================================================
-- Password default: password
-- INSERT INTO users (nama, email, password, role) VALUES 
-- ('Administrator', 'admin@simeks.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

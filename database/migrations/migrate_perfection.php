<?php
if (php_sapi_name() !== 'cli') {
    header('HTTP/1.1 403 Forbidden');
    exit('Akses ditolak. Skrip ini hanya dapat dijalankan melalui CLI.');
}
include __DIR__ . '/../../config/koneksi.php';

try {
    // 1. Create Absensi Table
    $koneksi->exec("CREATE TABLE IF NOT EXISTS absensi (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        eskul_id INT,
        tanggal DATE,
        status ENUM('hadir', 'izin', 'sakit', 'alpa') DEFAULT 'hadir',
        keterangan VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (eskul_id) REFERENCES eskul(id) ON DELETE CASCADE
    )");
    echo "Tabel absensi berhasil dipastikan ada.<br>";

    // 2. Create Notifikasi Table
    $koneksi->exec("CREATE TABLE IF NOT EXISTS notifikasi (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        judul VARCHAR(100),
        pesan TEXT,
        type VARCHAR(20) DEFAULT 'info',
        is_read TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");
    echo "Tabel notifikasi berhasil dipastikan ada.<br>";

    // 3. Add 'foto' column to users if not exists (already in schema but just in case)
    try {
        $koneksi->query("SELECT foto FROM users LIMIT 1");
    } catch (Exception $e) {
        $koneksi->exec("ALTER TABLE users ADD COLUMN foto VARCHAR(255) DEFAULT 'default.png' AFTER role");
        echo "Kolom foto berhasil ditambahkan ke tabel users.<br>";
    }

    echo "Migrasi Sempurna Selesai!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

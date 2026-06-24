<?php
include 'config/koneksi.php';

try {
    // 1. Tambahkan kolom target_pertemuan ke tabel eskul jika belum ada
    $check_col = $koneksi->query("SHOW COLUMNS FROM eskul LIKE 'target_pertemuan'")->fetchAll();
    if (empty($check_col)) {
        $koneksi->exec("ALTER TABLE eskul ADD COLUMN target_pertemuan INT DEFAULT 16 AFTER status");
        echo "Kolom 'target_pertemuan' berhasil ditambahkan ke tabel eskul.<br>\n";
    } else {
        echo "Kolom 'target_pertemuan' sudah ada di tabel eskul.<br>\n";
    }

    // 2. Buat tabel eskul_libur jika belum ada
    $koneksi->exec("CREATE TABLE IF NOT EXISTS eskul_libur (
        id INT AUTO_INCREMENT PRIMARY KEY,
        eskul_id INT NOT NULL,
        tanggal DATE NOT NULL,
        keterangan VARCHAR(255) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_eskul_tanggal (eskul_id, tanggal),
        FOREIGN KEY (eskul_id) REFERENCES eskul(id) ON DELETE CASCADE
    )");
    echo "Tabel 'eskul_libur' berhasil dibuat / dipastikan ada.<br>\n";

    echo "Migrasi database selesai dengan sukses!";
} catch (PDOException $e) {
    echo "Migrasi database gagal: " . $e->getMessage();
}
?>

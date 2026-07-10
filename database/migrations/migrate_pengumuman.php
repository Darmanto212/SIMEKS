<?php
if (php_sapi_name() !== 'cli') {
    header('HTTP/1.1 403 Forbidden');
    exit('Akses ditolak. Skrip ini hanya dapat dijalankan melalui CLI.');
}
include __DIR__ . '/../../config/koneksi.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS pengumuman (
        id INT AUTO_INCREMENT PRIMARY KEY,
        judul VARCHAR(255) NOT NULL,
        isi TEXT NOT NULL,
        kategori ENUM('PENTING', 'INFO', 'EVENT', 'UPDATE') DEFAULT 'INFO',
        tanggal DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
    $koneksi->exec($sql);
    echo "Table 'pengumuman' created successfully!";
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>

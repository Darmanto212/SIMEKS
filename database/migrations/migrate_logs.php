<?php
if (php_sapi_name() !== 'cli') {
    header('HTTP/1.1 403 Forbidden');
    exit('Akses ditolak. Skrip ini hanya dapat dijalankan melalui CLI.');
}
include __DIR__ . '/../../config/koneksi.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        aktivitas VARCHAR(255) NOT NULL,
        keterangan TEXT,
        tipe ENUM('INFO', 'SUKSES', 'PERINGATAN', 'BAHAYA') DEFAULT 'INFO',
        tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        ip_address VARCHAR(45),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    )";
    $koneksi->exec($sql);
    echo "Table 'logs' created successfully!";
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>

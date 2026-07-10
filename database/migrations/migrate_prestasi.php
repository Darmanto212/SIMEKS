<?php
if (php_sapi_name() !== 'cli') {
    header('HTTP/1.1 403 Forbidden');
    exit('Akses ditolak. Skrip ini hanya dapat dijalankan melalui CLI.');
}
include __DIR__ . '/../../config/koneksi.php';

try {
    // Check if user_id exists in prestasi
    $check = $koneksi->query("SHOW COLUMNS FROM prestasi LIKE 'user_id'");
    if ($check->rowCount() == 0) {
        $koneksi->exec("ALTER TABLE prestasi ADD COLUMN user_id INT AFTER id");
        $koneksi->exec("ALTER TABLE prestasi ADD FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE");
        echo "Kolom user_id berhasil ditambahkan ke tabel prestasi.<br>";
    }

    // Check if tingkat exists in prestasi
    $check = $koneksi->query("SHOW COLUMNS FROM prestasi LIKE 'tingkat'");
    if ($check->rowCount() == 0) {
        $koneksi->exec("ALTER TABLE prestasi ADD COLUMN tingkat VARCHAR(50) AFTER nama_prestasi");
        echo "Kolom tingkat berhasil ditambahkan ke tabel prestasi.<br>";
    }

    echo "Migrasi tabel prestasi selesai!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

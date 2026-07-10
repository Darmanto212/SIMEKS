<?php
if (php_sapi_name() !== 'cli') {
    header('HTTP/1.1 403 Forbidden');
    exit('Akses ditolak. Skrip ini hanya dapat dijalankan melalui CLI.');
}
include __DIR__ . '/../../config/koneksi.php';
try {
    $stmt = $koneksi->prepare("UPDATE eskul SET gambar = 'default_eskul.png' WHERE gambar = 'default_eskul.jpg'");
    $stmt->execute();
    echo "Berhasil memperbarui " . $stmt->rowCount() . " data ekskul.";
} catch (Exception $e) {
    echo $e->getMessage();
}
?>

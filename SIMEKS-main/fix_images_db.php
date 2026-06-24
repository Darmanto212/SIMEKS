<?php
include 'config/koneksi.php';
try {
    $stmt = $koneksi->prepare("UPDATE eskul SET gambar = 'default_eskul.png' WHERE gambar = 'default_eskul.jpg'");
    $stmt->execute();
    echo "Berhasil memperbarui " . $stmt->rowCount() . " data ekskul.";
} catch (Exception $e) {
    echo $e->getMessage();
}
?>

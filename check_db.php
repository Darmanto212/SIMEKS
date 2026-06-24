<?php
include 'config/koneksi.php';
try {
    $tables = ['users', 'eskul', 'absensi', 'notifikasi', 'pendaftaran', 'pengumuman', 'prestasi', 'eskul_libur', 'logs'];
    foreach ($tables as $tbl) {
        echo "<h3>Tabel $tbl:</h3><pre>";
        $q = $koneksi->query("DESCRIBE $tbl");
        print_r($q->fetchAll());
        echo "</pre>";
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
?>

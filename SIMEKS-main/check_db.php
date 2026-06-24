<?php
include 'config/koneksi.php';
try {
    $q = $koneksi->query("DESCRIBE eskul");
    $cols = $q->fetchAll();
    echo "<pre>";
    print_r($cols);
    echo "</pre>";
} catch (Exception $e) {
    echo $e->getMessage();
}
?>

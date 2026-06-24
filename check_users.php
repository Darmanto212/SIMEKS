<?php
include 'config/koneksi.php';
$users = $koneksi->query("SELECT id, nama, email, role FROM users")->fetchAll();
header('Content-Type: application/json');
echo json_encode($users);
?>

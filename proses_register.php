<?php
session_start();
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $nisn = $_POST['nisn'];
    $email = $_POST['username'] . "@siswa.com"; // Mocking email if not provided in form
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'siswa';

    try {
        // Check if NISN already exists
        $stmt = $koneksi->prepare("SELECT id FROM users WHERE nisn = ?");
        $stmt->execute([$nisn]);
        if ($stmt->fetch()) {
            echo "<script>alert('NISN sudah terdaftar!'); window.location='register.php';</script>";
            exit();
        }

        // Insert new user
        $stmt = $koneksi->prepare("INSERT INTO users (nama, nisn, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nama, $nisn, $email, $password, $role]);

        echo "<script>alert('Pendaftaran Berhasil! Silakan Login.'); window.location='login.php';</script>";
        exit();
    } catch (PDOException $e) {
        error_log("Registration error: " . $e->getMessage());
        echo "<script>alert('Terjadi kesalahan pada sistem. Silakan coba lagi.'); window.location='register.php';</script>";
        exit();
    }
}
?>

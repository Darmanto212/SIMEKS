<?php
session_start();
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['username']; // Login uses username field for email or username
    $password = $_POST['password'];

    // Search for user by email (or username if you want to support both)
    $stmt = $koneksi->prepare("SELECT * FROM users WHERE email = ? OR nisn = ?");
    $stmt->execute([$email, $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user->password)) {
        // Clear all existing session data to prevent session pollution
        session_unset();

        // Login success - Store in role-specific sub-array
        $sessionKey = $user->role . '_data';
        $_SESSION[$sessionKey] = [
            'user_id' => $user->id,
            'nama'    => $user->nama,
            'role'    => $user->role
        ];

        include 'includes/auth_check.php';
        log_activity($koneksi, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai ' . $user->role, 'SUKSES');

        if ($user->role === 'admin' || $user->role === 'pembina' || $user->role === 'master') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: siswa/dashboardsiswa.php");
        }
        exit();
    } else {
        // Login failed
        include 'includes/auth_check.php';
        log_activity($koneksi, 'Upaya Login Gagal', 'Seseorang mencoba masuk dengan email: ' . $email, 'PERINGATAN');
        echo "<script>alert('Email/Password salah!'); window.location='login.php';</script>";
        exit();
    }
}

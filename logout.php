<?php
include_once 'includes/auth_check.php';
include_once 'config/koneksi.php';

// Record activity before clearing session
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    log_activity($koneksi, 'Logout Berhasil', 'Pengguna keluar dari sistem sebagai ' . $_SESSION['role'], 'INFO');
}

// Clear and destroy session completely to prevent session pollution
logout_user();

// Redirect to Landing Page
header("Location: index.php");
exit();

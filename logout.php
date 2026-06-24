<?php
session_start();
include 'config/koneksi.php';
include 'includes/auth_check.php';

// Record activity before clearing session
$role = $_GET['role'] ?? '';

// If role not provided, try to detect from referer or current sessions
if (empty($role)) {
    if (strpos($_SERVER['HTTP_REFERER'], '/admin/') !== false) $role = 'admin';
    elseif (strpos($_SERVER['HTTP_REFERER'], '/siswa/') !== false) $role = 'siswa';
}

$sessionKey = $role . '_data';

if (isset($_SESSION[$sessionKey])) {
    log_activity($koneksi, 'Logout Berhasil', 'Pengguna keluar dari sistem sebagai ' . $role, 'INFO');
} else {
    // Fallback if no specific role detected, but session exists
    if (isset($_SESSION['admin_data'])) {
        log_activity($koneksi, 'Logout Berhasil', 'Pengguna keluar dari sistem (Auto-Admin)', 'INFO');
    } elseif (isset($_SESSION['siswa_data'])) {
        log_activity($koneksi, 'Logout Berhasil', 'Pengguna keluar dari sistem (Auto-Siswa)', 'INFO');
    }
}

// Clear and destroy session completely to prevent session pollution
session_unset();
if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
}

// Redirect to Landing Page
header("Location: index.php");
exit();

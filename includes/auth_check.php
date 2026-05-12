<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Centrally check if user is logged in and has the correct role.
 * 
 * @param string $requiredRole The role required to access the page ('admin' or 'siswa')
 */
function check_auth($requiredRole)
{
    $sessionKey = $requiredRole . '_data'; // admin_data or siswa_data

    // Master admin can access admin pages
    if ($requiredRole === 'admin' && isset($_SESSION['master_data'])) {
        return;
    }

    if (!isset($_SESSION[$sessionKey])) {
        header("Location: ../login.php");
        exit();
    }

    // Role specific verification
    if ($_SESSION[$sessionKey]['role'] !== $requiredRole) {
        // Redirect to their respective correct dashboard if they have a session but wrong role page
        if (isset($_SESSION['master_data']) || isset($_SESSION['admin_data'])) {
            header("Location: ../admin/dashboard.php");
        } elseif (isset($_SESSION['siswa_data'])) {
            header("Location: ../siswa/dashboardsiswa.php");
        } else {
            header("Location: ../login.php");
        }
        exit();
    }
}

/**
 * Record user activity to database
 */
function log_activity($koneksi, $aktivitas, $keterangan = null, $tipe = 'INFO')
{
    $user_id = null;
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

    // Check which session is active to get user_id
    if (isset($_SESSION['master_data'])) {
        $user_id = $_SESSION['master_data']['user_id'];
    } elseif (isset($_SESSION['admin_data'])) {
        $user_id = $_SESSION['admin_data']['user_id'];
    } elseif (isset($_SESSION['siswa_data'])) {
        $user_id = $_SESSION['siswa_data']['user_id'];
    }

    $stmt = $koneksi->prepare("INSERT INTO logs (user_id, aktivitas, keterangan, tipe, ip_address) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $aktivitas, $keterangan, $tipe, $ip]);
}

/**
 * Global helper to send notifications to users
 */
function send_notification($koneksi, $user_id, $judul, $pesan, $tipe = 'info')
{
    $stmt = $koneksi->prepare("INSERT INTO notifikasi (user_id, judul, pesan, type) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $judul, $pesan, $tipe]);
}

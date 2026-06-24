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
    // If required role is admin, we allow both 'admin' (Master Admin) and 'pembina' roles.
    if ($requiredRole === 'admin') {
        if (isset($_SESSION['admin_data']) && $_SESSION['admin_data']['role'] === 'admin') {
            return;
        }
        if (isset($_SESSION['pembina_data']) && $_SESSION['pembina_data']['role'] === 'pembina') {
            return;
        }
        header("Location: ../login.php");
        exit();
    }

    $sessionKey = $requiredRole . '_data'; // e.g., 'siswa_data'

    if (!isset($_SESSION[$sessionKey])) {
        header("Location: ../login.php");
        exit();
    }

    // Role specific verification
    if ($_SESSION[$sessionKey]['role'] !== $requiredRole) {
        if (isset($_SESSION['admin_data']) || isset($_SESSION['pembina_data'])) {
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
    if (isset($_SESSION['admin_data'])) {
        $user_id = $_SESSION['admin_data']['user_id'];
    } elseif (isset($_SESSION['pembina_data'])) {
        $user_id = $_SESSION['pembina_data']['user_id'];
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

/**
 * Get the eskul ID managed by a Pembina
 */
function get_pembina_eskul_id($koneksi, $pembina_id)
{
    $stmt = $koneksi->prepare("SELECT id FROM eskul WHERE pembina_id = ?");
    $stmt->execute([$pembina_id]);
    return $stmt->fetchColumn();
}

